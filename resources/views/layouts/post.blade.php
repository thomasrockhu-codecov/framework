{{-- The Post Page Layout --}}
@extends('hyde::layouts.app')
@section('content')

@push('meta')
<!-- Blog Post Meta Tags -->
@foreach ($post->getMetadata() as $name => $content)
    <meta name="{{ $name }}" content="{{ $content }}">
@endforeach
@foreach ($post->getMetaProperties() as $name => $content)
    <meta property="{{ $name }}" content="{{ $content }}">
@endforeach
@endpush

<main id="content" class="mx-auto max-w-7xl py-16 px-8">
	@include('hyde::components.post.article')
</main>

@endsection