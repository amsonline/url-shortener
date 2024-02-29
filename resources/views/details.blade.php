@extends('layouts.main')

@section('title', setting('site_name') . ' - URL Details')

@section('content')
            <div class="title">URL Details</div>
            <div class="description">
                You can check the actual website behind the shortened url, alongside with
                total views and the last time it was viewed.
            </div>
            <div class="main-card">
                <div class="card-title">Paste or type the short URL</div>
                <div class="url-form">
                    <input v-model="shortUrl" type="text" placeholder="{{env('APP_URL')}}/aBCdE/" class="url-input" />
                    <div class="url-button-wrapper">
                        <button @click="getUrlDetails()" class="url-button">Get details!</button>
                    </div>
                </div>
                <div class="center">
                    <div class="error" v-if="errorText != null">@{ errorText }</div>
                    <div class="qr-holder" v-if="urlDetails != null">
                        <img :src="'/qr/' + urlDetails.shortenurl + '.svg'" alt="QR" />
                    </div>
                </div>
                <div class="details" v-if="urlDetails != null">
                    <b>Long url:</b> <a :href="urlDetails.originalurl">@{ urlDetails.originalurl }</a><br />
                    <b>Created:</b> @{ urlDetails.created_at | formatDate }<br />
                    <b>Last used:</b> @{ urlDetails.last_opened | formatDate }<br />
                    <b>Total openings:</b> @{ urlDetails.views }<br />
                </div>
            </div>
@endsection
@section('js')
    <script type="text/javascript" src="/urlfunctions.js"></script>
@endsection
