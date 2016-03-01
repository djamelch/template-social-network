@extends('templates.default')

@section('content')
    <h3>Your search for "{{ Request::input('query') }}"</h3> <!-- adds teh value that was inputed to the input query(or search bar) -->

    @if(!$users->count()) <!-- If we have a user count of 0 do this. -->
        <p>No results found sorry.</p>
    @else
        <div class="row">
            <div class="col-lg-12">
                @foreach($users as $user)
                    @include('user/partial/userblock')
                @endforeach
            </div>
        </div>
    @endif
@stop