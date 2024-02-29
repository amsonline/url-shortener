new Vue({
    el: '#app',
    delimiters: ['@{', '}'],
    data: {
        shortUrl: '',
        urlDetails: null,
        errorText: null,
        captcha: '',
        reportComment: '',
        reportSent: false,
        captchaKey: null,
        captchaImage: null,
    },
    mounted() {
        this.getCaptcha();
    },
    methods: {
        getUrlDetails() {
            this.errorText = null;
            this.urlDetails = null;
            axios.post(`/api/getUrl`, { url: this.shortUrl })
                .then(result => {
                    this.urlDetails = result.data;
                })
                .catch(error => {
                    this.errorText = error.response.data.errors.url;
                });
        },
        submitReport() {
            this.errorText = null;
            axios.post(`/api/url/report`, { url: this.shortUrl, comment: this.reportComment, captcha: this.captcha, ckey: this.captchaKey })
                .then(() => {
                    this.reportSent = true;
                    this.getCaptcha();
                })
                .catch(error => {
                    if (error.response.data.errors.url) {
                        this.errorText = error.response.data.errors.url[0];
                    }else if (error.response.data.errors.captcha) {
                        this.errorText = error.response.data.errors.captcha[0];
                    }
                    this.getCaptcha();
                })
        },
        resetReport() {
            this.reportSent = false;
            this.shortUrl = '';
            this.reportComment = '';
            this.captcha = '';
            this.getCaptcha();
        },
        getCaptcha() {
            axios.get(`/captcha/api`)
                .then(result => {
                    this.captchaKey = result.data.key;
                    this.captchaImage = result.data.img;
                })
                .catch(error => {
                    this.errorText = error.response.error;
                });

        }
    },
    filters: {
        formatDate: function(value) {
            if (!value) return '';
            const dateTime = new Date(value);
            return dateTime.toLocaleString(); // Customize this format as needed
        },
    },
});
