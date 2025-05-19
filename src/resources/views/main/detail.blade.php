<!-- 商品詳細画面 -->
@extends('layouts.app')

@section('title')
detail
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('header')

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
    <div class="product-price">¥{{ number_format($item->price) }} <span class="tax-in">（税込）</span></div>
    <div class="product-icons" data-item-id="{{ $item->id }}">
      <button type="button"
      class="icon-star {{ auth()->check() && $item->favorites()->where('user_id', auth()->id())->exists() ? 'active' : '' }}"
      aria-pressed="{{ auth()->check() && $item->favorites()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }}"
      aria-label="お気に入り登録">
      ☆<span class="icon-count">{{ $item->favorite_count ?? 0 }}</span>
      </button>
      <span class="icon-comment" aria-label="コメント数">
      💬<span class="icon-count">{{ $item->comments()->count() }}</span>
      </span>
    </div>
    <div id="favorite-error-message" style="color: red; margin-top: 8px; display: none;"></div>

    {{-- ログイン状態をJSに渡す --}}
    <script>
      const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
      document.addEventListener('DOMContentLoaded', function () {
      // お気に入りボタンの処理
      const starIcon = document.querySelector('.icon-star');
      const errorMessage = document.getElementById('favorite-error-message');

      if (starIcon) {
      starIcon.addEventListener('click', function () {
      if (isLoggedIn) {
      errorMessage.style.display = 'none'; // エラーメッセージ非表示
      const countElement = this.querySelector('.icon-count');
      const itemId = this.closest('.product-icons').dataset.itemId;
      const isActive = this.classList.contains('active');

      // 見た目を先に更新（UX向上）
      this.classList.toggle('active');
      this.setAttribute('aria-pressed', !isActive);
      let count = parseInt(countElement.textContent);
      countElement.textContent = isActive ? (count - 1) : (count + 1);

      // サーバーへ送信
      fetch('/favorite/toggle', {
      method: 'POST',
      headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
      item_id: itemId,
      action: isActive ? 'remove' : 'add'
      })
      })
      .then(response => response.json())
      .then(data => {
      if (!data.success) {
      // エラー時は元に戻す
      this.classList.toggle('active');
      this.setAttribute('aria-pressed', isActive);
      countElement.textContent = count;
      errorMessage.textContent = 'お気に入り処理に失敗しました。';
      errorMessage.style.display = 'block';
      console.error('お気に入り処理に失敗しました');
      } else {
      // サーバーからの正確な数を反映
      countElement.textContent = data.count;
      }
      })
      .catch(error => {
      // エラー時は元に戻す
      this.classList.toggle('active');
      this.setAttribute('aria-pressed', isActive);
      countElement.textContent = count;
      errorMessage.textContent = '通信エラーが発生しました。';
      errorMessage.style.display = 'block';
      console.error('エラーが発生しました:', error);
      });
      } else {
      // 未ログイン時
      window.location.href = "{{ route('login') }}";
      }
      });
      }
      });
    </script>
    <div class="product-actions">
    @if($item->sold_at)
        <button class="purchase-btn soldout" disabled>売り切れました</button>
    @else
        <a href="{{ route('purchase.show', ['item' => $item->id]) }}" class="purchase-btn">購入手続きへ</a>
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
      <div class="comment-bubble">
      {{ $comment->comment }}
      </div>
      <div class="comment-header">
      <span class="comment-user-icon">
        @if(optional($comment->user->profileImage)->path)
        <img src="{{ asset('storage/' . $comment->user->profileImage->path) }}" alt="icon" style="width:36px; height:36px; border-radius:50%;">
        @else
        <span style="display:inline-block;width:36px;height:36px;background:#ccc;border-radius:50%;"></span>
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
      <button type="submit" class="comment-submit-btn">コメントを投稿する</button>
    </form>
    </section>
    </div>
    </div>
    </div>
@endsection

