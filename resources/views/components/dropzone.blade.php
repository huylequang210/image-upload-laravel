<div>
    <!-- Well begun is half done. - Aristotle -->
    <form action="/image?r={{$param}}" method='POST' 
        enctype="multipart/form-data"  class="dropzone" id="my-dropzone">
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
            text-gray-100">Submit
        </button>
    </form>
</div>