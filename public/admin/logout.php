<?php
session_start();
require_once __DIR__ . '/../../app/config/autoload.php';
require_once __DIR__ . '/../../app/config/config.php';

use App\Controller\AdminController;

$adminController = new AdminController();
$adminController->logout(); 