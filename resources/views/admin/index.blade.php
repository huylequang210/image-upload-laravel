@extends('layouts.app')

@section('section')
    <div class="container">
      <div class="pandel-heading">
        <p>Admin Dasboard</p>
      </div>
      This is Admin Dashboard. You must be privileged to be here!
      <div class="user">
        @foreach ($users as $user)
            <div class="user-info flex flex-col mb-4 mt-2">
              <div>
                <div>
                  <a href="/admin/user/{{$user->id}}" class="font-semibold">{{$user->name}}</a>
                </div>
                <div>
                  <span>{{$user->storage->usage_original}}</span>
                  <span>{{$user->storage->usage_thumbnail}}</span>
                </div>
              </div>
              <div>
                @if (session('action') && session('id') === $user->id)
                  @php
                      $action = session('action')
                  @endphp
                  {{$user->userAction()->first()->$action === 0 ? 
                    ($user->name . ' is not able to ' . $action) : ($user->name . ' now can ' . $action) }}
                @endif
              </div>
              <div class="flex flex-row justify-between sm:justify-start">
                <form action="/admin/{{$user->id}}/upload" method="POST">
                  @method('PATCH')
                  @csrf
                  <button class="upload {{$user->userAction()->first()->upload === 0 ? 'userActionNotAllow line-through' : ''}} flex cursor-pointer justify-center bg-indigo-400 hover:bg-indigo-500 text-white sm:mr-4 rounded-md mt-1 w-70px sm:w-90px">Upload</button>
                </form>
                <form action="/admin/{{$user->id}}/comment" method="POST">
                  @method('PATCH')
                  @csrf
                  <button class="comment {{$user->userAction()->first()->comment === 0 ? 'userActionNotAllow line-through' : ''}} flex cursor-pointer justify-center bg-indigo-400 hover:bg-indigo-500 text-white sm:mr-4 rounded-md mt-1 w-70px sm:w-90px">Comment</button>
                </form>
                <form action="/admin/{{$user->id}}/vote" method="POST">
                  @method('PATCH')
                  @csrf
                  <button class="vote {{$user->userAction()->first()->vote === 0 ? 'userActionNotAllow line-through' : ''}} flex cursor-pointer justify-center bg-indigo-400 hover:bg-indigo-500 text-white sm:mr-4 rounded-md mt-1 w-70px sm:w-90px">Vote</button>
                </form>
                <form action="/admin/all/{{$user->id}}/action" method="POST">
                  @method('PATCH')
                  @csrf
                  <button class="all flex cursor-pointer justify-center bg-indigo-400 hover:bg-indigo-500 text-white sm:mr-4 rounded-md mt-1 w-70px sm:w-90px">All</button>
                </form>
              </div>
            </div>
        @endforeach
      </div>
    </div>
@endsection
@section('jsFile')
    <script src={{asset('/js/admin.js')}}></script>
@endSection