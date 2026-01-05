@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Admin Panel</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.login.process') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Login</label>
                            <input type="text" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   name="username"
                                   value="{{ old('username') }}"
                                   required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Parol</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Kirish</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection