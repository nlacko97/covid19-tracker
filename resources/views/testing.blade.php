@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Testing page
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        {{-- <img src="https://picsum.photos/200" alt="" class="img-fluid"> --}}
                    </div>
                    <div class="col-8">
                        🐱‍🚀Query output:
                        <p class="text-info text-xs">Refer to Laravel Debugbar for detailed info</p>
                        <code>
                            {{ $output }}
                        </code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection