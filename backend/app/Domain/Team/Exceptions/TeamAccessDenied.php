<?php

namespace App\Domain\Team\Exceptions;

use Exception;
use App\Traits\Responser;
use Illuminate\Http\Response;

class TeamAccessDenied extends Exception
{
  use Responser;

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
    return $this->sendErrorResponse(null, 'You can\'t access this team!', Response::HTTP_FORBIDDEN);
  }
}
