<!-- 商品詳細画面 -->
@extends('layouts.app')

@section('title', 'detail')

@section('css')
      <link rel="stylesheet" href="{{ asset('css/pages/detail.css') }}">
@endsection

@section('content')
      <div class="product-detail-container">
        <div class="product-image-area">
        <div class="product-image-box">
          @if($item->sold_at)
              <div class="sold-label">SOLD</div>
        @endif
          @if($item->images->count())
              <img src="{{ asset('storage/' . $item->images->first()->path) }}" alt="{{ $item->name }}">
        @else
            <span>画像なし</span>
        @endif
        </div>
        </div>
        <div class="product-info-area">
        <h1 class="product-title">{{ $item->name }}</h1>
        <div class="product-brand">{{ $item->brand }}</div>
        <div class="product-price">
          ¥{{ number_format($item->price) }}
          <span class="tax-in">（税込）</span>
        </div>
        <div class="product-icons" data-item-id="{{ $item->id }}">
          <form action="{{ route('favorites.toggle', $item->id) }}" method="POST" style="display:inline;">
              @csrf
              <button type="submit"
                  class="icon-star {{ auth()->check() && $item->favorites()->where('user_id', auth()->id())->exists() ? 'active' : '' }}"
                  aria-pressed="{{ auth()->check() && $item->favorites()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }}"
                  aria-label="お気に入り登録">
                  ☆<span class="icon-count">{{ $item->favorites()->count() }}</span>
              </button>
          </form>
          <span class="icon-comment" aria-label="コメント数">
          💬<span class="icon-count">{{ $item->comments()->count() }}</span>
          </span>
        </div>
        <div id="favorite-error-message" style="color: red; margin-top: 8px; display: none;"></div>
        <div class="product-actions">
          @if($item->sold_at)
              <button class="purchase-btn soldout" disabled>売り切れました</button>
          @else
          @if(Auth::check() && Auth::user()->hasVerifiedEmail())
              <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="purchase-btn">購入手続きへ</a>
            @elseif(Auth::check())
        <a href="http://localhost:8025" target="_blank" class="purchase-btn" rel="noopener noreferrer">メール認証して購入手続きへ</a>
          @else
              <a href="{{ route('login.form') }}" class="purchase-btn">ログインして購入手続きへ</a>
          @endauth
          @endif
        </div>
        
        <section class="product-description-section">
          <h2 class="section-title">商品説明</h2>
          <div class="product-desc">
          {{ $item->condition->name }}<br>
          {{ $item->description }}
          </div>
        </section>
        <section class="product-info-section">
          <h2 class="section-title">商品の情報</h2>
          <div class="product-meta">
          <div class="meta-label">カテゴリー</div>
          @foreach($item->categories as $category)
              <span class="category-tag">{{ $category->name }}</span>
        @endforeach
          </div>
        </section>
        <section class="product-comments-section">
            <h2 class="section-title">コメント ({{ $comments->count() }})</h2>
            <div class="comment-list">
                @foreach($comments->sortByDesc('created_at') as $comment)
                    @php
                        $isMine = auth()->check() && $comment->user_id === auth()->id();
                    @endphp
                    <div class="comment-item {{ $isMine ? 'my-comment' : 'other-comment' }}">
                        <div class="comment-bubble">{{ $comment->comment }}</div>
                        <div class="comment-header">
                            <span class="comment-user-icon">
                                @if(optional($comment->user->profile)->image_path)
                                    <img src="{{ asset('storage/' . $comment->user->profile->image_path) }}" alt="icon" class="profile-image">
                                @else
                                    <span class="profile-image-placeholder"></span>
                                @endif
                            </span>
                            <span class="comment-user">{{ $comment->user->name }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <form method="POST" action="{{ route('comments.store') }}" class="comment-form-section" novalidate>
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <label for="comment" class="comment-label">商品へのコメント</label>
                <textarea id="comment" name="comment" class="comment-textarea" required>{{ old('comment') }}</textarea>
                @error('comment')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                @if($item->sold_at)
                    <button class="comment-submit-btn soldout" disabled>売り切れました</button>
                @elseif(Auth::check() && Auth::user()->hasVerifiedEmail())
                <button type="submit" class="comment-submit-btn">コメントを投稿する</button>
                @elseif(Auth::check())
                <a href="http://localhost:8025" target="_blank" class="comment-login-btn" rel="noopener noreferrer">メール認証してください</a>
                @else
                <a href="{{ route('login.form') }}" class="comment-login-btn">ログインしてコメントする</a>
                @endauth
            </form>
        </section>
        </div>
      </div>
@endsection