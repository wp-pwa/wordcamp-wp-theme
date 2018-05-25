<?php
/**
 * The main template file
 *
 */

$userAgent = $_SERVER['HTTP_USER_AGENT'];
// $userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_3 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12F70 Safari/600.1.4';
// $userAgent = 'Mozilla/5.0 (Linux; Android 4.2.1; en-us; Nexus 5 Build/JOP40D) AppleWebKit/535.19 (KHTML, like Gecko; googleweblight) Chrome/38.0.1025.166 Mobile Safari/535.19';

$isOldIos = preg_match('/ip(hone|od|ad).*?OS ([1-8]_)/i', $userAgent);
$isOldChrome = preg_match('/chrome\/([1-3]\d\.)/i', $userAgent);

?>

<!DOCTYPE HTML>
<html <?php language_attributes(); ?> class="no-js">
 <head>
 	<?php if (isset($GLOBALS['wp_pwa_path'])) { require(WP_PLUGIN_DIR . $GLOBALS['wp_pwa_path'] .'/injector/wp-pwa-injector.php'); } ?>
 	<meta charset="<?php bloginfo( 'charset' ); ?>">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link rel="profile" href="http://gmpg.org/xfn/11">
 	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
 	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
 	<?php endif; ?>
 	<?php wp_head(); ?>
 </head>
 <body>
   <?php if ($isOldIos === 1 || $isOldChrome === 1) {
    require_once(get_template_directory() . '/browser-too-old.php');
  } elseif ($_COOKIE['wppwaInjectorFailed']) {
    require_once(get_template_directory() . '/loading-failed.php');
  } else {
    require_once(get_template_directory() . '/iframe-view.php');
  } ?>
 </body>
</html>
