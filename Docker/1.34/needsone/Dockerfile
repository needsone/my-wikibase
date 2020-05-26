FROM ubuntu:xenial as fetcher

RUN apt-get update && apt-get install --yes --no-install-recommends unzip=6.* jq=1.* curl=7.* ca-certificates=201* && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY download-extension.sh .
ADD https://github.com/wikidata/WikibaseImport/archive/master.tar.gz /WikibaseImport.tar.gz
RUN bash download-extension.sh OAuth;\
bash download-extension.sh Elastica;\
bash download-extension.sh CirrusSearch;\
bash download-extension.sh WikibaseCirrusSearch;\
bash download-extension.sh UniversalLanguageSelector;\
bash download-extension.sh cldr;\
bash download-extension.sh EntitySchema;\
bash download-extension.sh Widgets;\
bash download-extension.sh Scribunto;\
bash download-extension.sh Gadgets;\
bash download-extension.sh MobileFrontend;\
bash download-extension.sh ConfirmEdit;\
bash download-extension.sh Nuke;\
bash download-extension.sh DeleteBatch;\
bash download-extension.sh SpamBlacklist;\
bash download-extension.sh WikibaseQualityConstraints;\
bash download-extension.sh SyntaxHighlight_GeSHi;\
bash download-extension.sh CodeMirror;\
bash download-extension.sh Echo;\
bash download-extension.sh CodeEditor;\
tar xzf OAuth.tar.gz;\
tar xzf Elastica.tar.gz;\
tar xzf CirrusSearch.tar.gz;\
tar xzf WikibaseCirrusSearch.tar.gz;\
tar xzf UniversalLanguageSelector.tar.gz;\
tar xzf cldr.tar.gz;\
tar xzf WikibaseImport.tar.gz;\
tar xzf EntitySchema.tar.gz;\
tar xzf Widgets.tar.gz;\
tar xzf Scribunto.tar.gz;\
tar xzf Gadgets.tar.gz;\
tar xzf MobileFrontend.tar.gz;\
tar xzf ConfirmEdit.tar.gz;\
tar xzf Nuke.tar.gz;\
tar xzf DeleteBatch.tar.gz;\
tar xzf SpamBlacklist.tar.gz;\
tar xzf WikibaseQualityConstraints.tar.gz;\
tar xzf SyntaxHighlight_GeSHi.tar.gz;\
tar xzf CodeMirror.tar.gz;\
tar xzf Echo.tar.gz;\
tar xzf CodeEditor.tar.gz;\
rm ./*.tar.gz

FROM needswiki:base as collector
COPY --from=fetcher /WikibaseImport-master /var/www/html/extensions/WikibaseImport
COPY --from=fetcher /Elastica /var/www/html/extensions/Elastica
COPY --from=fetcher /OAuth /var/www/html/extensions/OAuth
COPY --from=fetcher /CirrusSearch /var/www/html/extensions/CirrusSearch
COPY --from=fetcher /WikibaseCirrusSearch /var/www/html/extensions/WikibaseCirrusSearch
COPY --from=fetcher /UniversalLanguageSelector /var/www/html/extensions/UniversalLanguageSelector
COPY --from=fetcher /cldr /var/www/html/extensions/cldr
COPY --from=fetcher /EntitySchema /var/www/html/extensions/EntitySchema
COPY --from=fetcher /Widgets /var/www/html/extensions/Widgets
COPY --from=fetcher /Scribunto /var/www/html/extensions/Scribunto
COPY --from=fetcher /Gadgets /var/www/html/extensions/Gadgets
COPY --from=fetcher /MobileFrontend /var/www/html/extensions/EntitySchema
COPY --from=fetcher /ConfirmEdit /var/www/html/extensions/ConfirmEdit
COPY --from=fetcher /Nuke /var/www/html/extensions/Nuke
COPY --from=fetcher /DeleteBatch /var/www/html/extensions/DeleteBatch
COPY --from=fetcher /SpamBlacklist /var/www/html/extensions/SpamBlacklist
COPY --from=fetcher /WikibaseQualityConstraints /var/www/html/extensions/WikibaseQualityConstraints
COPY --from=fetcher /SyntaxHighlight_GeSHi /var/www/html/extensions/SyntaxHighlight_GeSHi
COPY --from=fetcher /CodeMirror /var/www/html/extensions/CodeMirror
COPY --from=fetcher /Echo /var/www/html/extensions/Echo
COPY --from=fetcher /CodeEditor /var/www/html/extensions/CodeEditor

FROM composer as composer
COPY --from=collector /var/www/html /var/www/html
WORKDIR /var/www/html/
RUN rm /var/www/html/composer.lock
RUN composer install --no-dev

FROM wikibase/wikibase:1.34

#RUN apt-get update && \
#    apt-get install --yes --no-install-recommends jq=1.* && \
#    apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer /var/www/html /var/www/html
COPY LocalSettings.php.wikibase-bundle.template /LocalSettings.php.wikibase-bundle.template
COPY extra-install.sh /
COPY extra-entrypoint-run-first.sh /
RUN cat /LocalSettings.php.wikibase-bundle.template >> /LocalSettings.php.template && rm /LocalSettings.php.wikibase-bundle.template
COPY oauth.ini /templates/oauth.ini
