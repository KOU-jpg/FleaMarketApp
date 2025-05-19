<!-- マイページ画面 -->
@extends('layouts.app')

@section('title')
mypage
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('header')
@endsection

@section('content')
<section class="profile-header">
    <div class="profile-avatar">
        @php
            $profileImage = \App\Models\ProfileImage::where('user_id', auth()->id())->first();
        @endphp

        @if($profileImage)
            <img src="{{ asset('storage/' . $profileImage->path) }}"
                 alt="プロフィール画像"
                 class="profile-image">
        @else
            <div class="profile-image-placeholder"></div>
        @endif
    </div>
    <div class="profile-info">
        <div class="profile-username">{{ auth()->user()->name }}</div>
        <button class="profile-edit-btn" onclick="location.href='{{ route('edit') }}'">プロフィールを編集</button>
    </div>
</section>

<!-- チェックボックスでモーダルのON/OFFを管理 -->
<input type="checkbox" id="modal-toggle" hidden>
<section class="item-list-wrapper" >
<nav class="modal-tabs">
    <button class="tab active" type="button">出品した商品</button>
    <label for="modal-toggle" class="tab">購入した商品</label>
</nav>
<hr>

<section class="item-list" id="exhibit-list">
    @foreach($userItems as $item)
      <a href="{{ route('items.show', $item->id) }}">
      <div class="item-card">
          <div class="item-image">
            @if($item->sold_at)
              <div class="sold-label">SOLD</div>
            @endif
            @if($item->images->count())
              <img src="{{ asset('storage/' . $item->images->first()->path) }}"
                   alt="{{ $item->name }}">
            @else
              <img src="{{ asset('images/no-image.png') }}"
                   alt="No Image">
            @endif
          </div>
        <div class="item-name">{{ $item->name }}</div>
      </div>
    </a>
    @endforeach
</section>
<!-- モーダル（購入商品一覧） -->
<div class="modal-overlay">
  <div class="modal-content">
    <!-- ここから -->
    <nav class="modal-tabs">
        <label for="modal-toggle" class="tab">出品した商品</label>
        <button class="tab active" type="button">購入した商品</button>
    </nav>
    <hr>
    <section class="item-list">
      @forelse($purchasedItems as $item)
        <a href="{{ route('items.show', $item->id) }}">
          <div class="item-card">
            <div class="item-image">
              @if($item->sold_at)
                <div class="sold-label">SOLD</div>
              @endif
              @if($item->images->count())
                <img src="{{ asset('storage/' . $item->images->first()->path) }}"
                     alt="{{ $item->name }}">
              @else
                <img src="{{ asset('images/no-image.png') }}"
                     alt="No Image">
              @endif
            </div>
            <div class="item-name">{{ $item->name }}</div>
          </div>
        </a>
      @empty
        <div>購入した商品はありません。</div>
      @endforelse
    </section>
    <!-- ここまで -->
  </div>
</div>
</section>


@endsection