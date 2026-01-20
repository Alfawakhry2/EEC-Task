@extends('layouts.app')

@section('title', __('products.products'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-box-seam"></i> {{ __('products.products') }}</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> {{ __('products.add_new_product') }}
        </a>
    </div>

    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="{{__('products.search_by_name')}}"
                        value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> {{ __('message.search') }}
                    </button>
                </div>
            </form>
            @if($search)
                <div class="mt-2">
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> {{ __('products.clear_search') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Products Table -->
    @if($products->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('products.id') }}</th>
                                <th>{{ __('products.image') }}</th>
                                <th>{{ __('products.title') }}</th>
                                <th>{{ __('products.description') }}</th>
                                <th>{{ __('products.price') }}</th>
                                <th>{{ __('products.quantity') }}</th>
                                <th>{{ __('products.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>

                                        <img src="{{ $product->image_url}}"
                                            class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                                            {{ $product->title }}
                                        </a>
                                    </td>
                                    <td>{{ Str::limit($product->description, 50) }}</td>
                                    <td>EGP{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        <!-- AJAX Editable Quantity -->
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="number" class="form-control form-control-sm quantity-input"
                                                id="quantity-{{ $product->id }}" data-product-id="{{ $product->id }}"
                                                value="{{ $product->quantity }}" min="0" style="width: 80px;">
                                            <button class="btn btn-sm btn-success update-quantity-btn"
                                                data-product-id="{{ $product->id }}" title="Update Quantity">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            <span
                                                class="badge bg-{{ $product->quantity > 0 ? 'success' : 'danger' }} quantity-badge"
                                                id="badge-{{ $product->id }}">
                                                {{ $product->quantity }}
                                            </span>
                                        </div>
                                        <small class="text-muted quantity-status" id="status-{{ $product->id }}"></small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info"
                                                title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning"
                                                title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
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
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            @if($search)
               {{__('products.no_products')}} "{{ $search }}".
            @else
                No products available. <a href="{{ route('products.create') }}">Create your first product</a>.
            @endif
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add CSRF token to all AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Handle quantity update buttons
            document.querySelectorAll('.update-quantity-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.dataset.productId;
                    const quantityInput = document.getElementById(`quantity-${productId}`);
                    const quantity = quantityInput.value;
                    const statusElement = document.getElementById(`status-${productId}`);
                    const badgeElement = document.getElementById(`badge-${productId}`);

                    // Show loading state
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                    statusElement.textContent = 'Updating...';
                    statusElement.className = 'text-muted quantity-status';

                    // Send AJAX request
                    fetch(`/ajax/products/${productId}/quantity`, {
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
                                statusElement.textContent = 'Updated!';
                                statusElement.className = 'text-success quantity-status';

                                // Update badge
                                badgeElement.textContent = data.quantity;
                                badgeElement.className = `badge bg-${data.quantity > 0 ? 'success' : 'danger'} quantity-badge`;

                                // Clear status after 2 seconds
                                setTimeout(() => {
                                    statusElement.textContent = '';
                                }, 2000);
                            } else {
                                // Error
                                statusElement.textContent =  data.message;
                                statusElement.className = 'text-danger quantity-status';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            statusElement.textContent = 'Update failed';
                            statusElement.className = 'text-danger quantity-status';
                        })
                        .finally(() => {
                            // Reset button
                            this.disabled = false;
                            this.innerHTML = '<i class="bi bi-check"></i>';
                        });
                });
            });

            // Allow update on Enter key
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const productId = this.dataset.productId;
                        document.querySelector(`.update-quantity-btn[data-product-id="${productId}"]`).click();
                    }
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .quantity-status {
            display: block;
            font-size: 0.75rem;
            margin-top: 2px;
        }

        .quantity-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .update-quantity-btn {
            transition: all 0.2s;
        }

        .update-quantity-btn:hover {
            transform: scale(1.1);
        }
    </style>
@endpush