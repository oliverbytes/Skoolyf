<?php 

defined('DS') ? null : 				define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : 		define('SITE_ROOT'		, DS.'xampp'.DS.'htdocs'.DS.'skoolyf');
//defined('SITE_ROOT') ? null : 		define('SITE_ROOT', DS.'Applications'.DS.'XAMPP'.DS.'xamppfiles'.DS.'htdocs'.DS.'skoolyf');
defined('DB_SERVER') ? null : 		define("DB_SERVER"		, "localhost");
defined('DB_NAME') ? null : 		define("DB_NAME"		, "nemory_skoolyf");
defined('DB_USER') ? null : 		define("DB_USER"		, "root");
defined('DB_PASS') ? null : 		define("DB_PASS"		, "");
defined('HOSTNAME') ? null : 		define("HOSTNAME"		, "http://localhost/skoolyf/");

// defined('SITE_ROOT') ? null : 		define('SITE_ROOT'		, DS.'home'.DS.'nemory'.DS.'public_html'.DS.'skoolyf.com');
// defined('DB_SERVER') ? null : 		define("DB_SERVER"		, "localhost");
// defined('DB_NAME') ? null : 		define("DB_NAME"		, "nemory_skoolyf");
// defined('DB_USER') ? null : 		define("DB_USER"		, "nemory_skoolyf");
// defined('DB_PASS') ? null : 		define("DB_PASS"		, "DhjkLmnOP2{}");
// defined('HOSTNAME') ? null : 		define("HOSTNAME"		, "http://skoolyf.com/");

// defined('SITE_ROOT') ? null : 		define('SITE_ROOT'		, DS.'home'.DS.'wwwkelly'.DS.'public_html'.DS.'skoolyf');
// defined('DB_SERVER') ? null : 		define("DB_SERVER"		, "localhost");
// defined('DB_NAME') ? null : 		define("DB_NAME"		, "wwwkelly_skoolyf");
// defined('DB_USER') ? null : 		define("DB_USER"		, "wwwkelly_user");
// defined('DB_PASS') ? null : 		define("DB_PASS"		, "DhjkLmnOP2{}");
// defined('HOSTNAME') ? null : 		define("HOSTNAME"		, "http://skoolyf.kellyescape.com/");

defined('INCLUDES_PATH') ? null : 	define('INCLUDES_PATH', SITE_ROOT.DS.'includes');
defined('PUBLIC_PATH') ? null : 	define('PUBLIC_PATH', SITE_ROOT.DS.'public');
defined('CLASSES_PATH') ? null : 	define('CLASSES_PATH', INCLUDES_PATH.DS.'classes');

defined('PHP_MAILER') ? null : 		define('PHP_MAILER', INCLUDES_PATH.DS.'PHPMailer');
defined('FACEBOOK_PHP_SDK') ? null :define('FACEBOOK_PHP_SDK', INCLUDES_PATH.DS.'facebook-php-sdk'.DS.'src');
defined('RECAPTCHA_SDK') ? null :	define('RECAPTCHA_SDK', INCLUDES_PATH.DS.'recaptcha-php-1.11');
defined('PDFGENERATOR') ? null : 	define('PDFGENERATOR', INCLUDES_PATH.DS.'pdfgenerator');

// HELPERS
require_once(INCLUDES_PATH.DS."config.php");
require_once(INCLUDES_PATH.DS."functions.php");

// CORE PHPS
require_once(CLASSES_PATH.DS."database.php");
require_once(CLASSES_PATH.DS."database_object.php");
require_once(CLASSES_PATH.DS."session.php");

// OBJECT PHPS
require_once(CLASSES_PATH.DS."user.php");
require_once(CLASSES_PATH.DS."superadmin.php");
require_once(CLASSES_PATH.DS."school.php");
require_once(CLASSES_PATH.DS."schooluser.php");
require_once(CLASSES_PATH.DS."batch.php");
require_once(CLASSES_PATH.DS."batchuser.php");
require_once(CLASSES_PATH.DS."section.php");
require_once(CLASSES_PATH.DS."sectionuser.php");
require_once(CLASSES_PATH.DS."comment.php");
require_once(CLASSES_PATH.DS."friend.php");
require_once(CLASSES_PATH.DS."status.php");
require_once(CLASSES_PATH.DS."notification.php");
require_once(CLASSES_PATH.DS."log.php");
require_once(CLASSES_PATH.DS."club.php");
require_once(CLASSES_PATH.DS."group.php");
require_once(CLASSES_PATH.DS."clubuser.php");
require_once(CLASSES_PATH.DS."groupuser.php");
require_once(CLASSES_PATH.DS."achievement.php");
require_once(CLASSES_PATH.DS."job.php");
require_once(CLASSES_PATH.DS."picture.php");
require_once(CLASSES_PATH.DS."hit.php");
require_once(CLASSES_PATH.DS."file.php");

// PHP MAILER
require_once(PHP_MAILER.DS."class.phpmailer.php");
require_once(PHP_MAILER.DS."class.smtp.php");

// FACEBOOK PHP SDK
require_once(FACEBOOK_PHP_SDK.DS."facebook.php");
require_once(RECAPTCHA_SDK.DS."recaptchalib.php");

// PDF GENERATOR
require_once(PDFGENERATOR.DS."phpToPDF.php");

$clientip = $_SERVER['REMOTE_ADDR'];

?>