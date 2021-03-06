<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait Responser
{
  /**
   * Send success response
   * 
   * @param $data
   * @param int $code
   * @param string $message
   * @return JsonResponse
   */
  protected function sendSuccessResponse($data, string $message, int $code = Response::HTTP_OK): JsonResponse
  {
    return response()
      ->json([
        'success' => true,
        'code' => $code,
        'message' => $message,
        'data' => $data,
      ], $code);
  }

  /**
   * Send error response
   * 
   * @param $data
   * @param int $code
   * @param string $message
   * @return JsonResponse
   */
  protected function sendErrorResponse($data, string $message, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
  {
    return response()
      ->json([
        'success' => false,
        'code' => $code,
        'message' => $message,
        'data' => $data,
      ], $code);
  }
}
