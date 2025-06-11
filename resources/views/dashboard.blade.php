@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name ?? 'log in to access team page' }}!</p>
    <p>Your dashboard overview goes here.</p>
</div>
@endsection
