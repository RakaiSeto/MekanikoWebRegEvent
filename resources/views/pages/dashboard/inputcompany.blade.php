@extends('layout.default')
@section('title', 'Home')

@push('js')
    <script>
        var posMenuItems = document.getElementById("catinput");
        function handleClick(e) {
            // alert('dor')
            // alert(posMenuItems.value)

            var targetType = posMenuItems.value

            // Show/hide elements based on the 'data-filter' attribute
            var posContentItems = document.querySelectorAll(
                ".subcatname"
            );

            for (let index = 0; index < posContentItems.length; index++) {
                if (targetType === "all" || posContentItems[index].getAttribute("data-type") === targetType) {
                    posContentItems[index].classList.remove("d-none");
                } else {
                    posContentItems[index].classList.add("d-none");
                }
            }
            document.getElementById("subcatinput").value = document.getElementById("subcatinput").getAttribute('defaultvalue');
        }

        document.addEventListener('DOMContentLoaded', function() {
            handleClick()

            const fnameinput = document.getElementById('fnameinput');
            const lnameinput = document.getElementById('lnameinput');
            const companyinput = document.getElementById('companyinput');
            const brandinput = document.getElementById('brandinput');
            const catinput = document.getElementById('catinput');
            const subcatinput = document.getElementById('subcatinput');
            const emailinput = document.getElementById('emailinput');
            const phoneinput = document.getElementById('phoneinput'); 

            let elementsArray = document.querySelectorAll("input");

            elementsArray.forEach(function(elem) {
                elem.addEventListener("keyup", function() {
                    if (fnameinput.value.length < 1 || lnameinput.value.length < 1 || companyinput.value < 1 || brandinput.value.length < 1 || catinput.value.length < 1 || subcatinput.value.length < 1 || emailinput.value.length < 1 || phoneinput.value.length < 1) {
                        document.getElementById('input-token').disabled = true
                    } else {
                        document.getElementById('input-token').disabled = false
                    }
                });
            });

            button = document.getElementById('input-token')
            button.addEventListener("click", function (e) {
                e.preventDefault();
                // // console.log('INI QTY: ' + newObject['qtyToCartMNURUMIPE23017UJ2F']);

                var xhr = new XMLHttpRequest();

                var data = "1=" + fnameinput.value + "&2=" + lnameinput.value + "&3=" + companyinput.value + "&4=" + brandinput.value + "&5=" + catinput.value + "&6=" + subcatinput.value + "&7=" + emailinput.value + "&8=" + phoneinput.value;
                // alert(data)

                xhr.open("POST", "/submitcompany", false);

                var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                var csrfToken = csrfTokenMeta.getAttribute('content');

                xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            location.replace('/dashboard')
                        } else {
                            location.reload('/dashboard')
                        }
                    }
                };

                xhr.send(data);
            });
        })
        function getData(form) {
            var formData = new FormData(form);

            for (var pair of formData.entries()) {
                // console.log(pair[0] + ": " + pair[1]);
            }

            return Object.fromEntries(formData);
        }
    </script>
@endpush

@section('content')
    <div class="landing-feature landing-coin-price bt-none" style="padding: 10px 0 80px">
        <div class="container">
            <div class="modal-content text-dark rounded p-2" style="background-color: #aaa">
                <div class="modal-header justify-content-center mb-2 text-black">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Input New Company</h1>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input type="text" id="fnameinput" class="form-control" placeholder="First name" aria-label="First name" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" id="lnameinput" class="form-control" placeholder="Last name" aria-label="Last name" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" id="companyinput" class="form-control" placeholder="Company" aria-label="Company" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" id="brandinput" class="form-control" placeholder="Brand name" aria-label="Brand name" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <select class="form-select" onchange="handleClick()" id="catinput" aria-label="Default select example">
                            @foreach ($cat as $value1)
                                <option data-filter="{{$value1->getId()}}" class="catname" value="{{$value1->getId()}}">{{$value1->getName()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <select class="form-select" aria-label="Default select example" id="subcatinput">
                            <option disabled selected value> -- select an option -- </option>
                            @foreach ($subcat as $key1 => $value2)
                                @foreach ($value2 as $value3)
                                    <option data-type="{{$cat[$key1]->getId()}}" class="subcatname" value="{{$value3->getId()}}">{{$value3->getName()}}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" id="emailinput" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <input type="number" id="phoneinput" class="form-control" placeholder="Phone" aria-label="Phone" aria-describedby="basic-addon1">
                    </div>
                      
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary btn-block" id="input-token" disabled>Input</button>
                </div>
              </div>
        </div>
    </div>
@endsection
