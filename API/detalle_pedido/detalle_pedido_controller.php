<?php

class detalle_pedido_controller {

    public $db = null;

    function __construct() {
        require_once('../config/DB.php');
        $database = new DB();
        $this->db = $database -> getConnection();
    }

    function getAll() {
        $sql = "SELECT * FROM detalle_pedido;";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[] = array(
                'id_detalle_pedido' => $row['id_detalle_pedido'],
                'id_pedido' => $row['id_pedido'],
                'cantidad' => $row['cantidad'],
                'id_menu' => $row['id_menu']
            );
        }   

        pg_close($this->db);

        $json = array('status' => 0, 'detalle_pedidos' => $combined);
        echo json_encode($json);
    }

    function createOne($params) {
        $id_pedido = $params['id_pedido'];
        $id_menu = $params['id_menu'];
        $cantidad = $params['cantidad'];

        $sql = "INSERT INTO detalle_pedido (
            id_pedido, 
            id_menu, 
            cantidad) VALUES (
                '$id_pedido',
                '$id_menu',
                '$cantidad');";

        echo($sql);
        pg_query($this->db, $sql);
        pg_close($this->db);
        echo "Se ha registrado el detalle del pedido";
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