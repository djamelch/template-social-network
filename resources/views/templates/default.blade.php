<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Social</title> <!-- Extend this file each time for views. -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

        <script src="{{asset('js/custom.js')}}"></script>
    </head>
    <body>
        @include('templates.partials.navigation')
        <div class="container"><!--content from child templates. -->
            @include('templates.partials.alerts')
            @yield('content')
        </div>
    </body>
</html>