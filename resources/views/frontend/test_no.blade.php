@extends('layouts.frontend')
@section('section')
<section class="header">
    <div class="message">
        <p class="question">Where do you see yourself in 5 years from now?</p>
    </div>
</section>
<main>
    <div class="countdown">
        <p class="count_number">10</p>
    </div>
    <div class="container">
        <form class="form-horizontal" action="/action_page.php">
            <div id="rec_camera"></div>
            <input type="hidden" name="image" class="image-tag">
        </form>
    </div>
    <div class="back_image">
        <div class="image_container">
            @if(session()->has('background'))
            <img src="{{ asset('storage/Background/'.session('background')) }}">
            @else
            <img src="{{ asset('storage/Background/background-image.png') }}">
            @endif
        </div>
        <div class="button_container">
            <div class="record">
                <a class="btn btn-success" href="{{ route('real.start', 'false') }}">Next</a>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<!-- Configure a few settings and attach camera -->

@if($errors->any())
<script src="{{ asset('assets/js/sweetalert2@9.js') }}"></script>
<script>
    Swal.fire({
        position: 'top-end',
        icon: 'warning',
        title: 'No Questions!',
        showConfirmButton: false,
        timer: 2000
    })
</script>
@endif
<script language="JavaScript">
    Webcam.set({
        width: 150,
		height: 150,
		dest_width: 640,
		dest_height: 480,
		image_format: 'jpeg',
		jpeg_quality: 90,
    });

    Webcam.attach( '#rec_camera' );
    $("#rec_camera video").hide();
    var shutter = new Audio();
    shutter.autoplay = true;
    function take_snapshot() {
        var take = $(".snap").data('val');
        if (take === "take")
        {
            shutter.src = './sounds/shutter.mp3';
            Webcam.snap( function(data_uri) {
                $(".image-tag").val(data_uri);
            });
            Webcam.freeze();

            $(".snap").data('val', "retake");
            $(".snap").html('Back To <br> Camera');

        }

        else if (take === "retake") {
            Webcam.unfreeze();
            $(".image-tag").val('')
            $(".snap").html('Take <br> Picture');
            $(".snap").data('val', "take");
        }
    }

    Webcam.on( 'error', function(err) {
        shutter.src = './sounds/error.mp3';
        shutter.play();
        $(".snap").data('val', "error");
    });
    Webcam.on( 'load', function(err) {
        $(".snap").data('val', "take");
    });

    var interval = setInterval(function(){
        var count = $(".count_number").text();
        if (count == 1) {
            $(".count_number").text("Ready!");
            $("#rec_camera video").show();
            clearInterval(interval);
        }
        else {
            $(".count_number").text(count-1);
        }
    }, 1000);

</script>
@endsection
