@extends('layout.default')
@section('title', 'Home')

@push('js')
  <script src="{{URL::asset('assets/js/signuppage.js')}}"></script>
@endpush

@section('content')
    <div class="vh-100 d-flex justify-content-center">
        <div class="form-access my-auto">
            <form action="/doSignup" id="signupform" method="POST">
                {{ csrf_field() }}
                <span>Sign Up</span>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email Address" id="emailx" name="emailx">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Full Name" id="namex" name="namex">
                </div>
                <div class="form-group">
                    <input id="phonex" name="phonex" class="form-control" style="width: 100%" type="tel"/>
                    {{-- <button id="btn" type="button" style="border-radius:5px; padding-top:6px; padding-bottom:6px; font-size:14px" class="btn-primary w-25 mt-0 text-center">Validate</button> --}}
                    <span id="valid-msg" class="d-none"></span>
                    <div id="error-msg" class="form-group alert alert-danger d-none text-center"></div>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" id="passwordx" name="passwordx">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" id="confpasswordx" name="confpasswordx">
                </div>
                <div class="form-group alert alert-danger d-none" id="confirm-password-alert">
                    Password and Confirm password is not same
                </div>
                {{-- <div class="text-right">
                    <a href="/resetpassword">Forgot Password?</a>
                </div> --}}
                {{-- <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="form-checkbox">
                    <label class="custom-control-label" for="form-checkbox">Remember me</label>
                </div> --}}
                <button disabled id="signup-submit" type="button" class="btn btn-primary">Sign Up</button>
            </form>
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
            <h2>Already have an account? <a href="/signin">Sign in here</a></h2>
        </div>
    </div>

    <style>
        .iti{
            width: 100%
        }

        span.iti__country-name, span.iti__dial-code{
            color: #111;
            display:contents
        }
    </style>

    <script>
        const errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
        const confirmPassword = document.getElementById('confpasswordx');

        confirmPassword.addEventListener('keyup', function (e) {
            const emailInput = document.getElementById('emailx');
            const passwordInput = document.getElementById('passwordx');
            const confirmPhone = document.getElementById('phonex');
            const nameInput = document.getElementById('namex');
            if (passwordInput.value != e.target.value) {
                document.getElementById('confirm-password-alert').classList.remove('d-none')
                document.getElementById('signup-submit').disabled = true
            } else if (passwordInput.value == e.target.value) {
                console.log('is same')
                document.getElementById('confirm-password-alert').classList.add('d-none')
                if (emailInput.value != '' && confirmPhone.value != '' && passwordInput.value.length >= 8 && nameInput.value != '') {
                    document.getElementById('signup-submit').disabled = false
                }
            }
        });

        const confirmEmail = document.getElementById('emailx');
        confirmEmail.addEventListener('keyup', function (e) {
            const confPasswordInput = document.getElementById('confpasswordx');
            const passwordInput = document.getElementById('passwordx');
            const confirmPhone = document.getElementById('phonex');
            const nameInput = document.getElementById('namex');
            console.log(passwordInput.value + ' | ' + confPasswordInput.value);
            if (e.target.value == '') {
                document.getElementById('signup-submit').disabled = true
            } else if (passwordInput.value == confPasswordInput.value) {
                console.log('sama kan')
                document.getElementById('confirm-password-alert').classList.add('d-none')
                if (e.target.value != '' && confirmPhone.value != '' && passwordInput.value.length >= 8 && nameInput.value != '') {
                    document.getElementById('signup-submit').disabled = false
                }
            }
        });

        const confirmPhone = document.getElementById('phonex');
        confirmPhone.addEventListener('keyup', function (e) {
            const errorMsg = document.getElementById("error-msg");
            const validMsg = document.getElementById("valid-msg");
            input.classList.remove("error");
            errorMsg.innerHTML = "";
            errorMsg.classList.add("d-none");
            validMsg.classList.add("d-none");
            if (input.value.trim()) {
                console.log(input.value.trim());
                if (iti.isValidNumber()) {
                    validMsg.classList.remove("d-none");
                } else {
                    input.classList.add("error");
                    const errorCode = iti.getValidationError();
                    if (errorCode != -99) {
                        errorMsg.innerHTML = errorMap[errorCode];
                    } else {
                        errorMsg.innerHTML = "Invalid number"
                    }
                    errorMsg.classList.remove("d-none");
                }
            }

            const confPasswordInput = document.getElementById('confpasswordx');
            const passwordInput = document.getElementById('passwordx');
            const emailInput = document.getElementById('emailx');
            const nameInput = document.getElementById('namex');
            if (e.target.value == '') {
                document.getElementById('signup-submit').disabled = true
            } else if (passwordInput.value == confPasswordInput.value) {
                document.getElementById('confirm-password-alert').classList.add('d-none')
                if (e.target.value != '' && emailInput.value != '' && passwordInput.value.length >= 8 && nameInput.value != '') {
                    document.getElementById('signup-submit').disabled = false
                }
            }
        });

        const submitButton = document.getElementById('signup-submit');
        submitButton.addEventListener('click', function() {
            const confirmPhone = document.getElementById('phonex');
            countrydata = iti.getSelectedCountryData();
            confirmPhone.value = '+' + countrydata['dialCode'] + '|' + confirmPhone.value
            document.getElementById("signupform").submit();
        })

        // Assuming you have an element with id "phonex"
        const input = document.getElementById("phonex");
        const button = document.querySelector("#btn");

        // initialise plugin
        const iti = window.intlTelInput(input, {
            initialCountry: "auto",
            geoIpLookup: callback => {
                fetch("https://ipapi.co/json")
                .then(res => res.json())
                .then(data => callback(data.country_code))
                .catch(() => callback("us"));
            },
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
            placeholderNumberType:"MOBILE",
            formatOnDisplay: true,
        });

    </script>
@endsection
