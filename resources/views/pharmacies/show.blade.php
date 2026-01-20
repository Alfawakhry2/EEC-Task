@extends('layouts.app')

@section('title', $pharmacy->name)

@section('content')
<div class="row">
    <!-- Pharmacy Details -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-shop"></i> {{__('pharmacies.pharmacy_details')}}</h4>
            </div>
            <div class="card-body">
                <h3>{{ $pharmacy->name }}</h3>
                
                <div class="mb-3">
                    <strong><i class="bi bi-geo-alt"></i> {{__('pharmacies.address')}}:</strong>
                    <p class="text-muted">{{ $pharmacy->address }}</p>
                </div>

                <div class="mb-3">
                    <strong><i class="bi bi-calendar"></i> {{__('pharmacies.created')}}:</strong>
                    <p class="text-muted">{{ $pharmacy->created_at->format('F d, Y') }}</p>
                </div>

                @if($pharmacy->products->count() > 0)
                    <div class="mb-3">
                        <strong><i class="bi bi-box-seam"></i> {{__('pharmacies.total_products')}}:</strong>
                        <span class="badge bg-primary fs-6">{{ $pharmacy->products->count() }}</span>
                    </div>
                @endif

                <div class="d-flex gap-2">
                    <a href="{{ route('pharmacies.edit', $pharmacy->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> {{__('products.edit')}}
                    </a>
                    <form action="{{ route('pharmacies.destroy', $pharmacy->id) }}" method="POST" 
                          onsubmit="return confirm('{{__('message.are_you_sure')}}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> {{__('products.delete')}}
                        </button>
                    </form>
                    <a href="{{ route('pharmacies.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> {{__('products.back')}}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Available -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-box-seam"></i> {{__('pharmacies.add_product')}}</h4>
                <a href="{{ route('pharmacies.products.create', $pharmacy->id) }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle"></i> {{__('pharmacies.add_product')}}
                </a>
            </div>
            <div class="card-body">
                @if($pharmacy->products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>{{__('products.product')}}</th>
                                    <th>{{__('products.description')}}</th>
                                    <th>{{__('products.price')}}</th>
                                    <th>{{__('products.stock')}}</th>
                                    <th>{{__('products.actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pharmacy->products as $product)
                                    <tr>
                                        <td>
                                            <a href="{{ route('products.show', $product->id) }}" 
                                               class="text-decoration-none">
                                                <strong>{{ $product->title }}</strong>
                                            </a>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ Str::limit($product->description, 50) }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                ${{ number_format($product->pivot->price, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <!-- AJAX Editable Quantity -->
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="number" 
                                                       class="form-control form-control-sm pharmacy-quantity-input" 
                                                       id="pharmacy-quantity-{{ $product->id }}"
                                                       data-pharmacy-id="{{ $pharmacy->id }}"
                                                       data-product-id="{{ $product->id }}"
                                                       value="{{ $product->pivot->quantity }}" 
                                                       min="0" 
                                                       style="width: 80px;">
                                                <button class="btn btn-sm btn-success update-pharmacy-quantity-btn" 
                                                        data-pharmacy-id="{{ $pharmacy->id }}"
                                                        data-product-id="{{ $product->id }}"
                                                        title="Update Quantity">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                                <span class="badge bg-{{ $product->pivot->quantity > 0 ? 'info' : 'danger' }} pharmacy-quantity-badge" 
                                                      id="pharmacy-badge-{{ $product->id }}">
                                                    {{ $product->pivot->quantity }}
                                                </span>
                                            </div>
                                            <small class="text-muted pharmacy-quantity-status" id="pharmacy-status-{{ $product->id }}"></small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('products.show', $product->id) }}" 
                                                   class="btn btn-sm btn-info" title="View Product">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('pharmacies.products.edit', [$pharmacy->id, $product->id]) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit Price/Stock">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('pharmacies.products.destroy', [$pharmacy->id, $product->id]) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Remove this product from the pharmacy?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Remove">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Statistics -->
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h6>{{__('pharmacies.total_products')}}</h6>
                                        <h4>{{ $pharmacy->products->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h6>Avg Price</h6>
                                        <h4>${{ number_format($pharmacy->products->avg('pivot.price'), 2) }}</h4>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h6>{{__('pharmacies.total_stock')}}</h6>
                                        <h4 id="total-stock">{{ $pharmacy->products->sum('pivot.quantity') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> This pharmacy doesn't have any products yet.
                    </div>
                    <a href="{{ route('pharmacies.products.create', $pharmacy->id) }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Add First Product
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF token to all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Handle pharmacy product quantity update buttons
    document.querySelectorAll('.update-pharmacy-quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const pharmacyId = this.dataset.pharmacyId;
            const productId = this.dataset.productId;
            const quantityInput = document.getElementById(`pharmacy-quantity-${productId}`);
            const quantity = quantityInput.value;
            const statusElement = document.getElementById(`pharmacy-status-${productId}`);
            const badgeElement = document.getElementById(`pharmacy-badge-${productId}`);
            
            // Show loading state
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            statusElement.textContent = 'Updating...';
            statusElement.className = 'text-muted pharmacy-quantity-status';
            
            // Send AJAX request
            fetch(`/ajax/pharmacies/${pharmacyId}/products/${productId}/quantity`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success
                    statusElement.textContent = '✓ Updated!';
                    statusElement.className = 'text-success pharmacy-quantity-status';
                    
                    // Update badge
                    badgeElement.textContent = data.quantity;
                    badgeElement.className = `badge bg-${data.quantity > 0 ? 'info' : 'danger'} pharmacy-quantity-badge`;
                    
                    // Update total stock
                    updateTotalStock();
                    
                    // Clear status after 2 seconds
                    setTimeout(() => {
                        statusElement.textContent = '';
                    }, 2000);
                } else {
                    // Error
                    statusElement.textContent = '✗ ' + data.message;
                    statusElement.className = 'text-danger pharmacy-quantity-status';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                statusElement.textContent = '✗ Update failed';
                statusElement.className = 'text-danger pharmacy-quantity-status';
            })
            .finally(() => {
                // Reset button
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-check"></i>';
            });
        });
    });
    
    // Allow update on Enter key
    document.querySelectorAll('.pharmacy-quantity-input').forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const pharmacyId = this.dataset.pharmacyId;
                const productId = this.dataset.productId;
                document.querySelector(`.update-pharmacy-quantity-btn[data-pharmacy-id="${pharmacyId}"][data-product-id="${productId}"]`).click();
            }
        });
    });
    
    // Update total stock in statistics card
    function updateTotalStock() {
        let total = 0;
        document.querySelectorAll('.pharmacy-quantity-input').forEach(input => {
            total += parseInt(input.value) || 0;
        });
        document.getElementById('total-stock').textContent = total;
    }
});
</script>
@endpush

@push('styles')
<style>
.pharmacy-quantity-status {
    display: block;
    font-size: 0.75rem;
    margin-top: 2px;
}

.pharmacy-quantity-input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.update-pharmacy-quantity-btn {
    transition: all 0.2s;
}

.update-pharmacy-quantity-btn:hover {
    transform: scale(1.1);
}
</style>
@endpush