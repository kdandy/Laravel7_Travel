@extends('layouts.app')
@section('title',__('Transaction detail').' #'.$order->id)

@section('styles')
<style>
    @import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);

    fieldset, label { margin: 0; padding: 0; }
    h1 { font-size: 1.5em; margin: 10px; }

    /****** Style Star Rating Widget *****/

    .rating {
    border: none;
    float: left;
    }

    .rating > input { display: none; }
    .rating > label:before {
    margin: 5px;
    font-size: 1.25em;
    font-family: FontAwesome;
    display: inline-block;
    content: "\f005";
    }

    .rating > .half:before {
    content: "\f089";
    position: absolute;
    }

    .rating > label {
    color: #ddd;
    float: right;
    }

    /***** CSS Magic to Highlight Stars on Hover *****/

    .rating > input:checked ~ label, /* show gold star when clicked */
    .rating:not(:checked) > label:hover, /* hover current star */
    .rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */

    .rating > input:checked + label:hover, /* hover current star when changing rating */
    .rating > input:checked ~ label:hover,
    .rating > label:hover ~ input:checked ~ label, /* lighten current selection */
    .rating > input:checked ~ label:hover ~ label { color: #FFED85;  }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8 mb-3">
            <div class="card bg-dark shadow">
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="media">
                            <div class="mr-3"
                                style="background-size: cover ;height: 64px; width: 64px;background-image: url('{{ asset(Storage::url($order->tour->galleries[0]->image)) }}')">
                            </div>
                            <div class="media-body">
                                <a href="{{ route('tour.show',['tour' => $order->tour , 'slug' => Str::slug($order->tour->name)]) }}" class="mt-0 mb-1 block-with-text h5 text-white font-weight-bold">{{ $order->tour->name }} #{{ $order->id }}</a>
                                <h6 class="mt-0 mb-1">{{ __('Price') }} : Rp. {{ substr(number_format($order->tour->price, 2, ',', '.'),0,-3) }}</h6>
                            </div>
                        </li>
                    </ul>
                    <div class="row">
                        <div class="col-md-5 mb-2">
                            <div class="form-group">
                                <label for="date_start">{{ __('Date Start')}}</label>
                                <input disabled type="text" id="date_start" class="form-control" name="date_start" value="{{ date('d F Y H:i:s', strtotime($order->date_start)) }}">
                            </div>
                        </div>
                        <div class="col-md-5 mb-2">
                            <div class="form-group">
                                <label for="date_end">{{ __('Date End') }}</label>
                                <input disabled type="text" id="date_end" class="form-control" name="date_end" value="{{ date('d F Y H:i:s', strtotime($order->date_end)) }}">
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="form-group">
                                <label for="quantity">{{ __('Quantity')}}</label>
                                <input disabled type="number" name="quantity" id="quantity" class="form-control" value="{{ $order->qty }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hometown">{{ __('Hometown') }}</label>
                        <input disabled type="text" name="hometown" id="hometown" class="form-control" value="{{ $order->hometown }}">
                    </div>
                    <div class="form-group">
                        <label for="note">{{ __('Note') }}</label>
                        <textarea name="note" id="note" class="form-control" rows="3" style="resize: none" disabled>{{ $order->note }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow bg-dark">
                <div class="card-body text-center">
                    @if ($order->paymentTime == null)
                        @if (strtotime(date('Y-m-d H:i:s')) >= strtotime($order->expired))
                            <h4>{{ __('Payment Time Expired') }}</h4>
                            <p><i class="fas fa-times-circle fa-10x" style="color: red"></i></p>
                            <p>{{ __('Payment Method') }}</p>
                            @if ($order->via == 'qris')
                                <p>{{ __('QRIS') }}</p>
                            @else
                                <p>{{ __('Virtual Account') }}:</p>
                            @endif
                            <p class="mb-0">{{ __('Nominal') }}</p>
                            <p class="h4">Rp. {{ substr(number_format($order->amount, 2, ',', '.'),0,-3) }}</p>
                            <p>{{ __('Deadline') }}<br>{{ date('d F Y, H:i:s', strtotime($order->expired)) }}</p>
                        @else
                            <h4>{{ __('Payment Method') }}</h4>
                            @if ($order->via == 'qris')
                                <input id="qr" type="text" value="{{ $order->payment_no }}" style="display: none">
                                <p>{{ __('Please make payment via QRIS') }}:</p>
                                <img src="{{ asset('images/qris.png') }}" height="30px">
                                <div class="py-3 my-3" style="background-color: white">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="qrcode"/>
                                    </svg>
                                </div>
                            @else
                                <p>{{ __('Please make payments through a virtual account') }}:</p>
                                <img src="{{ asset('images/'.$order->channel.'.png') }}" height="30px">
                                <p>{{ __('Payment Code') }}<br><h4 id="kode">{{ $order->payment_no }}</h4></p>
                            @endif
                            <p class="mb-0">{{ __('Nominal') }}</p>
                            <p class="h4">Rp. {{ substr(number_format($order->amount, 2, ',', '.'),0,-3) }}</p>
                            <p>{{ __('Deadline') }}<br>{{ date('d F Y, H:i:s', strtotime($order->expired)) }}</p>
                            @can('member')
                                <form action="{{ route('order.destroy', $order) }}" method="post">
                                    @csrf @method('delete')
                                    <button type="submit" class="btn btn-danger btn-block">Batalkan</button>
                                </form>
                            @endcan
                        @endif
                    @else
                        <h4>{{ __('Payment Success') }}</h4>
                        <p><i class="fas fa-check-circle fa-10x" style="color: greenyellow"></i></p>
                        <p>
                            {{ __("Payment method") }} :
                            @if ($order->via == 'qris')
                                {{ __('QRIS') }}
                            @else
                                {{ __('Virtual Account') }}
                            @endif
                        </p>
                        <p class="mb-0">{{ __('Nominal') }}</p>
                        <p class="h4">Rp. {{ substr(number_format($order->amount, 2, ',', '.'),0,-3) }}</p>
                        <p>{{ __('Time of payment') }}<br>{{ date('d F Y, H:i:s', strtotime($order->paymentTime)) }}</p>
                        <p class="h4 mb-0 mt-3">{{ __('Testimonial') }}</p>
                        <form action="{{ route('testimonial.update', $order) }}" method="post">
                            @csrf @method('put')
                            <div class="form-group">
                                <div class="rating">
                                    <input type="radio" id="star5" name="rating" value="5" {{ $order->rating == 5 ? 'checked='.'"'.'checked'.'"' : ''  }}/><label class = "full" for="star5" title="Awesome - 5 stars"></label>
                                    <input type="radio" id="star4" name="rating" value="4" {{ $order->rating == 4 ? 'checked='.'"'.'checked'.'"' : ''  }}/><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
                                    <input type="radio" id="star3" name="rating" value="3" {{ $order->rating == 3 ? 'checked='.'"'.'checked'.'"' : ''  }}/><label class = "full" for="star3" title="Meh - 3 stars"></label>
                                    <input type="radio" id="star2" name="rating" value="2" {{ $order->rating == 2 ? 'checked='.'"'.'checked'.'"' : ''  }}/><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
                                    <input type="radio" id="star1" name="rating" value="1" {{ $order->rating == 1 ? 'checked='.'"'.'checked'.'"' : ''  }}/><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
                                </div>
                                @error('rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <textarea name="testimonial" id="testimonial" rows="2" class="form-control" placeholder="{{ __('Enter your testimonial') }}">{{ old('testimonial', $order->testimonial) }}</textarea>
                            </div>
                            <button class="btn btn-block btn-success" type="submit">Kirim</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('js/qrcode.js') }}"></script>
    @if ($order->via == 'qris')
        <script>
            const qrcode = new QRCode(document.getElementById('qrcode'),{
                useSVG: true
            });

            qrcode.makeCode($("#qr").val());
        </script>
    @endif

    @if ($order->paymentTime == null)
        <script type="text/javascript">
            function checkTransaction(){
                $.getJSON("{{ route('check-transaction',$order->transaction_id) }}", function(result){
                    if (result.WaktuBayar != "") {
                        $.ajax({
                            url: "{{ route('order.update', $order) }}",
                            type: 'POST',
                            data: {
                                _token      : _token,
                                _method     : 'patch',
                                paymentTime : result.WaktuBayar,
                                status      : result.Keterangan
                            },
                            success: function(){
                                location.reload();
                            }
                        });
                    }
                });
            }

            checkTransaction();
            setInterval(checkTransaction(), 5000);
        </script>
    @endif

@endpush
