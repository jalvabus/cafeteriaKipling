<?php

class usuario_alumno_controller {

    public $db = null;

    function __construct() {
        require_once('../config/DB.php');
        $database = new DB();
        $this->db = $database -> getConnection();
    }

    function getAll() {
        $sql = "SELECT * FROM usuario_alumno
        INNER JOIN usuario 
        ON usuario_alumno.id_usuario = usuario.id_usuario
        INNER JOIN alumno 
        ON usuario_alumno.id_alumno = alumno.id_alumno;";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[] = array(
                'id_usuario_alumno' => $row['id_usuario_alumno'],
                'id_alumno' => $row['id_alumno'],
                'nombre' => $row['nombre'],
                'apellido_paterno' => $row['apellido_paterno'],
                'apellido_materno' => $row['apellido_materno'],
                'id_usuario' => $row['id_usuario'],
                'nombre_completo' => $row['nombre_completo']
            );
        }   

        pg_close($this->db);

        $json = array('status' => 0, 'usuario_alumnos' => $combined);
        echo json_encode($json);
    }

    function getAlumnosByUsuario ($params) {
        $id_usuario = $params['id_usuario'];

        $sql = "SELECT * FROM usuario_alumno
        INNER JOIN usuario 
        ON usuario_alumno.id_usuario = usuario.id_usuario
        INNER JOIN alumno 
        ON usuario_alumno.id_alumno = alumno.id_alumno
	    WHERE usuario_alumno.id_usuario = $id_usuario;";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[] = array(
                'id_usuario_alumno' => $row['id_usuario_alumno'],
                'id_alumno' => $row['id_alumno'],
                'nombre' => $row['nombre'],
                'apellido_paterno' => $row['apellido_paterno'],
                'apellido_materno' => $row['apellido_materno'],
                'id_usuario' => $row['id_usuario'],
                'nombre_completo' => $row['nombre_completo']
            );
        }   

        pg_close($this->db);

        $json = array('status' => 0, 'alumnos' => $combined);
        echo json_encode($json);
    }

    function createOne($params) {
        $id_alumno = $params['id_alumno'];
        $id_usuario = $params['id_usuario'];

        $sql = "INSERT INTO usuario_alumno (
            id_alumno, 
            id_usuario) VALUES (
                '$id_alumno',
                '$id_usuario');";

        echo($sql);
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se ha registrado la relaicion alumno - usuario";
    }

    function updateOne($params) {
        $id_alumno = $params['id_alumno'];
        $id_usuario = $params['id_usuario'];
        $id_usuario_alumno = $params['id_usuario_alumno'];

        $sql = "UPDATE usuario_alumno
            set id_alumno = '$id_alumno',
            id_usuario = '$id_usuario', 
            id_usuario_alumno = $id_usuario_alumno";

        echo($sql);
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se ha actualizado el alumno";
    }

    function deleteOne($params) {
        $id_usuario_alumno = $params['id_usuario_alumno'];
        $sql = "DELETE FROM usuario_alumno WHERE id_usuario_alumno = $id_usuario_alumno";
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se elimino el registro";	
    }

}
?>