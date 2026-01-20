@extends('layouts.app')

@section('title',  __('pharmacies.pharmacies'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-shop"></i> {{ __('pharmacies.pharmacies') }}</h1>
    <a href="{{ route('pharmacies.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> {{ __('pharmacies.add_new_pharmacy') }}
    </a>
</div>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('pharmacies.index') }}" method="GET" class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" 
                       placeholder="{{__('pharmacies.search_placeholder')}}" 
                       value="{{ $search ?? '' }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> {{__('message.search')}}
                </button>
            </div>
        </form>
        @if($search)
            <div class="mt-2">
                <a href="{{ route('pharmacies.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> {{__('products.clear_search')}}
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Pharmacies Grid -->
@if($pharmacies->count() > 0)
    <div class="row">
        @foreach($pharmacies as $pharmacy)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-shop text-primary"></i>
                            <a href="{{ route('pharmacies.show', $pharmacy->id) }}" 
                               class="text-decoration-none">
                                {{ $pharmacy->name }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-geo-alt"></i> {{ Str::limit($pharmacy->address, 80) }}
                        </p>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('pharmacies.show', $pharmacy->id) }}" 
                               class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> {{__('products.view')}}
                            </a>
                            <a href="{{ route('pharmacies.edit', $pharmacy->id) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> {{__('products.edit')}}
                            </a>
                            <form action="{{ route('pharmacies.destroy', $pharmacy->id) }}" 
                                  method="POST" class="d-inline" 
                                  onsubmit="return confirm('{{__('products.are_you_sure')}}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> {{__('products.delete')}}
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer text-muted small">
                        {{__('pharmacies.created')}}: {{ $pharmacy->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{$pharmacies->links()}}
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> 
        @if($search)
            {{__('pharmacies.no_results')}} "{{ $search }}".
        @else
            {{__('pharmacies.no_pharmacies_available')}} <a href="{{ route('pharmacies.create') }}">{{__('pharmacies.create_your_first_pharmacy')}}</a>.
        @endif
    </div>
@endif
@endsection