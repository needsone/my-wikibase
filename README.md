
# Installation / Configuration / Production Deployment

## My setting

This documentation explain how I setup my last wikibase configuration. Install, configure and migrate the data.

This documentation is a good start to put a wikibase in production with the docker-compile.yml.

* We have 1 Ip
* A 12G, 1 To HD and a Xeon with 4 cores
* A Debian 9.11
* A Docker 5:19.03.5
* Nginx 1.10.3
* My favorite text editor emacs
* I am also using Portainer to have a view on all docker container, volumes, images network ...
* Hostnames:
 * query.exemple.com (wdqs)
 * qs.exemple.com (QuickStatements)
 * wiki.exemple.com (wiki)
* All SSL certificates i need for those 3 hostnames.
* 1 sql dump of our previous installation and the content or /var/www/html/images on our previous Wikibase installation.

## Basic installation

I am starting by the git clone of all the git folder of Wikibase.

```
git clone https://github.com/wmde/wikibase-docker.git
cd wikibase-docker
## My scripts and configuration files are on this git with script and config
git clone  https://gitlab.com/PDIO-wikibase/wikibase-config.git
cp -r wikibase-config/config ./
cp -r wikibase-config/script ./
mv docker-compose.yml docker-compose.yml-orig
cp config/docker-compose.yml ./
cp config/LocalSettings.php .
```

Now we have to do the setting of docker-compile.yml to match our hostnames and admin basic settings. In LocalSettings.php we need to adjust the mail configuration and the setting for authentication. You can find this file in our git config/docker-compile.yml and config/LocalSettings.php

**For the keys of our authentication between Wikibase and QuickStatements services we need 2 files and an entrance in our db that must have to match**

Our qs-oauth.json and oauth.ini are the config folder and match our SQL dump entrance for the keys.

We are ready for the installation.

```
docker-compose pull
docker-compose up -d
```

We will now adjust the setting with our extensions and our settings.

```
./script/install_personal_data.sh
```

This script install a set of extensions that we are using.

* Widgets
* Gadgets
* Scibunto
* EmbedVideo
* MobileFrontend
* ConfirmEdit
* Nuke
* DeleteBatch
* SpamBlacklist
* ConfirmAccount
* Echo
* CodeEditor
* SyntaxHighlight
* WikibaseQualityConstraints

## Basic query test:

SELECT * WHERE {
  ?s ?p ?o.
}

# Docker compose

You need to learn how to use and understand how docker work.

# nginx configuration

We have 1 Ip and certificates via Let's encrypt.
Our Setting is a proxy from our real network to the port we'll use in our docker configuration.

Our docker network is configure to :
* Provide the wikidata http service on port 8181
* Provide the wdqs service (wdqs-frontend) on port 8282
* Provide the quickstatements service on port 9191

This configuration open 443 with SSL and forward the request to the ports (8181, 8282 and 9191)

Here is the nginx configuration for wdqs.

```
server {
       listen 80;
       server_name query.exemple.com;

       return 301 https://$server_name$request_uri;
       }

server {
       listen 443;
       server_name query.exemple.com;
       ssl on;
       ssl_certificate     /etc/letsencrypt/live/query.exemple.com/fullchain.pem;
       ssl_certificate_key /etc/letsencrypt/live/query.exemple.com/privkey.pem;
       ssl_protocols       TLSv1 TLSv1.1 TLSv1.2;
       ssl_ciphers         HIGH:!aNULL:!MD5;  
       access_log /var/log/nginx/query.exemple.com.log;
       location / {
        proxy_pass http://127.0.0.1:8282;
  	    proxy_set_header Host $host;
		    proxy_set_header X-Real-IP $remote_addr;
		    proxy_set_header X-forwarded-host $host;
		    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
		    proxy_set_header X-Forwarded-Proto $scheme;
      }
}
```
# Backup

```
cd data_backup
docker exec wikibase-docker_mysql_1 mysqldump -u wikiuser -psqlpass my_wiki > backup.sql
docker cp wikibase-docker_mysql_1:backup.sql ./
docker cp wikibase-docker_wikibase_1:/var/www/html/ ./
docker cp
```

# Restoring

**TO TEST**

```
cd data_backup
cat backup.sql | docker exec wikibase-docker_mysql_1 mysql -u wikiuser -psqlpass my_wiki
docker cp -a wikibase-docker-dev_wikibase_1:/var/www/html/ ./
cp -r html wikibase-docker-dev_wikibase_1:/var/www/
```
# New configuration

For Wikibase Repository and Wikibase Client
```
cd extensions/Wikibase
php repo/maintenance/rebuildItemsPerSite.php
php client/maintenance/populateInterwiki.php
```

# Prefix test

```
SELECT ?p ?o WHERE {
   pdio:Q1 ?p ?o.
}
```

# Bugs

### wdqs-updater_1

Sometimes we reach a situation with a problems on date / wikibase / wdqs_pers :

```
$ docker-compose ps | grep Exit
wikibase-docker-dev_wdqs-updater_1  /entrypoint.sh /runUpdate.sh Exit 1
```
The only way to repair that is too reset **EVERYTHING**.

## autocomplete in the query service uses wrong prefix

## guzzlehttp/streams
on _wikibase_1 /var/www/html/composer.json change

```
"guzzlehttp/streams": "^3.0"
```
to
```
"guzzlehttp/streams": "3.0.0"
```

# Useful links

https://www.mediawiki.org/wiki/Wikibase

https://phabricator.wikimedia.org/

and the real Bible :

https://addshore.com/tag/wikibase/

## Update of wikibase configuration
```
docker cp wikibase-docker_wikibase_1:/var/www/html/w/LocalSettings.php ./
emacs LocalSettings.php
docker cp LocalSettings.php wikibase-docker_wikibase_1:/var/www/html/w/
docker exec -ti wikibase-docker_wikibase_1  php maintenance/update.php
```

## wgDebugLogGroups

cp images/LogoPersonal.png /var/www/html/resources/assets/wiki.png
