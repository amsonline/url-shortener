@if($publisherId && $adUnitId)
    <div class="ad {{ $class }}">
        <!-- Google AdSense -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-{{ $publisherId }}"
             data-ad-slot="{{ $adUnitId }}"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
@endif
