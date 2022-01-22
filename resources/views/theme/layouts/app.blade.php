<!DOCTYPE html>

<html lang="en">
<head>

    <meta charset="utf-8" />
    <title>Dashboard | Tickets Management ERP</title>
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="app_creator" name="Elmarzougui Abdelghafour" />
    <meta content="app_version" name="v 1.1" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
     @yield('css')
    <!-- App Css-->
    <link href="{{mix('css/app.css')}}" rel="stylesheet" type="text/css" />

</head>

<body data-sidebar="dark">

   
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

    <div id="layout-wrapper">

        @include('theme.layouts._parts.__header')

        @include('theme.layouts._parts._leftSidebar')

        <div class="main-content">

            <div class="page-content">
                
                @yield('content')

            </div>
            <!-- End Page-content -->

            <!-- Transaction Modal -->
            @include('theme.layouts._parts._modal')
            <!-- end modal -->

            <!-- subscribeModal -->

            {{--@include('theme.layouts._parts._subscribe')--}}
            
            <!-- end modal -->

          @include('theme.layouts._parts._footer')
          
        </div>

        <!-- end main content-->

    </div>

    
    @include('theme.layouts._parts._rightSidebar')


    @include('theme.layouts._parts._overly')

    <script src="{{mix('js/app.js')}}"></script>

    @stack('scripts')


</body>

</html>
