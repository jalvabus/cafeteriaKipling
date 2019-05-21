<?php

class ciclo_escolar_actual_controller {

    public $db = null;

    function __construct() {
        require_once('../config/DB.php');
        $database = new DB();
        $this->db = $database -> getConnection();
    }

    function getAll() {
        $sql = "select ce.id_ciclo_escolar, cea.id_ciclo_escolar_actual, ce.fecha_inicio, ce.fecha_fin from ciclo_escolar_actual cea inner join ciclo_escolar ce on cea.id_ciclo_escolar = ce.id_ciclo_escolar;";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[0] = array(
                'id_ciclo_escolar_actual' => $row['id_ciclo_escolar_actual'],
                'id_ciclo_escolar' => $row ['id_ciclo_escolar'],
                'fecha_inicio' => $row ['fecha_inicio'],
                'fecha_fin' => $row ['fecha_fin']
            );
        }

        pg_close($this->db);

        $json = array('status' => 0, 'ciclo_escolar' => $combined[0]);
        echo json_encode($json);
    }

    function createOne($params) {
        $id_ciclo_escolar = $params['id_ciclo_escolar'];

        $sql = "INSERT INTO ciclo_escolar_actual (id_ciclo_escolar) VALUES ('$id_ciclo_escolar');";

        echo($sql);
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se ha registrado el ciclo escolar actual";
    }

    function updateOne($params) {
        $id_ciclo_escolar_actual = $params['id_ciclo_escolar_actual'];
        $id_ciclo_escolar = $params['id_ciclo_escolar'];

        $sql = "UPDATE ciclo_escolar_actual SET id_ciclo_escolar = '$id_ciclo_escolar'  where id_ciclo_escolar_actual = $id_ciclo_escolar_actual";

        echo($sql);
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se ha actualizado el usuario";	
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