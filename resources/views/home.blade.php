@extends('layouts.app')

@section('dropzone')
    <x-dropzone param="home">
        
    </x-dropzone>
@endsection

@section('section')
<input type="hidden" class="user-id" value={{Auth::id()}}>
<div class="imageContainer flex flex-wrap justify-center sm:justify-start">
    @foreach ($images as $image)
        <div class="imageDiv mr-1 mb-4 sm:mb-1 h-350px sm:h-300px sm:w-210px flex flex-col items-center relative">
            <a href="/gallery/{{$image->id}}" class="imageLink block w-full h-210" 
                data-id={{$image->id}}
                data-original={{$image->original}}
                data-thumbnail={{$image->thumbnail}}
                data-title="{{$image->title}}"
                data-public_status={{$image->public_status}}
                data-comments={{$image->comments->count()}}
                data-view={{$image->view}}
                data-upvote={{$image->upvote}}
                data-user_id="{{$image->user_id}}">
                <img class="girdImage" src="/images/{{$image->thumbnail}}" alt="images">
            </a>
            <div class="image-info w-full h-65px bg-gray-900 text-white text-sm p-1 flex flex-col absolute bottom-100px sm:bottom-90px">
                <div class="title flex-2"><p>{{$image->title}}</p></div>
                <div class="image-item text-white flex-1 flex justify-around">
                    <div class="image-item-upvote flex items-center">
                        <span class="icon upvote-icon mr-0.1"></span>
                        <span class="upvote-count text-sm">{{$image->upvote}}</span>
                    </div>
                    <div class="image-item-comment flex items-center">
                        <span class="icon comment-icon mr-0.1"></span>
                        <span class="comment-count text-sm">{{$image->comments->count()}}</span>
                    </div>
                    <div class="image-item-seen flex items-center">
                        <span class="icon seen-icon mr-0.1"></span>
                        <span class="seen-count text-sm">{{$image->view}}</span>
                    </div>
                </div>
            </div>
            <div class="image-edit h-full w-full bg-gray-900 text-white p-1 text-sm flex flex-col justify-around">
                <div class="image-status flex justify-center">
                    <form action="/image/{{$image->id}}"  method="POST" class="deleleImage block">
                        @method('DELETE')
                        @csrf
                        <button class="deleteImageButton flex">Delete</button>
                    </form>
                    <div class="image-visibility flex">
                    
                        <button 
                            class="image-public flex ml-1 {{$image->public_status == 1 ? 'status' : ''}}
                        ">Public</button>
                        <button 
                            class="image-private flex ml-1 {{$image->public_status == 0 ? 'status' : ''}}
                        ">Private</button>
                    </div>
                </div>

                <div class="edit-title-name">
                    <form action="/image/{{$image->id}}" method="POST" class="editImage block">
                        @method('PATCH')
                        @csrf
                        <input class="bg-gray-900 block w-full"
                            type="tile" name="title" id="title" 
                            placeholder="Click here to edit title" maxlength="50">
                        <button type="submit" class="editImageButton block">Edit</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
@section('jsFile')
    <script src={{asset('/js/home.js')}}></script>
@endSection