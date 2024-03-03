@extends('layouts.app')
@section('title', __('Transaction'))

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xl-9 mb-4 align-self-center">
            <h1>{{ __('Transaction') }}</h1>
        </div>
        @can('admin')
            <div class="col-xl-3 mb-4">
                <div class="card border-left-primary shadow bg-dark py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('Total Balance') }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ipaymu->checkBalance()['Saldo'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    <div class="card bg-dark">
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table text-white">
                    <thead>
                        <tr>
                            <th>{{ __('Transaction ID') }}</th>
                            <th>{{ __('Tour Package') }}</th>
                            <th>{{ __('Nominal') }}</th>
                            <th>{{ __('Expired') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Option') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script type="text/javascript" charset="utf8" src="{{ asset('js/script.js') }}"></script>
<script type="text/javascript" charset="utf8" src="{{ asset("js/jquery.dataTables.min.js") }}"></script>
<script type="text/javascript" charset="utf8" src="{{ asset("js/dataTables.bootstrap4.min.js") }}"></script>
<script>
    $(document).ready(function () {
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('api.order', auth()->user()->id) }}",
            },
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'tour_name',
                    name: 'tour_name'
                },
                {
                    data: 'nominal',
                    name: 'nominal'
                },
                {
                    data: 'expired',
                    name: 'expired'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable:false,
                },
            ],
            order: [[ 0, "desc" ]],
        });
    });
</script>
@endpush
