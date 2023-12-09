@extends('layout.default')
@section('title', 'Home')

@push('js')
    <script>
        function searchTable() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                td2 = tr[i].getElementsByTagName("td")[2];
                found = false;
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        found = true;
                    }
                }
                if (td2) {
                    txtValue = td2.textContent || td2.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        found = true;
                    }
                }
                if (!found) {
                    tr[i].style.display = "none";
                }
            }
        }
    </script>
@endpush

@section('content')
    <div class="landing-feature landing-coin-price bt-none" style="padding: 10px 0 80px">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="border-bottom: 1px solid white">
                    <h2 class="p-0 m-0 my-2">All Company</h2>
                </div>
                <div class="col-md-6 mx-auto mb-3">
                    <a href="/inputcompany" class="text-decoration-none btn btn-primary">Add new company</a>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Search</span>
                    <input type="text" onkeyup="searchTable()" id="myInput" class="form-control" placeholder="Search Table" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <table class="table table-success table-striped table-bordered align-middle mb-0 mx-auto" style="max-width: 90vw">
                    <thead class="bg-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Company</th>
                            <th scope="col">Brandname</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        @if (count($results) > 0)
                        @foreach ($results as $key => $oneitem)
                        <tr>
                            <td>
                                {{$index[$key]}}
                            </td>
                            <td>
                                <a href="#" data-toggle="modal" class="text-decoration-none text-primary" data-target="#modal{{$oneitem->getPrincipalid()}}">
                                    {{$oneitem->getCompany()}}
                                </a>
                            </td>
                            <td>
                                {{$oneitem->getBrandname()}}
                            </td>
                        </tr>
                        @endforeach
                        @else
                            <tr>
                                <th scope="row" colspan="3">No Company Present</th>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($itemdetail as $key => $item)
        
    <div class="modal fade" id="modal{{$onesid[$key]}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0">
				{{-- <a href="#" data-bs-dismiss="modal" class="btn-close position-absolute top-0 end-0 m-4"></a> --}}
				<div class="modal-header">
                    <h5 class="modal-title">Company Detail : <br>{{$item[0]->getCompany()}}</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-5"><div class="fs-6 text-start mb-1">- First Name</div></div>
                        <div class="col-6"><div class="fs-6 text-start mb-1">{{$item[0]->getFirstname()}}</div></div>
                    </div>
                    <div class="row">
                        <div class="col-5"><div class="fs-6 text-start mb-1">- Last Name</div></div>
                        <div class="col-6"><div class="fs-6 text-start mb-1">{{$item[0]->getLastname()}}</div></div>
                    </div>
                    <div class="row">
                        <div class="col-5"><div class="fs-6 text-start mb-1">- Brand Name</div></div>
                        <div class="col-6"><div class="fs-6 text-start mb-1">{{$item[0]->getBrandname()}}</div></div>
                    </div>
                    <div class="row">
                        <div class="col-5"><div class="fs-6 text-start mb-1">- Category</div></div>
                        <div class="col-6"><div class="fs-6 text-start mb-1">{{$item[0]->getCategory()}}</div></div>
                    </div>
                    <div class="row">
                        <div class="col-5"><div class="fs-6 text-start mb-1">- Subcategory</div></div>
                        <div class="col-6"><div class="fs-6 text-start mb-1">{{$item[0]->getSubcategory()}}</div></div>
                    </div>
                    <div class="row">
                        <div class="col-5"><div class="fs-6 text-start mb-1">- Email</div></div>
                        <div class="col-6"><div class="fs-6 text-start mb-1">{{$item[0]->getEmailaddress()}}</div></div>
                    </div>
                    <div class="row">
                        <div class="col-5"><div class="fs-6 text-start mb-1">- Phone</div></div>
                        <div class="col-6"><div class="fs-6 text-start mb-1">{{$item[0]->getPhone()}}</div></div>
                    </div>
                    <div class="row">
                        <div class="col-5"><div class="fs-6 text-start mb-1">- Code</div></div>
                        <div class="col-6"><div class="fs-6 text-start mb-1">{{$item[0]->getCode()}}</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
						{{-- <div class="row mb-3">
							<div class="col-12">
								<button type="submit" class="btn btn-theme fw-semibold d-flex justify-content-center align-items-center py-3 m-0 buttonAddCart w-100" data-form-id="formCart{{$menu->getMenuid()}}" id="buttonCart{{$menu->getMenuid()}}" onclick="confirmCloseOrder('{{$info[0]->getOrderid()}}')">Yes<i class="fa fa-plus ms-2 my-n3"></i></button>
							</div>
						</div> --}}
						
							{{-- <div class="col-3 d-flex align-items-center">
								<button type="button" class="btn btn-outline-primary h-100" onclick="confirmCloseOrder('{{$info[0]->getOrderid()}}')">
								<div class="fs-5 text-start mb-1" style="cursor: pointer">
										<img style="max-width: 100%; max-width" src="https://www.freepnglogos.com/uploads/visa-card-logo-9.png" alt="">
								</div>
								</button>
							</div>
							<div class="col-3 d-flex align-items-center">
								<button type="button" class="btn btn-outline-primary h-100" onclick="confirmCloseOrder('{{$info[0]->getOrderid()}}')">
								<div class="fs-5 text-start mb-1" style="cursor: pointer">
										<img style="max-width: 80%; margin-right: 10%; margin-left:10%" src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" alt="">
									</div>
								</button>
							</div>
							<div class="col-3 d-flex align-items-center">
								<button type="button" class="btn btn-outline-primary h-100" onclick="confirmCloseOrder('{{$info[0]->getOrderid()}}')">
								<div class="fs-5 text-start mb-1" style="cursor: pointer">
										<img style="max-width: 100%" src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/Credit_or_Debit_Card_Flat_Icon_Vector.svg/768px-Credit_or_Debit_Card_Flat_Icon_Vector.svg.png?20180301080914" alt="">
									</div>
								</button>
							</div>
							<div class="col-3 d-flex align-items-center">
								<button type="button" class="btn btn-outline-primary h-100" onclick="confirmCloseOrder('{{$info[0]->getOrderid()}}')">
								<div class="fs-5 text-start mb-1" style="cursor: pointer">
										<img style="max-width: 100%" src="https://png.pngtree.com/png-vector/20220217/ourmid/pngtree-cash-logo-icon-design-vector-illustration-company-modern-banking-vector-png-image_35391014.png" alt="">
									</div>
								</button>
							</div> --}}
    @endforeach

    {{-- <script>
        var currentURL = window.location.href
        File.prototype.convertToBase64 = function(callback){
                var reader = new FileReader();
                reader.onloadend = function (e) {
                    console.log(e.target.result)
                    callback(e.target.result, e.target.error);
                    
                };   
                reader.readAsDataURL(this);
        };

        var tokensName = [];
        @foreach ($token as $onetoken)
            tokensName.push('{{ $onetoken->getTokenid() }}');
        @endforeach

        var theBase64 = "" 
        var button = document.getElementById('input-token');
        button.addEventListener('click', function(e) {
            this.disabled = true;

            var xhr = new XMLHttpRequest();
            
            var data = "token_code=" + codeinput.value + "&token_name=" + nameinput.value + "&token_desc=" + descinput.value + "&token_logo=" + theBase64;
            
            xhr.open("POST", "/admin/inputtoken", false);

            var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            var csrfToken = csrfTokenMeta.getAttribute('content');

            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        location.replace(currentURL)
                    } else if (xhr.status === 400) {
                        // alert("FAILED TO SEND MENU ORDER " + newObject["menu_id"] + ": " + xhr.status)
                        location.replace("/signout")
                    } else {
                        location.replace(currentURL)
                    }
                }
            };

            xhr.send(data);
        })

        const logoinput = document.getElementById('logoinput');
        const codeinput = document.getElementById('codeinput');
        const nameinput = document.getElementById('nameinput');
        const descinput = document.getElementById('descinput');
        var isFound = false;
        codeinput.addEventListener('keyup', function (e) {
            isFound = tokensName.includes(codeinput.value);
            
            if (codeinput.value.length < 1 || nameinput.value.length < 1 || logoinput.value == "" || isFound == true) {
                document.getElementById('input-token').disabled = true
            } else {
                document.getElementById('input-token').disabled = false
            }
        });
        nameinput.addEventListener('keyup', function (e) {
            if (codeinput.value.length < 1 || nameinput.value.length < 1 || logoinput.value == "" || isFound == true) {
                document.getElementById('input-token').disabled = true
            } else {
                document.getElementById('input-token').disabled = false
            }
        });
        logoinput.addEventListener('change', function (e) {
            var reader = new FileReader();
            var baseString;
            reader.onloadend = function () {
                baseString = reader.result;
                theBase64 = baseString
            };
            reader.readAsDataURL(logoinput.files[0]);
            if (codeinput.value.length < 1 || nameinput.value.length < 1 || logoinput.value == "" || isFound == true) {
                document.getElementById('input-token').disabled = true
            } else {
                document.getElementById('input-token').disabled = false
            }
        });


        
    </script> --}}
@endsection
