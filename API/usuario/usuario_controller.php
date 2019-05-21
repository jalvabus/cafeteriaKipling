<?php

class usuario_controller {

    public $db = null;

    function __construct() {
        require_once('../config/DB.php');
        $database = new DB();
        $this->db = $database -> getConnection();
    }

    function getAll() {
        $sql = "SELECT * FROM usuario";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[] = array(
                'id_usuario' => $row['id_usuario'],
                'usuario' => $row['usuario'],
                'password' => $row['password'],
                'nombre_completo' => $row['nombre_completo']
            );
        }   

        pg_close($this->db);

        $json = array('status' => 0, 'usuarios' => $combined);
        echo json_encode($json);
    }

    function createOne($params) {
        $usuario = $params['usuario'];
        $password = $params['password'];
        $nombre_completo = $params['nombre_completo'];

        $sql = "INSERT INTO usuario (
            usuario, 
            password, 
            nombre_completo) VALUES (
                '$usuario',
                '$password',
                '$nombre_completo');";

        echo($sql);
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se ha registrado el usuario";
    }

    function updateOne($params) {
        $usuario = $params['usuario'];
        $password = $params['password'];
        $nombre_completo = $params['nombre_completo'];
        $id_usuario = $params['id_usuario'];

        $sql = "UPDATE usuario
            set usuario = '$usuario',
            password = '$password', 
            nombre_completo = '$nombre_completo'
            WHERE id_usuario = $id_usuario";

        echo($sql);
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se ha actualizado el usuario";
    }

    function deleteOne($params) {
        $id_usuario = $params['id_usuario'];
        $sql = "DELETE FROM usuario WHERE id_usuario = $id_usuario";
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se elimino el registro";	
    }

    function getLastID() {
        $sql = "SELECT MAX(id_usuario) as id_usuario FROM usuario";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[0] = $row['id_usuario'];
        }   

        pg_close($this->db);

        $json = array('status' => 0, 'id_usuario' => $combined[0]);
        echo json_encode($json);
    }
}
?>