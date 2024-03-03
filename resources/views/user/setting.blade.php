@extends('layouts.app')
@section('title', __('Setting'))

@section('content')
    <div class="container mt-5">
        @include('layouts.components.alert')
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow bg-dark">
                    <div class="card-body">
                        <h4 class="text-center font-weight-bold">{{ __('Setting') }}</h4>
                        <hr>
                        <form autocomplete="off" action="{{ route('update-setting') }}" method="post">
                            @csrf @method('patch')
                            <h6 class="heading-small mb-4">{{ __('Change email') }}</h6>
                            <div class="pl-lg-4">
                                <p class="mb-3" style="font-size: 12px;">{{ __("Leave blank if you don't want to change email.") }}</p>
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Old email') }}</label>
                                    <input readonly class="form-control" type="email" value="{{ auth()->user()->email }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="email">{{ __('New email') }}</label>
                                    <p class="mb-3 text-sm" style="font-size: 12px;"><em>{{ __('In order to receive the confirmation email, please enter the active email address.') }}</em></p>
                                    <input autocomplete="off" class="form-control" type="email" name="email" id="email" placeholder="{{ __('Enter a new email address') }}" value="{{ old('email') }}">
                                </div>
                            </div>
                            <hr>
                            <h6 class="heading-small mb-4">{{ __('Change password') }}</h6>
                            <div class="pl-lg-4">
                                <p class="mb-3" style="font-size: 12px;">{{ __("Leave blank if you don't want to change Password.") }}</p>
                                <div class="form-group">
                                    <label class="form-control-label" for="password">{{ __('New Password') }}</label>
                                    <input autocomplete="off" class="form-control" type="password" name="password" id="password" placeholder="{{ __('Enter a new password') }}" value="{{ old('password') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="password_confirmation">{{ __('Confirm new password') }}</label>
                                    <input autocomplete="off" class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="{{ __('Enter a new password') }}" value="{{ old('password_confirmation') }}">
                                </div>
                            </div>
                            <div class="form-group mt-5">
                                <label class="form-control-label" for="old_password">{{ __('Password') }} <span class="text-danger">*</span></label>
                                <input required class="form-control" type="password" name="old_password" id="old_password" placeholder="{{ __('Enter your password') }}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
