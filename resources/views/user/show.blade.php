@extends('layouts.app')

@section('template_title')
    {{ $user->name ?? 'Show User' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show User</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Category Id:</strong>
                            {{ $user->category_id }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $user->name }}
                        </div>
                        <div class="form-group">
                            <strong>Lastname:</strong>
                            {{ $user->lastname }}
                        </div>
                        <div class="form-group">
                            <strong>Identification:</strong>
                            {{ $user->identification }}
                        </div>
                        <div class="form-group">
                            <strong>Email:</strong>
                            {{ $user->email }}
                        </div>
                        <div class="form-group">
                            <strong>Country:</strong>
                            {{ $user->country }}
                        </div>
                        <div class="form-group">
                            <strong>Address:</strong>
                            {{ $user->address }}
                        </div>
                        <div class="form-group">
                            <strong>Mobile:</strong>
                            {{ $user->mobile }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
