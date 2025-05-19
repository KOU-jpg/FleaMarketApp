<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
        'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',
        'name'     => ['required', 'string', 'max:255'],
        'postal_code'  => ['required', 'regex:/^\d{3}-\d{4}$/', 'size:8'],
        'address'      => ['required', 'string', 'max:255'],
        'building'     => ['required', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
        'profile_image.image'   => '画像ファイルを選択してください',
        'profile_image.mimes'   => '画像ファイルはJPEGまたはPNG形式のみアップロードできます',
        'profile_image.max'     => '画像サイズは2MB以内にしてください',
        'name.required'         => 'ユーザー名を入力してください',
        'postal_code.required'  => '郵便番号を入力してください',
        'postal_code.regex'     => '郵便番号は「123-4567」の形式で入力してください',
        'postal_code.size'      => '郵便番号は8文字（例：123-4567）で入力してください',
        'address.required'      => '住所を入力してください',
        'building.required'     => '建物名を入力してください',
        ];
    }
}
