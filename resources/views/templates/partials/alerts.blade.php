@if(Session::has('info')) <!-- Checks if sessions has a key called info. -->
    <div class="alert alert-info" role="alert">
        {{ Session::get('info')  }}
    </div>
@endif