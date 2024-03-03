@extends('layouts.app')
@section('title', __('Package'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            @include('layouts.components.alert')
            <div class="card bg-dark">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold float-left">{{ __('Package') }}</h5>
                    <a id="tambah" href="#modal" title="Tambah" class="btn btn-sm btn-success float-right" data-toggle="modal"><i class="fas fa-plus"></i></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-white">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Option') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packages as $package)
                                    <tr>
                                        <td>{{ $package->name }}</td>
                                        <td>
                                            <a href="#modal" title="edit" data-toggle="modal" data-id="{{ $package->id }}" class="btn btn-sm btn-success edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger delete" data-id="{{ $package->id }}" title="{{ __('Delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="formdelete" method="POST">
    @csrf @method('delete')
</form>

<!-- Modal -->
<div class="modal fade" id="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="color: black">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPackage" method="POST">
                @csrf @method('post')
                <div class="modal-body">
                    <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('Enter a package name') }}">
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
    <script>
        $(document).ready(function () {
            $("#tambah").click(function(){
                $("#modalLabel").html('Tambah Paket');
                $("#formPackage")[0].reset();
                $("#formPackage").attr('action', baseURL + "/package");
                $("#formPackage > input[name='_method']").val('post');
            });

            $(".edit").click(function(){
                $("#modalLabel").html('edit Paket');
                $("#formPackage")[0].reset();
                $("#formPackage").attr('action', baseURL + "/package/" + $(this).data('id'));
                $("#formPackage > input[name='_method']").val('put');
                $.getJSON(baseURL + "/package/" + $(this).data('id'), function(data){
                    $("#name").val(data.name);
                });
            });

            $(".delete").click(function () {
                const id = $(this).data('id');
                let name;
                $.getJSON(baseURL + "/package/" + id, function(data){
                    swal({
                        title: "{{ __('Are you sure?') }}",
                        text: "{{ __('After deleted, ') }}" + data.name + " {{ __('cannot be recovered') }}",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $("#formdelete").attr('action', baseURL + "/package/" + id);
                            $("#formdelete").submit();
                        } else {
                            swal.close();
                        }
                    });
                });
            });
        });
    </script>
@endpush
