<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />

    <title>Casamaintenance APP INDISPONIBLE</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="app_creator" name="Elmarzougui Abdelghafour" />
    <meta content="app_version" name="1.1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />

    <link rel="shortcut icon" href="{{ asset('images/logo-app-2.png') }}">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>

    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">

                        <h1 class="display-2 fw-medium">5<i class="bx bx-buoy bx-spin text-primary display-3"></i>3</h1>

                        <h2 class="fw-medium text-danger">Maintenance MODE</h2>
                        <h4 class="">
                            Veuillez nous excuser pour la gêne occasionnée.<br> <b>Casamaintenance APP</b> sera à
                            nouveau disponible sous peu, revenez bientôt !
                        </h4>
                        <div class="mt-5 text-center">
                            <a class="btn btn-primary waves-effect waves-light"
                                href="https://casamaintenance.ma">casamaintenance.ma</a>
                        </div>

                        <hr>

                        <h2 class="fw-medium text-danger">Application by WEDOAPP</h2>
                        <div class="mt-5 text-center">
                            <a class="btn btn-primary waves-effect waves-light"
                                href="https://wedoapp.ma">wedoapp.ma</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-6">
                    <div>
                        <img src="{{ asset('assets/images/error-img.png') }}" alt="" class="img-fluid">
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>
