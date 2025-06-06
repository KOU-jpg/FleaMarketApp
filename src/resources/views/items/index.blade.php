<!-- 商品一覧画面（トップ） -->
@extends('layouts.app')

@section('title')
    index
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/index.css') }}">
@endsection

@section('header')

@endsection

@section('content')

<nav class="pages">
    <a href="{{ url('/' . (request('keyword') ? '?keyword=' . urlencode(request('keyword')) : '')) }}" class="page {{ ($page ?? 'recommend') === 'recommend' ? 'active' : '' }}">おすすめ</a>
    @if(Auth::check() && Auth::user()->hasVerifiedEmail())
    <a href="{{ url('/?page=mylist' . (request('keyword') ? '&keyword=' . urlencode(request('keyword')) : '')) }}" class="page {{ ($page ?? '') === 'mylist' ? 'active' : '' }}">マイリスト</a>
    @elseif(Auth::check())
    <a href="http://localhost:8025" target="_blank" class="page" rel="noopener noreferrer">マイリスト</a>
    @else
    <a href="{{ route('login.form') }}" class="page">マイリスト</a>
    @endauth
</nav>
<hr>
<section class="item-list" id="exhibit-list">
    @forelse($items as $item)
        <a href="{{ route('items.detail', $item->id) }}" >
            <div class="item-card">
                <div class="item-image">
                    @if($item->sold_at)
                        <div class="sold-label">SOLD</div>
                    @endif
                    @if($item->images->count())
                        <img src="{{ asset('storage/' . $item->images->first()->path) }}" alt="{{ $item->name }}">
                    @else
                        <img src="{{ asset('images/no-image.png') }}" alt="No Image">
                    @endif
                </div>
                <div class="item-name">{{ $item->name }}</div>
            </div>
        </a>
    @empty
        <div></div>
    @endforelse
</section>
@endsection