@extends('default')

@section('css')
<style>
    .sports-section {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        background-image: url('{{ asset('assets/media/bg/bg-3.jpg') }}');
        background-size: cover;
        background-position: center;
    }
    .sport-buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
    }
    .sport-buttons a {
        padding: 1rem 2rem;
        border: 2px solid #ff3c00;
        color: #ff3c00;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .sport-buttons a:hover {
        background-color: #ff3c00;
        color: white;
    }
</style>
@endsection

@section('content')
@include('layouts.mobile')
@include('layouts.aside')

<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
    @include('layouts.header')
    <div class="sports-section">
        <div class="text-center">
            <h2 class="text-black font-weight-bold mb-3">Спортын төрлүүд</h2>
            <p class="text-black-50 mb-5">Та доорх спортын төрлүүд дээр дарж тохиргоог удирдана уу.</p>
            <div class="sport-buttons">
                @foreach($sports as $sport)
                    <a href="{{ route('event.competition.per.card', $sport['id']) }}">
                        {{ $sport['name'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <!-- @include('layouts.footer') -->
</div>
@endsection
