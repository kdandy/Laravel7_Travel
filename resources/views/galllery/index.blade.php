@extends('layouts.app')
@section('title', __('Gallery'))

@section('styles')
<link rel="stylesheet" href="{{ asset('css/jquery.fancybox.css') }}">
<link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection

@section('content')
<div class="container">
    <h1 class="font-weight-bold d-inline-block">{{ __('Gallery') }}</h1>
    <form action="{{ route('gallery.store') }}" class="dropzone dz-clickable" id="dropzoneForm">
        @csrf
        <div class="dz-default dz-message"><span class="h3 mb-0 text-primary">Click or drop files here to upload - max file size is 2mb</span></div>
    </form>
    <button type="button" class="btn btn-success btn-block mt-3 mb-5" id="submit-all">Upload</button>
    <div id="field-images" class="row mt-3"></div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery.fancybox.js') }}"></script>
<script src="{{ asset('js/dropzone.js') }}"></script>
<script>
    function loadImages() {
        $.getJSON("{{ route('api.gallery') }}", function(result){
            $("#field-images").html('');
            $.each(result.data, function(i, data){
                let path = data.image;
                path = path.replace('public','storage');
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
            });
        });
    }

    Dropzone.options.dropzoneForm = {
        autoProcessQueue: false,
        parallelUploads: 10,
        maxFilesize: 2,
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        dictRemoveFile: 'Remove file',
        dictFileTooBig: 'Image is larger than 2MB',
        init: function() {
            var submitButton = document.querySelector("#submit-all");
            myDropzone = this;

            submitButton.addEventListener("click", function(){
                myDropzone.processQueue();
            });

            // Execute when file uploads are complete
            this.on("complete", function() {
            // If all files have been uploaded
                if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0)
                {
                    var _this = this;
                    // Remove all files
                    _this.removeAllFiles();
                }
                loadImages();
            });
        }
    };

    $(document).ready(function(){
        loadImages();

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
