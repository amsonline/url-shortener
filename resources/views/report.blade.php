@extends('layouts.main')

@section('title', setting('site_name') . ' - Report')

@section('content')
            <div class="title">Report malicious URL</div>
            <div class="description">
                You can report any URL which is malicious of any kind here.
            </div>
            <div class="main-card" v-if="!reportSent">
                <div class="card-title">Paste or type the short URL</div>
                <div class="url-form">
                    <input v-model="shortUrl" type="text" placeholder="{{env('APP_URL')}}/aBCdE/" class="url-input" />
                </div>
                <div class="url-form">
                    <textarea v-model="reportComment" rows="5" class="url-input" placeholder="Write your comment here"></textarea>
                </div>
                <div class="url-form">
                    <img :src="captchaImage" />
                    <input v-model="captcha" type="text" placeholder="Enter the code" class="url-input" />
                </div>
                <button class="url-button restart-button" @click="submitReport">Submit report</button>
                <div class="center">
                    <div class="error" v-if="errorText != null">@{ errorText }</div>
                </div>
            </div>
            <div class="main-card" v-if="reportSent">
                <div class="card-title">Thank you! Your report has been sent!</div>
                <div class="center">
                    <br /><br /><br />
                    <div class="restart-button-wrapper">
                        <button class="url-button restart-button" @click="resetReport">Report another url</button>
                    </div>
                    <br /><br /><br />
                </div>
            </div>
@endsection
@section('js')
    <script type="text/javascript" src="/urlfunctions.js"></script>
@endsection
