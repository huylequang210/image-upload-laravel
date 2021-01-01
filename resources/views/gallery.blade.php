@extends('layouts.app')
@section('title', 'Gallery')
@section('section')
  <input type="hidden" class="user-id" value={{Auth::id()}}>
  <div class="gallery flex flex-col w-11/12 mx-auto justify-between">
    <div class="gallery-container flex flex-col w-full sm:w-2/3">
      <div class="gallery-image cursor-zoom-in relative">
        <div class="post-image-container flex justify-center items-center">
          <img class="" src="/images/{{$img->original}}" alt="{{$img->title}}">
        </div>
        <div class="post-image-hero">
          <img class="w-auto" src="/images/{{$img->original}}" alt="{{$img->title}}">
        </div>
      </div>
      <div class="gallery-info text-white  bg-gray-800 py-2 px-4 flex">
        <div class="flex-2">
          <div class="post-title mb-1 text-lg">{{$img->title}}</div>
          <div class="post-info flex flex-row">
            <span class="text-xs">by &nbsp;</span>
            <span class="author text-xs font-bold mr-2 hover:text-pink-600"><a href="/user/{{$img->user->name}}">{{$img->user->name}}</a>&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span class="post-date text-xs font-bold">
              {{(new Carbon\Carbon($img->created_at))->shortRelativeDiffForHumans()}}
            </span>
          </div>
        </div>
        {{-- <div class="next-img flex-1 flex justify-end">
          <button class="bg-red-600 p-2 text-sm">Next image &rarr;</button>
        </div> --}}
      </div>
      <div class="text-white bg-gray-800 py-2 px-4">
        @auth
          <div class="gallery-upvote cursor-pointer mr-4 inline-block">  
            <svg class="w-8 inline-block pointer-events-none fill-current text-gray-500 @if($vote && $vote->score == 1)red @endif" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512.171 512.171" style="enable-background:new 0 0 512.171 512.171;" xml:space="preserve"><g><g><path d="M476.723,216.64L263.305,3.115C261.299,1.109,258.59,0,255.753,0c-2.837,0-5.547,1.131-7.552,3.136L35.422,216.64 c-3.051,3.051-3.947,7.637-2.304,11.627c1.664,3.989,5.547,6.571,9.856,6.571h117.333v266.667c0,5.888,4.779,10.667,10.667,10.667 h170.667c5.888,0,10.667-4.779,10.667-10.667V234.837h116.885c4.309,0,8.192-2.603,9.856-6.592 C480.713,224.256,479.774,219.691,476.723,216.64z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
          </div>
          <div class="gallery-downvote cursor-pointer inline-block">
            <svg class="w-8 inline-block pointer-events-none fill-current text-gray-500 @if($vote && $vote->score == -1)red @endif" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512.171 512.171" style="enable-background:new 0 0 512.171 512.171;" xml:space="preserve"><g><g><path d="M479.046,283.925c-1.664-3.989-5.547-6.592-9.856-6.592H352.305V10.667C352.305,4.779,347.526,0,341.638,0H170.971 c-5.888,0-10.667,4.779-10.667,10.667v266.667H42.971c-4.309,0-8.192,2.603-9.856,6.571c-1.643,3.989-0.747,8.576,2.304,11.627 l212.8,213.504c2.005,2.005,4.715,3.136,7.552,3.136s5.547-1.131,7.552-3.115l213.419-213.504 C479.793,292.501,480.71,287.915,479.046,283.925z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
          </div>
        @endauth
      </div>
      <div class="upvote-ratelimit text-white bg-gray-800 py-2 px-4">

      </div>
      <div class="gallery-numbers text-white bg-gray-800 py-2 px-4">
        <span class="gallery-points text-sm">{{$img->upvote}}</span>
        <span class="text-sm mr-4"> Points</span>
        <span class="gallery-view text-sm">{{$img->view}} Views</span>
      </div>
    </div>
    <div class="gallery-comments w-full sm:w-2/3 mt-4">
      <div class="comment-field">
        @if($errors->any())
          @foreach ($errors->all() as $error)
            <span>{{ $error }}</span>
          @endforeach
        @endif
        <form action="/comments" method="POST" class="flex flex-col border-b-30 border-gray-300 relative items-end">
          @csrf
          <input type="hidden" name="image_upload_id" value={{$img->id}}>
          <textarea name="body" id="comment" cols="47" rows="4"
            class="comment-area text-sm p-2 w-full" placeholder="comment here..."></textarea>
          <button type="submit" class="absolute -bottom-30px mr-2 text-gray-600 text-xl font-bold">Submit</button>
        </form>
      </div>
      <div class="comments mt-2">
        @if($comments)
        @foreach($comments as $comment)
          <div class="comment bg-gray-300 rounded-sm text-sm p-2 my-1 relative" data-id={{$comment->id}}>
            <div class="comment-head flex justify-between">
              <div class="comment-author mb-1">
                <span class="font-bold">{{$comment->user->name}}</span>
                <span>{{(new Carbon\Carbon($comment->created_at))->shortRelativeDiffForHumans()}}</span>
              </div>
              <div class="comment-action">
                @if(Auth::id() === $img->user->id)
                  <button type="submit" class="comment-update underline hover:text-red-700">Update</button>
                  <button type="submit" class="comment-delete underline hover:text-red-700">Delete</button>
                @endif
              </div>
            </div>
            <div class="comment-body text-base">
              <span>{{ $comment->body }}</span>
            </div>
          </div>
        @endforeach
        @endif
      </div>
    </div>
  </div>
  <div class="imageContainer flex flex-col sm:flex-row w-11/12 mx-auto mt-4">
  </div>
@endsection
@section('jsFile')
    <script src={{asset('/js/gallery.js')}}></script>
@endSection