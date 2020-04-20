<?php
/**
 * ----------------------------------------------------------------------------------------
 * This file is provided by the wikibase/wikibase docker image.
 * This file will be passed through envsubst which will replace "$" with "$".
 * If you want to change MediaWiki or Wikibase settings then either mount a file over this
 * template and or run a different entrypoint.
 * ----------------------------------------------------------------------------------------
 */
## Database settings
## Environment variables will be substituted in here.
$wgDBserver = "mysql.svc:3306";
$wgDBname = "my_wiki";
$wgDBuser = "wikiuser";
$wgDBpassword = "sqlpass";

## Logs
## Save these logs inside the container
$wgDebugLogGroups = array(
	'resourceloader' => '/var/log/mediawiki/resourceloader.log',
	'exception' => '/var/log/mediawiki/exception.log',
	'error' => '/var/log/mediawiki/error.log',
);

## Site Settings
# TODO pass in the rest of this with env vars?
$wgShellLocale = "en_US.utf8";
$wgLanguageCode = "en";
$wgSitename = "Wikibase SuperMegaYukulele data";
$wgMetaNamespace = "Project";

# Configured web paths & short URLs
# This allows use of the /wiki path
## https://www.mediawiki.org/wiki/Manual:Short_URL

$wgScriptPath = "/w";        // this should already have been configured this way
$wgArticlePath = "/wiki/$1";

#Set Secret
$wgSecretKey = "secretkey";

## RC Age
# https://www.mediawiki.org/wiki/Manual:
# Items in the recentchanges table are periodically purged; entries older than this many seconds will go.
# The query service (by default) loads data from recent changes
# Set this to 1 year to avoid any changes being removed from the RC table over a shorter period of time.
$wgRCMaxAge = 365 * 24 * 3600;

wfLoadSkin( 'Vector' );

## Wikibase
# Load Wikibase repo, client & lib with the example / default settings.
require_once "$IP/extensions/Wikibase/vendor/autoload.php";
require_once "$IP/extensions/Wikibase/lib/WikibaseLib.php";
require_once "$IP/extensions/Wikibase/repo/Wikibase.php";
require_once "$IP/extensions/Wikibase/repo/ExampleSettings.php";
require_once "$IP/extensions/Wikibase/client/WikibaseClient.php";
require_once "$IP/extensions/Wikibase/client/ExampleSettings.php";

# OAuth
wfLoadExtension( 'OAuth' );
$wgGroupPermissions['sysop']['mwoauthproposeconsumer'] = true;
$wgGroupPermissions['sysop']['mwoauthmanageconsumer'] = true;
$wgGroupPermissions['sysop']['mwoauthviewprivate'] = true;
$wgGroupPermissions['sysop']['mwoauthupdateownconsumer'] = true;

# WikibaseImport
require_once "$IP/extensions/WikibaseImport/WikibaseImport.php";

# CirrusSearch
wfLoadExtension( 'Elastica' );
require_once "$IP/extensions/CirrusSearch/CirrusSearch.php";
$wgCirrusSearchServers = [ 'elasticsearch.svc' ];
$wgSearchType = 'CirrusSearch';
$wgCirrusSearchExtraIndexSettings['index.mapping.total_fields.limit'] = 5000;

# UniversalLanguageSelector
wfLoadExtension( 'UniversalLanguageSelector' );

# cldr
wfLoadExtension( 'cldr' );

# item-term property-term createtalk item-redirect createaccount property-create item-merge writeapi

$wgGroupPermissions['*']['read'] = true;

$wgGroupPermissions['*']['createpage'] = false;
$wgGroupPermissions['user']['createpage'] = true;
$wgGroupPermissions['sysop']['createpage'] = true;
$wgGroupPermissions['editors']['createpage'] = true;

$wgGroupPermissions['*']['edit'] = false;
$wgGroupPermissions['user']['edit'] = true;
$wgGroupPermissions['sysop']['edit'] = true;
$wgGroupPermissions['editors']['edit'] = true;

$wgGroupPermissions['*']['item-term'] = false;
$wgGroupPermissions['user']['item-term'] = true;
$wgGroupPermissions['sysop']['item-term'] = true;
$wgGroupPermissions['editors']['item-term'] = true;

$wgGroupPermissions['*']['item-redirect'] = false;
$wgGroupPermissions['user']['item-redirect'] =  true;
$wgGroupPermissions['sysop']['item-redirect'] = true;
$wgGroupPermissions['editors']['item-redirect'] = true;

$wgGroupPermissions['*']['property-term'] = false;
$wgGroupPermissions['user']['property-term'] =  true;
$wgGroupPermissions['sysop']['property-term'] = true;
$wgGroupPermissions['editors']['property-term'] = true;

$wgGroupPermissions['*']['createtalk'] = false;
$wgGroupPermissions['user']['createtalk'] =  true;
$wgGroupPermissions['sysop']['createtalk'] = true;
$wgGroupPermissions['editors']['createtalk'] = true;

$wgGroupPermissions['*']['createtalk'] = false;
$wgGroupPermissions['user']['createtalk'] = true;
$wgGroupPermissions['sysop']['createtalk'] = true;
$wgGroupPermissions['editors']['createtalk'] = true;

$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['user']['createaccount'] = false;
$wgGroupPermissions['sysop']['createaccount'] = true;
$wgGroupPermissions['editors']['createaccount'] = false;

$wgGroupPermissions['*']['property-create'] = false;
$wgGroupPermissions['user']['property-create'] = true;
$wgGroupPermissions['sysop']['property-create'] = true;
$wgGroupPermissions['editors']['property-create'] = true;

$wgGroupPermissions['*']['item-merge'] = false;
$wgGroupPermissions['user']['item-merge'] = true;
$wgGroupPermissions['sysop']['item-merge'] = true;
$wgGroupPermissions['editors']['item-merge'] = true;

#$wgGroupPermissions['emailconfirmed']['skipcaptcha'] = true;
$ceAllowConfirmedEmail = true;

$wgPasswordPolicy['policies']['default']['MinimalPasswordLength'] = 10;
$wgPasswordPolicy['policies']['default']['MaximalPasswordLength'] = 128;
#$wgPasswordPolicy['policies']['default']['PasswordNotInLargeBlacklist'] = true;
$wgPasswordPolicy['policies']['default']['PasswordCannotMatchUsername'] = true;

$wgShowDBErrorBacktrace = true;
$wgShowExceptionDetails = true;

$wgSMTP = [
    'host' => 'ssl://smtp.gmail.com',
    'IDHost' => 'gmail.com',
    'localhost' => 'wiki.exemple.com',
    'port' => 465,
    'username' => 'Superme@gmail.com',
    'password' => 'SuperMegaYukulele',
    'auth' => true
];

require_once "$IP/extensions/Widgets/Widgets.php";

wfLoadExtension( 'WikiEditor' );
$wgHiddenPrefs[] = 'usebetatoolbar';

wfLoadExtension( 'ParserFunctions' );
$wgPFEnableStringFunctions = true;

# where lua is the name of the binary file
# e.g. sourceforge LuaBinaries 5.1.5 - Release 2 name the binary file lua5.1

$wgScribuntoEngineConf['luastandalone']['luaPath'] = "/usr/bin/lua5.1";

wfLoadExtension('Scribunto');

$wgScribuntoDefaultEngine = 'luastandalone';
$wgScribuntoUseGeSHi = true;
$wgScribuntoUseCodeEditor = true;

wfLoadExtension( 'Gadgets' );
wfLoadExtension( 'EmbedVideo' );

$wgAllowUserJs = true;

wfLoadExtension( 'MobileFrontend' );
$wgMFAutodetectMobileView = true;
$wgMFDefaultSkinClass = 'SkinVector';

$wgMFNearbyEndpoint = 'http://en.m.wikipedia.org/w/api.php';
$wgMFNearby = true;

$wgWBRepoSettings['formatterUrlProperty'] = 'P49';

wfLoadExtension( 'ConfirmEdit' );
wfLoadExtensions([ 'ConfirmEdit', 'ConfirmEdit/ReCaptchaNoCaptcha' ]);

$wgCaptchaClass = 'ReCaptchaNoCaptcha';
$wgReCaptchaSiteKey = 'XXXXXXXX';
$wgReCaptchaSecretKey = 'XXXXXXXX';

$wgMainCacheType    = CACHE_ANYTHING;

$wgCaptchaTriggers['edit']          = false;
$wgCaptchaTriggers['create']        = false;
$wgCaptchaTriggers['createtalk']    = true;
$wgCaptchaTriggers['addurl']        = false;
$wgCaptchaTriggers['createaccount'] = true;
$wgCaptchaTriggers['badlogin']      = true;
$wgCaptchaTriggers['login']      = true;

$wgGroupPermissions['*'            ]['skipcaptcha'] = false;
$wgGroupPermissions['user'         ]['skipcaptcha'] = false;
$wgGroupPermissions['autoconfirmed']['skipcaptcha'] = false;
$wgGroupPermissions['bot'          ]['skipcaptcha'] = true; // registered bots
$wgGroupPermissions['sysop'        ]['skipcaptcha'] = true;
$wgGroupPermissions['editors'        ]['skipcaptcha'] = true;

wfLoadExtension( 'Nuke' );
$wgGroupPermissions['sysop']['nuke'] = true;
$wgGroupPermissions['nuke']['nuke'] = true;

wfLoadExtension( 'DeleteBatch' );
$wgGroupPermissions['bureaucrat']['deletebatch'] = true;
$wgGroupPermissions['sysop']['deletebatch'] = true;

$wgEnableUploads = true; # Enable uploads
$wgGroupPermissions['user']['upload'] = true;
$wgGroupPermissions['editors']['upload'] = true;

# ConfirmAccount
require_once "$IP/extensions/ConfirmAccount/ConfirmAccount.php";

$wgMakeUserPageFromBio = false;
$wgAutoWelcomeNewUsers = false;

$wgConfirmAccountRequestFormItems = array(
 'UserName'        => array( 'enabled' => true ),
 'RealName'        => array( 'enabled' => false ),
 'Biography'       => array( 'enabled' => false, 'minWords' => 5 ),
 'AreasOfInterest' => array( 'enabled' => false ),
 'CV'              => array( 'enabled' => false ),
 'Notes'           => array( 'enabled' => false ),
 'Links'           => array( 'enabled' => false ),
 'TermsOfService'  => array( 'enabled' => false ),
);

$wgConfirmAccountContact = 'louis@yourneeds.ch';

wfLoadExtension( 'Echo' );

wfLoadExtension( 'CodeEditor' );
$wgDefaultUserOptions['usebetatoolbar'] = 1; // user option provided by WikiEditor extension

wfLoadExtension( 'CodeMirror' );

wfLoadExtension( 'SyntaxHighlight_GeSHi' );

wfLoadExtension( 'WikibaseQualityConstraints' );
