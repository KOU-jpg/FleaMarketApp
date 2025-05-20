<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Item;

class UserController extends Controller
{
//マイページを表示
    public function show(Request $request)
    {
        $user = auth()->user();
        $page = $request->query('page', 'buy'); // デフォルトは「購入した商品」

        if ($page === 'buy') {
            // 購入した商品（例：itemsテーブルにbuyer_idがある場合）
            $items = Item::where('buyer_id', $user->id)->with('images')->latest()->get();
        } elseif ($page === 'sell') {
            // 出品した商品
            $items = Item::where('user_id', $user->id)->with('images')->latest()->get();
        } else {
            // デフォルト（購入した商品）
            $items = Item::where('buyer_id', $user->id)->with('images')->latest()->get();
        }

        return view('users.mypage', [
            'user' => $user,
            'items' => $items,
            'page' => $page
        ]);
    }

//プロフィール変更画面を表示
    public function editProfile()
    {
        $user = auth()->user();
        return view('users.edit', compact('user'));
    }

//プロフィール変更処理
    public function updateProfile(ProfileRequest $request)
    {
        $user = auth()->user();

        DB::transaction(function () use ($request, $user) {
            $profileData = [
                'postal_code' => $request->input('postal_code'),
                'address' => $request->input('address'),
                'building' => $request->input('building'),
            ];

            // プロフィール画像の更新処理
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $path = $image->store('Images/profile_images', 'public');
                        // シーディング用画像かどうかを判定
                        if ($user->profile && $user->profile->image_path) {
                            if (!Str::startsWith($user->profile->image_path, 'Images/profile_images_sample/')) {
                                Storage::disk('public')->delete($user->profile->image_path);
                            }
                        }
                $profileData['image_path'] = $path;
                }

            // ユーザー名更新
            $user->name = $request->input('name');
            $user->save();

            // プロフィール情報更新（画像含む）
            Profile::updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
        });
        return redirect()->route('mypage');
    }
}
