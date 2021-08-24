<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Category;
use Symfony\Component\Console\Input\Input;

class ChangeRequest extends FormRequest
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
            'name1' => ['required','max:64','min:2'],
            'change_id' => ['required','exists:categories,id']
        ];
    }
    public function attributes()
{
    return[
        'name1' => 'name',
        'change_id' => 'id',
    ];

}
}
