@extends('layouts.frontend')
@section('section')
<section class="header">
    <div class="message">
        <p class="greeting_client">Hi, {{ session('applicant')->name }}!</p>
    </div>
</section>
<section class="notification_container">
    <div class="notification">
        <div class="notification_body">
            <p class="notice">If this is your first time using this app, would you like to conduct a test interview first?</p>
        </div>
        <div class="notification_footer">
            <a class="btn btn-danger" href="{{ route('real.start', 'true') }}">No</a>
            <a class="btn btn-success" href="{{ route('test.yes') }}">Yes</a>
        </div>
    </div>
</section>
<main>
    <div class="back_image">
        <div class="container">
            <form class="form-horizontal" action="/action_page.php">
                <div class="record-camera"></div>
                <input type="hidden" name="image" class="image-tag">
            </form>
        </div>
        <div class="image_container">
            @if(session()->has('background'))
            <img src="{{ asset('storage/Background/'.session('background')) }}">
            @else
            <img src="{{ asset('storage/Background/background-image.png') }}">
            @endif
        </div>
    </div>
</main>

@endsection

@section('script')
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
@endsection
