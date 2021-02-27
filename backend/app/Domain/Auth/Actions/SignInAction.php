<?php

namespace App\Domain\Auth\Actions;

use App\Domain\Auth\Utilities\PassportUtility;

class SignInAction
{
  /** @var PassportUtility */
  protected $utility;

  public function __construct(PassportUtility $utility)
  {
    $this->utility = $utility;
  }

  public function execute(array $data = [])
  {
    return $this->utility->generateAccessToken($data);
  }
}
