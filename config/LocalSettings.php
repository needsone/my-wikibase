<?php
/**
 * ----------------------------------------------------------------------------------------
 * ThisOB file is provided by the wikibase/wikibase docker image.
 * This file will be passed through envsubst which will replace "$" with "$".
 * If you want to change MediaWiki or Wikibase settings then either mount a file over this
 * template and or run a different entrypoint.
 * ----------------------------------------------------------------------------------------
 */

$wgServer="https://wiki.personaldata.io";

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
$wgSitename = "Wikibase Personal data";
$wgMetaNamespace = "Project";

# Configured web paths & short URLs
# This allows use of the /wiki/* path
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

$wgGroupPermissions['ninja']['deletelogentry'] = true;
$wgGroupPermissions['ninja']['deleterevision'] = true;

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

$wgPasswordPolicy['policies']['default']['MinimalPasswordLength'] = 6;
$wgPasswordPolicy['policies']['default']['MaximalPasswordLength'] = 128;
#$wgPasswordPolicy['policies']['default']['PasswordNotInLargeBlacklist'] = true;
$wgPasswordPolicy['policies']['default']['PasswordCannotMatchUsername'] = true;

$wgShowDBErrorBacktrace = true;
$wgShowExceptionDetails = true;

#$wgSMTP = array(
# 'host' => 'mail.infomaniak.com',
# 'IDHost' => 'yourneeds.ch',
# 'port' => 587,
# 'username' => 'perso@yourneeds.ch',
# 'password' => 'B9pCI34SQuHs',
# 'auth' => true
# );

$wgSMTP = [
    'host' => 'ssl://smtp.gmail.com',
    'IDHost' => 'gmail.com',
    'localhost' => 'wiki.yourneeds.ch',
    'port' => 465,
    'username' => '42@gmail.com',
    'password' => '42	',
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

#$wgLogo = "/var/www/html/resources/assets/LogoPersonal_web.png";

$wgWBRepoSettings['formatterUrlProperty'] = 'P49';

#wfLoadExtension( 'ConfirmEdit' );
#wfLoadExtensions([ 'ConfirmEdit', 'ConfirmEdit/ReCaptchaNoCaptcha' ]);

#$wgCaptchaClass = 'ReCaptchaNoCaptcha';
#$wgReCaptchaSiteKey = '6Lc0yaoUAAAAADSzyXSzevfitfd1NxJUUHGGVRHW';
#$wgReCaptchaSecretKey = '6Lc0yaoUAAAAAKz8IZYivr8AuIZu47z6G_iniBE2';
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
$wgGroupPermissions['sysop'        ]['	skipcaptcha'] = true;
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

$wgUsePrivateIPs = true;
#$wgSquidServers = array('83.166.154.137','172.18.0.1');
#$wgUseSquid = true;

$wgSquidServersNoPurge = array('83.166.154.137','172.18.0.1');

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

$wgAccountRequestThrottle = 42;

$wgConfirmAccountContact = 'confirmation@personaldata.io';

wfLoadExtension( 'Echo' );

wfLoadExtension( 'CodeEditor' );

wfLoadExtension( 'CodeMirror' );
$wgDefaultUserOptions['usecodemirror'] = 1;

wfLoadExtension( 'SyntaxHighlight_GeSHi' );

wfLoadExtension( 'WikibaseQualityConstraints' );

#$wgUseCdn = true;
#$wgCdnServersNoPurge = [];
#$wgCdnServersNoPurge[] = "172.18.0.0/24";


$wgWBRepoSettings['statementSections'] = [
        'item' => [
                'statements' => null,
                'identifiers' => [
                        'type' => 'dataType',
                        'dataTypes' => [ 'external-id' ],
                ],
        ],
        'property' => [
                'statements' => null,
                'constraints' => [
                        'type' => 'propertySet',
                        'propertyIds' => [ 'P300' ]
                ]
        ]
];

$wgWBQualityConstraintsInstanceOfId = 'P3';
$wgWBQualityConstraintsSubclassOfId = 'P4';
$wgWBQualityConstraintsPropertyConstraintId = 'P300';
$wgWBQualityConstraintsExceptionToConstraintId = 'P301';
$wgWBQualityConstraintsConstraintStatusId = 'P302';
$wgWBQualityConstraintsMandatoryConstraintId = 'Q3844';
$wgWBQualityConstraintsSuggestionConstraintId = 'Q3845';
$wgWBQualityConstraintsDistinctValuesConstraintId = 'Q3846';
$wgWBQualityConstraintsMultiValueConstraintId = 'Q3847';
$wgWBQualityConstraintsUsedAsQualifierConstraintId = 'Q3848';
$wgWBQualityConstraintsSingleValueConstraintId = 'Q3849';
$wgWBQualityConstraintsSymmetricConstraintId = 'Q3850';
$wgWBQualityConstraintsTypeConstraintId = 'Q3851';
$wgWBQualityConstraintsValueTypeConstraintId = 'Q3852';
$wgWBQualityConstraintsInverseConstraintId = 'Q3853';
$wgWBQualityConstraintsItemRequiresClaimConstraintId = 'Q3854';
$wgWBQualityConstraintsValueRequiresClaimConstraintId = 'Q3855';
$wgWBQualityConstraintsConflictsWithConstraintId = 'Q3856';
$wgWBQualityConstraintsOneOfConstraintId = 'Q3857';
$wgWBQualityConstraintsMandatoryQualifierConstraintId = 'Q3858';
$wgWBQualityConstraintsAllowedQualifiersConstraintId = 'Q3859';
$wgWBQualityConstraintsRangeConstraintId = 'Q3860';
$wgWBQualityConstraintsDifferenceWithinRangeConstraintId = 'Q3861';
$wgWBQualityConstraintsCommonsLinkConstraintId = 'Q3862';
$wgWBQualityConstraintsContemporaryConstraintId = 'Q3863';
$wgWBQualityConstraintsFormatConstraintId = 'Q3864';
$wgWBQualityConstraintsUsedForValuesOnlyConstraintId = 'Q3865';
$wgWBQualityConstraintsUsedAsReferenceConstraintId = 'Q3866';
$wgWBQualityConstraintsNoBoundsConstraintId = 'Q3867';
$wgWBQualityConstraintsAllowedUnitsConstraintId = 'Q3868';
$wgWBQualityConstraintsSingleBestValueConstraintId = 'Q3869';
$wgWBQualityConstraintsAllowedEntityTypesConstraintId = 'Q3870';
$wgWBQualityConstraintsCitationNeededConstraintId = 'Q3871';
$wgWBQualityConstraintsPropertyScopeConstraintId = 'Q3872';
$wgWBQualityConstraintsClassId = 'P29';
$wgWBQualityConstraintsRelationId = 'P304';
$wgWBQualityConstraintsInstanceOfRelationId = 'Q3873';
$wgWBQualityConstraintsSubclassOfRelationId = 'Q3874';
$wgWBQualityConstraintsInstanceOrSubclassOfRelationId = 'Q3875';
$wgWBQualityConstraintsPropertyId = 'P30';
$wgWBQualityConstraintsQualifierOfPropertyConstraintId = 'P306';
$wgWBQualityConstraintsMinimumQuantityId = 'P307';
$wgWBQualityConstraintsMaximumQuantityId = 'P308';
$wgWBQualityConstraintsMinimumDateId = 'P309';
$wgWBQualityConstraintsMaximumDateId = 'P310';
$wgWBQualityConstraintsNamespaceId = 'P311';
$wgWBQualityConstraintsFormatAsARegularExpressionId = 'P312';
$wgWBQualityConstraintsSyntaxClarificationId = 'P313';
$wgWBQualityConstraintsConstraintScopeId = 'P314';
$wgWBQualityConstraintsSeparatorId = 'P315';
$wgWBQualityConstraintsConstraintCheckedOnMainValueId = 'Q3876';
$wgWBQualityConstraintsConstraintCheckedOnQualifiersId = 'Q3877';
$wgWBQualityConstraintsConstraintCheckedOnReferencesId = 'Q3878';
$wgWBQualityConstraintsNoneOfConstraintId = 'Q3879';
$wgWBQualityConstraintsIntegerConstraintId = 'Q3880';
$wgWBQualityConstraintsWikibaseItemId = 'Q3881';
$wgWBQualityConstraintsWikibasePropertyId = 'Q3882';
$wgWBQualityConstraintsWikibaseLexemeId = 'Q3883';
$wgWBQualityConstraintsWikibaseFormId = 'Q3884';
$wgWBQualityConstraintsWikibaseSenseId = 'Q3885';
$wgWBQualityConstraintsWikibaseMediaInfoId = 'Q3886';
$wgWBQualityConstraintsPropertyScopeId = 'P316';
$wgWBQualityConstraintsAsMainValueId = 'Q3887';
$wgWBQualityConstraintsAsQualifiersId = 'Q3888';
$wgWBQualityConstraintsAsReferencesId = 'Q3889';
