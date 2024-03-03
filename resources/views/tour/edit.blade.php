@extends('layouts.app')
@section('title', __('Edit Tour'))

@section('styles')
<link rel="stylesheet" href="{{ asset('css/jquery.fancybox.css') }}">

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
                <div class="card-header">
                    <h5 class="font-weight-bold d-inline-block pt-2">
                        {{ __('Edit Tour') }}
                    </h5>
                    <a href="{{ route('tour.index') }}" class="btn btn-secondary float-right ">{{ __('Back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.components.alert')
                    @if (count($tour->galleries) == 0)
                        <div class="alert alert-info alert-dismissible fade show">
                            <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i> <strong>{{ __('Info') }}</strong></span>
                            <span class="alert-text">
                                {{ __("Please add an image for the customer to see") }}
                            </span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <form class="mb-3" action="{{ route('tour.update',$tour) }}" enctype="multipart/form-data" method="POST">
                        @csrf @method('patch')
                        <div class="form-group">
                            <label for="package">{{ __('Package') }}</label>
                            <select name="package_id" id="package" class="form-control">
                                <option value="" selected disabled>{{ __('Select package') }}</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package', $tour->package_id) == $package->id ? 'selected':'' }}>{{ $package->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name',$tour->name) }}">
                        </div>
                        <div class="form-group">
                            <label for="price">{{ __('Price') }} (Rp.)</label>
                            <input type="number" name="price" id="price" class="form-control" value="{{ old('price',$tour->price) }}">
                        </div>
                        <div class="form-group">
                            <label for="description">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control" id="description">{!! old('description',$tour->description) !!}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">{{ __('Save') }}</button>
                    </form>

                    <div class="form-group">
                        <label for="images">Images</label>
                        <div id="field-images" class="row"></div>
                    </div>
                    <div class="form-group">
                        <label for="portofolio">Portofolio</label>
                        <div id="field-portofolio" class="row"></div>
                    </div>
                    <input id="input-add-image" type="file" name="images" accept="image/*,video/*" class="images" style="display: none">
                    <input id="input-image" type="file" name="images" accept="image/*" class="images" style="display: none">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('ckeditor/ckeditor.js')}}"></script>
<script src="{{ asset('js/jquery.fancybox.js') }}"></script>

<script>
    let is_portofolio = null;
    let img_loading = null;

    function loadImages() {
        $.getJSON("{{ route('api.tour-gallery', $tour->id) }}", function(result){
            $("#field-images").html('');
            $("#field-portofolio").html('');
            $.each(result.data, function(i, data){
                let path = data.image;
                path = path.replace('public','storage');
                if (data.is_portofolio != 1) {
                    if (i == 0) {
                        $("#field-images").append(`
                            <div class="col-lg-4 col-sm-6 mb-3">
                                <a href="${baseURL}/${path}" data-fancybox>
                                    <img id="img-image" class="mw-100" style="max-height: 300px" src="${baseURL}/${path}" alt="${baseURL}/${path}">
                                </a>
                                <button id="change-image" onclick="$('#input-image').click()" data-id="${data.id}" title="{{ __('Change Photo') }}" class="btn btn-dark ml-3" style="position: absolute; top: 0; z-index: 1; left: 0;">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        `);
                    } else {
                        $("#field-images").append(`
                            <div class="col-lg-4 col-sm-6 mb-3">
                                <a href="${baseURL}/${path}" data-fancybox>
                                    <img class="mw-100" style="max-height: 300px" src="${baseURL}/${path}" alt="">
                                </a>
                                <form class="mb-0 hapus-foto" data-id="${data.id}" action="javascript:;" method="post">
                                    <input type="hidden" name="_token" value="${_token}">
                                    <input type="hidden" name="_method" value="delete">
                                    <button type="submit" title="{{ __('Delete') }}" class="btn btn-danger" style="position: absolute; top: 0; z-index: 1;" onclick="return confirm('Are you sure want to delete this image?');"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        `);
                    }
                } else {
                    if (path.split('.').pop() == 'jpg' || path.split('.').pop() == 'jpeg' || path.split('.').pop() == 'png' || path.split('.').pop() == 'jpg') {
                        $("#field-portofolio").append(`
                            <div class="col-lg-4 col-sm-6 mb-3">
                                <a href="${baseURL}/${path}" data-fancybox>
                                    <img class="mw-100" style="max-height: 300px" src="${baseURL}/${path}" alt="">
                                </a>
                                <form class="mb-0 hapus-foto" data-id="${data.id}" action="javascript:;" method="post">
                                    <input type="hidden" name="_token" value="${_token}">
                                    <input type="hidden" name="_method" value="delete">
                                    <button type="submit" title="{{ __('Delete') }}" class="btn btn-danger" style="position: absolute; top: 0; z-index: 1;" onclick="return confirm('Are you sure want to delete this image?');"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        `);
                    } else {
                        $("#field-portofolio").append(`
                            <div class="col-lg-4 col-sm-6 mb-3">
                                <a href="${baseURL}/${path}" data-fancybox>
                                    <video class="mw-100" style="max-height: 300px"  src="${baseURL}/${path}"></video>
                                </a>
                                <form class="mb-0 hapus-foto" data-id="${data.id}" action="javascript:;" method="post">
                                    <input type="hidden" name="_token" value="${_token}">
                                    <input type="hidden" name="_method" value="delete">
                                    <button type="submit" title="{{ __('Delete') }}" class="btn btn-danger" style="position: absolute; top: 0; z-index: 1;" onclick="return confirm('Are you sure want to delete this image?');"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        `);
                    }
                }
            });
            $("#field-images").append(`
                <div class="col-lg-4 col-sm-6">
                    <img onclick="clickUpload(0, this)" class="mw-100 upload-image" style="max-height: 300px" src="{{ asset('images/upload.jpg') }}" alt="">
                </div>
            `);
            $("#field-portofolio").append(`
                <div class="col-lg-4 col-sm-6">
                    <img onclick="clickUpload(1, this)" class="mw-100 upload-image" style="max-height: 300px" src="{{ asset('images/upload.jpg') }}" alt="">
                </div>
            `);
        });
    }

    function clickUpload (param, img) {
        is_portofolio = param;
        img_loading = img;
        $('#input-add-image').click();
    }

    CKEDITOR.replace( 'description' );

    $(document).ready(function(){
        loadImages();

        $(document).on('change','#input-add-image', function(){
            if (this.files && this.files[0]) {
                let formData = new FormData();
                let oFReader = new FileReader();
                formData.append("image", this.files[0]);
                formData.append("is_portofolio", is_portofolio);
                formData.append("_method", "patch");
                formData.append("_token", _token);
                oFReader.readAsDataURL(this.files[0]);

                $.ajax({
                    url: "{{ route('tour.update', $tour->id) }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        img_loading.src = baseURL + '/storage/loading.gif';
                    },
                    success: function (data) {
                        if (data.success) {
                            swal({
                                icon: 'success',
                                title: "{{ __('Success') }}",
                                text: data.message,
                                button: false,
                                timer: 1000,
                            });

                            loadImages();
                            param = null;
                            img_loading = null;
                        } else {
                            swal({
                                icon: 'error',
                                title: "{{ __('Fail') }}",
                                text: data.message,
                                button: false,
                                timer: 1000,
                            });

                            loadImages();
                            param = null;
                            img_loading = null;
                        }
                    }
                });
            }
        });

        $('#input-image').on('change', function () {
            if (this.files && this.files[0]) {
                let formData = new FormData();
                let oFReader = new FileReader();
                formData.append("image", this.files[0]);
                formData.append("_method", "patch");
                formData.append("_token", _token);
                oFReader.readAsDataURL(this.files[0]);

                $.ajax({
                    url: baseURL + "/gallery/" + $("#change-image").data("id"),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        document.querySelector("#img-image").src = baseURL + '/storage/loading.gif';
                    },
                    success: function (data) {
                        if (data.success) {
                            swal({
                                icon: 'success',
                                title: "{{ __('Success') }}",
                                text: data.message,
                                button: false,
                                timer: 1000,
                            });

                            loadImages();
                        } else {
                            swal({
                                icon: 'error',
                                title: "{{ __('Fail') }}",
                                text: data.message,
                                button: false,
                                timer: 1000,
                            });

                            loadImages();
                        }
                    }
                });
            }
        });

        $(document).on('submit', ".hapus-foto",function(){
            $.ajax({
                url: baseURL+"/gallery/"+$(this).data('id'),
                type: 'POST',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    if (data.success) {
                        swal({
                            icon: 'success',
                            title: "{{ __('Success') }}",
                            text: data.message,
                            button: false,
                            timer: 1000,
                        });

                        loadImages();
                    } else {
                        swal({
                            icon: 'error',
                            title: "{{ __('Fail') }}",
                            text: data.message,
                            button: false,
                            timer: 1000,
                        });

                        loadImages();
                    }
                }
            });
        });
    });
</script>
@endpush
