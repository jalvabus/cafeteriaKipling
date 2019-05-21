<?php

require_once('ciclo_escolar_actual_controller.php');

$controller = new ciclo_escolar_actual_controller();

switch ($_SERVER['REQUEST_METHOD']) {
    
    case "GET":
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'getAll':
            print_r($controller->getAll());
            break;
        }
    }
    break;

    case "POST":
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'createOne':
            print_r($controller->createOne($_POST));
            break;
            case 'deleteOne':
            print_r($controller->deleteOne($_POST));
            break;
            case 'updateOne':
            print_r($controller->updateOne($_POST));
            break;
        }
    }
    break;

    case "PUT":

    break;
}

?>