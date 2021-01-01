<div class="image-edit h-full w-full bg-gray-900 text-white p-1 text-sm flex flex-col justify-around">
    <div class="image-status flex justify-center">
      <form action="/profile/{{$image->id}}"  method="POST" class="deleleImage block">
        @method('DELETE')
        @csrf
        <button class="deleteImageButton flex">Delete</button>
      </form>
      <form action="/profile/{{$image->id}}" method="POST" class="">
        @method('PATCH')
        <button class="image-restore flex ml-1">Restore</button>
        @csrf
      </form>
      <button class="image-days flex ml-1">
        @php
          $now = Carbon\Carbon::now();
          $expires = new Carbon\Carbon($image->expires_at);
        @endphp
        {{$now->diffInDays($expires) === 0 ? 
          ($now->diffInHours($expires) === 0 ? 
            $now->diffInMinutes($expires) . ' min(s)' : $now->diffInHours($expires) . ' hour(s)') : 
              $now->diffInDays($expires) . ' day(s)'}}
      </button>
    </div>
  </div>