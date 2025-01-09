if (typeof authPopup !== 'function'){
    window.authPopup = class authPopup {
        constructor() {
            this.mainWindow = window;
            this.create();
        }
        create() {
            console.log('Opening auth Window');
            const h = 600;
            const w = 500;
            const y = window.top.outerHeight / 2 + window.top.screenY - (h / 2);
            const x = window.top.outerWidth / 2 + window.top.screenX - (w / 2);
            this.popup = window.open('', '_blank', `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=${w},height=${h}, top=${y}, left=${x}`);
            if (!this.popup) throw new Error('Popup blocked by the browser');
            this.popup.document.write("<html lang='en'><head><title>Authenticating...</title></head><body><div class='loader'></div></body></html>");
            const styleElement = this.popup.document.createElement("style");
            const loaderStyle = ".loader{border:8px solid #f3f3f3;border-top:8px solid #020c34;border-radius:50%;width:50px;height:50px;animation:spin 1s linear infinite;}@keyframes spin{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}body{display:flex;justify-content:center;align-items:center;height:100vh;overflow:hidden;background-color:#f7f7f7;font-family:Arial,sans-serif;}";
            styleElement.appendChild(document.createTextNode(loaderStyle));
            this.popup.document.head.appendChild(styleElement);
            this.popup.document.cookie = `hslogin_referer=${this.mainWindow.location.href}; expires=0; path=/`;
        }
        oAuth(provider) {
            const url = `${this.mainWindow.location.origin}/sso/${provider}`;
            this.popup.location.replace(url);
        }
    }
}

window.launchAuthWindow = function(provider){
    let popup = new window.authPopup();
    popup.oAuth(provider);
    window.addEventListener('storage', function(event) {
        if (event.key === 'hsloginResponse') {
            let res = JSON.parse(localStorage.getItem('hsloginResponse'));
            if(res !== null && res.redirect_to !== null){
                window.location.href = res.redirect_to;
            }
            localStorage.removeItem('hsloginResponse');
        }
    });
}
