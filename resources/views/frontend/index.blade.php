@extends('layouts.frontend')
@section('style')
<style>
    .modal-dialog-full-width {
        width: 100% !important;
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        max-width:none !important;

    }

    .modal-content-full-width  {
        height: auto !important;
        min-height: 100% !important;
        border-radius: 0 !important;
        background-color: #ececec !important
    }

    .modal-header-full-width  {
        border-bottom: 1px solid #9ea2a2 !important;
    }

    .modal-footer-full-width  {
        border-top: 1px solid #9ea2a2 !important;
    }

    /* Style the video: 100% width and height to cover the entire window */
    #myVideo {
        position: fixed;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
    }

    /* Add some content at the bottom of the video/page */
    .content {
        position: fixed;
        bottom: 3rem;
        left:0;
        color: #f1f1f1;
        width: 100%;
    }

    .close {
        position: fixed;
        top: 2rem;
        right: 2rem;
    }

    .takePicture {
        width: fit-content;
        margin: auto;
    }

    /* Style the button used to pause/play the video */
    #myBtn {
        width: 200px;
        font-size: 25px;
        padding: 10px;
        border: none;
        border-radius: 50%;
        background: #fff;
        color: red;
        cursor: pointer;
    }

    #myBtn:hover {
        background: #ddd;
        color: black;
        border: 2px red solid;
    }

    .imagepreview {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 100;
        width: 150px;
        height: 150px;
    }

    @media screen and (max-width: 400px) {
        .imagepreview {
            width: 120px;
            height: 120px;
        }
    }

    @media screen and (max-width: 350px) {
        .imagepreview {
            width: 100px;
            height: 100px;
        }
    }
</style>
@endsection
@section('section')

<section class="header">
    <div class="back">
        <button type="button" class="btn"><i class='fas fa-arrow-left'></i></button>
    </div>
    <div class="message">
        <p class="greeting">Welcome</p>
        <p class="notice">please fill out the information below and click start interview</p>
    </div>
    <div class="logo">
        @if(session()->has('logo'))
        <img src="{{ asset('storage/Company/'.session()->get('logo')) }}" width="70" height="70">
        @else
        <p >Logo</p>
        @endif
    </div>
</section>
<main>
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="form-horizontal" action="{{route('saveApplicant')}}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="name" placeholder="Full name" name="name" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <input type="number" class="form-control form-control-lg" id="age" placeholder="Age" name="age" value="{{ old('age') }}">
            </div>
            <div class="form-group">
                <input type="email" class="form-control form-control-lg" id="email" placeholder="Email" name="email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <input type="tel" class="form-control form-control-lg" id="phone" placeholder="Phone" name="phone" value="{{ old('phone') }}">
            </div>
            <div class="camera_container">
                <div id="my_camera"></div>
                <p class="snap"  data-toggle="modal" data-target="#exampleModalPreview">Take<br>Picture</p>
            </div>
            <input type="hidden" name="image" class="image-tag">

            <div class="form-group text-center">
                <button type="submit" class="btn interview" href="interview.html">Start interview</button>
            </div>
            <!-- Modal -->
            <div class="modal fade right" id="exampleModalPreview" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
                <div class="modal-dialog-full-width modal-dialog momodel modal-fluid" role="document">
                    <div class="modal-content-full-width modal-content ">
                        <div class="modal-body">
                            <!-- The video -->
                            <video autoplay muted loop id="myVideo">
                            </video>
                            <!-- Optional: some overlay text to describe the video -->
                            <div class="content">
                                <div class="takePicture">
                                    <a id="myBtn" href="javascript:void(0)" onclick="take_snapshot()">
                                        <i class="fas fa-camera" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection

@section('script')
<!-- Configure a few settings and attach camera -->

<script language="JavaScript">
    $(".imagepreview").hide();
    Webcam.set({
        width: 150,
		height: 150,
		dest_width: 640,
		dest_height: 480,
		image_format: 'jpeg',
		jpeg_quality: 90,
    });

    var video = document.getElementById('myVideo');
    var recorder;
    navigator.mediaDevices.getUserMedia({ video: true, audio: true }).then(function(camera) {
        // preview camera during recording
        video.muted = true;
        video.srcObject = camera;

        // recording configuration/hints/parameters
        var recordingHints = {
            type: 'video',
            mimeType: 'video/webm;',
        };
    });

    Webcam.attach( '#my_camera' );

    $("#my_camera video").hide();
    var shutter = new Audio();
    shutter.autoplay = true;
    function take_snapshot() {
        var take = $(".snap").data('val');
        shutter.src = '/public/assets/sounds/shutter.mp3';
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
        });
        Webcam.freeze();
        $(".snap").html('');

        $("#exampleModalPreview").modal('toggle');
    }

    Webcam.on( 'error', function(err) {
        shutter.src = '/public/assets/sounds/error.mp3';
        shutter.play();
        $(".snap").data('val', "error");
    });
    Webcam.on( 'load', function(err) {
        $(".snap").data('val', "take");
    });

</script>
@endsection
