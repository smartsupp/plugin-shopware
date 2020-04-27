<?php

namespace SmartsuppLiveChat\Components;

/**
 * Class SmartsuppChatGenerator is helper class over Smartsupp ChatGenerator library - we extend render method for
 * cookie check.
 * @package SmartsuppLiveChat\Components
 */
class SmartsuppChatGenerator extends \Smartsupp\ChatGenerator
{
    /**
     * Will assemble chat JS code. Class property with name key need to be set before rendering.
     *
     * @param bool|false $print_out Force to echo JS chat code instead of returning it.
     * @return string
     * @throws \Exception Will reach exception when key param is not set.
     */
    public function render($print_out = false)
    {
        if (empty($this->key)) {
            throw new \Exception("At least KEY param must be set!");
        }

        $params = array();
        $params2 = array();

        // set cookie domain if not blank
        if ($this->cookie_domain) {
            $params[] = "_smartsupp.cookieDomain = '%cookie_domain%';";
        }

        // If is set to false, turn off. Default value is true.
        if (!$this->send_email_transcript) {
            $params[] = "_smartsupp.sendEmailTanscript = false;";
        }

        if ($this->rating_enabled) {
            $params[] = "_smartsupp.ratingEnabled = true;  // by default false";
            $params[] = "_smartsupp.ratingType = '" . $this->rating_type . "'; // by default 'simple'";
            $params[] = "_smartsupp.ratingComment = " . ($this->rating_comment? 'true':'false') . ";  // default false";
        }

        if ($this->align_x && $this->align_y && $this->widget) {
            $params[] = "_smartsupp.alignX = '" . $this->align_x . "'; // or 'left'";
            $params[] = "_smartsupp.alignY = '" . $this->align_y . "';  // by default 'bottom'";
            $params[] = "_smartsupp.widget = '" . $this->widget . "'; // by default 'widget'";
        }

        if ($this->offset_x && $this->offset_y) {
            $params[] = "_smartsupp.offsetX = " . (int)$this->offset_x . ";    // offset from left / right, default 10";
            $params[] = "_smartsupp.offsetY = " . (int)$this->offset_y . ";    // offset from top, default 100";
        }

        if ($this->platform) {
            $params[] = "_smartsupp.sitePlatform = '" . static::javascriptEscape($this->platform) . "';";
        }

        // set detailed visitor's info
        // basic info
        if ($this->email) {
            $params2[] = "smartsupp('email', '" . static::javascriptEscape($this->email) . "');";
        }

        if ($this->name) {
            $params2[] = "smartsupp('name', '" . static::javascriptEscape($this->name) . "');";
        }


        // extra info
        if ($this->variables) {
            $options = array();

            foreach ($this->variables as $key => $value) {
                $options[] = static::javascriptEscape($value['id']) .": {label: '" .
                    static::javascriptEscape($value['label']) . "', value: '" . static::javascriptEscape($value['value']) .
                    "'}";
            }

            $params2[] = "smartsupp('variables', {" .
                implode(", ", $options) .
                "});";
        }


        // set GA key and additional GA params
        if ($this->ga_key) {
            $params[] = "_smartsupp.gaKey = '%ga_key%';";

            if (!empty($this->ga_options)) {
                $options = array();

                foreach ($this->ga_options as $key => $value) {
                    $options[] = "'" . static::javascriptEscape($key) . "': '" . static::javascriptEscape($value) . "'";
                }

                $params[] = "_smartsupp.gaOptions = {" . implode(", ", $options) . "};";
            }
        }

        // hide widget if needed
        if ($this->hide_widget) {
            $params[] = "_smartsupp.hideWidget = true;";
        }

        $codeChatCode = "_smartsupp.key = '%key%';\n" .
            implode("\n", $params) . "\n" .
            "window.smartsupp||(function(d) {
        var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
                s=d.getElementsByTagName('script')[0];c=d.createElement('script');
                c.type='text/javascript';c.charset='utf-8';c.async=true;
                c.src='//www.smartsuppchat.com/loader.js';s.parentNode.insertBefore(c,s);
            })(document);"
            . implode("\n", $params2);

        // create basic code and append params
        if (!$this->loadAsync) {
            $code = "<script type=\"text/javascript\">
                    var _smartsupp = _smartsupp || {};
                    $codeChatCode
                </script>";
        } else {
            // create code to be load async
            $code = "<script type=\"text/javascript\">
            setTimeout(function() {
                let chatEnabled = (typeof $.getCookie('cookiePreferences') === 'undefined' && $.getCookie('allowCookie') == 1) || ($.getCookie('cookiePreferences') && $.getCookiePreference('ssupp'));

                console.log(typeof $.getCookie('cookiePreferences') === 'undefined' && $.getCookie('allowCookie') == 1, $.getCookie('cookiePreferences') && $.getCookiePreference('ssupp'));

                if (chatEnabled) {
                    window._smartsupp = window._smartsupp || {};
                    var _smartsupp = window._smartsupp;
                    $codeChatCode
                }
            }, {$this->loadAsyncDelay});
            </script>";
        }

        $code = str_replace('%key%', static::javascriptEscape($this->key), $code);
        $code = str_replace('%cookie_domain%', static::javascriptEscape($this->cookie_domain), $code);
        $code = str_replace('%ga_key%', static::javascriptEscape($this->ga_key), $code);


        if ($print_out) {
            echo $code;
        } else {
            return $code;
        }
    }
}
