@extends('layouts.app')

@section('title',__('pharmacies.edit_product_in') . ' ' . $pharmacy->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-pencil"></i> {{__('products.edit_product')}}: {{ $product->title }}
                </h4>
            </div>
            <div class="card-body">
                <!-- Product Info -->
                <div class="alert alert-info">
                    <h5><i class="bi bi-box-seam"></i> {{ $product->title }}</h5>
                    <p class="mb-1"><strong>{{__('pharmacies.pharmacy')}}:</strong> {{ $pharmacy->name }}</p>
                    <p class="mb-0"><strong>{{__('products.base_price')}}:</strong> ${{ number_format($product->price, 2) }}</p>
                </div>

                <form action="{{ route('pharmacies.products.update', [$pharmacy->id, $product->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Current Values Info -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <small class="text-muted">{{__('pharmacies.current_price')}}</small>
                                    <h4 class="mb-0">${{ number_format($pivotData->pivot->price, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <small class="text-muted">{{__('pharmacies.current_stock')}}</small>
                                    <h4 class="mb-0">{{ $pivotData->pivot->quantity }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price and Quantity Row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">
                                    {{__('pharmacies.new_price')}} (EGP) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" 
                                       value="{{ old('price', $pivotData->pivot->price) }}" 
                                       step="0.01" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">
                                    {{__('pharmacies.new_quantity')}} <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" 
                                       value="{{ old('quantity', $pivotData->pivot->quantity) }}" 
                                       min="0" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> {{__('products.edit_product')}}
                        </button>
                        <a href="{{ route('pharmacies.show', $pharmacy->id) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> {{__('pharmacies.cancel')}}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection