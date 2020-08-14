<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Book;
use Validator;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Traits\RestApi;
use Illuminate\Support\Facades\Auth;
use Config, DB;

class BookController extends Controller
{
    use RestApi;

    public function addNewBook(Request $request) {
        $now = Carbon::now('Asia/Kolkata');
        $requestData = $request->all();
        $user = Auth::user();
        $validator = Validator::make($requestData, [
            'book_name' => 'required|string',
            'book_author' => 'required|string',
            'book_price' => 'regex:/^\d+(\.\d{1,2})?$/'
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

        $data = [
            'name'           => $requestData['book_name'],
            'author'         => $requestData['book_author'],
            'price'          => $requestData['book_price'],
            'user_id'        => $user->id,
            'created_by'     => $user->id,
            'publish_on'     => $now,
            'created_at'     => $now,
            'updated_at'     => $now
        ];

        $result = Book::create($data);
        DB::beginTransaction();
        try {
            $result = Book::create($data);
            return $this->resultResponse(
                Config::get('restresponsecode.SUCCESS'),
                $result,
                [],
                'New Book added successfully'
            );
            DB::commit();
        } catch (\Exception $e) {
            /* I know Showing database error on api response or web response is not good
                I am just testing something
            */
            DB::rollback();
            return $this->resultResponse(
                Config::get('restresponsecode.UNPROCESSABLE'),
                [],
                $e,
                'Database query error!'
            );
        }
    }

    public function getAllBooks(Request $request) {
        $user = Auth::user();
        $result = Book::where('is_deleted', Config::get('constants.IS_DELETED_NO'))->get()->toArray();
        if(!empty($result)) {
            return $this->resultResponse(
                Config::get('restresponsecode.SUCCESS'),
                $result,
                [],
                'List of all available books'
            );
        } else {
            return $this->resultResponse(
                Config::get('restresponsecode.EMPTY_RESPONSE'),
                $result,
                [],
                'No book found!'
            );
        }
    }
}
