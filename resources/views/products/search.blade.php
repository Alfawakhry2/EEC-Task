@extends('layouts.app')

@section('title', 'Search Products')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-search"></i> {{ __('products.search_products') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('products.search') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" name="q" 
                               placeholder="{{ __('products.search_by_name') }}" 
                               value="{{ $search ?? '' }}" autofocus>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i> {{ __('message.search') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($search) && $search)
            <h5 class="mb-3">
                {{__('products.search_results_for')}}: <strong>"{{ $search }}"</strong>
                @if($products->total() > 0)
                    <span class="badge bg-primary">{{ $products->total() }} {{__('message.results')}}</span>
                @endif
            </h5>

            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                    
                                            <img src="{{ $product->image_url }}" 
                                                 alt="{{ $product->title }}" 
                                                 class="img-fluid rounded-start h-100 object-fit-cover">
        
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a href="{{ route('products.show', $product->id) }}" 
                                                   class="text-decoration-none">
                                                    {{ $product->title }}
                                                </a>
                                            </h5>
                                            <p class="card-text text-muted small">
                                                {{ Str::limit($product->description, 80) }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-primary fw-bold fs-5">
                                                    EGP{{ number_format($product->price, 2) }}
                                                </span>
                                                <span class="badge bg-{{ $product->quantity > 0 ? 'success' : 'danger' }}">
                                                    Stock: {{ $product->quantity }}
                                                </span>
                                            </div>
                                            <div class="mt-2">
                                                <a href="{{ route('products.show', $product->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> {{__('products.product_details')}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->appends(['q' => $search])->links() }}
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> 
                    No products found matching "<strong>{{ $search }}</strong>". 
                    Try a different search term.
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-search" style="font-size: 5rem; color: #ccc;"></i>
                <h5 class="text-muted mt-3">Enter a search term to find products</h5>
                <p class="text-muted">You can search by product name or partial name</p>
            </div>
        @endif
    </div>
</div>
@endsection