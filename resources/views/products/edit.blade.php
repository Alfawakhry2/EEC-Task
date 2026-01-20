@extends('layouts.app')

@section('title', __('products.edit_product'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-pencil"></i> {{__('products.edit_product')}}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">{{__('products.title')}} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $product->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">{{__('products.description')}}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Current Image -->
                    @if($product->image)
                        <div class="mb-3">
                            <label class="form-label">{{__('products.current_image')}}</label>
                            <div>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" 
                                     class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>
                    @endif

                    <!-- New Image -->
                    <div class="mb-3">
                        <label for="image" class="form-label">
                            {{ $product->image ? 'Change Image' : 'Product Image' }}
                        </label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <small class="text-muted">{{__('products.accepted_formats')}}</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price and Quantity Row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">{{__('products.price')}} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $product->price) }}" 
                                       step="0.01" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">{{__('products.quantity')}} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" 
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
                            <i class="bi bi-save"></i> {{__('products.update')}}
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> {{__('products.cancel')}}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection