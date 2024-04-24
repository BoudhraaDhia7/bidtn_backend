<?php

namespace App\Helpers;

use Illuminate\Http\Response;

class ResponseHelper
{
    /**
     * Get HTTP status code by code
     *
     * @param int $code
     * @return int
     */
    public static function resolveStatusCode(int $code): int
    {
        switch ($code) {
            case 100:
                return Response::HTTP_CONTINUE;
            case 101:
                return Response::HTTP_SWITCHING_PROTOCOLS;
            case 200:
                return Response::HTTP_OK;
            case 201:
                return Response::HTTP_CREATED;
            case 202:
                return Response::HTTP_ACCEPTED;
            case 203:
                return Response::HTTP_NON_AUTHORITATIVE_INFORMATION;
            case 204:
                return Response::HTTP_NO_CONTENT;
            case 205:
                return Response::HTTP_RESET_CONTENT;
            case 206:
                return Response::HTTP_PARTIAL_CONTENT;
            case 300:
                return Response::HTTP_MULTIPLE_CHOICES;
            case 301:
                return Response::HTTP_MOVED_PERMANENTLY;
            case 302:
                return Response::HTTP_FOUND;
            case 303:
                return Response::HTTP_SEE_OTHER;
            case 304:
                return Response::HTTP_NOT_MODIFIED;
            case 305:
                return Response::HTTP_USE_PROXY;
            case 307:
                return Response::HTTP_TEMPORARY_REDIRECT;
            case 400:
                return Response::HTTP_BAD_REQUEST;
            case 401:
                return Response::HTTP_UNAUTHORIZED;
            case 402:
                return Response::HTTP_PAYMENT_REQUIRED;
            case 403:
                return Response::HTTP_FORBIDDEN;
            case 404:
                return Response::HTTP_NOT_FOUND;
            case 405:
                return Response::HTTP_METHOD_NOT_ALLOWED;
            case 406:
                return Response::HTTP_NOT_ACCEPTABLE;
            case 407:
                return Response::HTTP_PROXY_AUTHENTICATION_REQUIRED;
            case 408:
                return Response::HTTP_REQUEST_TIMEOUT;
            case 409:
                return Response::HTTP_CONFLICT;
            case 410:
                return Response::HTTP_GONE;
            case 411:
                return Response::HTTP_LENGTH_REQUIRED;
            case 412:
                return Response::HTTP_PRECONDITION_FAILED;
            case 413:
                return Response::HTTP_REQUEST_ENTITY_TOO_LARGE;
            case 414:
                return Response::HTTP_REQUEST_URI_TOO_LONG;
            case 415:
                return Response::HTTP_UNSUPPORTED_MEDIA_TYPE;
            case 416:
                return Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE;
            case 417:
                return Response::HTTP_EXPECTATION_FAILED;
            case 500:
                return Response::HTTP_INTERNAL_SERVER_ERROR;
            case 501:
                return Response::HTTP_NOT_IMPLEMENTED;
            case 502:
                return Response::HTTP_BAD_GATEWAY;
            case 503:
                return Response::HTTP_SERVICE_UNAVAILABLE;
            case 504:
                return Response::HTTP_GATEWAY_TIMEOUT;
            case 505:
                return Response::HTTP_VERSION_NOT_SUPPORTED;
            default:
                return Response::HTTP_NOT_FOUND;
        }
    }
}
