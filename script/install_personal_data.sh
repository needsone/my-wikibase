#!/bin/sh

#
# This script is ugly I want to use the download-extesion.sh or redo the Dockerfile
# to add extension
#

CWD=`pwd`
WIKI_DOCKER_NAME=`basename $CWD`

echo '
####################################
edit your ./LocalSettings.php before
####################################
'
read OK
wget https://extdist.wmflabs.org/dist/extensions/Widgets-REL1_34-d1271af.tar.gz
wget https://extdist.wmflabs.org/dist/extensions/Scribunto-REL1_34-f7bc2e3.tar.gz
#wget https://extdist.wmflabs.org/dist/extensions/Scribunto-REL1_33-8328acb.tar.gz
wget https://extdist.wmflabs.org/dist/extensions/Gadgets-REL1_34-1cefbf1.tar.gz
#wget https://extdist.wmflabs.org/dist/extensions/Gadgets-REL1_32-fe3c8a7.tar.gz
wget https://gitlab.com/hydrawiki/extensions/EmbedVideo/-/archive/master/EmbedVideo-master.tar.gz
wget https://extdist.wmflabs.org/dist/extensions/MobileFrontend-REL1_34-383273b.tar.gz

#wget https://extdist.wmflabs.org/dist/extensions/MobileFrontend-REL1_33-91eb242.tar.gz

wget https://extdist.wmflabs.org/dist/extensions/ConfirmEdit-REL1_34-45ca059.tar.gz
#wget https://extdist.wmflabs.org/dist/extensions/ConfirmEdit-REL1_33-0e549d7.tar.gz

wget https://extdist.wmflabs.org/dist/extensions/Nuke-REL1_34-27d74b6.tar.gz
#wget https://extdist.wmflabs.org/dist/extensions/Nuke-REL1_33-0b3f8cc.tar.gz

wget https://extdist.wmflabs.org/dist/extensions/DeleteBatch-REL1_34-ced83f8.tar.gz
#wget https://extdist.wmflabs.org/dist/extensions/DeleteBatch-REL1_33-d0382c9.tar.gz

wget https://extdist.wmflabs.org/dist/extensions/SpamBlacklist-REL1_34-9d55ccf.tar.gz
#wget https://extdist.wmflabs.org/dist/extensions/SpamBlacklist-REL1_33-5254448.tar.gz

wget https://extdist.wmflabs.org/dist/extensions/WikibaseQualityConstraints-REL1_34-16d77f9.tar.gz
#wget https://extdist.wmflabs.org/dist/extensions/WikibaseQualityConstraints-REL1_33-819d1c7.tar.gz

wget https://extdist.wmflabs.org/dist/extensions/ConfirmAccount-REL1_34-3ffa446.tar.gz
#wget https://extdist.wmflabs.org/dist/extensions/ConfirmAccount-REL1_33-cacb682.tar.gz

wget https://extdist.wmflabs.org/dist/extensions/SyntaxHighlight_GeSHi-REL1_34-d45d04f.tar.gz
#wget https://extdist.wmflabs.org/dist/extensions/SyntaxHighlight_GeSHi-REL1_33-7b18bb0.tar.gz

wget https://extdist.wmflabs.org/dist/extensions/CodeMirror-REL1_34-81ce8b3.tar.gz
#wget https://extdist.wmflabs.org/dist/extensions/CodeMirror-REL1_33-fc4d4b0.tar.gz

wget https://extdist.wmflabs.org/dist/extensions/Echo-REL1_34-e56e8ac.tar.gz
# wget https://extdist.wmflabs.org/dist/extensions/Echo-REL1_33-f106596.tar.gz

wget https://extdist.wmflabs.org/dist/extensions/CodeEditor-REL1_34-b3fb04b.tar.gz
#wget https://extdist.wmflabs.org/dist/extensions/CodeEditor-REL1_32-1ff0d89.tar.gz

wget https://extdist.wmflabs.org/dist/extensions/VisualEditor-REL1_34-74116a7.tar.gz

docker cp ./script/install_composer.sh ${WIKI_DOCKER_NAME}_wikibase_1:/tmp/install_composer.sh
docker exec ${WIKI_DOCKER_NAME}_wikibase_1 sh /tmp/install_composer.sh

# Widgets installation
#wget https://extdist.wmflabs.org/dist/extensions/Widgets-REL1_34-d1271af.tar.gz
tar -zxf Widgets*.gz
docker cp -a Widgets ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/Widgets
rm -rf Widgets*

# Scibunto installation
#wget https://extdist.wmflabs.org/dist/extensions/Scribunto-REL1_33-8328acb.tar.gz
tar -zxf Scribunto*.tar.gz

docker cp -a Scribunto ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/Scribunto
rm -rf Scribunto*
docker exec -ti ${WIKI_DOCKER_NAME}_wikibase_1 apt-get install lua5.1
docker exec ${WIKI_DOCKER_NAME}_wikibase_1 chmod a+x /var/www/html/extensions/Scribunto/includes/engines/LuaStandalone/binaries/lua5_1_5_linux_64_generic/lua
docker exec ${WIKI_DOCKER_NAME}_wikibase_1 chcon -t httpd_sys_script_exec_t /var/www/html/extensions/Scribunto/includes/engines/LuaStandalone/binaries/lua5_1_5_linux_64_generic/lua

# Gadgets Installation
tar -xzf Gadgets-*.tar.gz
rm -rf Gadgets*
docker cp -a Gadgets ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/Gadgets
docker cp config/LocalSettings.php ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/LocalSettings.php

# Embed Video
tar -xzf EmbedVideo-master.tar.gz
docker cp -a EmbedVideo ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/EmbedVideo
rm -rf EmbedVideo*

# MobileFrontend
tar -xzf MobileFrontend-*.tar.gz
docker cp -a MobileFrontend ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/MobileFrontend
rm -rf MobileFrontend*
docker exec ${WIKI_DOCKER_NAME}_wikibase_1  "/bin/cd /var/www/html/extensions/MobileFrontend ; /bin/composer update --no-dev"

##wget https://extdist.wmflabs.org/dist/extensions/StopForumSpam-REL1_32-74d5445.tar.gz
##tar -zxvf StopForumSpam-REL1_32-74d5445.tar.gz
## I need a API keys I did the request but I still wait for it

tar -zxf ConfirmEdit-*.tar.gz
docker cp -a ConfirmEdit ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/
rm -rf ConfirmEdit*

tar -zxf Nuke-*.tar.gz
docker cp -a Nuke ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/
rm -rf Nuke*

tar -zxf DeleteBatch-*.tar.gz
docker cp -a DeleteBatch ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/
rm -rf DeleteBatch*

tar -zxf SpamBlacklist-*.tar.gz
docker cp -a SpamBlacklist ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/
rm -rf SpamBlacklist*

tar -xzf ConfirmAccount-*.tar.gz
docker cp -a ConfirmAccount ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/
rm -rf ConfirmAccount*

tar -xzf  Echo-*.tar.gz
docker cp -a Echo ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/
rm -rf Echo*

tar -xzf CodeEditor-*.tar.gz
docker cp -a CodeEditor ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/
rm -rf CodeEditor*

tar -xzf CodeMirror-*.tar.gz
docker cp -a CodeMirror ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/
rm -rf CodeMirror*

tar -xzf SyntaxHighlight_*.tar.gz
docker cp -a SyntaxHighlight_GeSHi ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/
rm -r SyntaxHighlight_GeSHi*
docker exec -ti ${WIKI_DOCKER_NAME}_wikibase_1  chmod a+x /var/www/html/extensions/SyntaxHighlight_GeSHi/pygments/pygmentize

tar -xzf WikibaseQualityConstraints-*.tar.gz
docker cp -a WikibaseQualityConstraints ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/
rm -r WikibaseQualityConstraints*

tar -xzf VisualEditor*.tar.gz
docker cp -a VisualEditor ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/
rm -r VisualEditor*

docker cp ./LocalSettings.php ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/LocalSettings.php
docker cp config/wiki.png ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/resources/assets/wiki.png
docker exec -ti ${WIKI_DOCKER_NAME}_wikibase_1  php maintenance/update.php
docker restart ${WIKI_DOCKER_NAME}_wdqs-frontend_1

docker cp config/qs-oauth.json ${WIKI_DOCKER_NAME}_wikibase_1:/quickstatements/data/qs-oauth.json
docker cp config/oauth.ini ${WIKI_DOCKER_NAME}_wikibase_1:/quickstatements/data/oauth.ini

docker cp config/oauth.ini ${WIKI_DOCKER_NAME}_quickstatements_1:/quickstatements/oauth.ini

docker cp config/prefixes.conf ${WIKI_DOCKER_NAME}_wdqs_1:/wdqs/
docker cp config/RdfNamespaces.js ${WIKI_DOCKER_NAME}_wdqs-frontend_1:/usr/share/nginx/html/wikibase/queryService/RdfNamespaces.js

docker restart ${WIKI_DOCKER_NAME}_wdqs_1
docker restart ${WIKI_DOCKER_NAME}_wikibase_1
docker restart ${WIKI_DOCKER_NAME}_quickstatements_1

# https://www.mediawiki.org/wiki/Special:ExtensionDistributor/MobileFrontend
# wfLoadExtension( 'MobileFrontend' );
# $wgMFAutodetectMobileView = true;
# wget https://extdist.wmflabs.org/dist/extensions/MobileFrontend-REL1_32-9b48b3c.tar.gz
# tar -xzf MobileFrontend-REL1_32-9b48b3c.tar.gz
# docker cp MobileFrontend wikibase-docker-dev_wikibase_1:/var/www/html/extensions/MobileFrontend
