@extends('layouts.admin')

@section('content')
    <h1>QR Settings</h1>

    <form method="POST" action="{{ route('admin.qrsettings.update') }}">
        @csrf
        <table class="admin-layout-table">
            <tr>
                <th>QR Size</th>
                <td>
                    <input type="number" name="qr_size" min="100" max="300" step="25" value="{{ setting('qr_size') }}">
                </td>
                <td rowspan="4">
                    <center>
                        <img id="qrImage" src="qr/test.svg" alt="QR" />
                    </center>
                </td>
            </tr>
            <tr>
                <th>Style</th>
                <td>
                    <select name="qr_style">
                        <option value="square"{{ setting('qr_style') == 'square' ? ' selected' : '' }}>Square</option>
                        <option value="dot"{{ setting('qr_style') == 'dot' ? ' selected' : '' }}>Dot</option>
                        <option value="round"{{ setting('qr_style') == 'round' ? ' selected' : '' }}>Round</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Eye style</th>
                <td>
                    <select name="qr_eye">
                        <option value="square"{{ setting('qr_style') == 'square' ? ' selected' : '' }}>Square</option>
                        <option value="circle"{{ setting('qr_style') == 'circle' ? ' selected' : '' }}>Circle</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Quality</th>
                <td>
                    <select name="qr_quality">
                        <option value="L"{{ setting('qr_quality') == 'L' ? ' selected' : '' }}>L (7% correction)</option>
                        <option value="M"{{ setting('qr_quality') == 'M' ? ' selected' : '' }}>M (15% correction)</option>
                        <option value="Q"{{ setting('qr_quality') == 'Q' ? ' selected' : '' }}>Q (25% correction)</option>
                        <option value="H"{{ setting('qr_quality') == 'H' ? ' selected' : '' }}>H (30% correction)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <td colspan="2">
                    <button type="submit" class="btn btn-primary">Save</button>
                </td>
            </tr>
        </table>
    </form>
    <script type="text/javascript">
        function updateQRImage() {
            // Retrieve the values from the inputs and selects
            var size = document.querySelector('input[name="qr_size"]').value;
            var style = document.querySelector('select[name="qr_style"]').value;
            var eye = document.querySelector('select[name="qr_eye"]').value;
            var quality = document.querySelector('select[name="qr_quality"]').value;

            // Construct the new URL
            var newSrc = 'qr/test.svg?size=' + size + '&eye=' + eye + '&quality=' + quality + '&style=' + style;

            // Update the image src
            document.getElementById('qrImage').src = newSrc;
        }

        // Add event listeners to all inputs and selects
        document.querySelector('input[name="qr_size"]').addEventListener('change', updateQRImage);
        document.querySelector('select[name="qr_style"]').addEventListener('change', updateQRImage);
        document.querySelector('select[name="qr_eye"]').addEventListener('change', updateQRImage);
        document.querySelector('select[name="qr_quality"]').addEventListener('change', updateQRImage);
    </script>
@endsection
