<?php

class menu_controller {

    public $db = null;

    function __construct() {
        require_once('../config/DB.php');
        $database = new DB();
        $this->db = $database -> getConnection();
    }

    function getAll() {
        $sql = "SELECT * FROM menu;";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[] = array(
                'id_menu' => $row['id_menu'],
                'comida' => $row['comida'],
                'agua' => $row['agua'],
                'postre' => $row['postre'],
                'precio' => $row['precio'],
                'fecha' => $row['fecha']
            );
        }   

        pg_close($this->db);

        $json = array('status' => 0, 'menus' => $combined);
        echo json_encode($json);
    }

    function getByDate($params) {
        $fecha_inicio = $params['fecha_inicio'];
        $fecha_fin = $params['fecha_fin'];

        $sql = "SELECT * FROM menu WHERE fecha between '$fecha_inicio' and '$fecha_fin';";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[] = array(
                'id_menu' => $row['id_menu'],
                'comida' => $row['comida'],
                'agua' => $row['agua'],
                'postre' => $row['postre'],
                'precio' => $row['precio'],
                'fecha' => $row['fecha']
            );
        }   

        pg_close($this->db);

        $json = array('status' => 0, 'menus' => $combined);
        echo json_encode($json);
    }

    function createOne($params) {
        $comida = $params['comida'];
        $agua = $params['agua'];
        $postre = $params['postre'];
        $precio = $params['precio'];
        $fecha = $params['fecha'];

        $sql = "INSERT INTO menu (
            comida, 
            agua,
            postre,
            precio,
            fecha) VALUES (
                '$comida',
                '$agua',
                '$postre',
                '$precio',
                '$fecha');";

        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se ha registrado el menu";
    }

    function updateOne($params) {
       
    }

    function deleteOne($params) {
        $id_menu = $params['id_menu'];
        $sql = "DELETE FROM menu WHERE id_menu = $id_menu";
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se elimino el registro";	
    }

}
?>