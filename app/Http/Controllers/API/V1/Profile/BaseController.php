<?php

namespace App\Http\Controllers\API\V1\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class BaseController extends Controller
{
    // Define HTTP status codes for responses
    protected const HTTP_OK = 200;
    protected const HTTP_INTERNAL_SERVER_ERROR = 500;

    // Default status code for responses
    protected int $statusCode = self::HTTP_OK;

    /**
     * Get the current status code.
     *
     * @return int Status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set the status code for the response and return the instance.
     *
     * @param int $statusCode HTTP status code
     * @return self
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Generate a JSON response with the provided data and headers.
     *
     * @param array $data Response data
     * @param array $headers Custom headers (optional)
     * @return JsonResponse
     */
    protected function respond(array $data, array $headers = []): JsonResponse
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Generate a successful response with data.
     *
     * @param mixed $data Response data
     * @param int $status_code HTTP status code (default: 200)
     * @param string|null $message Optional message
     * @return JsonResponse
     */
    protected function respondData(mixed $data, string $message = null, int $status_code = self::HTTP_OK): JsonResponse
    {
        return $this->setStatusCode($status_code)
            ->respond([
                'status' => true,
                'code' => $status_code,
                'message' => $message,
                'data' => $data,
                'errors' => []
            ]);
    }

    /**
     * Build pagination response data.
     *
     * @param $items
     * @param string|null $message Optional message
     * @param int $status_code HTTP status code (default: 200)
     * @return JsonResponse
     */
    protected function respondWithPagination($items, string $message = null, int $status_code = self::HTTP_OK): JsonResponse
    {
        return $this->setStatusCode($status_code)->respond([
            'status' => true,
            'code' => $status_code,
            'message' => $message,
            'data' => $items->items(),
            'paginator' => [
                'total_count' => $items->total(),
                'total_pages' => $items->lastPage(),
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
            ]
        ]);
    }

    /**
     * Generate a response with a message.
     *
     * @param string|null $message Optional message
     * @param int $status_code HTTP status code (default: 200)
     * @return JsonResponse
     */
    protected function respondMessage(string $message = null, int $status_code = self::HTTP_OK): JsonResponse
    {
        return $this->setStatusCode($status_code)
            ->respond([
                'status' => true,
                'code' => $status_code,
                'message' => $message,
                'data' => null,
                'errors' => []
            ]);
    }

    /**
     * Generate an error response with a message.
     *
     * @param string|null $message Optional error message
     * @param int $status_code HTTP status code (default: 500)
     * @return JsonResponse
     */
    protected function respondError(string $message = null, int $status_code = self::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return $this->setStatusCode($status_code)
            ->respond([
                'status' => false,
                'code' => $status_code,
                'message' => $message,
                'data' => null,
                'errors' => []
            ]);
    }
}
