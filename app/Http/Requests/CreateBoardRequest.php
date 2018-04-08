<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBoardRequest extends FormRequest
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

            //
            $id = $this->route('board');
        return [
            //
            'name'=>'required|unique:board,name,'.$id,
            'url'=>'required|unique:board,url,'.$id,
            'intro'=>'required'
        ];
    }
}
