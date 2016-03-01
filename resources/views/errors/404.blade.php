@extends('templates.default')

@section('content')
    <!-- Custom 404 -->
    <h3>Opps, that page could not be found. </h3>
    <a href="{{ route('home') }}"><input type="button" class="btn btn-primary" value="Go home"></a>
@stop