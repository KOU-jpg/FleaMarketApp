<!-- 商品出品画面 -->
@extends('layouts.app')

@section('title')
sell
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('header')

@endsection

@section('content')
    <h1>商品の出品</h1>
    <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" novalidate>
    @csrf
    <section>
    <div class="form-group">
    <div class="form-header">
    <label>商品画像</label>
    <div class="error-message">
      @error('product_image')
      {{ $message }}
      @enderror
    </div>
    </div>
    <div class="image-upload-box" id="image-upload-box">
    <div id="preview" style="width:100%; display:flex; justify-content:center; align-items:center;"></div>
    <input id="product-image" name="product_image" type="file" accept="image/*" style="display:none;">
    </div>
    <button type="button" class="image-upload-btn" id="customBtn" style="display:block; margin:0 auto;">
    画像を選択する
    </button>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('product-image');
    const button = document.getElementById('customBtn');
    const preview = document.getElementById('preview');

    // ファイル選択時の処理（プレビュー表示のみ）
    input.addEventListener('change', (e) => {
    preview.innerHTML = '';
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
      const img = document.createElement('img');
      img.src = e.target.result;
      preview.appendChild(img);
      button.textContent = '画像を変更する';
    };
    reader.readAsDataURL(file);
    });

    // ボタンクリックでファイル選択ダイアログを開く
    button.addEventListener('click', () => {
    input.click();
    });
    });
    </script>
    </section>


    <section>
    <h2>商品の詳細</h2>
    <hr>
    <div class="form-group">
      <div class="form-header">
      <label>カテゴリー</label>
      <div class="error-message">
        @error('category')
        {{ $message }}
        @enderror
      </div>
      </div>
      <div class="category-tags">
      @foreach($categories as $category)
        <button type="button"
        class="category-tag{{ (is_array(old('category')) && in_array($category->id, old('category', []))) ? ' selected' : '' }}"
        data-id="{{ $category->id }}">
        {{ $category->name }}
        </button>
      @endforeach
      </div>
      <input type="hidden" name="category" id="category"
      value="{{ old('category') ? implode(',', (array) old('category')) : '' }}">
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      const tags = document.querySelectorAll('.category-tag');
      const hidden = document.getElementById('category');
      let selectedIds = hidden.value ? hidden.value.split(',').filter(Boolean) : [];

      // 初期表示時のselected
      tags.forEach(tag => {
      if (selectedIds.includes(tag.dataset.id)) {
        tag.classList.add('selected');
      }
      });

      tags.forEach(tag => {
      tag.addEventListener('click', function () {
        const id = this.dataset.id;
        if (this.classList.contains('selected')) {
        // 選択解除
        this.classList.remove('selected');
        selectedIds = selectedIds.filter(val => val !== id);
        } else {
        // 選択
        this.classList.add('selected');
        selectedIds.push(id);
        }
        // hiddenにセット（カンマ区切り）
        hidden.value = selectedIds.join(',');
      });
      });
    });
    </script>


    <div class="form-group">
    <div class="form-header">
    <label for="condition">商品の状態</label>
    <div class="error-message">
      @error('condition')
      {{ $message }}
      @enderror
    </div>
    </div>
    <select id="condition" name="condition">
    <option value="" {{ old('condition') == '' ? 'selected' : '' }}>選択してください</option>
    <option value="1" {{ old('condition') == '1' ? 'selected' : '' }}>良好</option>
    <option value="2" {{ old('condition') == '2' ? 'selected' : '' }}>目立った傷や汚れなし</option>
    <option value="3" {{ old('condition') == '3' ? 'selected' : '' }}>やや傷や汚れあり</option>
    <option value="4" {{ old('condition') == '4' ? 'selected' : '' }}>状態が悪い</option>
    </select>
    </div>

    <section>
      <h2>商品名と説明</h2>
      <hr>
      <div class="form-group">
      <div class="form-group">
      <div class="form-group">
    <div class="form-header">
      <label for="product_name">商品名</label>
      <div class="error-message">
      @error('product_name')
        {{ $message }}
      @enderror
      </div>
    </div>
    <input type="text" id="product_name" name="product_name" value="{{ old('product_name') }}" autocomplete="off">
    </div>
    <div class="form-header">
      <label for="brand">ブランド名</label>
      <div class="error-message">
      @error('brand')
        {{ $message }}
      @enderror
      </div>
    </div>
    <input type="text" id="brand" name="brand" value="{{ old('brand') }}" autocomplete="off">
    </div>

    <div class="form-group">
    <div class="form-header">
      <label for="description">商品の説明</label>
      <div class="error-message">
      @error('description')
        {{ $message }}
      @enderror
      </div>
    </div>
    <textarea id="description" name="description">{{ old('description') }}</textarea>
    </div>

      <div class="form-group">
      <div class="form-header">
      <label for="price">販売価格</label>
      <div class="error-message">
        @error('price')
        {{ $message }}
        @enderror
      </div>
      </div>
      <input  type="text"  id="price"  name="price"  placeholder="¥"  value="{{ old('price') }}" autocomplete="off">
      </div>
    </section>
    <button type="submit" class="submit-btn">出品する</button>
    </form>

@endsection
