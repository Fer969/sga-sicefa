@extends('sga::layouts.master')

@section('content')
<style>
    .parent {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, 1fr);
        gap: 8px;
    }

    .div1 {
        grid-column-start: 1;
        grid-row-start: 2;
    }

    .div2 {
        grid-column: span 2 / span 2;
        grid-row-start: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 12px;
        border-radius: 10px;
    }

    .div3 {
        grid-column-start: 2;
        grid-row-start: 2;
    }
</style>


<div class="parent">
    <div class="div1">
        <div class="card" style="width: auto;">
            <div class="card-body">
                <h5 class="card-title">{{ trans('sga::contents.AdmIndKnow') }}</h5>
                <p class="card-text">{{ trans('sga::contents.AdmIndText-1') }}</p>
                <p class="card-text">{{ trans('sga::contents.AdmIndText-2') }}</p>
                <a href="" class= "btn btn-success" {{ Route::is('cefa.sga.admin.index') ? 'active' : '' }}>
                    <p>{{ trans('sga::contents.AdmManualUser') }}</p>
                </a>
            </div>
        </div>
    </div>
    <div class="div2">
        <img src="{{ asset('modules/sga/img/banner-cefa-sena-la.jpeg') }}"
            alt="Imagen de CEFA SENA La"
            style="width: 100%; height: 200px; max-height: 200px; border-radius: 8px;">
    </div>
    <div class="div3">
        <div class="card" style="width: auto;">
            <div class="card-body">
                <h5 class="card-title">{{ trans('sga::contents.AdmIndPoints') }}</h5>
                <p class="card-text">{{ trans('sga::contents.AdmIndli-1') }}</p>
                <p class="card-text">{{ trans('sga::contents.AdmIndli-2') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection