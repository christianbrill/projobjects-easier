<?php

// Autoload PSR-4
spl_autoload_register();

// Imports
use \Classes\Webforce3\Config\Config;

// Get the config object
$conf = Config::getInstance();

$selectSessions = Session::getAllForSelect();

// Formulaire soumis
if(!empty($_POST)) {

}

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir().'header.php';
require $conf->getViewsDir().'session.php';
require $conf->getViewsDir().'footer.php';
