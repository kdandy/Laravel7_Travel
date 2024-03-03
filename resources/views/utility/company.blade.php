@extends('layouts.app')
@section('title', 'Company')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>

    <style>
        #display-logo:hover{
            cursor: pointer;
            opacity: 0.5;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div id="alert">
        @include('layouts.components.alert')
    </div>
    <div class="card bg-dark">
        <div class="card-header">
            <h5 class="m-0 pt-1 font-weight-bold">{{ __('Company') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('company.update',$company) }}" method="post" enctype="multipart/form-data">
                @csrf @method('patch')
                <input type="hidden" name="id" value="{{ $company->id }}">
                <div class="row justify-content-center">
                    <div class="col-lg-4 mb-1">
                        <div class="form-group">
                            <label for="logo">Logo</label><br>
                            <img title="{{ __('Change Logo')}}" onclick="document.getElementById('logo').click()" id="display-logo" src="{{ asset(Storage::url($company->logo)) }}" alt="{{ asset(Storage::url($company->logo)) }}" width="100px" height="100px">
                            <input type="file" name="logo" id="logo" style="display: none">
                        </div>
                        <div class="form-group">
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $company->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $company->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">{{ __('Phone') }}</label>
                            <input onkeypress="return hanyaAngka(event)" type="text" name="phone" id="phone" class="form-control" value="{{ $company->phone }}" required>
                        </div>
                        <div class="form-group">
                            <label for="whatsapp">{{ __('WhatsApp') }}</label>
                            <input onkeypress="return hanyaAngka(event)" type="text" name="whatsapp" id="whatsapp" class="form-control" value="{{ $company->whatsapp }}" required>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-1">
                        <div class="form-group">
                            <label for="instagram">{{ __('Instagram') }}</label>
                            <input type="text" name="instagram" id="instagram" class="form-control" value="{{ $company->instagram }}">
                        </div>
                        <div class="form-group">
                            <label for="youtube">{{ __('youtube') }}</label>
                            <input type="text" name="youtube" id="youtube" class="form-control" value="{{ $company->youtube }}">
                        </div>
                        <div class="form-group">
                            <label for="address">{{ __('Address') }}</label>
                            <textarea style="resize:none" class="form-control" name="address" id="address" rows="1" required>{{ $company->address }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="description">{{ __('Description') }}</label>
                            <textarea style="resize:none" class="form-control" name="description" id="description" rows="3"  required>{{ $company->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="testimonial">{{ __('Testimonial') }}</label>
                            <textarea style="resize:none" class="form-control" name="testimonial" id="testimonial" rows="2"  required>{{ $company->testimonial }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-1">
                        <div class="form-group">
                            <label for="virtual_account">{{ __('Virtual Account') }}</label>
                            <input onkeypress="return hanyaAngka(event)" type="text" name="virtual_account" id="virtual_account" class="form-control" value="{{ $company->va }}" required>
                        </div>
                        <div class="form-group">
                            <label for="api_key">{{ __('API Key') }}</label>
                            <input type="text" name="api_key" id="api_key" class="form-control" value="{{ $company->api_key }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude">{{ __('Latitude') }}</label>
                                    <input class="form-control" type="text" name="latitude" id="latitude" value="{{ $company->latitude }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude">{{ __('Longitude') }}</label>
                                    <input class="form-control" type="text" name="longitude" id="longitude" value="{{ $company->longitude }}" required>
                                </div>
                            </div>
                            <div class="container">
                                <div id="mapid" style="height: 215px; width:100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-3" id="simpan">{{ __('Save') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>

    <!-- location control -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js" charset="utf-8"></script>

    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/leaflet.js') }}"></script>
    <script>
        $(document).ready(function(){
            $("#logo").on('change', function(){
                if (this.files && this.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $("#display-logo").attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            function updateMarker(lat, lng) {
                marker
                .setLatLng([lat, lng])
                .bindPopup("Your location :  " + marker.getLatLng().toString())
                .openPopup();
                return false;
            };

            mymap.on('click', function(e) {
                let latitude = e.latlng.lat.toString().substring(0, 15);
                let longitude = e.latlng.lng.toString().substring(0, 15);
                $('#latitude').val(latitude);
                $('#longitude').val(longitude);
                updateMarker(latitude, longitude);
            });

            var updateMarkerByInputs = function() {
                return updateMarker( $('#latitude').val() , $('#longitude').val());
            }
            $('#latitude').on('input', updateMarkerByInputs);
            $('#longitude').on('input', updateMarkerByInputs);


            L.control.locate({
                position: "bottomright"
            }).addTo(mymap);
            $("#formCompany").on('submit',function(e){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: baseURL + "/company/1",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $("#simpan").html('Loading <img height="20px" src="'+ baseURL + '/storage/loading.gif' +'" alt="">');
                        $("#simpan").attr('disabled', 'disabled');
                    },
                    success: function (result) {
                        $("#simpan").html('Simpan');
                        $("#simpan").removeAttr('disabled');
                        if (result.success) {
                            swal({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Company berhasil diperbarui'
                            });
                            $("#logo").val('');

                        } else {
                            swal({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Company gagal diperbarui'
                            });
                            let alert = `
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i> <strong>Gagal</strong></span>
                                    <span class="alert-text">
                                        <ul>

                            `;
                            $.each(result.message, function (i, e) {
                                alert += `
                                            <li>`+e+`</li>
                                `;
                                if ((e.replace(/ .*/, '')) == 'logo') {
                                    $("#display-logo").attr('src', $("#display-logo").attr('alt'));
                                    $("#logo").val('');
                                }
                            });
                            alert += `  </ul>
                                    </span>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>`;
                            $("#alert").html(alert);
                        }
                    }
                })
            });
        });
    </script>
@endpush
