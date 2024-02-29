@extends('layouts.admin')

@section('content')
    <h1>Change password</h1>

    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        <table class="admin-layout-table">
            <tr>
                <th>Current password</th>
                <td>
                    <input type="password" name="current_password">
                </td>
            </tr>
            <tr>
                <th>New password</th>
                <td>
                    <input type="password" name="new_password">
                </td>
            </tr>
            <tr>
                <th>Confirm new password</th>
                <td>
                    <input type="password" name="new_password_confirmation">
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
