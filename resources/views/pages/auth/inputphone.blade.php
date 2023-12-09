@extends('layout.default')
@section('title', 'Home')

@push('js')
  <script src="{{URL::asset('assets/js/inputphone.js')}}"></script>
@endpush

@section('content')
    <div class="vh-100 d-flex justify-content-center">
        <div class="form-access my-auto">
            <form action="/dochangephone" id="signupform" method="POST">
                {{ csrf_field() }}
                <span>Register Phone Number</span>
                <div class="form-group">
                    <input id="phonex" name="phonex" class="form-control" style="width: 100%" type="tel"/>
                    {{-- <button id="btn" type="button" style="border-radius:5px; padding-top:6px; padding-bottom:6px; font-size:14px" class="btn-primary w-25 mt-0 text-center">Validate</button> --}}
                    <span id="valid-msg" class="d-none"></span>
                    <div id="error-msg" class="form-group alert alert-danger d-none text-center"></div>
                </div>
                <button disabled id="signup-submit" type="button" class="btn btn-primary">Confirm Phone Number</button>
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

            if (e.target.value == '') {
                document.getElementById('signup-submit').disabled = true
            } else if (iti.isValidNumber()){
                document.getElementById('signup-submit').disabled = false
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
