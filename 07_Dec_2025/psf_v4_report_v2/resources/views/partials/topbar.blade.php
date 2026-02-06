@php $locale = (session()->get('locale') == "")?"kh":session()->get('locale'); App::SetLocale($locale); @endphp
<header class="main-header">
    <style>

.dropdown-menu {
min-width: 80px;
}
</style>
    <!-- Logo -->
    <a href="{{ url('/home') }}" class="logo"
       style="font-size: 16px;">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
           @lang('global.global_title')</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
           @lang('global.global_title')</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

       

        <ul class="navbar-nav" style="padding:15px; float: right; list-style:none">
            <!-- Authentication Links -->
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="color:white">
                    @switch($locale)
                        @case('us')
                        <img src="{{asset('img/us.png')}}"> English
                        @break
                        @case('kh')
                        <img src="{{asset('img/kh.png')}}"> Khmer
                        @break
                        @default
                        <img src="{{asset('img/us.png')}}"> English
                    @endswitch
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/lang/en"><img src="{{asset('img/us.png')}}"> English</a><br>
                    <a class="dropdown-item" href="/lang/kh"><img src="{{asset('img/kh.png')}}"> Khmer</a>                            
                </div>
            </li>
        </ul>
          

    </nav>
</header>


