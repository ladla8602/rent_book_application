<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Arr;
use App\Traits\RestApi;
use Illuminate\Auth\AuthenticationException;
use Config;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    use RestApi;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if(get_class($exception) == "Illuminate\Database\Eloquent\ModelNotFoundException") {
            return $this->resultResponse(
                Config::get('restresponsecode.NOT_FOUND'),
                [],
                $exception->getMessage(),
                'Data not found!'
                );
        }
        if(get_class($exception) == "Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException") {
            return $this->resultResponse(
                Config::get('restresponsecode.NOT_ACCEPTABLE'),
                [],
                $exception->getMessage(),
                'Wrong http method error!'
                );
        }
        return parent::render($request, $exception);
    }

    public function unauthenticated($request, AuthenticationException $exception){

        $guard = Arr::get($exception->guards(),0);
        switch($guard){

          case 'web':
              return redirect('/login');
              break;

          case 'api':
            return $this->resultResponse(
                Config::get('restresponsecode.PERMISSION_DENIED'),
                [],
                [],
                'You are not logged in!'
                );
            break;

          default:
            return $this->resultResponse(
                Config::get('restresponsecode.PERMISSION_DENIED'),
                [],
                [],
                'You are not logged in!'
            );
            break;
        }
      }
}
