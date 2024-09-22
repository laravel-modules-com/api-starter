<?php

declare(strict_types=1);

namespace Modules\App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;

trait ApiResponses
{
    protected function error(string $message = 'Ok', int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function ok(string $message = 'Ok', array $data = []): JsonResponse
    {
        return $this->success($message, $data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function success(string $message, array $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $statusCode,
        ], $statusCode);
    }
}
