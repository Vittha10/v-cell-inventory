<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <title>Register Page </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc."/>
        <meta name="author" content="Zoyothemes"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />


        <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico')}}">


        <link href="{{ asset('backend/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />


        <link href="{{ asset('backend/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />

    </head>

    <body class="bg-white">

        <div class="account-page">
            <div class="p-0 container-fluid">
    <div class="row align-items-center g-0">
        <div class="col-xl-5">
            <div class="row">
                <div class="mx-auto col-md-7">
                    <div class="p-4 mb-0 border-0 p-md-5 p-lg-0">
                        <div class="p-0 mb-4">
                            <a href="index.html" class="auth-logo">
                                <img src="{{ asset('backend/assets/images/logo-dark.png')}}" alt="logo-dark" class="mx-auto" height="28" />
                            </a>
                        </div>

                        <div class="pt-0">
    <form  method="POST" action="{{ route('register') }}" class="my-4">
        @csrf

        <div class="mb-3 form-group">
            <label for="emailaddress" class="form-label">Name</label>
            <input class="form-control" name="name" type="text" id="name" required="" placeholder="Enter your Name">
        </div>

        <div class="mb-3 form-group">
            <label for="emailaddress" class="form-label">Email address</label>
            <input class="form-control" name="email" type="email" id="email" required="" placeholder="Enter your email">
        </div>

        <div class="mb-3 form-group">
            <label for="password" class="form-label">Password</label>
            <input class="form-control" name="password" type="password" required="" id="password" placeholder="Enter your password">
        </div>

        <div class="mb-3 form-group">
            <label for="password" class="form-label">Confirm Password</label>
            <input class="form-control" name="password_confirmation" type="password" required="" id="password_confirmation" placeholder="Enter your Confirm password">
        </div>

        <div class="mb-3 form-group d-flex">
            <div class="col-sm-6 text-end">
                <a class='text-muted fs-14' href='{{ route('password.request') }}'>Forgot password?</a>
            </div>
        </div>

        <div class="mb-0 form-group row">
            <div class="col-12">
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit"> Register </button>
                </div>
            </div>
        </div>
    </form>

                            <div class="my-4 saprator"><span>or sign in with</span></div>

                            <div class="mb-4 text-center text-muted">
                                <p class="mb-0">Don't have an account ?<a class='text-primary ms-2 fw-medium' href='{{ route('login') }}'>Sing In</a></p>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="p-4 account-page-bg p-md-5">
                <div class="text-center">
                    <h3 class="mb-3 text-dark pera-title">Register Page for Inventory Managment System</h3>
                    <div class="auth-image">
                        <img src="{{ asset('backend/assets/images/authentication.svg')}}" class="mx-auto img-fluid"  alt="images">
                    </div>
                </div>
            </div>
        </div>
    </div>
            </div>
        </div>


        <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js')}}"></script>
        <script src="{{ asset('backend/assets/libs/waypoints/lib/jquery.waypoints.min.js')}}"></script>
        <script src="{{ asset('backend/assets/libs/jquery.counterup/jquery.counterup.min.js')}}"></script>
        <script src="{{ asset('backend/assets/libs/feather-icons/feather.min.js')}}"></script>


        <script src="{{ asset('backend/assets/js/app.js')}}"></script>

    </body>
</html>
