<?php


namespace App\Http\Forms\UserPaperFav;


use Illuminate\Foundation\Http\FormRequest;

class UserPaperFavAddForm extends FormRequest
{
    public function rules()
    {
        return [
            'paper_id' => ['required', 'integer']
        ];
    }
}