@extends('layouts.app')
@section('title', 'Profile')
@section('section')
  <input type="hidden" class="user-id" value={{Auth::id()}}>
  <div class="container sm:mx-4">
    <div class="flex flex-col mb-8">
      <div class="mb-2">
        @if ($storage)
          <span>Usage: {{round($storage->usage_original / 1024 / 1000, 2)}} MB</span><span>/{{$storage->limit}} MB,</span>
        @else
          <span>Usage: 0 MB</span><span>/30.0 MB</span>
        @endif
        <span> total image(s): {{$totalImages}}</span>
      </div>
      <div class="flex flex-row w-full justify-between md:w-1/3 sm:w-1/2">
        <div class="flex flex-col">
          <span>Change password</span>
          <span class="text-xs opacity-75">Password must be at least 6 characters long</span>
        </div>
        <button class="changepassword w-16 text-blue-600 text-xs border-solid border-2 border-blue-600 rounded-2xl">Change</button>
      </div>
      <div class="panel-changepassword @error('oldpassword') @enderror @error('newpassword') showPanel @enderror">
        <form method="POST" action="{{ route('password.change') }}" class="w-full sm:w-1/4">
          @csrf
          @method('PATCH')
          <div>
            <div>
              <label for="oldpassword">{{ __('Old password') }}</label>
              <div>
                <input id="oldpassword" type="password"
                class="mb-2 mt-1 pt-1 pb-1 pl-2 bg-gray-100 bg-rgba w-full" name="oldpassword"
                required autocomplete="off">
                @error('oldpassword')
                  <span role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div>
              <label for="newpassword">{{__('New password')}}</label>
              <div>
                <input id="newpassword" type="password"
                class="mb-2 mt-1 pt-1 pb-1 pl-2 bg-gray-100 bg-rgba w-full" name="newpassword"
                required>
                @error('newpassword')
                  <span role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div>
              <label for="newpassword_confirm">{{__('Comfirm new password')}}</label>
              <div>
                <input id="newpassword_confirm" type="password"
                class="mb-2 mt-1 pt-1 pb-1 pl-2 bg-gray-100 bg-rgba w-full" name="newpassword_confirm"
                required>
              </div>
            </div>
          </div>
          <div>
            <button type="submit" class="w-full mb-4 block bg-indigo-400 pt-1 pb-1 bg-rgba text-center text-white">
              {{ __('Change Password') }}
            </button>
          </div>
        </form>
      </div>
    </div>
    <div class="flex flex-col"> 
      <div class="flex flex-row w-full justify-between md:w-1/3 sm:w-1/2 mb-2">
        <div class="flex flex-col">
          <span>Deleted images</span>
          <span class="text-xs opacity-75">{{count($images)}} images deleted</span>
        </div>
        <div class="deleteButtons flex flex-row">
          <button class="showDeletedimages w-16 text-blue-600 text-xs border-solid border-2 border-blue-600 rounded-2xl">Show</button>
          <form action="/profile/delete/all" class="ml-2 flex flex-row" method="POST" >
            @method('DELETE')
            @csrf
            <button class="deletedImages w-16 text-blue-600 text-xs border-solid border-2 border-blue-600 rounded-2xl">Delete all</button>
          </form>
        </div>
      </div>
      <?php $showPanel = session('open') ?>
    <div class="panel-deletedimage flex flex-wrap justify-center sm:justify-start {{session('open')}}">
        @foreach ($images as $image)
          <div class="imageDiv mr-1 mb-4 sm:mb-1 h-265px sm:h-240px sm:w-210px flex flex-col items-center relative">
              
              <x-image :image="$image">

              </x-image>
              <x-image-action-restore :image="$image">

              </x-image-action-restore>
          </div>
        @endforeach
      </div>
    </div> 
  </div>
@endsection
@section('jsFile')
  <script src="{{asset('js/profile.js')}}"></script>
@endsection