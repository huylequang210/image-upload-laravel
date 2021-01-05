<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
<a href="/gallery/{{$image->id}}" class="imageLink block relative w-full h-210" 
    data-id={{$image->id}}
    data-original={{$image->original}}
    data-thumbnail={{$image->thumbnail}}
    data-title="{{$image->title}}"
    data-public_status={{$image->public_status}}
    data-view={{$image->view}}
    data-comments={{$image->comments->count()}}
    data-upvote={{$image->upvote}}
    data-user_id="{{$image->user_id}}"> 
    <img class="girdImage" src="{{$b2_url . $image->thumbnail}}" alt="images">
    <div class="image-info w-full h-65px bg-gray-900 text-white text-sm p-1 flex flex-col absolute bottom-0">
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
</a>
