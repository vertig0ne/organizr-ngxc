##!/usr/bin/with-contenv bash

if [ ! -z "$ORGANIZR_PATH" ]; then
    ORGANIZR_PATH=/config/www
fi

echo "Organizr-ngxc Plugin Installer - v0.1"
mkdir -p '${ORGANIZR_PATH}/api/plugins/ngxc'
cp -R ngxc/* ${ORGANIZR_PATH}/api/plugins/ngxc
cp api/ngxc.php ${ORGANIZR_PATH}/api/plguins/api/ngxc.php
cp config/ngxc.php ${ORGANIZR_PATH}/api/plguins/config/ngxc.php
cp js/ngxc.js ${ORGANIZR_PATH}/api/plguins/js/ngxc.js
cp images/ngxc.png ${ORGANIZR_PATH}/plugins/images/ngxc.png
cp ngxc.php ${ORGANIZR_PATH}/api/plguins/ngxc.php