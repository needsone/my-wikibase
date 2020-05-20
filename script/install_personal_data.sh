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

## Download & install new extensions
mkdir extension_to_install
cd  extension_to_install
for extension in Widgets Gadgets MobileFrontend ConfirmEdit Nuke DeleteBatch SpamBlacklist WikibaseQualityConstraints ConfirmAccount SyntaxHighlight_GeSHi CodeMirror Echo CodeEditor VisualEditor
do
  download-extension.sh $extension
done
git clone https://gitlab.com/hydrawiki/extensions/EmbedVideo.git

tar -zxf *.gz
docker cp -a ./* ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/extensions/
rm -r *
cd ..
# end extensions copy

docker cp ./script/install_composer.sh ${WIKI_DOCKER_NAME}_wikibase_1:/tmp/install_composer.sh
docker exec ${WIKI_DOCKER_NAME}_wikibase_1 sh /tmp/install_composer.sh

docker exec ${WIKI_DOCKER_NAME}_wikibase_1  "/bin/cd /var/www/html/extensions/MobileFrontend ; /bin/composer update --no-dev"
docker exec -ti ${WIKI_DOCKER_NAME}_wikibase_1  chmod a+x /var/www/html/extensions/SyntaxHighlight_GeSHi/pygments/pygmentize

docker cp ./LocalSettings.php ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/LocalSettings.php
docker cp config/wiki.png ${WIKI_DOCKER_NAME}_wikibase_1:/var/www/html/resources/assets/wiki.png
docker exec -ti ${WIKI_DOCKER_NAME}_wikibase_1  php maintenance/update.php

docker restart ${WIKI_DOCKER_NAME}_wdqs-frontend_1

docker cp config/qs-oauth.json ${WIKI_DOCKER_NAME}_wikibase_1:/quickstatements/data/qs-oauth.json
docker cp config/oauth.ini ${WIKI_DOCKER_NAME}_wikibase_1:/quickstatements/data/oauth.ini
docker cp config/oauth.ini ${WIKI_DOCKER_NAME}_quickstatements_1:/quickstatements/oauth.ini
docker cp config/prefixes.conf ${WIKI_DOCKER_NAME}_wdqs_1:/wdqs/

docker restart ${WIKI_DOCKER_NAME}_wdqs_1
docker restart ${WIKI_DOCKER_NAME}_wikibase_1
docker restart ${WIKI_DOCKER_NAME}_quickstatements_1

# https://www.mediawiki.org/wiki/Special:ExtensionDistributor/MobileFrontend
# wfLoadExtension( 'MobileFrontend' );
# $wgMFAutodetectMobileView = true;
# wget https://extdist.wmflabs.org/dist/extensions/MobileFrontend-REL1_32-9b48b3c.tar.gz
# tar -xzf MobileFrontend-REL1_32-9b48b3c.tar.gz
# docker cp MobileFrontend wikibase-docker-dev_wikibase_1:/var/www/html/extensions/MobileFrontend
