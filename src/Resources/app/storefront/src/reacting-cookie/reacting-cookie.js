import { COOKIE_CONFIGURATION_UPDATE } from 'src/plugin/cookie/cookie-configuration.plugin';

document.$emitter.subscribe(COOKIE_CONFIGURATION_UPDATE, eventCallback);

function eventCallback(updatedCookies) {
    if (typeof updatedCookies.detail['cookieSmartsuppFunctional'] !== 'undefined') {
        const smartEl = document.getElementById('smartsupp_live_chat');
        if (smartEl) {
            const smartKey = smartEl.getAttribute('key');
            const smartColor = smartEl.getAttribute('color');
            const hasColor = smartEl.hasAttribute('color');
            const smartConfig = smartEl.getAttribute('config');
            const hasConfig = smartEl.hasAttribute('config');
            const smartApi = smartEl.getAttribute('api');
            const hasApi = smartEl.hasAttribute('api');
            const newScript = document.createElement('script');
            const inlineScript = document.createTextNode(
                `function getCookie(name) {
                    var cookieMatch = document.cookie.match(name + '=(.*?)(;|$)');
                    return cookieMatch && decodeURI(cookieMatch[1]);
                }

                var ssCookieFcn = getCookie('cookieSmartsuppFunctional'),
                    ssCookieSts = getCookie('cookieSmartsuppStatistics'),
                    ssCookieMkt = getCookie('cookieSmartsuppMarketing')

                if (ssCookieFcn != null) {
                    var _smartsupp = _smartsupp || {};
                        _smartsupp.key = '${smartKey}';
                        _smartsupp.pluginVersion = '1.0.1';
                        _smartsupp.offsetY = 65;

                    window.smartsupp||(function(d) {
                        var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
                        s=d.getElementsByTagName('script')[0];c=d.createElement('script');
                        c.type='text/javascript';c.charset='utf-8';c.async=true;
                        c.src='//www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
                    })(document);
                        
                    if (ssCookieSts != null) {
                        smartsupp('analyticsConsent', true);
                    } else {
                        smartsupp('analyticsConsent', false);
                    }

                    if (ssCookieMkt != null) {
                        smartsupp('marketingConsent', true);
                    } else {
                        smartsupp('marketingConsent', false);
                    }
                }
                
                if (${hasColor}) {
                    _smartsupp.color = '${smartColor}';
                }
                
                if (${hasConfig}) {
                    ${smartConfig}
                }

                if (${hasApi}) {
                    ${smartApi}
                }`
            );

            if (smartKey != '') {
                newScript.appendChild(inlineScript);

                const script = document.body.firstChild;
                script.parentNode.insertBefore(newScript, script);
            }
        }
    }   
}
 