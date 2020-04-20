#!/bin/bash

##
# This backup script erase data of 14 days off
##

FOLDER=`date +%Y%m%d%H%M%S`
FOLDER_OLD=`date +%Y%m%d --date="14 days ago"`

rm -r $FOLDER_OLD

cd /folder_for_your_backup

mkdir $FOLDER
cd  $FOLDER

# sudo -u louis docker cp -a wikibase-docker_wikibase_1:/var/www ./ && tar -czf www.tgz www
docker cp -a wikibase-docker_wikibase_1:/quickstatements/data/qs-oauth.json ./
docker cp -a wikibase-docker_wdqs_1:/wdqs/data/data.jnl ./
docker exec wikibase-docker_mysql_1 mysqldump -u wikiuser -psqlpass my_wiki > db.sql

docker exec wikibase-docker_wikibase_1 rm /tmp/www_images.tgz
docker exec wikibase-docker_wikibase_1 tar -zcvf /tmp/www_images.tgz /var/www/html/images
docker exec wikibase-docker_wikibase_1 cat /tmp/www_images.tgz >  ./www_images.tgz

cd ..
tar -zcf  $FOLDER.tgz $FOLDER
