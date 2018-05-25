<?php

$type = null;
$id = null;
$page = null;
$env = 'prod';
$perPage = get_option('posts_per_page');
$dynamicUrl = 'https://pressr.wp-pwa.com';
$staticUrl = 'https://prestatic.wp-pwa.com';
$inject = false;
$pwa = false;
$exclusion = false;
$dev = 'false';
$break = false;
$prettyPermalinks = get_option('permalink_structure') !== '';
$url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']
 . $_SERVER['REQUEST_URI'];
$initialUrl = $prettyPermalinks ? strtok($url, '?') : $url;
$settings = get_option('wp_pwa_settings');

$pwaStatus = $settings['wp_pwa_status'];
$forceFrontpage = $settings['wp_pwa_force_frontpage'];
$excludes = $settings['wp_pwa_excludes'];

if (($forceFrontpage === true && is_front_page()) || is_home()) {
 $type = 'latest';
 $id = 'post';
 $page = 1;
} elseif (is_page() || is_single()) {
 if (get_queried_object()->post_type !== 'attachment') {
   $type = get_queried_object()->post_type;
   $id = get_queried_object()->ID;
 }
} elseif (is_post_type_archive()) {
 $queriedObject = get_queried_object();
 if ((isset($queriedObject->show_in_rest)) && (isset($queriedObject->rest_base)) &&
 ($queriedObject->show_in_rest === true)) {
   $type = 'latest';
   $id = $queriedObject->name;
   $page = 1;
 }
} elseif (is_category()) {
 $type = 'category';
 $id = get_queried_object()->term_id;
 $page = 1;
} elseif (is_tag()) {
 $type = 'tag';
 $id = get_queried_object()->term_id;
 $page = 1;
} elseif (is_author()) {
 $type = 'author';
 $id = get_queried_object()->ID;
 $page = 1;
}

if (is_paged()) {
 if (is_front_page() && get_option('page_on_front') !== '0') {
   $page = get_query_var('page');
 } else {
   $page = get_query_var('paged');
 }
}

if (isset($_GET['siteId'])) {
 $siteId = $_GET['siteId'];
} elseif (isset($settings['wp_pwa_siteid']) && $settings['wp_pwa_siteid'] !== '' ) {
 $siteId = $settings['wp_pwa_siteid'];
}

if (isset($_GET['env']) && ($_GET['env'] === 'pre' || $_GET['env'] === 'prod')) {
 $env = $_GET['env'];
} elseif (isset($settings['wp_pwa_env'])) {
 $env = $settings['wp_pwa_env'];
}

if (isset($_GET['dynamicUrl'])) {
 $dynamicUrl = $_GET['dynamicUrl'];
} elseif (isset($_GET['server'])) {
 $dynamicUrl = $_GET['server'];
} elseif (isset($settings['wp_pwa_ssr'])) {
 $dynamicUrl = $settings['wp_pwa_ssr'];
}
if (isset($_GET['staticUrl'])) {
 $staticUrl = $_GET['staticUrl'];
} elseif (isset($_GET['server'])) {
 $staticUrl = $_GET['server'];
} elseif (isset($settings['wp_pwa_static'])) {
 $staticUrl = $settings['wp_pwa_static'];
}

if (isset($_GET['pwa']) && $_GET['pwa'] === 'true' ){
 $pwa = true;
}

if (isset($_GET['pwa']) || isset($_GET['server']) || isset($_GET['staticUrl']) ||
 isset($_GET['dynamicUrl']) || isset($_GET['env']) || isset($_GET['siteId'])) {
   $dev = 'true';
 }
if (isset($_GET['dev'])) {
 $dev = $_GET['dev'];
}
if (isset($_GET['break']) && ($_GET['break'] === 'true')) {
 $break = true;
}

if (sizeof($excludes) !== 0 && $pwa === false) {
 foreach ($excludes as $regex) {
   $output = array();
   $regex = str_replace('/', '\/', $regex);
   preg_match('/' . $regex . '/', $url, $output);
   if (sizeof($output) > 0) {
     $exclusion = true;
   }
 }
}

if ($siteId && $type && $id) {
 if ($pwa || ($pwaStatus === 'mobile' && $exclusion === false)) {
   $inject = true;
 }
 if (isset($page) && $page >= 2) {
   $inject = false;
 }
}

$src = $dynamicUrl . '?siteId=' . $siteId
  . '&type=' . $type
  . '&id=' . $id
  . '&dev=' . $dev
  . '&staticUrl=' . rawurlencode($staticUrl)
  . '&dynamicUrl=' . rawurlencode($dynamicUrl)
  . '&env=' . $env
  . '&perPage=' . $perPage
  . '&device=' . $device
  . '&initialUrl=' . rawurlencode($initialUrl);

if ($page) $src = $src . '&page=' . $page;

?>

<iframe width="320" height="640" src="<?php echo $src; ?>"></iframe>
