import { COOKIE_CONFIGURATION_UPDATE } from 'src/plugin/cookie/cookie-configuration.plugin';

document.$emitter.subscribe(COOKIE_CONFIGURATION_UPDATE, eventCallback);

function eventCallback(updatedCookies) {
    if (typeof updatedCookies.detail['ssupp.consent'] !== 'undefined') {
        const smartKey = document.getElementById('smartsupp_live_chat').getAttribute('key');
        const newScript = document.createElement('script');
        const inlineScript = document.createTextNode(
            `var _smartsupp = _smartsupp || {};
            _smartsupp.key = '${smartKey}';
            window.smartsupp||(function(d) {
            var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
            s=d.getElementsByTagName('script')[0];c=d.createElement('script');
            c.type='text/javascript';c.charset='utf-8';c.async=true;
            c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
            })(document);`
        );
        newScript.appendChild(inlineScript);

        const script = document.body.firstChild;
        script.parentNode.insertBefore(newScript, script);
    }
}
