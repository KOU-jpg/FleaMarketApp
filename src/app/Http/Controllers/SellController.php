<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemImage;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;


class SellController extends Controller
{
//出品ページ表示
    public function showForm()  { return view('items.create');  }

//出品処理
    public function sell(ExhibitionRequest $request)
    {
        $item = new Item();
        $item->user_id = Auth::id();
        $item->name = $request->product_name;
        $item->description = $request->description;
        $item->brand = $request->brand;
        $item->price = $request->price;
        $item->condition_id = $request->condition;
        $item->save();

        // カテゴリーIDを配列に変換してattach
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
}
