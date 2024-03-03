@extends('layouts.app')
@section('title', auth()->user()->name)

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
                        <a href="{{ asset(Storage::url(auth()->user()->avatar)) }}" data-fancybox>
                            <img id="img-avatar" class="mw-100" src="{{ asset(Storage::url(auth()->user()->avatar)) }}" alt="">
                        </a>
                        <button id="btn-ganti-avatar" title="{{ __('Change Photo') }}" class="btn btn-dark" style="position: absolute; right: 25px; top: 25px">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h5 class="font-weight-bold">{{ auth()->user()->name }}</h5>
                    <a href="mailto:{{ auth()->user()->email }}" class="">{{ auth()->user()->email }}</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card shadow mt-5 bg-dark">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h3>{{ __('Profile')}}</h3>
                        </div>
                    </div>
                    <hr>
                    @include('layouts.components.alert')
                    <form action="{{ route("update-profile") }}" method="post">
                        @csrf @method('patch')
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group row mb-2">
                                    <label for="name" class="col-md-3 col-form-label pr-0">{{ __('Name') }}</label>
                                    <div class="col-md-9">
                                        <input id="name" name="name" type="text" class="form-control" value="{{ auth()->user()->name }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="phone" class="col-md-3 col-form-label pr-0">{{ __('Phone') }}</label>
                                    <div class="col-md-9">
                                        <input id="phone" name="phone" type="text" class="form-control" value="{{ auth()->user()->phone }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="phone_emergency" class="col-md-3 col-form-label pr-0">{{ __('Phone emergency') }}</label>
                                    <div class="col-md-9">
                                        <input id="phone_emergency" name="phone_emergency" type="text" class="form-control" value="{{ auth()->user()->phone_emergency }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row mb-2">
                                    <label for="address" class="col-md-3 col-form-label pr-0">{{ __('Address') }}</label>
                                    <div class="col-md-9">
                                        <textarea name="address" id="address" class="form-control" rows="3" style="resize: none">{{ auth()->user()->address }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="float-right">
                            <a href="{{ route('profile') }}" class="btn btn-secondary mt-3">{{ __('Back')}}</a>
                            <button type="submit" class="btn btn-success mt-3">{{ __('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="file" name="avatar" id="input-avatar" style="display: none">
@endsection

@push('scripts')
<script src="{{ asset('js/jquery.fancybox.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#btn-ganti-avatar').on('click', function () {
                $('#input-avatar').click();
            });

            $('#input-avatar').on('change', function () {
                if (this.files && this.files[0]) {
                    let formData = new FormData();
                    let oFReader = new FileReader();
                    formData.append("avatar", this.files[0]);
                    formData.append("_method", "patch");
                    formData.append("_token", _token);
                    oFReader.readAsDataURL(this.files[0]);

                    $.ajax({
                        url: "{{ route('update-avatar', auth()->user()) }}",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function () {
                            $('#img-avatar').attr('src', baseURL + '/storage/loading.gif');
                        },
                        success: function (data) {
                            if (data.error) {
                                swal({
                                    icon: 'error',
                                    title: "{{ __('Fail') }}",
                                    text: data.message,
                                });
                                let path = data.avatar;
                                if (path != 'noavatar.jpg') {
                                    path = path.replace('public', '/storage');
                                }
                                $('#img-avatar').attr('src', baseURL + path);
                            } else {
                                swal({
                                    icon: 'success',
                                    title: "{{ __('Success') }}",
                                    text: data.message,
                                });
                                let path = data.avatar;
                                path = path.replace('public', '/storage');
                                $('#img-avatar').attr('src', baseURL + path);
                                $('#img-avatar-header').attr('src', baseURL + path);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
