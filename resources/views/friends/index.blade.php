@extends('templates.default')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <h3>Your friends</h3>

            <!-- Friiend list -->
            @if(!$friends->count())
                <p>You have no friends.</p>
            @else
            @foreach($friends as $user)
                @include('user/partial/userblock') <!-- Show friends in blocks -->
            @endforeach
            @endif
        </div>
        <div class="col-lg-6">
            <h4>Friend requests</h4>

            <!-- friend requests -->
            @if(!$requests->count())
                <p>You have no friend requests.</p>
            @else
                @foreach($requests as $user)
                    @include ('user.partial.userblock')
                @endforeach
            @endif
        </div>
    </div>
@stop