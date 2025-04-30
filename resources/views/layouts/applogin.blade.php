<!doctype html>



<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">



<head>

    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />



    <title></title>



    <meta name="description" content="" />



    <!-- Favicon -->

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logobem.png') }}" />



    <!-- Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />



    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />



    <!-- Core CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />

    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />



    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <script src="{{ asset('assets/js/config.js') }}"></script>



    <style>

        body {

            padding: 5%;

        }



        @media (min-width: 768px) {

            body {

                padding-left: 20%;

                padding-right: 20%;

            }

        }



        @media (min-width: 992px) {

            body {

                padding-left: 30%;

                padding-right: 30%;

            }

        }



        @media (min-width: 1374px) {

            body {

                padding-left: 32%;

                padding-right: 32%;

            }

        }



        .app-brand {

            margin-bottom: 30px;

            display: flex;

            justify-content: center;

            align-items: center;

            /* Memastikan logo berada di tengah vertikal */

        }



        .app-brand-link {

            display: flex;

            align-items: center;

            /* Memastikan logo berada di tengah vertikal */

            text-decoration: none;

        }



        .app-brand-logo img {

            max-height: 150px;

            /* Membatasi tinggi maksimum logo */

            width: auto;

            /* Memastikan lebar logo mengikuti rasio aspek */

            object-fit: contain;

            /* Menghindari pemotongan logo */

        }

        @media (max-width: 1459px) {

            .app-brand-logo img {

                max-height: 120px;

                /* Mengurangi tinggi maksimum untuk layar kecil */

            }

        }

        @media (max-width: 1024px) {

            .app-brand-logo img {

                max-height: 70px;

                /* Mengurangi tinggi maksimum untuk layar kecil */

            }

        }

        @media (max-width: 576px) {

            .app-brand-logo img {

                max-height: 80px;

                /* Mengurangi tinggi maksimum untuk layar yang sangat kecil */

            }

        }

    </style>

</head>



<body>

    @yield('content')



    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>



</html>