<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <style>
        *[v-cloak] {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('vendor/admin/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css', 'vendor/admin') }}">

    <title>{{ config('app.name') }}</title>
</head>
<body class="font-body">
<div id="app" v-cloak>
    <div class="layout flex h-screen relative">
        <div class="close-sidebar absolute w-screen h-full hidden lg:block left-0 top-0"
             :class="{active: mobileSidebar}" @click.prevent="mobileSidebar = false"></div>
        <div class="sidebar bg-purple-700 h-full flex flex-col relative" :class="{active: mobileSidebar}">
            @include('admin::logo')
            <ul class="sidebar__menu flex-grow overflow-y-scroll">
                @include('admin::nav')
            </ul>
            <div class="flex hidden md:flex w-full">
                <div class="w-full account bg-gray-100 p-1 flex border border-gray-300 border-t-0 border-b-0">
                    <img class="w-8 h-8 rounded-full mr-3" src="{{ asset('vendor/admin/images/avatar.jpg') }}">
                    <div class="account-data flex flex-col">
                        <span class="text-sm text-gray-600 leading-none mb-1">{{auth()->user()->name}}</span>
                        <span class="text-xs text-gray-600 leading-none">
                              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin.logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-grow flex flex-col h-full w-full relative">
            <button class="sidebar-button hidden lg:flex" @click.prevent="mobileSidebar = !mobileSidebar">
                <i class="dripicons-menu text-3xl"></i>
            </button>
            <div class="header bg-white shadow-md flex justify-between
            items-center px-8 lg:pl-20 sm:pr-4
             sm:pl-16 sm:flex-row sm:items-center sm:justify-end es:pr-0">
                <div></div>
{{--                TODO: Global search--}}
{{--                <div class="search flex relative md:my-2 md:w-full">--}}
{{--                    <input type="text" class="outline-none pl-10 px-3 bg-gray-200 rounded-sm rounded-r-none md:w-full"--}}
{{--                           placeholder="Search">--}}
{{--                    <i class="dripicons-search"></i>--}}
                    {{--<button class="bg-purple-700 text-white px-4 text-sm rounded-sm rounded-l-none shadow-md">Search--}}
                    {{--</button>--}}
{{--                </div>--}}
                <div class="header-right flex items-stretch">
                    @include('admin::header')
                    <button class="flex items-center text-sm text-gray-600 mr-3 md:hidden">
                        <img src="{{ asset('vendor/admin/images/lang-us.jpg') }}" class="w-4 mr-3">
                        <span class="leading-none mr-1">English</span>
                        <i class="dripicons-chevron-down text-xs"></i>
                    </button>
                    <button class="px-2 mx-2 flex items-center">
                        <i class="dripicons-bell inline-flex text-2xl text-gray-600"></i>
                    </button>
                    <div
                        class="account bg-gray-100 p-5 flex border border-gray-300 border-t-0 border-b-0 ml-2 md:p-2 md:hidden">
                        <img class="w-8 h-8 rounded-full mr-3" src="{{ asset('vendor/admin/images/avatar.jpg') }}">
                        <div class="account-data flex flex-col">
                            <span class="text-sm text-gray-600 leading-none mb-1">{{auth()->user()->name}}</span>
                            <span class="text-xs text-gray-600 leading-none">
                                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('admin.logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                          style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content bg-gray-200 flex-grow overflow-y-scroll p-10 lg:p-3">
                <router-view></router-view>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('js/app.js', 'vendor/admin') }}"></script>
</body>
</html>
