# Smartsupp plugin - Shopware 6 (IN PROGRESS, DON'T USE)
Official implementation of Smartsupp into Shopware 6. Older version available in v5 branche. 

## Ways to install

### Shopware marketplace installation

Install plugin from [Shopware Smartsupp live chat marketplace page](https://store.shopware.com/en/smart42671339166f/smartsupp-live-chat.html).

For Shopware 5 install plugin from [here](https://store.shopware.com/en/smart42671339166f/smartsupp-live-chat.html).

---

### Manual instalation

1. Download this repo and rename root folder to SuppSmartsuppLiveChat. 
2. Upload it into your plugin folder.
3. Use following commands to install and activate the plugin in Shopware:
 ```
bin/console plugin:refresh
bin/console plugin:install --activate SuppSmartsuppLiveChat
```

## Usage

For the Smartsupp key, log into your Smartsupp account and navigate to Settings > Chat box > Chat code.

The plugin comes pre-configured for the conditional cookie consent build in Shopware. If you plan to use external cookie consent manager, deactivate the Shopware Cookie consent support in plugin settings.

## Copyright

Copyright (c) 2021 [Smartsupp.com](https://www.smartsupp.com/)
