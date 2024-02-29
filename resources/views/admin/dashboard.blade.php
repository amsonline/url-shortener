@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1>Welcome to the Admin Dashboard</h1>

    <div class="dashboard-stats">
        <div class="stat">
            <h2>URLs Created Today</h2>
            <p>{{ $urlsCreatedToday }}</p>
        </div>
        <div class="stat">
            <h2>Total URLs Created</h2>
            <p>{{ $totalUrlsCreated }}</p>
        </div>
        <div class="stat">
            <h2>Total URL Views</h2>
            <p>{{ $totalUrlViews }}</p>
        </div>
    </div>

    <div class="dashboard-details project-version">URL Shortener version {{ getProjectVersion() }}</div>
    <div class="dashboard-details project-author">Created by <a href="https://codecanyon.net/user/amsonline" target="_blank">amsonline</a></div>
@endsection
