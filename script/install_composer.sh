#!/bin/sh

apt-get update
apt-get --yes install unzip
cd /tmp/
curl -q https://getcomposer.org/installer > composer-setup.php
php composer-setup.php --quiet
rm composer-setup.php
mv  /tmp/composer.phar  /bin/composer
chmod +x /bin/composer

