<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{
    protected $statusCode = 200;

    const CODE_WRONG_ARGS     = 'CODE_WRONG_ARGS';
    const CODE_NOT_FOUND      = 'CODE_NOT_FOUND';
    const CODE_INTERNAL_ERROR = 'CODE_INTERNAL_ERROR';
    const CODE_UNAUTHORIZED   = 'CODE_UNAUTHORIZED';
    const CODE_FORBIDDEN      = 'CODE_FORBIDDEN';


    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }


    /**
     * @param string $message
     * @return Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = "Not Found!")
    {
        return $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * @param string $message
     */
    public function respondInternalError($message = "Internal Error!")
    {
        return $this->setStatusCode(HttpResponse::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    /**
     * @param       $data
     * @param array $headers
     * @return JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    protected function respondCreated($message): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_ACCEPTED)
            ->respond([
                'message' => $message
            ]);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    protected function respondUpdated($message): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_ACCEPTED)
            ->respond([
                'message' => $message
            ]);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    protected function respondWithOKMessage($message): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_ACCEPTED)
            ->respond([
                'message' => $message
            ]);
    }

    /**
     * @return JsonResponse
     */
    protected function respondOK(): JsonResponse
    {
        return $this->respondWithOKMessage('');
    }
    /**
     * @param $message
     * @return JsonResponse
     */
    protected function respondDeleted($message): JsonResponse
    {
        return $this->setStatusCode(HttpResponse::HTTP_ACCEPTED)
            ->respond([
                'message' => $message
            ]);
    }

    /**
     * @param $paginator
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithPagination(LengthAwarePaginator $paginator, $data): \Illuminate\Http\JsonResponse
    {
        $data = array_merge($data, [
            'paginator' => [
                'total_count'  => $paginator->total(),
                'total_pages'  => ceil($paginator->total() / $paginator->perPage()),
                'current_page' => $paginator->currentPage(),
                'limit'        => $paginator->perPage()
            ]
        ]);
        return $this->respond($data);
    }

    /**
     * @param       $message
     * @param       $errorCode
     * @param array ...$detail
     * @return Illuminate\Http\JsonResponse
     */
    protected function respondWithError($message, $errorCode, ...$detail)
    {
        if ($this->statusCode === 200) {
            trigger_error(
                "You better have a really good reason for erroring on a 200...",
                E_USER_WARNING
            );
        }

        return $this->respondWithArray([
            'code'    => $errorCode,
            'title'   => $message,
            'details' => $detail,
        ]);
    }


    /**
     * Generates a Response with a 403 HTTP header and a given message.
     * @param string $message
     * @return Illuminate\Http\JsonResponse
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)
            ->respondWithError($message, self::CODE_FORBIDDEN);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     * @param string $message
     * @return Illuminate\Http\JsonResponse
     */
    public function errorInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)
            ->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     * @param string $message
     * @return Illuminate\Http\JsonResponse
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)
            ->respondWithError($message, self::CODE_NOT_FOUND);
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     * @param string $message
     * @return Illuminate\Http\JsonResponse
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)
            ->respondWithError($message, self::CODE_UNAUTHORIZED);
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     * @param string $message
     * @return Illuminate\Http\JsonResponse
     */
    public function errorWrongArgs($message = 'Wrong Arguments')
    {
        return $this->setStatusCode(400)
            ->respondWithError($message, self::CODE_WRONG_ARGS);
    }

    /**
     * response with data
     * @param  mixed $array   the data to return
     * @param  mixed $headers the header to return
     * @return Illuminate\Http\JsonResponse
     */
    public function respondWithArray(array $array, array $headers = [])
    {
        if ($this->isSuccessResponse()) {
            $apiResponse = [
                'status' => 'ok',
                'data'   => $array,
            ];
        } else {
            $apiResponse = [
                'status' => 'error',
                'error'  => $array,
            ];
        }

        return response()
            ->json($apiResponse, $this->statusCode, $headers);
    }

    public function isSuccessResponse()
    {
        return preg_match('/2\d\d/', $this->statusCode) === 1;
    }
}
