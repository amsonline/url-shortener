@extends('layouts.admin')

@section('content')
    <h1>AdSense Settings</h1>

    <form method="POST" action="{{ route('admin.ads.update') }}">
        @csrf
        <table class="admin-layout-table">
            <tr>
                <td colspan="2">
                    In order to disable ads in each part, leave the respective field blank.
                    If you don't have an AdSense account, you can create one <a href="https://ads.google.com/" target="_blank">here</a>.
                </td>
            </tr>
            <tr>
                <th>AdSense Publisher ID</th>
                <td>
                    <input type="text" name="ad_publisher_id" value="{{ setting('ad_publisher_id') }}">
                    <br />
                    <sup>If you leave this blank, all ads will be disabled regardless of the other fields.</sup>
                </td>
            </tr>
            <tr>
                <th>Top Ad Unit ID</th>
                <td>
                    <input type="text" name="ad_top_unit_id" value="{{ setting('ad_top_unit_id') }}">
                </td>
            </tr>
            <tr>
                <th>Left Side Ad Unit ID</th>
                <td>
                    <input type="text" name="ad_left_unit_id" value="{{ setting('ad_left_unit_id') }}">
                </td>
            </tr>
            <tr>
                <th>Right Side Ad Unit ID</th>
                <td>
                    <input type="text" name="ad_right_unit_id" value="{{ setting('ad_right_unit_id') }}">
                </td>
            </tr>
            <tr>
                <th>Bottom Ad Unit ID</th>
                <td>
                    <input type="text" name="ad_bottom_unit_id" value="{{ setting('ad_bottom_unit_id') }}">
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
