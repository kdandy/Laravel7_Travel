@extends('layouts.app')
@section('title', __('Add Tour'))

@section('styles')
<style>
    .upload-image:hover{
        cursor: pointer;
        opacity: 0.7;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-dark">
                <div class="card-header font-weight-bold">
                    {{ __('Add Tour') }}
                </div>
                <form action="{{ route('tour.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @include('layouts.components.alert')
                        <div class="form-group">
                            <label for="package">{{ __('Package') }}</label>
                            <select name="package" id="package" class="form-control">
                                <option value="" selected disabled>{{ __('Select package') }}</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package') == $package->id ? 'selected':'' }}>{{ $package->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                        </div>
                        <div class="form-group">
                            <label for="price">{{ __('Price') }} (Rp.)</label>
                            <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}">
                        </div>
                        <div class="form-group">
                            <label for="description">{{ __('Description') }}</label>
                            <textarea name="description" id="description">{!! old('description') !!}</textarea>
                        </div>
                    </div>
                    <div class="card-footer p-0">
                        <div class="float-right p-3">
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">{{ __('Back') }}</a>
                            <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('ckeditor/ckeditor.js')}}"></script>

<script>
    CKEDITOR.replace( 'description' );
</script>
@endpush
