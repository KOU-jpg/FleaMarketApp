<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 認証済みユーザーのみ許可する場合は true
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
            'payment_method' => 'required|in:convenience,card',
            'address_id' => 'required|exists:profiles,id',
        ];
    }

    /**
     * エラーメッセージをカスタマイズしたい場合
     */
    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください。',
            'address_id.required' => '配送先を選択してください。',
        ];
    }
}
