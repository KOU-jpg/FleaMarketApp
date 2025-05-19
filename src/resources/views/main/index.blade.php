<!-- 商品一覧画面（トップ） -->
@extends('layouts.app')

@section('title')
index
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('header')

@endsection

@section('content')
<!-- チェックボックスでモーダルのON/OFFを管理 -->
<input type="checkbox" id="modal-toggle" hidden>
<section class="item-list-wrapper" >
<nav class="modal-tabs">
    <button class="tab active" type="button">おすすめ</button>
    <label for="modal-toggle" class="tab">マイリスト</label>
</nav>
<hr>

<section class="item-list" id="exhibit-list">
    @foreach($items as $item)
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
    <nav class="modal-tabs">
      <label for="modal-toggle" class="tab">おすすめ</label>
      <button class="tab active" type="button">マイリスト</button>
    </nav>
    <hr>
    <section class="item-list">
      @forelse($mylistItems as $item)
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
        <div></div>
      @endforelse
    </section>
  </div>
</div>
</section>
@endsection