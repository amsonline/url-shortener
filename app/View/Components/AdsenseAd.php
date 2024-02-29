<?php

namespace App\View\Components;

use App\Models\Setting;
use Illuminate\View\Component;

class AdsenseAd extends Component
{
    public $publisherId;
    public $adUnitId;
    public string $class;

    /**
     * Create a new component instance.
     *
     * @param  string  $location
     * @return void
     */
    public function __construct($location)
    {
        $this->publisherId = Setting::getSetting('ad_publisher_id', null);
        $this->adUnitId = Setting::getSetting("ad_{$location}_unit_id", null);
        $this->class = "ad-{$location}";
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.adsense-ad');
    }
}
