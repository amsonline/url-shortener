@extends('layouts.admin')

@section('content')
    <h1>URLs</h1>

    {{-- Display statistics here, e.g., URLs created today and total URLs created --}}

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Original URL</th>
            <th>Shortened URL</th>
            <th>Views</th>
            <th>Created</th>
            <th>Last clicked</th>
        </tr>
        </thead>
        <tbody>
        @foreach($urls as $url)
            <tr>
                <td>{{ $url->id }}</td>
                <td>{{ $url->originalurl }}</td>
                <td>{{ $url->shortenurl }}</td>
                <td>{{ $url->views }}</td>
                <td>{{ $url->created_at }}</td>
                <td>{{ $url->updated_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $urls->links() }}
    </div>
@endsection
