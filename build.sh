#!/bin/bash

lastTag=$(git tag | tail -n 1)
customTag=$1

if [ "$customTag" != "" ]; then lastTag=$customTag; fi
if [ "$lastTag" = "" ]; then lastTag="master"; fi

rm -f SmartsuppLiveChat-${lastTag}.zip
rm -rf SmartsuppLiveChat
mkdir -p SmartsuppLiveChat
git archive $lastTag | tar -x -C SmartsuppLiveChat

cd SmartsuppLiveChat
composer install --no-dev -n -o
composer dump-autoload -o
cd ../
zip -r SmartsuppLiveChat-${lastTag}.zip SmartsuppLiveChat
rm -r SmartsuppLiveChat