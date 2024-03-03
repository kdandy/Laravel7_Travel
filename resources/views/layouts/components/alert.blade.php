@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i> <strong>{{ __('Failed') }}</strong></span>
        <span class="alert-text">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
