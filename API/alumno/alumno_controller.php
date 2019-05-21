<?php

class alumno_controller {

    public $db = null;

    function __construct() {
        require_once('../config/DB.php');
        $database = new DB();
        $this->db = $database -> getConnection();
    }

    function getAll() {
        $sql = "SELECT * FROM alumno al
        INNER JOIN ciclo_escolar ce
        ON al.id_ciclo_escolar = ce.id_ciclo_escolar;";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[] = array(
                'id_alumno' => $row['id_alumno'],
                'nombre' => $row['nombre'],
                'apellido_paterno' => $row['apellido_paterno'],
                'apellido_materno' => $row['apellido_materno'],
                'salon' => $row['salon'],
                'status' => $row['status'],
                'id_ciclo_escolar' => $row['id_ciclo_escolar'],
                'fecha_inicio' => $row ['fecha_inicio'],
                'fecha_fin' => $row ['fecha_fin']
            );
        }   

        pg_close($this->db);

        $json = array('status' => 0, 'alumnos' => $combined);
        echo json_encode($json);
    }

    function getByUsuario($params) {
        $sql = "SELECT * FROM alumno al
        INNER JOIN ciclo_escolar ce
        ON al.id_ciclo_escolar = ce.id_ciclo_escolar;";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[] = array(
                'id_alumno' => $row['id_alumno'],
                'nombre' => $row['nombre'],
                'apellido_paterno' => $row['apellido_paterno'],
                'apellido_materno' => $row['apellido_materno'],
                'salon' => $row['salon'],
                'status' => $row['status'],
                'id_ciclo_escolar' => $row['id_ciclo_escolar'],
                'fecha_inicio' => $row ['fecha_inicio'],
                'fecha_fin' => $row ['fecha_fin']
            );
        }   

        pg_close($this->db);

        $json = array('status' => 0, 'alumnos' => $combined);
        echo json_encode($json);
    }

    function createOne($params) {
        $nombre = $params['nombre'];
        $apellido_paterno = $params['apellido_paterno'];
        $apellido_materno = $params['apellido_materno'];
        $id_ciclo_escolar = $params['id_ciclo_escolar'];
        $salon = $params['salon'];
        $status = $params['status'];

        $sql = "INSERT INTO alumno (
            nombre, 
            apellido_paterno, 
            apellido_materno, 
            id_ciclo_escolar,
            salon,
            status) VALUES (
                '$nombre',
                '$apellido_paterno',
                '$apellido_materno',
                '$id_ciclo_escolar',
                '$salon',
                '$status');";

        echo($sql);
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se ha registrado el alumno";
    }

    function updateOne($params) {
        $nombre = $params['nombre'];
        $apellido_paterno = $params['apellido_paterno'];
        $apellido_materno = $params['apellido_materno'];
        $id_ciclo_escolar = $params['id_ciclo_escolar'];
        $salon = $params['salon'];
        $status = $params['status'];
        $id_alumno = $params['id_alumno'];

        $sql = "UPDATE alumno
            set nombre = '$nombre',
            apellido_paterno = '$apellido_paterno', 
            apellido_materno = '$apellido_materno',
            id_ciclo_escolar = '$id_ciclo_escolar',
            salon = '$salon',
            status = '$status' WHERE id_alumno = $id_alumno";

        echo($sql);
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se ha actualizado el alumno";
    }

    function deleteOne($params) {
        $id = $params['id_alumno'];
        $sql = "DELETE FROM alumno WHERE id_alumno = $id";
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se elimino el registro";	
    }

}
?>