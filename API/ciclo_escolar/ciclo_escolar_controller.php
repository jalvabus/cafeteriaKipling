<?php

class ciclo_escolar_controller {

    public $db = null;

    function __construct() {
        require_once('../config/DB.php');
        $database = new DB();
        $this->db = $database -> getConnection();
    }

    function getAll() {
        $sql = "SELECT * FROM ciclo_escolar;";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[] = array(
                'id_ciclo_escolar' => $row['id_ciclo_escolar'],
                'fecha_inicio' => $row ['fecha_inicio'],
                'fecha_fin' => $row ['fecha_fin']
            );
        }

        pg_close($this->db);

        $json = array('status' => 0, 'ciclos_escolares' => $combined);
        echo json_encode($json);
    }

    function createOne($params) {
        $fecha_inicio = $params['fecha_inicio'];
        $fecha_fin = $params['fecha_fin'];

        $sql = "INSERT INTO ciclo_escolar (fecha_inicio, fecha_fin) VALUES ('$fecha_inicio','$fecha_fin');";

        echo($sql);
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se ha registrado el ciclo_escolar";
    }

    function updateOne($params) {

    }

    function deleteOne($params) {
        $id = $params['id_ciclo_escolar'];
        $sql = "DELETE FROM ciclo_escolar WHERE id_ciclo_escolar = $id";
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se elimino el registro";	
    }

}
?>