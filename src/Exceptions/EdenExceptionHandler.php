<?php

namespace Dgharami\Eden\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as CoreExceptionHandler;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class EdenExceptionHandler extends CoreExceptionHandler
{

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ( $isEden = in_array('eden', ($request->route()->computedMiddleware ?? [])) ) {
            $statusCode = 500;

            if ($e instanceof HttpExceptionInterface) {
                $statusCode = $e->getStatusCode();
            }

            if ($e instanceof AuthorizationException) {
                $statusCode = 403;
            }

            if ($statusCode === 403) {
                return Response::view('eden::errors.403')
                    ->setStatusCode($statusCode);
            } elseif ($statusCode === 404) {
                return Response::view('eden::errors.404')
                    ->setStatusCode($statusCode);
            }
        }

        return parent::render($request, $e);
    }

}
