<!-- Start Content -->
<?php
$page = 'pages/home.php';
$productpage = 'pages/productpage.php';
$template = 'components/layouts/template.php';

if (isset($_GET['page'])) {
    switch ($_GET['page']) {
        case 'home':
            $page = 'pages/home.php';
            include $template;
            break;
        case 'costume':
            if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'checkout') {
                $page = 'pages/checkoutpage.php';
            } else {
                $page = $productpage;
            }
            include $template;
            break;
        case 'hasilPaket':
            $page = 'hasilPaket.php';
            include $template;
            break;
    }
} else {
    include 'components/layouts/template.php'; // default action if 'page' is not set
}

?>
<!-- End Content -->