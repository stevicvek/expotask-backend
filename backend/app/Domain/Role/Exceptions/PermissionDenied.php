<?php

namespace App\Domain\Role\Exceptions;

use Exception;
use Illuminate\Http\Response;

class PermissionDenied extends Exception
{
  /**
   * Report the exception.
   *
   * @return bool|null
   */
  public function report()
  {
    //
  }

  /**
   * Render the exception into an HTTP response.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function render($request)
  {
    return response()
      ->json(['error' => 'You don\'t have permission to do this!'], Response::HTTP_NOT_ACCEPTABLE);
  }
}
