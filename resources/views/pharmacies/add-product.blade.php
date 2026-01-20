@extends('layouts.app')

@section('title', __('pharmacies.add_product_to') . ' ' . $pharmacy->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-plus-circle"></i> {{__('pharmacies.add_product_to')}} {{ $pharmacy->name }}
                </h4>
            </div>
            <div class="card-body">
                @if($availableProducts->count() > 0)
                    <form action="{{ route('pharmacies.products.store', $pharmacy->id) }}" method="POST">
                        @csrf

                        <!-- Product Selection -->
                        <div class="mb-3">
                            <label for="product_id" class="form-label">{{__('pharmacies.select_product')}} <span class="text-danger">*</span></label>
                            <select class="form-select @error('product_id') is-invalid @enderror" 
                                    id="product_id" name="product_id" required onchange="updateBasePrice(this)">
                                <option value="">{{__('pharmacies.select_product')}}</option>
                                @foreach($availableProducts as $product)
                                    <option value="{{ $product->id }}" 
                                            data-base-price="{{ $product->price }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->title }} {{__('pharmacies.base_price_info')}}: EGP{{ number_format($product->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{__('pharmacies.select_a_product')}}</small>
                        </div>

                        <!-- Base Price Info -->
                        <!-- <div class="alert alert-info" id="basePriceInfo" style="display: none;">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Base Price:</strong> $<span id="basePriceValue">0.00</span>
                            <br>
                            <small>You can set a different price for this pharmacy below.</small>
                        </div> -->

                        <!-- Price and Quantity Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">
                                      {{__('pharmacies.set_selling_price')}} (EGP) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" 
                                           value="{{ old('price') }}" 
                                           step="0.01" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">{{__('pharmacies.set_selling_price')}}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">
                                        {{__('pharmacies.stock_quantity')}} <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" 
                                           value="{{ old('quantity', 0) }}" 
                                           min="0" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">{{__('pharmacies.how_many_units')}}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> {{__('pharmacies.add_product')}}
                            </button>
                            <a href="{{ route('pharmacies.show', $pharmacy->id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> {{__('products.cancel')}}
                            </a>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> 
                        All available products have already been added to this pharmacy.
                    </div>
                    <a href="{{ route('pharmacies.show', $pharmacy->id) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Pharmacy
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateBasePrice(select) {
        const selectedOption = select.options[select.selectedIndex];
        const basePrice = selectedOption.getAttribute('data-base-price');
        
        if (basePrice) {
            document.getElementById('basePriceValue').textContent = parseFloat(basePrice).toFixed(2);
            document.getElementById('basePriceInfo').style.display = 'block';
            
            // Auto-fill price field with base price
            document.getElementById('price').value = parseFloat(basePrice).toFixed(2);
        } else {
            document.getElementById('basePriceInfo').style.display = 'none';
        }
    }
</script>
@endpush
@endsection