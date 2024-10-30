@if (session('message'))
<div class="alert alert-warning" role="alert">
    {{ session('message') }}
</div>
@endif
@if (session('verified_message'))
<div class="alert alert-success">
    {{ session('verified_message') }}
</div>
@endif
