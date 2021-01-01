@extends('layouts.app')
@section('title', 'Home')
@section('dropzone')
    <x-dropzone param="home">
        
    </x-dropzone>
@endsection

@section('section')
<input type="hidden" class="user-id" value={{Auth::id()}}>
<div class="imageContainer flex flex-wrap justify-center sm:justify-start">
    @foreach ($images as $image)
        <div class="imageDiv mr-1 mb-4 sm:mb-1 h-350px sm:h-300px sm:w-210px flex flex-col items-center relative">
            <x-image :image="$image">

            </x-image>
            <x-image-action :image="$image">

            </x-image-action>
        </div>
        <div class="deleted-placeholder justify-center items-center mr-1 mb-4 sm:mb-1 h-350px sm:h-300px sm:w-210px hide">
            <span class="font-bold">Move image to trash</span>
        </div>
    @endforeach
</div>
@endsection
@section('jsFile')
    <script src={{asset('/js/home.js')}}></script>
@endSection