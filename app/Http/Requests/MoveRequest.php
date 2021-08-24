<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoveRequest extends FormRequest
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
            'who' => ['required','exists:categories,id'],
            'where' => ['required'],
        ];
    }
    public function attributes()
    {
        return[
            'who' => 'id',
            'where' => 'id',
        ];
    
    }
}
