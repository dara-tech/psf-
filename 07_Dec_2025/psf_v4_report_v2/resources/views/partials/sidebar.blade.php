@php $locale = (session()->get('locale') == "")?"kh":session()->get('locale'); App::SetLocale($locale); @endphp
@inject('request', 'Illuminate\Http\Request')
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">

             <li class="treeview">
                <a href="#">
                        <i class="fa fa-file-text-o"></i>
                        <span class="title">@lang('admin.menu_reporting')</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                </a>
                <ul class="treeview-menu">

                    <li class="treeview">
                        <a href="#">
                                <i class="fa fa-file-text-o"></i>
                                <span class="title">@lang('admin.menu_patient')</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ $request->segment(1) == 'home' ? 'active' : '' }}">
                                <a href="{{ url('/home') }}">
                                    <i class="fa fa-tachometer"></i>
                                    <span class="title">@lang('global.app_dashboard')</span>
                                </a>
                            </li>
                            @if(auth()->user()->can('users_manage') || auth()->user()->can('view_only'))
                            <li class="{{ $request->segment(1) == 'psf' ? 'active' : '' }}">
                                <a href="{{ url('/reporting') }}">
                                    <i class="fa fa-table"></i>
                                    <span class="title">@lang('admin.menu_export')</span>
                                </a>
                            </li>
                            @endif
                        </ul>                       
                    </li>

                    <li class="treeview">
                        <a href="#">
                                <i class="fa fa-file-text-o"></i>
                                <span class="title">@lang('admin.menu_hfs')</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ $request->segment(1) == 'home' ? 'active' : '' }}">
                                <a href="{{ url('/hfs_dashboard') }}">
                                    <i class="fa fa-tachometer"></i>
                                    <span class="title">@lang('global.app_dashboard')</span>
                                </a>
                            </li>
                            @if(auth()->user()->can('users_manage') || auth()->user()->can('view_only'))
                            <li class="{{ $request->segment(1) == 'psf' ? 'active' : '' }}">
                                <a href="{{ url('/hfs') }}">
                                    <i class="fa fa-table"></i>
                                    <span class="title">@lang('admin.menu_export')</span>
                                </a>
                            </li>
                            @endif
                        </ul>

                        
                    </li>

                    @can('users_manage')
                     <li class="{{ $request->segment(1) == 'psf' ? 'active' : '' }}">
                        <a href="{{ url('/admin_dashboard') }}">
                            <i class="fa fa-table"></i>
                            <span class="title">Admin Dashboard</span>
                        </a>
                    </li>
                    @endcan
                </ul>
                
            </li>

            
            @can('users_manage')
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span class="title">@lang('global.user-management.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li class="{{ $request->segment(2) == 'permissions' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.permissions.index') }}">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">
                                @lang('global.permissions.title')
                            </span>
                        </a>
                    </li>
                    <li class="{{ $request->segment(2) == 'roles' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">
                                @lang('global.roles.title')
                            </span>
                        </a>
                    </li>
                    <li class="{{ $request->segment(2) == 'sites' ? 'active active-sub' : '' }}">
                         <a href="{{ route('admin.sites.index') }}">
                            <i class="fa fa-hospital-o"></i>
                            <span class="title">
                            @lang('global.sites.title')
                            </span>
                        </a>
                    </li>
                    <li class="{{ $request->segment(2) == 'users' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fa fa-user"></i>
                            <span class="title">
                                @lang('global.users.title')
                            </span>
                        </a>
                    </li>
                    
                </ul>
            </li>
            @endcan

            <li class="{{ $request->segment(1) == 'change_password' ? 'active' : '' }}">
                <a href="{{ route('auth.change_password') }}">
                    <i class="fa fa-key"></i>
                    <span class="title">  @lang('global.app_change_pwd')</span>
                </a>
            </li>

            <li>
                <a href="#logout" onclick="$('#logout').submit();">
                    <i class="fa fa-arrow-left"></i>
                    <span class="title">@lang('global.app_logout')</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">@lang('global.logout')</button>
{!! Form::close() !!}
