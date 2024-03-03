@extends('layouts.app')
@section('title',$tour->name)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
<link rel="stylesheet" href="{{ asset("css/mystyle.css") }}">
<link rel="stylesheet" href="{{ asset('css/jquery.fancybox.css') }}">
<link rel="stylesheet" href="{{ asset('css/Testimonials.css') }}">
@endsection

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-dark">
            <li class="breadcrumb-item">
                <a href="{{ route('tour.package',['package' => $tour->package, 'slug' => Str::slug($tour->package->name)]) }}" class="text-white">{{ $tour->package->name }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $tour->name }}</li>
        </ol>
    </nav>

    <div class="row mt-3">
        <div class="col-lg-8 mb-3">
            <div class="card bg-dark shadow">
                <div class="card-body">
                    <div id="owl-one" class="owl-carousel owl-theme">
                        @foreach($tour->galleries->where('is_portofolio', 0) as $gallery)
                            <a href="{{ asset(Storage::url($gallery->image)) }}" data-fancybox>
                                <img src="{{ asset(Storage::url($gallery->image)) }}" class="mw-100" alt="Gambar {{ $tour->name }} {{ $gallery->id }}">
                            </a>
                        @endforeach
                    </div>
                    <h1 class="font-weight-bold text-center my-3">{{ $tour->name }}</h1>
                    <div id="description">
                        {!! $tour->description !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card bg-dark shadow mb-3">
                <div class="card-body">
                    <h4>{{ __('Price') }} : Rp. {{ substr(number_format($tour->price, 2, ',', '.'),0,-3) }} </h4>
                    <a href="{{ route('order.create',['tour' => $tour , 'slug' => Str::slug($tour->name)]) }}" class="btn btn-success">{{  __('Order Ticket') }}</a>
                </div>
            </div>

            @if($tours->count() > 1)
                <div class="card bg-dark shadow mb-3">
                    <div class="card-body">
                        <h4>{{ __('Other tour packages') }}</h4>
                        <ul class="list-unstyled">
                            @foreach($tours as $item)
                                @if ($item->id != $tour->id)
                                    <hr>
                                    <li class="media">
                                        <div class="mr-3"
                                            style="background-size: cover ;height: 64px; width: 64px;background-image: url('{{ asset(Storage::url($item->galleries[0]->image)) }}')">
                                        </div>
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1 block-with-text">{{ $item->name }}</h5>
                                            <div class="">
                                                {{ __('Price') }} : Rp. {{ substr(number_format($item->price, 2, ',', '.'),0,-3) }}
                                            </div>
                                            <a href="{{ route('tour.show',['tour' => $item , 'slug' => Str::slug($item->name)]) }}"
                                                class="btn btn-sm btn-primary float-right">Lihat</a>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($tour->galleries->where('is_portofolio', 1)->count() > 1)
        <div class="text-center mt-5">
            <h2 class="font-weight-bold">Portofolio</h2>
            <div class="row justify-content-center">
                @foreach ($tour->galleries->where('is_portofolio',1) as $gallery)
                    @php
                        $fileparts = explode('.', $gallery->image);
                        $filetype = array_pop($fileparts);
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-3 text-center">
                        <a href="{{ asset(Storage::url($gallery->image)) }}" data-fancybox>
                            @if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'png' || $filetype == 'jpg')
                                <img class="mw-100" src="{{ asset(Storage::url($gallery->image)) }}" alt="">
                            @else
                                <video class="mw-100" src="{{ asset(Storage::url($gallery->image)) }}"></video>
                            @endif
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($tour->orders->count() >= 1 && $tour->orders[0]->rating && $tour->orders[0]->testimonial)
        <section id="testimonial" class="mt-5">
            <div class="pt-5">
                <h2 class="text-center font-weight-bold text-white mt-3">{{ __('Testimonial') }}</h2>
                <div id="owl-two" class="owl-carousel owl-theme">
                    @foreach($tour->orders as $testimony)
                        @if ($testimony->rating && $testimony->testimonial)
                            <div class="card bg-dark shadow testimonials-clean">
                                <div class="card-body">
                                    <div class="item mb-0">
                                        <div class="box">
                                            @if($testimony->rating == 1)
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star-empty.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star-empty.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star-empty.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star-empty.svg')) }}">
                                            @elseif($testimony->rating == 2)
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star-empty.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star-empty.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star-empty.svg')) }}">
                                            @elseif($testimony->rating == 3)
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star-empty.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star-empty.svg')) }}">
                                            @elseif($testimony->rating == 4)
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star-empty.svg')) }}">
                                            @elseif($testimony->rating == 5)
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                                <img height="20px" class="mr-0" src="{{ asset(Storage::url('star.svg')) }}">
                                            @endif
                                            <br>
                                            <p class="description">{{ $testimony->testimonial }}</p>
                                        </div>
                                        <div class="author">
                                            <img class="rounded-circle" src="{{ asset(Storage::url($testimony->user->avatar)) }}">
                                            <h5 class="name text-white">{{ $testimony->user->name }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/jquery.fancybox.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#owl-one').owlCarousel({
                loop: true,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                smartSpeed:1000,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    1000: {
                        items: 1
                    }
                }
            });

            $('#owl-two').owlCarousel({
                margin:10,
                responsive:{
                    0:{
                        items: 1
                    },
                    600:{
                        items: 1
                    },
                    1000:{
                        items: 3
                    }
                }
            });

            $(".owl-dots").hide();
        });

    </script>
@endpush
