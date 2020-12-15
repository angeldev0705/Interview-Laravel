@extends('layouts.frontend')
@section('section')
<section class="header">
    <div class="message">
    </div>
</section>
<main>
    <div class="countdown">
        <p>10</p>
    </div>
    <div class="hint">
        <p>You have 10 seconds to prepare for each question.</p>
        <p>Once the countdown is finished the recording will start.</p>
        <p class="p-2">When your finished, click "next question"</p>
        <a class="btn btn-success" href="{{ route('test.no') }}">OK</a>
    </div>
    <div class="back_image">
        <div class="container">
            <form class="form-horizontal" action="/action_page.php">
                <div class="record-camera"></div>
                <input type="hidden" name="image" class="image-tag">
            </form>
        </div>
    </div>
</main>

@endsection
