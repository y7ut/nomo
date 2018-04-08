<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditBoardRequest extends FormRequest
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
        if ($this->method()=='post') {
            //
            return [
                //
                'name'=>'required|unique:boards,name',
                'url'=>'required|unique:boards,url',
                'intro'=>'required',
                'banner'=>'required'
            ];
        }
        $id = substr($this->url(),strrpos($this->url(),'/')+1);
        return [
            //
            'name'=>'required|unique:boards,name,'.$id,
            'url'=>'required|unique:boards,url,'.$id,
            'intro'=>'required'
        ];
    }
}
