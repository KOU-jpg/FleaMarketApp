<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class FavoriteController extends Controller
{

    public function toggle(Request $request)
    {
        $userId = Auth::id();
        $itemId = $request->input('item_id');
        $action = $request->input('action'); // 'add' or 'remove'

        if (!$itemId || !in_array($action, ['add', 'remove'])) {
            return response()->json(['success' => false, 'message' => 'Invalid parameters']);
        }

        if ($action === 'add') {
            Favorite::firstOrCreate([
                'user_id' => $userId,
                'item_id' => $itemId,
            ]);
        } else { // remove
            Favorite::where('user_id', $userId)
                    ->where('item_id', $itemId)
                    ->delete();
        }

        // お気に入りの合計数を取得
        $count = Favorite::where('item_id', $itemId)->count();

        // itemsテーブルのfavorite_countカラムを更新
        Item::where('id', $itemId)->update(['favorite_count' => $count]);

        return response()->json(['success' => true, 'count' => $count]);
    }
}
