<?php

namespace App\Domain\Team\Requests;

use App\Domain\Team\Rules\Color;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return auth()->user();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'name' => ['required', 'string', 'min:6', 'max:32'],
      'color' => ['required', new Color],
    ];
  }
}
