@extends('layouts.app')
@section('title',__('Users'))

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="container">
    @include('layouts.components.alert')
    <div class="card bg-dark">
        <div class="card-header">
            <h5 class="m-0 pt-1 font-weight-bold float-left">{{ __('Users') }}</h5>
            <a id="add" href="#modal" title="{{ __('Add new user') }}" class="btn btn-sm btn-success float-right" data-toggle="modal"><i class="fas fa-plus"></i></a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table text-white">
                    <thead>
                        <tr>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Option') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form id="formDelete" method="POST">
    @csrf @method('delete')
</form>

<!-- Modal -->
<div class="modal fade" id="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="color: black">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">{{ __('Add new user') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route("user.store") }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role">{{ __('Role') }}</label>
                        <select name="role" id="role" class="form-control">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('Enter an email')}}" value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('Enter a name')}}" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="phone">{{ __('Phone') }}</label>
                        <input type="text" onkeypress="return hanyaAngka(event);" name="phone" id="phone" class="form-control" placeholder="{{ __('Enter a phone number')}}" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group">
                        <label for="phone_emergency">{{ __('Phone Emergency') }}</label>
                        <input type="text" onkeypress="return hanyaAngka(event);" name="phone_emergency" id="phone_emergency" class="form-control" placeholder="{{ __('Enter a phone emergency number')}}" value="{{ old('phone_emergency') }}">
                    </div>
                    <div class="form-group">
                        <label for="address">{{ __('Address') }}</label>
                        <input type="text" name="address" id="address" class="form-control" placeholder="{{ __('Enter an address')}}" value="{{ old('address') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </form>
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
                url: "{{ route('api.users') }}",
            },
            columns: [
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'role.name',
                    name: 'role.name'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable:false,
                },
            ],
            order: [4]
        });
        $(document).on('click',".delete",function () {
            const id = $(this).data('id');
            let name;
            $.getJSON(baseURL + "/api/user/" + id, function(result){
                swal({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('After deleted, ') }}" + result.data.name + " {{ __('cannot be recovered') }}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $("#formDelete").attr('action', baseURL + "/user/" + id);
                        $("#formDelete").submit();
                    } else {
                        swal.close();
                    }
                });
            });
        });
    });
</script>
@endpush
