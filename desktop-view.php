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
<div class="desktop-view">
  <div class="mobile">
    <svg width="416px" height="746px" viewBox="0 0 416 746" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <defs>
        <rect id="path-1" x="28" y="0" width="360" height="745.714286" rx="40"></rect>
        <rect id="path-2" x="0" y="0" width="334.279999" height="591.428571" rx="3"></rect>
      </defs>
      <g id="Desktop" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Desktop:-Welcome" transform="translate(-416.000000, -227.000000)">
          <g id="Phone" transform="translate(416.000000, 227.000000)">
            <rect id="Rectangle-2" fill-opacity="0.5" fill="#FFFFFF" x="0" y="13" width="416" height="720"></rect>
            <g id="outline">
              <use fill-opacity="0.4" fill="#E9E9E6" fill-rule="evenodd" xlink:href="#path-1"></use>
              <rect stroke="#E9E9E6" stroke-width="3" x="29.5" y="1.5" width="357" height="742.714286" rx="40"></rect>
            </g>
              <circle id="camera" stroke="#E9E9E6" stroke-width="2" cx="208" cy="36" r="4.14285714"></circle>
              <circle id="button" stroke="#E9E9E6" stroke-width="2" cx="208" cy="703.285714" r="22.1428571"></circle>
            <g id="screen">
              <foreignObject x="40" y="72" width="335" height="592">
                <iframe class="frontity" width="335" height="592" src="<?php echo $src; ?>"></iframe>
              </foreignObject>
            </g>
          </g>
        </g>
      </g>
    </svg>
  </div>
  <div class="container">
    <header>
      <img class="logo" src="<?php echo get_template_directory_uri(); ?>/images/wceu-18-logo-222x222.png" width="222" height="222" srcset="<?php echo get_template_directory_uri(); ?>/images/wceu-18-logo-222x222.png 1x, <?php echo get_template_directory_uri(); ?>/images/wceu-18-logo-444x444.png 2x" alt="" />
      <h1 class="title">WordCamp Europe 2018</h1>
      <p class="subtitle">June 14-16, Belgrade, Serbia | #WCEU</p>
    </header>
    <section>
      <h2 class="title">Progressive Web App</h2>
      <p class="content">
        Scan QR code or visit <a href="https://app.wp-europe.org">app.wp-europe.org</a> from your
        mobile phone to get the best experience. For more information about WordCamp Europe 2018 visit
        the <a href="https://2018.europe.wordcamp.org/">official website</a>.
      </p>
      <img class="qr" src="<?php echo get_template_directory_uri(); ?>/images/qr-code-132x132.png" width="132" height="132" srcset="<?php echo get_template_directory_uri(); ?>/images/qr-code-132x132.png 1x, <?php echo get_template_directory_uri(); ?>/images/qr-code-264x264.png 2x" alt="" />
    </section>
  </div>
</div>
