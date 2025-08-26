<?php
    $route = \Route::currentRouteName();
    $assetsPath = asset('assets/admin');
?>
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true" style="overflow-y:auto">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="{{ route('admin.home') }}">
                    <h2 class="brand-text">{{ config('app.name') }}</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="{{ route('admin.home') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">{{ __('admin.statistics') }}</span>
                </a>
            </li>

            <li class=" nav-item {{ request()->routeIs('admin.clients*')|| request()->routeIs('admin.new_clients*')||request()->routeIs('admin.current_clients*') ? 'open active' : '' }}">
                <a class="d-flex align-items-center {{ request()->routeIs('admin.clients*')|| request()->routeIs('admin.new_clients*')||request()->routeIs('admin.current_clients*') ? 'active' : '' }}" href="#">
                    <i data-feather="user"></i>
                    <span class="menu-title text-truncate" data-i18n="">{{ __('admin.clients_list') }}</span>
                </a>
                <ul class="menu-content">
                    @can('new_clients.view')
                    <li class=" nav-item  {{ request()->routeIs('admin.new_clients.index')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.new_clients.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.new_clients') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('current_clients.view')
                    <li class=" nav-item  {{ request()->routeIs('admin.current_clients.index')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.current_clients.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.current_clients') }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>

            <li class=" nav-item {{ request()->routeIs('admin.suppliers*')|| request()->routeIs('admin.new_suppliers*')||request()->routeIs('admin.current_suppliers*') ? 'open active' : '' }} ">
                <a class="d-flex align-items-center {{ request()->routeIs('admin.suppliers*')|| request()->routeIs('admin.new_suppliers*')||request()->routeIs('admin.current_suppliers*') ? 'active' : '' }}" href="#">
                    <i data-feather="user"></i>
                    <span class="menu-title text-truncate" data-i18n="">{{ __('admin.suppliers_list') }}</span>
                </a>
                <ul class="menu-content">
                    @can('suppliers.new')
                    <li class=" nav-item {{ request()->routeIs('admin.suppliers.new')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.suppliers.new') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.new_suppliers') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('suppliers.current')
                    <li class=" nav-item  {{ request()->routeIs('admin.suppliers.current')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.suppliers.current') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.current_suppliers') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('suppliers.requests')
                    <li class=" nav-item  {{ request()->routeIs('admin.suppliers.requests')?'active':''}}">
                        <a class="d-flex align-items-center" title="{{ __('admin.suppliers_requests') }}" href="{{ route('admin.suppliers.requests') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.suppliers_requests') }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            <li class=" nav-item {{ request()->routeIs('admin.orders*')||request()->routeIs('admin.canceled_orders*')|| request()->routeIs('admin.new_orders*')||request()->routeIs('admin.current_orders*') ? 'open active' : '' }} ">

                <a class="d-flex align-items-center {{ request()->routeIs('admin.orders*')||request()->routeIs('admin.canceled_orders*')|| request()->routeIs('admin.new_orders*')||request()->routeIs('admin.current_orders*') ? 'active' : '' }}" href="#">
                    <i data-feather="user"></i>
                    <span class="menu-title text-truncate" data-i18n="">{{ __('admin.orders_list') }}</span>
                </a>
                <ul class="menu-content">
                    @can('new_orders.view')
                    <li class=" nav-item {{ request()->routeIs('admin.new_orders.index')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.new_orders.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.new_orders') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('current_orders.view')
                    <li class=" nav-item  {{ request()->routeIs('admin.current_orders.index')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.current_orders.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.current_orders') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('canceled_orders.view')

                    <li class=" nav-item  {{ request()->routeIs('admin.canceled_orders.index')?'active':''}}">
                        <a class="d-flex align-items-center" title="{{ __('admin.orders_requests') }}" href="{{ route('admin.canceled_orders.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.canceled_orders') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('compleated_orders.view')

                    <li class=" nav-item  {{ request()->routeIs('admin.compleated_orders.index')?'active':''}}">
                        <a class="d-flex align-items-center" title="{{ __('admin.orders_requests') }}" href="{{ route('admin.compleated_orders.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.compleated_orders') }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            <li class=" nav-item {{ request()->routeIs('admin.accounts*') ? 'active' : '' }} ">
                <a class="d-flex align-items-center {{ request()->routeIs('admin.accounts*') ? 'active' : '' }}" href="#">
                    <i data-feather="user"></i>
                    <span class="menu-title text-truncate" data-i18n="">{{ __('admin.accountants') }}</span>
                </a>
                <ul class="menu-content">
                    @can('accounts.payments')
                    <li class=" nav-item {{ request()->routeIs('admin.accounts.payments')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.accounts.payments') }} ">
                            <i data-feather="calendar"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.order_accounts') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('accounts.suppliers')
                    <li class=" nav-item {{ request()->routeIs('admin.accounts.suppliers')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.accounts.suppliers') }} ">
                            <i data-feather="calendar"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.suppliers_accounts') }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>


            @can('adds.view')
            <li>
                <a class='d-flex align-items-center' href='{{ route('admin.adds.index') }} '>
                    <i data-feather='key'></i>
                    <span class='menu-item text-truncate' data-i18n='List'>{{ __('admin.adds') }}</span>
                </a>
            </li>
            @endcan

            @can('trips.view')
            <li>
                <a class='d-flex align-items-center' href='{{ route('admin.trips.index') }} '>
                    <i data-feather='smile'></i>
                    <span class='menu-item text-truncate' data-i18n='List'>{{ __('admin.trips') }}</span>
                </a>
            </li>
            @endcan
            @can('gifts.view')
            <li>
                <a class='d-flex align-items-center' href='{{ route('admin.gifts.index') }} '>
                    <i data-feather='gift'></i>

                    <span class='menu-item text-truncate' data-i18n='List'>{{ __('admin.gifts') }}</span>
                </a>
            </li>
            @endcan
            @can('effectivenes.view')
            <li>
                <a class='d-flex align-items-center' href='{{ route('admin.effectivenes.index') }} '>
                    <i data-feather='circle'></i>
                    <span class='menu-item text-truncate' data-i18n='List'>{{ __('admin.effectivenes') }}</span>
                </a>
            </li>
            @endcan
            @can('offers.view')
            <li>
                <a class='d-flex align-items-center' href='{{ route('admin.offers.index') }} '>
                    <i data-feather='key'></i>
                    <span class='menu-item text-truncate' data-i18n='List'>{{ __('admin.offers') }}</span>
                </a>
            </li>
            @endcan
            @can('contacts.view')
            <li>
                <a class="d-flex align-items-center" href="{{route('admin.contacts.index')}}">
                    <i data-feather="phone"></i>
                    <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.suppports_contact_us') }}</span>
                </a>
            </li>
            @endcan
            @can('articles.view')

            <li>
                <a class="d-flex align-items-center" href="{{ route('admin.articles.index') }}">
            <i data-feather="image"></i>
            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.articles_news') }}</span>
            </a>
            </li>
            @endcan
            @can('blogs.view')
            <li>
                <a class='d-flex align-items-center' href='{{ route('admin.blogs.index') }} '>
                    <i data-feather='key'></i>
                    <span class='menu-item text-truncate' data-i18n='List'>{{ __('admin.blogs') }}</span>
                </a>
            </li>
            @endcan
            {{-- <li>
                <a class="d-flex align-items-center" href="{{ route('admin.jobs.index') }}">
                    <i data-feather="image"></i>
                    <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.jobs') }}</span>
                </a>
            </li> --}}
            <li>
                <a class="d-flex align-items-center" href="{{ route('admin.faqs.index') }}">
                    <i data-feather="image"></i>
                    <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.faqs') }}</span>
                </a>
            </li>
            <li class=" nav-item {{ request()->routeIs('admin.users*')|| request()->routeIs('admin.roles*') ? 'open active' : '' }} ">
                <a class="d-flex align-items-center {{ request()->routeIs('admin.users*')|| request()->routeIs('admin.roles*') ? 'active' : '' }}" href="#">
                    <i data-feather="user"></i>
                    <span class="menu-title text-truncate" data-i18n="">{{ __('admin.users_roles') }}</span>
                </a>
                <ul class="menu-content">
                    @can('users.view')
                    <li class=" nav-item {{ request()->routeIs('admin.users.index')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.users.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.users') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('roles.view')
                    <li class=" nav-item  {{ request()->routeIs('admin.roles.index')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.roles.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.roles') }}</span>
                        </a>
                    </li>

                    @endcan
                </ul>
            </li>

            <li class=" nav-item {{ request()->routeIs('admin.settings*')||request()->routeIs('admin.onboardings*') ||request()->routeIs('admin.features*')||request()->routeIs('admin.requirements*') ? 'open active' : '' }} ">
                <a class="d-flex align-items-center {{ request()->routeIs('admin.settings*')||request()->routeIs('admin.onboardings*') ||request()->routeIs('admin.features*')||request()->routeIs('admin.requirements*') ? 'active' : '' }}" href="#">
                    <i data-feather="settings"></i>
                    <span class="menu-title text-truncate" data-i18n="">{{ __('admin.site_Settings') }}</span>
                </a>
                <ul class="menu-content">
                    <li class=" nav-item {{ request()->routeIs('admin.settings.header')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.settings.header') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.header_settings') }}</span>
                        </a>
                    </li>
                    <li class=" nav-item {{ request()->routeIs('admin.settings.footer')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.settings.footer') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.footer_settings') }}</span>
                        </a>
                    </li>
                    <li class=" nav-item {{ request()->routeIs('admin.settings.home')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.settings.home') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.home_settings') }}</span>
                        </a>
                    </li>
                    @can('onboardings.view')
                    <li class=" nav-item {{ request()->routeIs('admin.onboardings*')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.onboardings.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.onboardings') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('features.view')
                    <li class=" nav-item {{ request()->routeIs('admin.features*')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.features.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.features') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('requirements.view')
                    <li class=" nav-item {{ request()->routeIs('admin.requirements*')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.requirements.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.requirements') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('sliders.view')

                    <li class=" nav-item {{ request()->routeIs('admin.sliders*')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.sliders.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.slider_settings') }}</span>
                        </a>
                    </li>
                    @endcan

                    @can('why_bookings.view')
                    <li>
                        <a class='d-flex align-items-center' href='{{ route('admin.why_bookings.index') }} '>
                            <i data-feather='circle'></i>
                            <span class='menu-item text-truncate' data-i18n='List'>{{ __('admin.why_atour_booking_settings') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('currencies.view')
                    <li>
                        <a class='d-flex align-items-center' href='{{ route('admin.currencies.index') }} '>
                            <i data-feather='key'></i>
                            <span class='menu-item text-truncate' data-i18n='List'>{{ __('admin.currencies') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('settings.cancel_terms')

                    <li class=" nav-item {{ request()->routeIs('admin.settings.cancel_terms')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.settings.cancel_terms') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.cancel_terms') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('settings.helping')

                    <li class=" nav-item {{ request()->routeIs('admin.settings.helping')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.settings.helping') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.helping') }}</span>
                        </a>
                    </li>
                    @endcan

                    @can('countries.view')

                    <li class=" nav-item {{ request()->routeIs('admin.countries.index')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.countries.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.countries') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('cities.view')

                    <li class=" nav-item {{ request()->routeIs('admin.cities.index')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.cities.index') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.cities') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('departments.view')
                    <li>
                        <a class='d-flex align-items-center' href='{{ route('admin.departments.index') }} '>
                            <i data-feather='key'></i>
                            <span class='menu-item text-truncate' data-i18n='List'>{{ __('admin.departments') }}</span>
                        </a>
                    </li>
                    @endcan

                    @can('sub_categories.view')
                    <li>
                        <a class='d-flex align-items-center' href='{{ route('admin.sub_categories.index') }} '>
                            <i data-feather='key'></i>
                            <span class='menu-item text-truncate' data-i18n='List'>{{ __('admin.sub_categories') }}</span>
                        </a>
                    </li>
                    @endcan
                    <li class=" nav-item {{ request()->routeIs('admin.settings.about')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.settings.about') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.about_settings') }}</span>
                        </a>
                    </li>
                    <li class=" nav-item {{ request()->routeIs('admin.settings.term_condition')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.settings.term_condition') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.term_condition') }}</span>
                        </a>
                    </li>
                    <li class=" nav-item {{ request()->routeIs('admin.settings.terms')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.settings.terms') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.terms') }}</span>
                        </a>
                    </li>
                    <li class=" nav-item {{ request()->routeIs('admin.settings.experience')?'active':''}}">
                        <a class="d-flex align-items-center" href="{{ route('admin.settings.experience') }} ">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="List">{{ __('admin.experience_settings') }}</span>
                        </a>
                    </li>

                </ul>
            </li>
            <li class=" nav-item {{ request()->routeIs('admin.security.security') ? 'open active' : '' }} ">

                <a class="d-flex align-items-center {{ request()->routeIs('admin.security*') ? 'active' : '' }}" href="#">
                    <i data-feather="settings"></i>
                    <span class="menu-title text-truncate" data-i18n="">{{ __('security.security') }}</span>
                </a>
                <ul class="menu-content">
            @can('security.dashboard')
            <li class=" nav-item {{ request()->routeIs('admin.security.*')?'active':''}}">
                <a class="d-flex align-items-center" href="{{ route('admin.security.dashboard') }} ">
                    <i data-feather="circle"></i>
                    <span class="menu-item text-truncate" data-i18n="List">{{ __('security.security') }}</span>
                </a>
            </li>
            @endcan
            @can('security.audit-trail')
            <li class=" nav-item {{ request()->routeIs('admin.security.audit-trail')?'active':''}}">
                <a class="d-flex align-items-center" href="{{ route('admin.security.audit-trail') }} ">
                    <i data-feather="circle"></i>
                    <span class="menu-item text-truncate" data-i18n="List">{{ __('security.audit_trail') }}</span>
                </a>
            </li>
            @endcan
            @can('security.login-attempts')
            <li class=" nav-item {{ request()->routeIs('admin.security.login-attempts')?'active':''}}">
                <a class="d-flex align-items-center" href="{{ route('admin.security.login-attempts') }} ">
                    <i data-feather="circle"></i>
                    <span class="menu-item text-truncate" data-i18n="List">{{ __('security.login_attempts') }}</span>
                </a>
            </li>
            @endcan
            @can('security.2fa.settings')
            <li class=" nav-item {{ request()->routeIs('admin.security.2fa.settings')?'active':''}}">
                <a class="d-flex align-items-center" href="{{ route('admin.security.2fa.settings') }} ">
                    <i data-feather="circle"></i>
                    <span class="menu-item text-truncate" data-i18n="List">{{ __('security.2fa_settings') }}</span>
                </a>
            </li>
            @endcan
            </ul>
            </li>
            {{--addnewrouteheredontdeletemeplease--}}
        </ul>
    </div>
</div>
