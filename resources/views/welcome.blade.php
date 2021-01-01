@extends('layouts.app')
@section('title', 'Welcome')
@section('dropzone')
    <x-dropzone param="welcome">
        
    </x-dropzone>
@endsection
@section('section')
<input type="hidden" class="user-id" value={{Auth::id()}}>
<div class="imageContainer flex flex-wrap justify-center sm:justify-start">
    @foreach ($images as $image)
        <div class="imageDiv mr-1 mb-1 sm:w-210px sm:h-210px flex flex-col items-center relative">
            <x-image :image="$image">

            </x-image>
        </div>
    @endforeach
</div>
@endsection
@section('jsFile')
    <script src={{asset('/js/welcome.js')}}></script>
@endSection
