<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'ckey' => 'required',
            'captcha' => 'required|captcha_api:' . $request->get('ckey'),
            'comment' => 'required'
        ], [
            'captcha.captcha_api' => "Captcha is incorrect"
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $strippedUrl = str_replace(env('APP_URL') . "/", '', $request->get('url'));
        $tag = substr(parse_url($strippedUrl, PHP_URL_PATH), 0, 5);
        if (!preg_match('/^[a-zA-Z0-9_-]{5}[\/]?$/', $tag)) {
            return response()->json(['errors' => ['url' => 'URL is not a valid shortened URL']], 422);
        }


        // Get the record from database
        $urlId = URL::where('shortenurl', $tag)->value('id');
        if (!$urlId) {
            return response()->json(['errors' => ['url' => ['URL is not a valid shortened URL']]], 422);
        }

        $reportCount = Report::where('url_id', $urlId)
            ->where('comment', $request->get('comment'))
            ->count();

        if ($reportCount > 0) {
            return response()->json(['errors' => ['url' => ['A same report has been submitted before.'] ]], 422);
        }

        $report = new Report;
        $report->url_id = $urlId;
        $report->comment = $request->get('comment');
        $report->save();

        return response()->json(['success' => true, 'data' => []]);
    }

}
