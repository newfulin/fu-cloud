<?php

namespace App\Handlers;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionsHandler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldntReport($e)) {
            return;
        }
        Log::error($e->getMessage());
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if(Config::get('app.debug')) dd($e);
        switch ($e) {
            case ($e instanceof AuthorizationException) :
                $result = $this->getAuthorizationException($e);
                break;
            case ($e instanceof \ReflectionException):
                $result = $this->getReflectionException($e);
                break;
            case ($e instanceof FatalErrorException) :
                $result = $this->getFatalErrorException($e);
                break;
            case ($e instanceof ModelNotFoundException):
                $result = $this->getModelNotFoundException($e);
                break;
            case ($e instanceof ValidationException) :
                $result = $this->getValidationException($e);
                break;
            case ($e instanceof HttpException) :
                $result = $this->getHttpException($e);
                break;
            case ($e instanceof \RuntimeException) :
                $result = $this->getRuntimeException($e);
                break;
            default :
                $result = ['code' => $e->getCode(), 'msg' => $e->getMessage()];
        }
        return response()->json(['exception' => $result]);

    }

    protected function getAuthorizationException(AuthorizationException $e)
    {
        return ['code' => 701, 'msg' => $e->getMessage()];
    }

    protected function getReflectionException(\ReflectionException $e)
    {
        return ['code' => 702, 'msg' => $e->getMessage()];
    }

    protected function getFatalErrorException(FatalErrorException $e)
    {
        return ['code' => 703, 'msg' => $e->getMessage()];
    }

    protected function getModelNotFoundException(ModelNotFoundException $e)
    {
        return ['code' => 704, 'msg' => $e->getMessage()];
    }

    protected function getHttpException(HttpException $e)
    {
        return [
            'code' => $e->getStatusCode(),
            'msg' => "HTTP请求错误,检查POST/GET"
        ];
    }

    protected function getValidationException(ValidationException $e)
    {
        $errors = $e->validator->errors();
        $msg = "";
        foreach ($errors->getMessages() as $item) {
            $msg .= $item[0];
        }
        return ['code' => 705, 'msg' => $msg];
    }

    protected function getRuntimeException(\RuntimeException $e)
    {
         return ['code' => 706, 'msg' => $e->getMessage()];
    }

}
