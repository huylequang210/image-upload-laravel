@extends('layouts.app')

@section('dropzone')
<form 
action="/api/images" 
method='POST' 
enctype="multipart/form-data" 
class="dropzone" 
id="my-dropzone">
@csrf
<div class="dz-message needsclick dz-clickable">
    <p>Click here to upload image(s)</p>
</div>
<div class="fallback">
    <input type="file" name="file" multiple>
</div>
<button type="submit" id="button" 
    class="block mx-auto my-2 p-1 rounded
     bg-gray-800
     hover:bg-teal-600
     text-gray-100">Submit</button>
</form>
@endsection
@section('section')
<div class="imageContainer flex flex-wrap">
    @foreach ($images as $image)
        <div class="imageDiv mr-1 mb-1 w-210px h-210px flex justify-center relative">
            <a href="/images/{{$image->original}}" class="imageLink block w-full h-full" 
                data-id={{$image->id}}
                data-original={{$image->original}}
                data-thumbnail={{$image->thumbnail}}>
                <img class="girdImage" src="/images/{{$image->thumbnail}}" alt="images">
            </a>
            <form 
                action="/api/images/{{$image->id}}" 
                method="POST" 
                class="deleleImage block absolute -bottom-20px invisible">
                @method('DELETE')
                @csrf

                <button class="deleteImageButton block text-white">Delete</button>
            </form>
        </div>
    @endforeach
</div>
@endsection
@section('jsFile')
    <script src={{asset('/js/app.js')}}></script>
@endSection
