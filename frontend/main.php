<?php
session_start();
require_once dirname(__FILE__) . '/pages.php';

// Gets all the main page and content page css and js files ...	  
if (isset($_REQUEST['page']) && array_key_exists($_REQUEST['page'],$pages)) {
    $page = $_REQUEST['page'];
} else {
    $page = DEFAULT_PAGE;      
    $_REQUEST['page'] = $page;      
}

$pageTitles = [
    'o' => 'Smoke & Caviar',
    'co' => 'Checkout',
    'r' => 'Reservation',
];

$title = $pageTitles[$page] ?? 'Home Page';

$css_files = array_merge($pages['main']['css'], $pages[$page]['css']);
$js_files  = array_merge($pages['main']['js'],  $pages[$page]['js']);

foreach ($css_files as $css) {
    echo "<link media='all' type='text/css' rel='stylesheet' href='frontend/css/{$css}?t=".time()."' />\n";
}
foreach ($js_files as $js) {
    echo "<script type='text/javascript'  src='frontend/js/{$js}?t=".time()."' ></script>\n";
}   

require dirname(__FILE__) . '/header.php';
?>

<div id="container">
    <?php
        foreach ($pages[$page]['html'] as $content) {
            require "html/{$content}";
        }
    ?>    
</div>
<?php require dirname(__FILE__) . '/footer.php'; ?>