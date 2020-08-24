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
use \Illuminate\Database\QueryException;

class BookController extends Controller
{
    use RestApi;

    public function __construct() {
        $this->now = Carbon::now('Asia/Kolkata');
    }

    public function addNewBook(Request $request) {
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
            'publish_on'     => $this->now,
            'created_at'     => $this->now,
            'updated_at'     => $this->now,
        ];

        try {
            $result = Book::create($data);
            if($result) {
                return $this->resultResponse(
                    Config::get('restresponsecode.CREATED'),
                    $result,
                    [],
                    'New Book added successfully'
                );
            } else {
                return $this->resultResponse(
                    Config::get('restresponsecode.UNPROCESSABLE'),
                    [],
                    $result,
                    'Something went wrong'
                );
            }
        } catch(QueryException $e) {
            return $this->resultResponse(
                Config::get('restresponsecode.UNPROCESSABLE'),
                [],
                $e->getMessage(),
                'Database query error!'
            );
        }

    }

    public function getAllBooks(Request $request) {
        $user = Auth::user();
        $requestData = $request->all();
        $books = Book::where('is_deleted', Config::get('constants.IS_DELETED_NO'));
        if(isset($requestData['search']) && !empty($requestData['search'])) {
            $books = $books->where('name', 'LIKE', '%'.$requestData['search'].'%')
                            ->orWhere('author', 'LIKE', '%'.$requestData['search'].'%');
        }

        if(!empty($requestData['sort'])){
            $order = !empty($requestData['sort_type']) ? $requestData['sort_type'] : 'ASC';
            $books = $books->orderBy($requestData['sort'], $order);
        } else {
            $books = $books->orderBy('publish_on', 'DESC');
        }

        $books = $books->get();

        if(!empty($books)) {
            foreach($books as $book){
                $book->user = $book->user;
                $query = DB::table('rent_history')->where([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'is_returned' => 0
                ]);
                if($query->exists()) {
                    $book->rented = 1;
                } else {
                    $book->rented = 0;
                }
                unset($book->user->role, $book->user->created_at, $book->user->updated_at);
            }
            return $this->resultResponse(
                Config::get('restresponsecode.SUCCESS'),
                $books,
                [],
                'List of all available books'
            );
        } else {
            return $this->resultResponse(
                Config::get('restresponsecode.NOT_FOUND'),
                [],
                [],
                'No book found!'
            );
        }
    }

    public function updateBook(Request $request, $book_id) {
        $user = Auth::user();
        $requestData = $request->all();
        $bookId = $book_id;
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
            'updated_by'     => $user->id,
            'updated_at'     => $this->now,
        ];

        try {
            $selector = Book::where('id', $bookId);
            $result = $selector->update($data);
            if($result) {
                return $this->resultResponse(
                    Config::get('restresponsecode.SUCCESS'),
                    $selector,
                    [],
                    'Book details updated successfully'
                );
            } else {
                return $this->resultResponse(
                    Config::get('restresponsecode.UNPROCESSABLE'),
                    [],
                    [],
                    'Something went wrong!'
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

    public function deleteBook(Request $request, $book_id) {
        $user = Auth::user();
        $requestData = $request->all();
        $bookId = $book_id;

        $data = [
            'is_deleted' => Config::get('constants.IS_DELETED_YES')
        ];

        try {
            $selector = Book::where([
                'id' => $bookId,
                'is_deleted' => Config::get('constants.IS_DELETED_NO')
                ]);

            if($selector->exists()) {
                $result = $selector->update($data);
                if($result) {
                    return $this->resultResponse(
                        Config::get('restresponsecode.SUCCESS'),
                        [],
                        [],
                        'Book details removed successfully'
                    );
                } else {
                    return $this->resultResponse(
                        Config::get('restresponsecode.UNPROCESSABLE'),
                        [],
                        [],
                        'Something went wrong!'
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

    public function countsAll() {
        $data = [
            'count_books' => 0,
            'count_renters' => 0,
            'count_authors' => 0,
            'count_rented_books' => 0
        ];
        try {

            $data['count_books'] = DB::table('books')->where('is_deleted', Config::get('constants.IS_DELETED_NO'))->count();
            $data['count_renters'] = DB::table('users')->where('role', Config::get('constants.RENTER_ROLE'))->count();
            $queryDistinctAuthor = DB::table('books')->select(DB::raw("COUNT(DISTINCT author)"))
                                        ->where('is_deleted', Config::get('constants.IS_DELETED_NO'))
                                        ->groupBy('author')->get()->toArray();
            $data['count_authors'] = count($queryDistinctAuthor);
            $data['count_rented_books'] = DB::table('rent_history')->where('is_returned', 0)->count();
        } catch (QueryException $e) {
            return $this->resultResponse(
                Config::get('restresponsecode.UNPROCESSABLE'),
                [],
                $e->getMessage(),
                'Database query error!'
            );
        }

        return $this->resultResponse(
            Config::get('restresponsecode.SUCCESS'),
            $data,
            [],
            'Count fetch successfully'
        );
    }
}
