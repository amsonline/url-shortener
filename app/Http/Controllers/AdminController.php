<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAdsRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateQRSettingsRequest;
use App\Http\Requests\UpdateReportsRequest;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Url;
use App\Models\Report;
use App\Models\Setting;

class AdminController extends Controller
{
    public function index()
    {
        // Implement logic to fetch statistics
        $urlsCreatedToday = Url::whereDate('created_at', today())->count();
        $totalUrlsCreated = Url::count();
        $totalUrlViews = Url::sum('views');

        return view('admin.dashboard', compact('urlsCreatedToday', 'totalUrlsCreated', 'totalUrlViews'));
    }

    public function ads()
    {
        // Fetch site settings from the database
        $settings = Setting::all();

        return view('admin.ads', compact('settings'));
    }

    public function urls()
    {
        // Fetch URLs from the database
        $urls = Url::paginate(10);

        return view('admin.urls', compact('urls'));
    }

    public function reports()
    {
        // Fetch reports from the database
        $reports = Report::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $pendingReportsCounts = Report::select('url_id')
            ->selectRaw('COUNT(*) as pending_reports_count')
            ->where('status', 'pending')
            ->groupBy('url_id')
            ->pluck('pending_reports_count', 'url_id')
            ->toArray();

        return view('admin.reports', compact('reports', 'pendingReportsCounts'));
    }

    public function settings()
    {
        // Fetch site settings from the database
        $settings = Setting::all();

        return view('admin.settings', compact('settings'));
    }

    public function password()
    {
        return view('admin.password');
    }

    public function qrsettings()
    {
        // Fetch QR settings from the database
        $qrSettings = Setting::all();

        return view('admin.qrsettings', compact('qrSettings'));
    }

    public function updateAds(UpdateAdsRequest $request)
    {
        Setting::setSetting('ad_publisher_id', $request->input('ad_publisher_id'));
        Setting::setSetting('ad_top_unit_id', $request->input('ad_top_unit_id'));
        Setting::setSetting('ad_left_unit_id', $request->input('ad_left_unit_id'));
        Setting::setSetting('ad_right_unit_id', $request->input('ad_right_unit_id'));
        Setting::setSetting('ad_bottom_unit_id', $request->input('ad_bottom_unit_id'));

        return redirect()->back()->with('success', 'Ads updated successfully.');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateReports(UpdateReportsRequest $request)
    {
        if($request->has('subdisable')) {
            Url::where('id', $request->input('url_id'))
                ->update(['enabled' => false]);

            $message = 'URL disabled successfully.';
        }else{
            $message = 'Reports for this url were removed successfully.';
        }

        $this->removeAllReportsForUrl($request->input('url_id'));

        return redirect()->back()->with('success', $message);
    }

    public function updateSettings(UpdateSettingsRequest $request)
    {
        Setting::setSetting('site_name', $request->input('site_name'));
        Setting::setSetting('url_length', $request->input('url_length'));
        Setting::setSetting('social', implode(',', $request->input('social')));

        return redirect()->back()->with('success', 'Site settings updated successfully.');
    }

    public function updateQRSettings(UpdateQRSettingsRequest $request)
    {
        Setting::setSetting('qr_size', $request->input('qr_size'));
        Setting::setSetting('qr_style', $request->input('qr_style'));
        Setting::setSetting('qr_eye', $request->input('qr_eye'));
        Setting::setSetting('qr_quality', $request->input('qr_quality'));

        return redirect()->back()->with('success', 'Site settings updated successfully.');
    }

    private function removeAllReportsForUrl($urlId)
    {
        Report::where('url_id', $urlId)->delete();
    }
}
