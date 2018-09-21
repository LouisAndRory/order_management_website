<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    </head>
    <body class="app">
        <div>
            <!-- #Left Sidebar ==================== -->
            <div class="sidebar">
                <div class="sidebar-inner">
                    <!-- ### $Sidebar Header ### -->
                    <div class="sidebar-logo">
                        <div class="peers ai-c fxw-nw">
                            <div class="peer peer-greed">
                                <a class="sidebar-link td-n" href="index.html">
                                <div class="peers ai-c fxw-nw">
                                    <div class="peer">
                                        <div class="logo text-center py-2">
                                            <img src="{{ asset('/images/logo.png') }}" alt="">
                                        </div>
                                    </div>
                                    <div class="peer peer-greed">
                                        <h5 class="lh-1 mB-0 logo-text">{{ config('app.name') }}</h5>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="peer">
                                <div class="mobile-toggle sidebar-toggle">
                                <a href="" class="td-n">
                                    <i class="ti-arrow-circle-left"></i>
                                </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ### $Sidebar Menu ### -->
                    <ul class="sidebar-menu scrollable pos-r">
                        <li class="nav-item mT-30 active">
                            <a class="sidebar-link active" href="{{ route('order.create')}}">
                                <span class="icon-holder">
                                <i class="c-blue-500 ti-clipboard"></i>
                                </span>
                                <span class="title">{{ __('navigation.order.create')}}</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown open">
                            <a class="dropdown-toggle" href="javascript:void(0);">
                                <span class="icon-holder">
                                    <i class="c-brown-500 ti-search"></i>
                                </span>
                                <span class="title">{{ __('navigation.order.title')}}</span>
                                <span class="arrow">
                                    <i class="ti-angle-right"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                <a href="{{ route('package.search')}}">{{ __('navigation.order.search')}}</a>
                                </li>
                                <li>
                                <a href="{{ route('package.search')}}">{{ __('navigation.package.search')}}</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown open">
                            <a class="dropdown-toggle " href="javascript:void(0);">
                                <span class="icon-holder">
                                <i class="c-orange-500 ti-panel"></i>
                                </span>
                                <span class="title">{{ __('navigation.options.title')}}</span>
                                <span class="arrow">
                                <i class="ti-angle-right"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class='sidebar-link' href="{{ route('cookie.index')}}">{{ __('navigation.options.cookie')}}</a>
                                </li>
                                <li>
                                    <a class='sidebar-link' href="{{ route('pack.index')}}">{{ __('navigation.options.pack')}}</a>
                                </li>
                                <li>
                                    <a class='sidebar-link' href="{{ route('caseType.index')}}">{{ __('navigation.options.case')}}</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- #Main ============================ -->
            <div class="page-container">
                <!-- ### $Topbar ### -->
                <div class="header navbar">
                    <div class="header-container">
                        <ul class="nav-left">
                            <li>
                                <a id='sidebar-toggle' class="sidebar-toggle" href="javascript:void(0);">
                                    <i class="ti-menu"></i>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav-right">
                            <li>
                                <a href="javascript:void(0);">
                                    {{ __('navigation.logout')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- ### $App Screen Content ### -->
                <main class='main-content bgc-grey-100'>
                    <div id='mainContent'>
                        <div class="row gap-20 masonry pos-r">
                            @yield('content')
                        </div>
                    </div>
                </main>

                <!-- ### $App Screen Footer ### -->
                <footer class="bdT ta-c p-20 lh-0 fsz-sm c-grey-600">
                    <span>Copyright © 2018 Designed by {{ config('app.name') }}. All rights reserved.</span>
                </footer>
            </div>
        </div>

        <script>
            window.notificationLang = @json(__('notification'));
            @yield('custom-js')
        </script>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
