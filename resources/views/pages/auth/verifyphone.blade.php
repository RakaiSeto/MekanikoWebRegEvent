@extends('layout.default')
@section('title', 'Home')

@push('js')
  <script src="{{URL::asset('assets/js/verifyphone.js')}}"></script>
@endpush

@section('content')
    <div class="vh-100 d-flex justify-content-center">
        <div class="form-access my-auto">
            <span>Verify Phone</span>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-7">
                        <input type="number" class="form-control" placeholder="Verification Code" id="verifx" name="verifx" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6">
                    </div>
                    <div class="col-5">
                        <button type="button" class="btn btn-primary" style="margin-top: 0px" id="sendverif">Get Code</button>
                    </div>
                </div>
            </div>
            <div class="form-group alert alert-danger d-none" id="verify-alert"></div>
            <div class="form-group alert alert-info d-none" id="verify-info"></div>
                
            {{-- </div> --}}
            {{-- <div class="text-right">
                <a href="/resetpassword">Forgot Password?</a>
            </div> --}}
            {{-- <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="form-checkbox">
                <label class="custom-control-label" for="form-checkbox">Remember me</label>
            </div> --}}
            <button disabled id="signup-submit" type="button" class="btn btn-primary">Verify Phone</button>
            <div class="form-group alert alert-danger d-none" id="verify-error"></div>
            <div class="form-group alert alert-info d-none" id="verify-valid"></div>
            
            @if (session('message'))
                &nbsp
                <div class="form-group alert alert-danger">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('valid'))
                &nbsp
                <div class="form-group alert alert-info">
                    {{ session('valid') }}
                </div>
            @endif
        </div>
    </div>

    <script>
        const verifinput = document.getElementById('verifx');
        verifinput.addEventListener('keyup', function (e) {
            if (verifinput.value.length < 6) {
                document.getElementById('signup-submit').disabled = true
            } else {
                document.getElementById('signup-submit').disabled = false
            }
        });

    </script>
@endsection
