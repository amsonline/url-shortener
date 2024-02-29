<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UrlController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $url = new Url;
        $url->originalurl = $request->input('url');
        $url->shortenurl = Url::getFreeUrl();
        $url->save();

        return response()->json(['success' => true, 'data' =>
            [
                'url' => env('APP_URL') . "/{$url->shortenurl}/",
                'tag' => $url->shortenurl,
                'social' => Setting::getAvailableSocials()
            ]]);

    }

    public function generateQR(string $code)
    {
        // Generate a QR code with the given data
        $qrCode = QrCode::size(Setting::getSetting('qr_size'))
            ->eye(Setting::getSetting('qr_eye'))
            ->errorCorrection(Setting::getSetting('qr_quality'))
            ->style(Setting::getSetting('qr_style'))
            ->generate(env('APP_URL') . "/{$code}/");

        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }

    public function generateTestQR(Request $request)
    {
        // Generate a QR code with the given data
        $qrCode = QrCode::size($request->input('size') ?? Setting::getSetting('qr_size'))
            ->eye($request->input('eye') ?? Setting::getSetting('qr_eye'))
            ->errorCorrection($request->input('quality') ?? Setting::getSetting('qr_quality'))
            ->style($request->input('style') ?? Setting::getSetting('qr_style'))
            ->generate("This is a test");

        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }

    public function getUrl(Request $request) {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!str_contains($request->get('url'), env('APP_URL'))) {
            return response()->json(['errors' => ['url' => 'URL is not a valid shortened URL']], 422);
        }

        $strippedUrl = str_replace(env('APP_URL') . "/", '', $request->get('url'));
        $tag = substr(parse_url($strippedUrl, PHP_URL_PATH), 0, 5);
        if (!preg_match('/^[a-zA-Z0-9_-]{5}[\/]?$/', $tag)) {
            return response()->json(['errors' => ['url' => 'URL is not a valid shortened URL']], 422);
        }


        // Get the record from database
        $url = URL::where('shortenurl', $tag)->first(['originalurl', 'shortenurl', 'views', 'created_at', 'updated_at as last_opened']);
        if (!$url) {
            return response()->json(['errors' => ['url' => 'URL is not a valid shortened URL']], 422);
        }

        return $url;
    }

    public function handleSegment(string $segment)
    {
        /* @var Url $url */
        $url = Url::whereShortenurl($segment)->first();

        if (!$url || !$url->enabled) {
            abort(404);
        }
        $url->increment('views');
        return redirect($url->originalurl);
    }
}
