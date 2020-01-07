@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header shadow-lg rounded bg-info text-white">
                    <h3 class="text-center display-4">Dashboard</h3>
                </div>

                <div class="card-body">
                    <task-component></task-component>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
