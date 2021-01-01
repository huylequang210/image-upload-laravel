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