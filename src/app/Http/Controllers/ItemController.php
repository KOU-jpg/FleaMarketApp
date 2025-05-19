<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemImage;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class ItemController extends Controller
{
    public function address()  { return view('order/edit_address');}

    public function store(ExhibitionRequest $request)
    {
        $item = new Item();
        $item->user_id = Auth::id();
        $item->name = $request->product_name;
        $item->description = $request->description;
        $item->brand = $request->brand;
        $item->price = $request->price;
        $item->condition_id = $request->condition;
        $item->save();

        // ★ここがポイント！カテゴリーIDを配列に変換してattach
        if ($request->filled('category')) {
            $categories = is_array($request->category)
                ? $request->category
                : array_filter(explode(',', $request->category));
            $item->categories()->attach($categories);
        }

        // 画像保存
        $path = $request->file('product_image')->store('Images/Item_images', 'public');
        $itemImage = new ItemImage();
        $itemImage->item_id = $item->id;
        $itemImage->path = $path;
        $itemImage->order = 1;
        $itemImage->save();

        return redirect()->route('mypage');
    }
    public function show(Item $item)
    {
        // 商品の関連データを事前にロード
        $item->load([
            'images',                // 商品画像
            'categories',            // カテゴリ（多対多）
            'condition',             // 商品状態
            'user.profile',          // 出品者プロフィール
            'user.profileImage',     // 出品者プロフィール画像
        ]);

        // コメントと、そのユーザー＆ユーザーのプロフィール画像をまとめてロード
        $comments = $item->comments()
            ->with(['user.profileImage'])
            ->oldest()
            ->get();

        return view('main.detail', [
            'item' => $item,
            'comments' => $comments
        ]);
    }


    public function store_comment(CommentRequest $request)
    {
        // バリデーション
        $validated = $request->validate([
            'comment' => 'required|max:1000',
            'item_id' => 'required|exists:items,id'
        ]);

        // コメント保存
        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $validated['item_id'],
            'comment' => $validated['comment']
        ]);

        // 元のページにリダイレクト
        return redirect()->route('items.show', $validated['item_id'])
                         ->with('success', 'コメントを投稿しました');
    }


}






