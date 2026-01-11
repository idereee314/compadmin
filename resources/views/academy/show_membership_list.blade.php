@extends('default')

@section('content')
<style>
    .academy-page-wrap {
        background-image: url('{{ asset('assets/media/bg/bg-3.jpg') }}');
        background-size: cover;
        background-position: center;
        min-height: 100vh;
    }

    .academy-title {
        color: #0f4b63;
        font-weight: 800;
        letter-spacing: .3px;
    }

    .academy-muted {
        color: #6c757d;
        font-size: 14px;
    }

    .badge-soft {
        background: #f4f6f8;
        border: 1px solid rgba(15,75,99,.12);
        color: #0f4b63;
        padding: .35rem .55rem;
        border-radius: .42rem;
        font-weight: 600;
        font-size: 12px;
        display: inline-block;
        line-height: 1;
    }

    .icon-academy { color: #f96815; }

    .table thead th { white-space: nowrap; }

    .dt-wrap {
        background: rgba(255,255,255,.92);
        backdrop-filter: blur(2px);
        border-radius: .6rem;
    }

    .btn-soft-primary{
        background: rgba(15,75,99,.08);
        border: 1px solid rgba(15,75,99,.18);
        color: #0f4b63;
        font-weight: 700;
    }
    .btn-soft-primary:hover{
        background: rgba(15,75,99,.14);
        color:#0f4b63;
    }
    .pdf-frame{
        width:100%;
        height:70vh;
        border: 1px solid rgba(0,0,0,.08);
        border-radius:.6rem;
        background:#fff;
    }
</style>

@php
    // PDF файлын зам: public/assets/rulesbook/weight-merge-rule.pdf
    $pdfUrl = asset('assets/rulesbook/weight-merge-rule.pdf');
@endphp

<div class="d-flex flex-column flex-row-fluid wrapper academy-page-wrap" id="kt_wrapper">
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="d-flex flex-column-fluid">
            <div class="container">

                <div class="d-flex justify-content-center mb-7">
                    <div class="text-center">
                        <h1 class="text-uppercase academy-title mb-2" style="font-size: 3rem;">
                            <i class="la la-university icon-academy mr-2" style="font-size: 2.4rem;"></i>
                            ЖИНГИЙН НЭГТГЭЛИЙН ЖУРАМ
                        </h1>
                        <div class="academy-muted">PDF файлыг хэвлэхгүйгээр шууд татаж авна.</div>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <div class="col-lg-10">
                        <div class="card dt-wrap">
                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap" style="gap:10px;">
                                    <div>
                                        <span class="badge-soft">JJIF / U10–U16 / Adults</span>
                                    </div>

                                    <div class="d-flex align-items-center" style="gap:10px;">
                                        {{-- Шууд татах --}}
                                        <a href="{{ $pdfUrl }}"
                                           class="btn btn-soft-primary"
                                           download>
                                            <i class="la la-download mr-1"></i> PDF татах
                                        </a>

                                        {{-- Шинэ таб дээр нээх (optional) --}}
                                        <a href="{{ $pdfUrl }}"
                                           class="btn btn-light"
                                           target="_blank" rel="noopener">
                                            <i class="la la-external-link mr-1"></i> Нээх
                                        </a>
                                    </div>
                                </div>

                                {{-- PDF preview (optional) --}}
                                <iframe class="pdf-frame" src="{{ $pdfUrl }}#toolbar=1&navpanes=0"></iframe>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('layouts.footer')
</div>
@endsection
