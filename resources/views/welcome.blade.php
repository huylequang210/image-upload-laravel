@extends('layouts.app')

@section('content')
<form action="/api/images" method='POST' enctype="multipart/form-data" class="dropzone" id="my-dropzone">
    @csrf
    <div class="dz-message needsclick dz-clickable">
        <p>Click here to upload image(s)</p>
    </div>
    <div class="fallback">
        <input type="file" name="file" multiple>
    </div>
    <button type="submit" id="button">Submit</button>
</form>
<div class="imageContainer">
    @foreach ($images as $image)
        <div class="imageDiv">
            <a href="{{$image->original}}">
                <img src="{{$image->thumbnail}}" alt="images">
            </a>
            <form action="/api/images/{{$image->id}}" method="POST" class="deleleImage">
                @method('DELETE')
                @csrf

                <button class="deleteImageButton">Delete</button>
            </form>
        </div>
    @endforeach
</div>
@endsection
@section('jsFile')
    <script src={{asset('/js/app.js')}}></script>
@endSection
