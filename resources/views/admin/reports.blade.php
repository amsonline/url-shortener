@extends('layouts.admin')

@section('content')
    <h1>Reports</h1>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>URL</th>
            <th>Original URL</th>
            <th>Comment</th>
            <th>Total URL reports</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        {{-- Loop through reports and display them in a table --}}
        @foreach($reports as $report)
            <tr>
                <td>{{ $report->id }}</td>
                <td>{{ $report->url->shortenurl }}</td>
                <td>{{ $report->url->originalurl }}</td>
                <td>{{ $report->comment }}</td>
                <td>{{ $pendingReportsCounts[$report->url_id] ?? 0 }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.reports.update') }}">
                        @csrf
                        <input type="hidden" name="url_id" value="{{ $report->url_id }}">
                        <button type="submit" name="subdisable" value="1" class="btn btn-danger">Disable URL</button>
                        <button type="submit" name="subremove" value="1" class="btn btn-warning">Remove Reports</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $reports->links() }}
    </div>
@endsection
