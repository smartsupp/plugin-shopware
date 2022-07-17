# Smartsupp plugin - Shopware 6
Official implementation of Smartsupp into Shopware 6. Older version available in v5 branche. 

## Ways to install

### Shopware marketplace installation

Install plugin from [Shopware Smartsupp live chat marketplace page](https://store.shopware.com/en/smart40678717991f/smartsupp-live-chat-chatbots-video-recordings.html).

For Shopware 5 install plugin from [here](https://store.shopware.com/en/smart42671339166f/smartsupp-live-chat-chatbots-video-recordings.html).

---

### Manual installation

1. Download this repo and rename root folder to SmartsuppChat. 
2. Upload it into your plugin folder.
3. Use following commands to install and activate the plugin in Shopware:
 ```
bin/console plugin:refresh
bin/console plugin:install --activate SmartsuppChat
```

## Usage

For the Smartsupp key, sign up to Smartsupp to create your own account. Your Smartsupp key can be found in Smartsupp > Settings > Chat box > Chat code.

The plugin comes pre-configured for the conditional cookie consent build in Shopware 6. If you plan to use external cookie consent manager, deactivate the Shopware Cookie consent support in plugin settings and read our [cookie consent tutorial](https://www.smartsupp.com/help/cookie-consent/) with a Cookiebot example.

## Copyright

Copyright (c) 2022 [Smartsupp.com](https://www.smartsupp.com/)
