<?php

namespace App\Domain\Team\Exceptions;

use Exception;
use Illuminate\Http\Response;

class MembershipAlreadyAccepted extends Exception
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
      ->json(['error' => 'Membership is already accepted!'], Response::HTTP_NOT_ACCEPTABLE);
  }
}
