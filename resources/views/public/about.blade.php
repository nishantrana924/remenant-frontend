@extends('public.layouts.app')

@php
    seo()->set([
        'title' => $data['seo']['title'] ?? 'About Us | Remenant Health - Our Mission & Vision',
        'description' => $data['seo']['description'] ?? 'Learn about Remenant Health, India\'s leading effervescent wellness brand. Discover our journey, our clean-label commitment, and our mission to simplify healthcare for everyone.',
    ]);

    seo()->addSchema('AboutPage', [
        'name' => 'About Remenant Health',
        'description' => 'The story and mission of Remenant Health wellness brand.',
        'url' => request()->url(),
    ]);
@endphp

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