@extends('layouts.app')

@section('title')
  {{$user->name}}
@endsection

@section('section')
<div class="userName mb-4">
  <span>{{$user->name}}'s images:</span>
</div>
<div class="imageContainer flex flex-wrap justify-center sm:justify-start">
  @foreach ($images as $image)
    <div class="imageDiv mr-1 mb-1 sm:w-210px sm:h-210px flex flex-col items-center relative">
      <x-image :image="$image">

      </x-image>
    </div>
  @endforeach
</div>
@endsection