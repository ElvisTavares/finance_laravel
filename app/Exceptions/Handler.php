<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use GrahamCampbell\GitHub\Facades\GitHub;
use Crypt;

class Handler extends ExceptionHandler
{
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        try {
            if (env('APP_ENV', 'development') == 'production') {
                $title = 'Auto inssue code: ' . md5($exception->getMessage());
                $backtrace = Crypt::encrypt($exception->getMessage()."<br>".$exception->getTraceAsString());
                $issues = array_map(function ($value) {
                    return $value['title'];
                }, Github::issues()->all('guifabrin', 'FinancasLaravel'));
                if (!in_array($title, $issues)) {
                    Github::issues()->create('guifabrin', 'FinancasLaravel', [
                        'title' => $title,
                        'body' => 'This is a auto inssue created by ' . env('APP_URL') . ' for security reasons is encoded:<br> '. $backtrace
                    ]);
                }
            }
        } catch (Exception $githubException) {

        }
        return parent::render($request, $exception);
    }

}
