<?php
// Facilita o acesso: http://localhost/biblioteca/ leva direto para a pasta public
$pasta = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
header('Location: ' . $pasta . '/public/index.php');
exit;
