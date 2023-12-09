@extends('layout.default')
@section('title', 'Home')

@section('content')
    <div class="vh-100 d-flex justify-content-center mx-auto" style="max-width: 75vw">
        <div class="form-access my-auto rounded" style="background-color: #aaa">
            <form action="/doLogin" method="POST">
                {{ csrf_field() }}
                <span>Sign In</span>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" id="emailx" name="emailx">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" id="passwordx" name="passwordx">
                </div>
                {{-- <div class="text-right">
                    <a href="/resetpassword">Forgot Password?</a>
                </div> --}}
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>
            @if (session('message'))
                &nbsp
                <div class="form-group alert alert-danger">
                    {{ session('message') }}
                </div>
            @endif
        </div>
    </div>

    <style>
    </style>
@endsection
