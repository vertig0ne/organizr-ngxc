#!/bin/sh

ORGANIZR_PATH=/config/www

echo "Organizr-ngxc Plugin Installer - v0.1"
cp ./api/ngxc.php $ORGANIZR_PATH/api/plugins/api/
cp ./js/ngxc.js $ORGANIZR_PATH/api/plugins/js/
cp ./images/ngxc.png $ORGANIZR_PATH/plugins/images/
cp ./ngxc.php $ORGANIZR_PATH/api/plugins/
echo "Complete"