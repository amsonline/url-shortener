@extends('layouts.admin')

@section('content')
    <h1>Site Settings</h1>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <table class="admin-layout-table">
            <tr>
                <th>Site name</th>
                <td>
                    <input type="text" name="site_name" value="{{ setting('site_name') }}">
                </td>
            </tr>
            <tr>
                <th>Short url length</th>
                <td>
                    <input type="number" name="url_length" min="4" max="8" value="{{ setting('url_length', 5) }}">
                </td>
            </tr>
            <tr>
                <th>Social media icons</th>
                <td>
                    @foreach (['facebook', 'instagram', 'twitter', 'pinterest', 'linkedin', 'whatsapp', 'telegram', 'snapchat'] as $social)
                        <label>
                            <input type="checkbox" name="social[]" value="{{ $social }}"{{ (social_enabled($social) ? " checked" : "") }}>
                            {{ ucwords($social) }}
                        </label>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <td>
                    <button type="submit" class="btn btn-primary">Save</button>
                </td>
            </tr>
        </table>
    </form>
@endsection
