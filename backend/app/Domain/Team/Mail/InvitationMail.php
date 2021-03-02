<?php

namespace App\Domain\Team\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Domain\Auth\Models\User;
use App\Domain\Team\Models\Team;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
	use Queueable, SerializesModels;

	protected User $user;
	protected string $code;

	/**
	 * Create a new message instance.
	 *
	 * @param User $user
	 * @param Team $team
	 * @param string $invitationCode
	 * @return void
	 */
	public function __construct(User $user, Team $team, string $invitationCode)
	{
		$this->user = $user;
		$this->team = $team;
		$this->code = $invitationCode;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this
			->view('mail.invation')
			->with([
				'user' => $this->user,
				'team' => $this->team,
				'code' => $this->code,
			]);
	}
}
