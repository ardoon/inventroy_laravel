@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('گزارشات') }}</div>

                    <div class="card-body">
                        <div class="row justify-content-center">
                            <a href="{{ route('reports.entries') }}" class="btn btn-outline-secondary ml-2">گزارش ورود</a>
                            <a href="#" class="btn btn-outline-secondary ml-2">گزارش خروج</a>
                            <a href="#" class="btn btn-outline-secondary ml-2">گزارش کاردکس</a>
                            <a href="#" class="btn btn-outline-secondary">گزارش موجودی</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
