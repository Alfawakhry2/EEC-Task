@extends('layouts.app')

@section('title', __('pharmacies.edit_pharmacy'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-pencil"></i> {{ __('pharmacies.edit_pharmacy') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pharmacies.update', $pharmacy->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">{{__('pharmacies.name')}} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $pharmacy->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label">{{__('pharmacies.address')}} <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="4" required>{{ old('address', $pharmacy->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> {{__('pharmacies.update')}}
                        </button>
                        <a href="{{ route('pharmacies.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> {{__('products.cancel')}}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection