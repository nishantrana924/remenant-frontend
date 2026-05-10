@extends('public.layouts.app')

@section('title', $data['seo']['title'] ?? 'About Us - Remenant Health')
@if(isset($data['seo']['description']))
    @section('seo')
        <meta name="description" content="{{ $data['seo']['description'] }}">
    @endsection
@endif

@section('content')

    {{-- Hero Section --}}
    @include('public.about.sections.hero', ['hero' => $data['hero']])

    {{-- Features Section --}}
    @include('public.about.sections.features', ['features' => $data['features']])

    {{-- Vision & Mission Section --}}
    @include('public.about.sections.vision_mission', [
        'vision' => $data['vision'],
        'mission' => $data['mission']
    ])

    {{-- Process Section --}}
    @include('public.about.sections.process', ['process' => $data['process']])

    {{-- Founders Section --}}
    @include('public.about.sections.founders', ['founders' => $data['founders']])

    {{-- Certifications Section --}}
    @include('public.about.sections.certifications', ['certifications' => $data['certifications']])

    {{-- CTA Section --}}
    @include('public.about.sections.cta', ['cta' => $data['cta']])

@endsection