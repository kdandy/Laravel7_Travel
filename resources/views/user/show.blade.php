@extends('layouts.app')
@section('title', $user->name)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/jquery.fancybox.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3 text-center">
            <div class="card shadow mt-5 bg-dark">
                <div class="card-body">
                    <div class="img-thumbnail mb-3">
                        <a href="{{ asset(Storage::url($user->avatar)) }}" data-fancybox>
                            <img class="mw-100" src="{{ asset(Storage::url($user->avatar)) }}" alt="">
                        </a>
                    </div>
                    <h5 class="font-weight-bold">{{ $user->name }}</h5>
                    <a href="mailto:{{ $user->email }}" class="">{{ $user->email }}</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card shadow mt-5 bg-dark">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h3>{{ __('Detail Users') }}</h3>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group row mb-2">
                                <label for="role" class="col-md-3 col-form-label pr-0">{{ __('Role') }}</label>
                                <div class="col-md-9">
                                    <input id="role" type="text" class="form-control" value="{{ $user->role->name }}" disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="email" class="col-md-3 col-form-label pr-0">{{ __('Email') }}</label>
                                <div class="col-md-9">
                                    <input id="email" type="text" class="form-control" value="{{ $user->email }}" disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="name" class="col-md-3 col-form-label pr-0">{{ __('Name') }}</label>
                                <div class="col-md-9">
                                    <input id="name" type="text" class="form-control" value="{{ $user->name }}" disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="phone" class="col-md-3 col-form-label pr-0">{{ __('Phone') }}</label>
                                <div class="col-md-9">
                                    <input id="phone" type="text" class="form-control" value="{{ $user->phone }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row mb-2">
                                <label for="phone_emergency" class="col-md-3 col-form-label pr-0">{{ __('Phone Emergency') }}</label>
                                <div class="col-md-9">
                                    <input id="phone_emergency" type="text" class="form-control" value="{{ $user->phone_emergency }}" disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="address" class="col-md-3 col-form-label pr-0">{{ __('Address') }}</label>
                                <div class="col-md-9">
                                    <textarea name="address" id="address" class="form-control" rows="3" disabled style="resize: none">{{ $user->address }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="float-right">
                        <a href="{{ route('user.index') }}" class="btn btn-secondary mt-3">{{ __('Back') }}</a>
                        <a href="{{ route('user.edit',$user) }}" class="btn btn-success mt-3"><i class="fas fa-edit"></i> {{ __('Edit') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="file" name="avatar" id="input-avatar" style="display: none">

@endsection

@push('scripts')
<script src="{{ asset('js/jquery.fancybox.js') }}"></script>
@endpush
