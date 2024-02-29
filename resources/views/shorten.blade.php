@extends('layouts.main')

@section('title', setting('site_name'))

@section('content')
            <div class="title">Shorten URL</div>
            <div class="description">
                Using this service, you can shorten any URL around the web.
                No matter how long it is, this service gives you a shorten url that you can easily share with anybody.
            </div>
            <div class="main-card" v-if="!shortUrl">
                <div class="card-title">Paste or type the URL you want to shorten</div>
                <div class="url-form">
                    <input v-model="longUrl" type="text" placeholder="http:// or https://" class="url-input" />
                    <div class="url-button-wrapper">
                        <button @click="requestShortUrl()" class="url-button">Shorten!</button>
                    </div>
                </div>
            </div>
            <div class="main-card" v-if="shortUrl">
                <div class="card-title">The URL got shortened!</div>
                <div class="url-form">
                    <input v-model="shortUrl" type="text" disabled class="url-input" />
                    <div class="url-button-wrapper">
                        <button @click="copyUrl()" class="url-button">Copy URL</button>
                    </div>
                </div>
                <div v-if="clipboardSuccess" class="clipboard-success">
                    Copied to clipboard!
                </div>
                <div class="qr-holder">
                    <img :src="'/qr/' + shortTag + '.svg'" alt="QR" />
                </div>
                <div class="center">
                    <div class="long-url">
                        Long url: <a :href="longUrl">@{ longUrl }</a>
                    </div>
                    <div class="restart-button-wrapper">
                        <button class="url-button restart-button" @click="resetPage">Shorten another url</button>
                    </div>
                    <div class="share-bar">
                        <div class="share-caption">Share:</div>
                        <div v-for="social in socials" :key="social" :class="['share-icon', 'icon-' + social]">
                            <a href="javascript:" @click="shareUrl(social)"></a>
                        </div>
                    </div>
                </div>
            </div>
@endsection
@section('js')
    <script type="text/javascript" src="/app.js"></script>
@endsection
