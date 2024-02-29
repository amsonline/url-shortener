new Vue({
    el: '#app',
    delimiters: ['@{', '}'],
    data: {
        longUrl: '',
        shortUrl: null,
        shortTag: null,
        socials: [],
        clipboardSuccess: false,
    },
    mounted() {
    },
    methods: {
        requestShortUrl() {
            axios.post(`/api/urls`, { url: this.longUrl })
                .then(result => {
                    this.shortUrl = result.data.data.url;
                    this.shortTag = result.data.data.tag;
                    this.socials = result.data.data.social;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        resetPage() {
            this.longUrl = '';
            this.shortUrl = null;
            this.shortTag = null;
        },
        shareUrl(platform) {
            let url = '';
            switch (platform) {
                case 'facebook':
                    url = `https://www.facebook.com/sharer/sharer.php?u=${this.shortUrl}`;
                    break;
                case 'instagram':
                    url = `https://www.instagram.com/share?url=${this.shortUrl}`;
                    break;
                case 'twitter':
                    url = `https://x.com/intent/tweet?url=${this.shortUrl}`;
                    break;
                case 'pinterest':
                    url = `https://www.pinterest.com/pin/create/button/?url=${this.shortUrl}`;
                    break;
                case 'linkedin':
                    url = `https://www.linkedin.com/shareArticle?mini=false&url=${this.shortUrl}`;
                    break;
                case 'whatsapp':
                    url = `https://api.whatsapp.com/send?text=${this.shortUrl}`;
                    break;
                case 'telegram':
                    url = `https://t.me/share/url?url=${this.shortUrl}`;
                    break;
                case 'snapchat':
                    url = `https://www.snapchat.com/share?url=${this.shortUrl}`;
                    break;
            }
            window.open(url);
        },
        copyUrl() {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                // Modern browsers with the Clipboard API:
                navigator.clipboard.writeText(this.shortUrl)
                    .then(() => {
                        this.clipboardSuccess = true; // Indicate successful copying
                        setTimeout(() => {
                            this.clipboardSuccess = false; // Hide success message after a while
                        }, 3000); // Adjust timeout as desired
                    })
                    .catch(error => {
                        console.error('Failed to copy to clipboard:', error);
                        // Use an appropriate fallback if writeText fails (e.g., text area creation)
                    });
            } else {
                // Fallback for older browsers without the Clipboard API:
                const textArea = document.createElement('textarea');
                textArea.value = this.shortUrl;
                textArea.style.position = 'fixed';
                textArea.style.left = '-9999px';
                textArea.style.top = '-9999px';
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                this.clipboardSuccess = true; // Indicate successful copying using fallback
                setTimeout(() => {
                    this.clipboardSuccess = false; // Hide success message after a while
                }, 3000); // Adjust timeout as desired
            }
        }
    },
});
