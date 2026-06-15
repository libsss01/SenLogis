<?php
session_start();
require_once __DIR__ . '/controller/sessionSecurity.php';
sendNoCacheHeaders();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}

?>

<?php require_once __DIR__ . '/views/pages/admin/head.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/preloader.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/nav-header.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/header.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/sidebar.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/content.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/footer.php'; ?>
<?php require_once __DIR__ . '/views/pages/admin/scripts.php'; ?>
