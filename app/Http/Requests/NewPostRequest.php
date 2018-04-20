<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewPostRequest extends FormRequest
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
            //
            'title'=>'required|unique:posts|max:255',
            'board'=>'required',
            'type'=>'required',
            'tag'=>'required',
            'content'=>'required',
            'background'=>'',
            'needintergation'=>'',
            'intergation'=>'numeric|between:1,30',
        ];
    }
}
