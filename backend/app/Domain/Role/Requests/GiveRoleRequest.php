<?php

namespace App\Domain\Role\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiveRoleRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->check();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'user' => ['required', 'integer', 'exists:users,id'],
      'role' => ['required', 'string', 'in:admin,manager,member'],
    ];
  }
}
