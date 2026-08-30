<?php
require_once dirname(__DIR__, 2) . '/config.php';
session_start();
session_destroy();
redirect('/views/login/index.php');
?>

