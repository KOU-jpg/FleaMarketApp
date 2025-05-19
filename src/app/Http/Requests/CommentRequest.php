<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認証済みユーザーのみ許可
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'item_id' => ['required', 'exists:items,id'],
            'comment' => ['required', 'string', 'max:200'],
        ];
    }
    public function messages(): array
    {
        return [
            'item_id.required' => '商品IDが指定されていません',
            'item_id.exists'   => '指定された商品が存在しません',
            'comment.required' => 'コメントを入力してください',
            'comment.string'   => 'コメントは文字列で入力してください',
            'comment.max'      => 'コメントは200文字以内で入力してください',
        ];
    }
}
