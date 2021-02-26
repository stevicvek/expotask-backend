<?php

namespace App\Domain\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'firstname' => ['required', 'string', 'min:2', 'max:32'],
      'lastname' => ['required', 'string', 'min:3', 'max:32'],
      'email' => ['required', 'email', 'min:6', 'max:32'],
      'password' => ['required', 'string', 'min:6', 'max:32', 'confirmed'],
    ];
  }
}
