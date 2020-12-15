@extends('layouts.app')

@section('section')
  <div class="p-16 flex">
    <div class="review">
      <img src="/images/{{$image->thumbnail}}" alt="images" alt="">
    </div>
    <div class="edit-form">

    </div>
    <div class="edit-status">
      <span>Public</span>
      <span>Private</span>
    </div>
  </div>
@endsection