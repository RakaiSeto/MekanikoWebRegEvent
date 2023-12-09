<!DOCTYPE html>
<html lang="en">

@include('partial.head')

<body >
@include('partial.header')

@yield('content')

{{-- @include('partial.footer') --}}

@include('partial.js')
</body>

</html>
