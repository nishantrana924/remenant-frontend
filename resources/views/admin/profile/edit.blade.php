@extends('admin.layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Profile Settings') }}
    </h2>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Profile Header Card -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg overflow-hidden">
            <div class="p-8">
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center text-3xl font-bold text-blue-600 shadow-lg">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="flex-1 text-white">
                        <h1 class="text-3xl font-bold mb-2">{{ Auth::user()->name }}</h1>
                        <p class="text-blue-100 mb-1">{{ Auth::user()->email }}</p>
                        <p class="text-blue-100">{{ Auth::user()->phone }}</p>
                        <span class="inline-block mt-3 px-4 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                            Admin
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6">
                @include('admin.profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6">
                @include('admin.profile.partials.update-password-form')
            </div>
        </div>
    </div>
@endsection
