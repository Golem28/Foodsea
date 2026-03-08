@extends('layouts/app')

@section('title')
    Recipes
@endsection

@section('content')
    <div class="container">
        @component('components/recipelist', ['recipes' => $recipes])
        @endcomponent
    </div>
@endsection