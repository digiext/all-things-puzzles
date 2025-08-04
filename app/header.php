<?php
    $theme = 'dark';

    if (isset($_SESSION['theme'])) {
        $theme = $_SESSION['theme'];
    } else {
        $theme = getLoggedInUser()->getTheme()->getName() ?? 'dark';
    }
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo $theme; ?>">
<title><?php echo ($title); ?></title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="<?php echo BASE_URL ?>/images/atp.png" />
<link href="<?php echo BASE_URL ?>/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL ?>/css/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?php echo BASE_URL ?>/css/bootstrap-table.min.css">
<link rel="stylesheet" href="<?php echo BASE_URL ?>/css/util.css">
<script src="<?php echo BASE_URL ?>/scripts/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL ?>/scripts/jquery-3.7.1.min.js"></script>
<script src="<?php echo BASE_URL ?>/scripts/bootstrap-table.min.js"></script>
<script src="<?php echo BASE_URL ?>/scripts/popper.min.js"></script>
<script src="<?php echo BASE_URL ?>/scripts/bootstrap-table-print.min.js"></script>
<?php header("X-Frame-Options: DENY"); ?>