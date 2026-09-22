@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="mb-4">
            <h1>
                @if($category)
                    {{ $category->title }}
                @else
                    Все товары
                @endif
            </h1>
            @if($category)
                <a href="{{ route('products.index') }}" class="text-muted text-decoration-none">
                    ← Все товары
                </a>
            @endif
        </div>

        <div class="row">
            <!-- Sidebar с категориями -->
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-header fw-bold">Категории</div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('products.index') }}"
                           class="list-group-item list-group-item-action {{ !$category ? 'active' : '' }}">
                            Все товары
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('products.by-category', $cat->slug) }}"
                               class="list-group-item list-group-item-action {{ $category?->id === $cat->id ? 'active' : '' }}">
                                {{ $cat->title }}
                            </a>
                            @foreach($cat->children->where('active', true) as $child)
                                <a href="{{ route('products.by-category', $child->slug) }}"
                                   class="list-group-item list-group-item-action ps-4 {{ $category?->id === $child->id ? 'active' : '' }}">
                                    {{ $child->title }}
                                </a>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Список товаров -->
            <div class="col-md-9">
                @if($products->count())
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    @if($product->image)
                                        <img src="{{ asset(Storage::disk('public')->url($product->image)) }}"
                                             class="card-img-top"
                                             alt="{{ $product->title }}"
                                             style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                             style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $product->title }}</h5>
                                        <p class="card-text text-muted small">
                                            {{ $product->category?->title ?? '' }}
                                        </p>
                                        @if($product->description)
                                            <p class="card-text small">
                                                {{ Str::limit($product->description, 80) }}
                                            </p>
                                        @endif
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fw-bold text-primary">
                                                    {{ number_format($product->price, 2) }} ₽
                                                </span>
                                                <span class="badge {{ $product->quantity > 0 ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $product->quantity > 0 ? 'В наличии' : 'Нет в наличии' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        @if($category)
                            В категории «{{ $category->title }}» пока нет товаров.
                        @else
                            Товаров пока нет.
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
