<?php
session_start();
session_unset();
session_destroy();
header('Location: /nhahang/index.php');
exit();
