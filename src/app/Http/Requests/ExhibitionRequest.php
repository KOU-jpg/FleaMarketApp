<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
    public function rules(){
    return [
        'product_image' => 'required|file|mimes:jpeg,png',
        'product_name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'brand' => 'required|string|max:255',
        'category' => 'required',
        'condition' => 'required',
        'price' => 'required|integer|min:1',
    ];}
    public function messages(){
    return [
        'product_image.required' => '商品画像は必須です',
        'product_image.mimes' => '画像はjpegまたはpng形式でアップロードしてください',
        'product_name.required' => '商品名は必須です',
        'product_name.string' => '使用不可能な文字が含まれています',
        'product_name.max' => '商品名は255文字以内で入力してください',
        'description.required' => '商品説明は必須です',
        'description.max' => '商品説明は255文字以内で入力してください',
        'description.string' => '使用不可能な文字が含まれています',
        'brand.required' => '商品説明は必須です',
        'brand.max' => '商品説明は255文字以内で入力してください',
        'brand.string' => '使用不可能な文字が含まれています',
        'category.required' => 'カテゴリーは必須です',
        'condition.required' => '商品の状態は必須です',
        'price.required' => '価格は必須です',
        'price.integer' => '不適切な金額が入力されています',
        'price.min' => '価格は0円以上で入力してください',
    ];}
}