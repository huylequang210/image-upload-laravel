<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('/css/app.css')}}">

        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="container">
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
        </div>
        <script src={{asset('/js/app.js')}}></script>
    </body>
</html>
