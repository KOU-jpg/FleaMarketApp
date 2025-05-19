<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Profile;
use App\Models\ProfileImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;





class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('mypage.edit', compact('user'));
    }
    public function mypage()
    {
        return view('mypage/home');
    }



    public function edit_profile(ProfileRequest $request)
    {
        $user = auth()->user();

        DB::transaction(function () use ($request, $user) {
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $path = $image->store('Images/profile_images', 'public');

                $existingImage = ProfileImage::where('user_id', $user->id)->first();
                if ($existingImage) {
                    // シーディング用画像かどうかを判定
                    if (!Str::startsWith($existingImage->path, 'Images/profile_images_sample/')) {
                        Storage::disk('public')->delete($existingImage->path);
                    }
                    $existingImage->path = $path;
                    $existingImage->save();
                } else {
                    ProfileImage::create([
                        'user_id' => $user->id,
                        'path' => $path,
                    ]);
                }
            }

            $user->name = $request->input('name');
            $user->save();

            $profileData = [
                'postal_code' => $request->input('postal_code'),
                'address' => $request->input('address'),
                'building' => $request->input('building'),
            ];

            Profile::updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
        });
        return redirect()->route('mypage');
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();
        // バリデーションやプロフィール画像保存など
        $user->name = $request->input('name');
        $user->profile->postal_code = $request->input('postal_code');
        $user->profile->address = $request->input('address');
        $user->profile->building = $request->input('building');
        $user->save();
        $user->profile->save();

        $itemId = session('last_purchase_item_id');
        if ($itemId) {
            return redirect()->route('purchase.show', ['item' => $itemId])
                ->with('success', '住所を更新しました');
        } else {
            // パラメータがなければマイページなどにリダイレクト
            return redirect()->route('mypage')
                ->with('success', '住所を更新しました');
        }
    }
}
