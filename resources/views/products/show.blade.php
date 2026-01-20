@extends('layouts.app')

@section('title', $product->title)

@section('content')
<div class="row">
    <!-- Product Details -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-box-seam"></i> {{__('products.product_details')}}</h4>
            </div>
            <div class="card-body">
                <!-- Product Image -->
   
                        <img src="{{ $product->image_url}}" alt="{{ $product->title }}" 
                         class="img-fluid rounded mb-3">
               

                <!-- Product Info -->
                <h3>{{ $product->title }}</h3>
                
                <div class="mb-3">
                    <strong>{{__('products.base_price')}}</strong> 
                    <span class="text-primary fs-4">${{ number_format($product->price, 2) }}</span>
                </div>
<!-- 
                <div class="mb-3">
                    <strong>{{__('products.total_quantity')}}</strong> 
                    <span class="badge bg-{{ $product->quantity > 0 ? 'success' : 'danger' }} fs-6">
                        {{ $product->quantity }}
                    </span>
                </div> -->

                @if($product->description)
                    <div class="mb-3">
                        <strong>{{__('products.description')}}</strong>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>
                @endif

                <div class="mb-3">
                    <strong>{{ __('products.created') }}:</strong> {{ $product->created_at->format('M d, Y') }}
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> {{__('products.edit')}}
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" 
                          onsubmit="return confirm('{{__('message.remove_product_confirm')}}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> {{__('products.delete')}}
                        </button>
                    </form>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> {{__('products.back')}}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pharmacies Table -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-shop"></i> {{__('products.available_at_pharmacies')}}</h4>
            </div>
            <div class="card-body">
                @if($product->pharmacies->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>{{__('products.pharmacy_name')}}</th>
                                    <th>{{__('products.address')}}</th>
                                    <th>{{__('products.price')}}</th>
                                    <th>{{__('products.stock')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->pharmacies as $pharmacy)
                                    <tr>
                                        <td>
                                            <a href="{{ route('pharmacies.show', $pharmacy->id) }}" 
                                               class="text-decoration-none">
                                                <strong>{{ $pharmacy->name }}</strong>
                                            </a>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($pharmacy->address, 40) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                EGP{{ number_format($pharmacy->pivot->price, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $pharmacy->pivot->quantity > 0 ? 'info' : 'danger' }}">
                                                {{ $pharmacy->pivot->quantity }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Price Statistics -->
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h6>Cheapest Price</h6>
                                        <h4>EGP{{ number_format($product->pharmacies->min('pivot.price'), 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h6>Highest Price</h6>
                                        <h4>EGP{{ number_format($product->pharmacies->max('pivot.price'), 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> {{__('products.no_pharmacies')}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection