@extends('layouts.app')

@section('title')
@if ($package)
    {{ $package->name }}
@else
    {{ __('Tour List') }}
@endif
@endsection

@section('styles')
<style>
    .tour:hover{
        opacity: 0.7;
    }
</style>
    <link rel="stylesheet" href="{{ asset("css/mystyle.css") }}">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 mb-3">
            <h1 class="font-weight-bold">
                @if ($package)
                    {{ $package->name }}
                @else
                    {{ __('Tour List') }}
                @endif
            </h1>
        </div>
        @can('admin')
            <div class="col-md-2 mb-3">
                <a href="{{ route('tour.create') }}" class="btn btn-success btn-block">{{ __('Add Tour') }}</a>
            </div>
        @endcan
        <div class="col-md-2 mb-3">
            <div class="dropdown">
                <button class="btn btn-secondary btn-block dropdown-toggle" type="button" id="dropdownPaket" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ __('Select Package') }}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownPaket">
                    <a class="dropdown-item {{ Request::segment(1) == 'tour' ? "active" : "" }}" href="{{ route('tour.index') }}">{{ __('All Package') }}</a>
                    @foreach ($packages as $package)
                        <a class="dropdown-item" href="{{ route('tour.package',['package' => $package, 'slug' => Str::slug($package->name)]) }}">{{ $package->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="@can('admin') col-md-4 @else col-md-6 @endcan mb-3">
            <form action="{{ route('tour.index') }}" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="key" placeholder="{{ __('Search') }}" aria-label="{{ __('Search') }}" aria-describedby="button-addon2" value="{{ Request('key') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit" id="button-addon2">{{ __('Search') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-columns">
        @foreach ($tours as $tour)
            @if (count($tour->galleries) >= 1)
                <a title="Detail" class="card-link" href="{{ route('tour.show',['tour' => $tour , 'slug' => Str::slug($tour->name)]) }}">
                    <div class="card shadow mt-3 bg-dark tour">
                        <div class="card-img-top w-100 d-block" style="height: 250px; background-image: url('{{asset(Storage::url($tour->galleries[0]->image))}}'); background-size: cover"></div>
                        <div class="card-body text-white">
                            <h4 class="card-title block-with-text font-weight-bold text-white" style="height: 55px">
                                {{$tour->name}}
                            </h4>
                            <p class="text-white float-right h4 pb-3">Rp. {{ substr(number_format($tour->price, 2, ',', '.'),0,-3) }}</p>
                            @can('admin')
                                <div>
                                    <a title="Ubah" class="btn btn-sm btn-success" href="{{ route('tour.edit',$tour) }}"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('tour.destroy',$tour) }}" method="post" class="d-inline-block">
                                        @csrf @method('delete')
                                        <button title="Hapus" class="btn btn-sm btn-danger" type="submit"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </div>
                </a>
            @else
                @can('admin')
                    <div class="card shadow mt-3 bg-dark">
                        <div class="card-body text-white">
                            <h4 class="card-title block-with-text font-weight-bold text-white" style="height: 55px">
                                {{$tour->name}}
                            </h4>
                            <p class="text-white float-right h4 pb-3">Rp. {{ substr(number_format($tour->price, 2, ',', '.'),0,-3) }}</p>
                            @can('admin')
                                <div>
                                    <a title="Ubah" class="btn btn-sm btn-success" href="{{ route('tour.edit',$tour) }}"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('tour.destroy',$tour) }}" method="post" class="d-inline-block">
                                        @csrf @method('delete')
                                        <button title="Hapus" class="btn btn-sm btn-danger" type="submit"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </div>
                @endcan
            @endif
        @endforeach
    </div>
    {{ $tours->links() }}
</div>
@endsection
