<!-- 商品購入処理確認画面 -->

@extends('layouts.app')

@section('title')
    complete
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/complete.css') }}">
@endsection

@section('header')
@endsection

@section('content')
    <div class="background-text">Thankyou</div>

    <div class="content">
        <h1>ご購入ありがとうございました</h1>
        <a href="{{ route('items.index')}}" class="content-button">ホーム画面へ</a>
    </div>
@endsection