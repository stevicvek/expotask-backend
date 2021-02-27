<?php

namespace App\Domain\Auth\Actions;

use App\Domain\Auth\Utilities\PassportUtility;

class RefreshTokenAction
{
  /** @var PassportUtility */
  protected $utility;

  public function __construct(PassportUtility $utility)
  {
    $this->utility = $utility;
  }

  /**
   * Execute action.
   */
  public function execute()
  {
    return $this->utility->refreshAccessToken();
  }
}
