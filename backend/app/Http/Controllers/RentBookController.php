<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Book;
use App\RentHistory;
use Validator;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Traits\RestApi;
use Illuminate\Support\Facades\Auth;
use Config, DB, File;
use \Illuminate\Database\QueryException;
use App\Jobs\GenerateInvoice;
use PDF;

class RentBookController extends Controller
{
    use RestApi;

    public function __construct() {
        $this->now = Carbon::now('Asia/Kolkata');
    }

    public function getInvoice($invoiceNumber) {

        $invoice = RentHistory::where([
            'id' => $invoiceNumber
        ]);
        if($invoice->exists()) {
            $file = $invoice->first()->invoice;
            if($file) {
                $filePath = Config::get('constants.STORAGE_INVOICE_PATH') . '/' . $file . '.pdf';
            $file = File::get($filePath);
            $type = File::mimeType($filePath);
            $response = \Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
            } else {
                return $this->resultResponse(
                    Config::get('restresponsecode.NOT_FOUND'),
                    [],
                    [],
                    'Data not found!'
                );
            }
        } else {
            return $this->resultResponse(
                Config::get('restresponsecode.NOT_FOUND'),
                [],
                [],
                'Data not found!'
            );
        }
    }

    public function getRentedBook() {
        $user = Auth::user();
        try {
            $rentHistory = RentHistory::where('user_id', $user->id)->orderBy('rent_date', 'DESC')->get();
            foreach($rentHistory as $rent) {
                $rentHistory->book = $rent->book;
            }
            if ($rentHistory->isNotEmpty()) {
                return $this->resultResponse(
                    Config::get('restresponsecode.SUCCESS'),
                    $rentHistory,
                    [],
                    'Data found!'
                );
            } else {
                return $this->resultResponse(
                    Config::get('restresponsecode.NOT_FOUND'),
                    [],
                    [],
                    'Data not found!'
                );
            }

        } catch (QueryException $e) {
            return $this->resultResponse(
                Config::get('restresponsecode.UNPROCESSABLE'),
                [],
                $e->getMessage(),
                'Database query error!'
            );
        }
    }

    public function rentBook(Request $request) {
        $user = Auth::user();
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'book_id' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return $this->resultResponse(
                Config::get('restresponsecode.BAD_REQUEST'),
                [],
                $errors,
                'Field Validation Error!'
            );
        }
        $bookId = $requestData['book_id'];
        $userId = $user->id;
        $data = [
            'user_id'   => $user->id,
            'book_id'   => $bookId,
            'rent_date' => $this->now,
            'invoice'   => '',
            'created_at'=> $this->now
        ];
        // Check for duplicate rent record
        $checkBookHistory = RentHistory::where([
            'book_id' => $bookId,
            'user_id' => $userId,
            'is_returned' => false
        ]);
        if(!$checkBookHistory->exists()) {
            try {
                $result = RentHistory::create($data);
                if($result) {
                    $renterDetail = [
                        'name' => $user->name,
                        'email' => $user->email,
                    ];
                    $invoiceNumber = $result->id;
                    $bookDetail = Book::findOrFail($bookId);

                    $invoiceData = [
                        'from'       => Config::get('constants.INVOICE_FROM_ADDRESS'),
                        'to'         => $renterDetail,
                        'invoice_no' => $invoiceNumber,
                        'rent_date'  => $result->created_at,
                        'item'       => $bookDetail,
                        'billing_info'=> Config::get('constants.INVOICE_BILLING_INFO'),
                        'payment_info'=> Config::get('constants.INVOICE_PAYMENT_INFO')
                    ];

                    // generating & saving invoice
                    $getFileName = $this->generateAndSaveInvoice($invoiceData, $result->id);
                    $findBook = RentHistory::where('id', $result->id);
                    $updateInvoice = $findBook->update([
                        'invoice' => $getFileName
                    ]);
                    if($updateInvoice) {
                        return $this->resultResponse(
                            Config::get('restresponsecode.CREATED'),
                            $result,
                            [],
                            'Book rented successfully'
                        );
                    }
                    return $this->resultResponse(
                        Config::get('restresponsecode.CREATED'),
                        $result,
                        [],
                        'Book rented successfully but invoice not generated'
                    );
                } else {
                    return $this->resultResponse(
                        Config::get('restresponsecode.UNPROCESSABLE'),
                        [],
                        $result,
                        'Something went wrong'
                    );
                }
            } catch (QueryException $e) {
                return $this->resultResponse(
                    Config::get('restresponsecode.UNPROCESSABLE'),
                    [],
                    $e->getMessage(),
                    'Database query error!'
                );
            }
        } else {
            return $this->resultResponse(
                Config::get('restresponsecode.UNPROCESSABLE'),
                [],
                [],
                'This book is already rented by you.'
            );
        }

    }

    public function returnBook(Request $request, $id) {
        $user = Auth::user();
        $requestData = $request->all();
        $rentId = $id;

        try {
            $selector = RentHistory::where(['id' => $rentId, 'user_id' => $user->id]);
            if($selector->exists()) {
                $record = $selector->first();
                if(!$record->is_returned) {
                    $result = $selector->update(['is_returned' => true]);
                    return $this->resultResponse(
                        Config::get('restresponsecode.SUCCESS'),
                        [],
                        [],
                        'Book returned successfully'
                    );
                } else {
                    return $this->resultResponse(
                        Config::get('restresponsecode.UNPROCESSABLE'),
                        [],
                        [],
                        'You have already returned this book'
                    );
                }
            } else {
                return $this->resultResponse(
                    Config::get('restresponsecode.NOT_FOUND'),
                    [],
                    [],
                    'Data not found!'
                );
            }

        } catch (QueryException $e) {
            return $this->resultResponse(
                Config::get('restresponsecode.UNPROCESSABLE'),
                [],
                $e->getMessage(),
                'Database query error!'
            );
        }
    }

    protected function generateAndSaveInvoice($data, $id) {
        // Create storage folder if not exists
        if(!File::exists(Config::get('constants.STORAGE_ASSET_PATH'))){
            mkdir(Config::get('constants.STORAGE_ASSET_PATH'));
        }
        if(!File::exists(Config::get('constants.STORAGE_INVOICE_PATH'))){
            mkdir(Config::get('constants.STORAGE_INVOICE_PATH'));
        }
        $fileName = time();
        $ext = '.pdf';
        $file = $fileName . $ext;
        $filePath = Config::get('constants.STORAGE_INVOICE_PATH') .'/' . $file;
        dispatch(new GenerateInvoice($data, $filePath, $id));
        return $fileName;
    }
}
