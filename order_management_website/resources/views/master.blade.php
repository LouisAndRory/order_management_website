<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    </head>
    <body>
            <aside id="left-panel" class="left-panel">
                <nav class="navbar navbar-expand-sm navbar-default">
                    <div class="navbar-header">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa fa-bars"></i>
                        </button>

                        <a class="navbar-brand" href="./"><img src="images/logo.png" alt="Logo"></a>
                        <a class="navbar-brand hidden" href="./"><img src="images/logo2.png" alt="Logo"></a>
                    </div>
                    <div id="main-menu" class="main-menu collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="menu-item-has-children dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <i class="menu-icon fa fa-laptop"></i>{{ __('navigation.order.title')}}</a>
                                <ul class="sub-menu children dropdown-menu">
                                    <li><i class="fa fa-paste"></i><a href="ui-buttons.html">{{ __('navigation.order.create')}}</a></li>
                                    <li><i class="fa fa-search"></i><a href="ui-badges.html">{{ __('navigation.order.search')}}</a></li>
                                    <li><i class="fa fa-id-badge"></i><a href="ui-badges.html">{{ __('navigation.package.search')}}</a></li>
                                </ul>
                            </li>
                            <li class="menu-item-has-children dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <i class="menu-icon fa fa-list-ul"></i>{{ __('navigation.options.title')}}</a>
                                <ul class="sub-menu children dropdown-menu">
                                    <li><i class="fa fa-gears"></i><a href="ui-buttons.html">{{ __('navigation.options.cookie')}}</a></li>
                                    <li><i class="fa fa-gift"></i><a href="ui-badges.html">{{ __('navigation.options.case')}}</a></li>
                                    <li><i class="fa fa-inbox"></i><a href="ui-badges.html">{{ __('navigation.options.pack')}}</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </aside><!-- /#left-panel -->

            <!-- Right Panel -->

            <div id="right-panel" class="right-panel">

                <!-- Header-->
                <header id="header" class="header">
                    <div class="col">
                        <a id="menuToggle" class="menutoggle pull-left text-primary"><i class="fa fa-align-left"></i></a>
                    </div>
                    <div class="col-auto ml-auto float-right">
                        <a href="#">{{ __('navigation.logout')}}</a>
                    </div>

                </header><!-- /header -->
                <!-- Header-->

                <div class="content mt-3">
                    @yield('content')
                </div> <!-- .content -->
            </div><!-- /#right-panel -->
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
