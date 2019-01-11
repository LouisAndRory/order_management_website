<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        <!-- Fonts -->
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    </head>
    <body class="bgc-grey-100">
        <div class="container-fluid">
            <div class="row">
                <div class="logo col-12 text-center py-1 d-lg-none">
                    <a href="{{route('order.index')}}">
                        <img class="logo__img" src="{{ asset('/images/logo.png') }}" alt="">
                    </a>
                </div>
                <div class="navigation col-12 px-0">
                    <nav class="navigation__nav d-flex justify-content-end">
                        <li class="navigation__nav__item d-none d-lg-flex mr-auto logo ml-3">
                            <a class="mt-auto mb-auto" href="{{route('order.index')}}">
                                <img class="logo__img w-100 h-auto" src="{{ asset('/images/logo_lg.png') }}" alt="">
                            </a>
                        </li>
                        <li id="dateStr" class="d-none d-lg-flex m-auto date-str font-size-25"><li>
                        <li class="navigation__nav__item {{ request()->is('order/create')? 'navigation__nav__item--active':''}}"
                        >
                            <a href="{{ route('order.create')}}">
                                <i class="navigation__nav__item__icon ti-clipboard"></i>
                                <div class="navigation__nav__item__text">新增訂單</div>
                            </a>
                        </li>
                        <li class="navigation__nav__item {{ request()->is('order/search')? 'navigation__nav__item--active':''}}"
                        >
                            <a href="{{ route('order.search')}}">
                                <i class="navigation__nav__item__icon ti-search"></i>
                                <div class="navigation__nav__item__text">訂單查詢</div>
                            </a>
                        </li>
                        <li class="navigation__nav__item {{ request()->is('package/search')? 'navigation__nav__item--active':''}}"
                        >
                            <a href="{{ route('package.search')}}">
                                <i class="navigation__nav__item__icon ti-truck"></i>
                                <div class="navigation__nav__item__text">包裹查詢</div>
                            </a>
                        </li>
                        <li class="navigation__nav__item {{ request()->is('items')? 'navigation__nav__item--active':''}}"
                        >
                            <a href="">
                                <i class="navigation__nav__item__icon ti-panel"></i>
                                <div class="navigation__nav__item__text">選項管理</div>
                            </a>
                        </li>
                        <li class="navigation__nav__item">
                            <a href="{{ route('logout')}}">
                                <i class="navigation__nav__item__icon ti-power-off"></i>
                                <div class="navigation__nav__item__text">登出系統</div>
                            </a>
                        </li>
                    </nav>
                </div>
                <div class="main-content col-12 mt-4">
                    @yield('content')
                </div>
            </div>

        </div>

        <script>
            window.notificationLang = @json(__('notification'));
            const today = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('dateStr').innerText = today.toLocaleDateString('zh-TW', options);
            @yield('custom-js')
        </script>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
