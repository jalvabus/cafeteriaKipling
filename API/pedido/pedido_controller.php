<?php

class pedido_controller {

    public $db = null;

    function __construct() {
        require_once('../config/DB.php');
        $database = new DB();
        $this->db = $database -> getConnection();
    }

    function getAll() {
        $sql = "SELECT * FROM pedido
        INNER JOIN usuario_alumno
        ON usuario_alumno.id_usuario = pedido.id_usuario
        INNER JOIN alumno 
        ON usuario_alumno.id_alumno = alumno.id_alumno
        INNER JOIN usuario
        ON pedido.id_usuario = usuario.id_usuario;";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[] = array(
                'id_pedido' => $row['id_pedido'],
                'total' => $row['total'],
                'pagado' => $row['pagado'],
                'fecha' => $row['fecha'],
                'id_usuario' => $row['id_usuario'],
                'usuario' => $row['usuario'],
                'nombre_completo' => $row['nombre_completo'],
                'id_alumno' => $row['id_alumno'],
                'nombre' => $row['nombre'],
                'apellido_paterno' => $row['nombre'],
                'apellido_materno' => $row['nombre'],
                'salon' => $row['salon']
            );
        }   

        pg_close($this->db);

        $json = array('status' => 0, 'pedidos' => $combined);
        echo json_encode($json);
    }

    function getByWeek($params) {
        $inicio = $params['fecha_inicio'];
        $fin = $params['fecha_fin'];

        $sql = "SELECT * FROM pedido
        INNER JOIN usuario_alumno
        ON usuario_alumno.id_usuario = pedido.id_usuario
        INNER JOIN alumno 
        ON usuario_alumno.id_alumno = alumno.id_alumno
        INNER JOIN usuario
        ON pedido.id_usuario = usuario.id_usuario
        WHERE pedido.fecha BETWEEN '$inicio' AND '$fin';";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[] = array(
                'id_pedido' => $row['id_pedido'],
                'total' => $row['total'],
                'pagado' => $row['pagado'],
                'fecha' => $row['fecha'],
                'id_usuario' => $row['id_usuario'],
                'usuario' => $row['usuario'],
                'nombre_completo' => $row['nombre_completo'],
                'id_alumno' => $row['id_alumno'],
                'nombre' => $row['nombre'],
                'apellido_paterno' => $row['nombre'],
                'apellido_materno' => $row['nombre'],
                'salon' => $row['salon']
            );
        }   

        pg_close($this->db);

        $json = array('status' => 0, 'pedidos' => $combined);
        echo json_encode($json);
    }

    function createOne($params) {
        $id_usuario = $params['id_usuario'];
        $total = $params['total'];
        $pagado = $params['pagado'];
        $fecha = $params['fecha'];

        $sql = "INSERT INTO pedido (
            id_usuario, 
            total, 
            pagado, 
            fecha) VALUES (
                '$id_usuario',
                '$total',
                '$pagado',
                '$fecha');";        
        pg_query($this->db, $sql);

        $json = array('status' => 0, 'mensaje' => 'Pedido Registrado');
        echo json_encode($json);
    }

    function getLastID() {
        $sql = "SELECT MAX(id_pedido) as id_pedido FROM pedido;";

        if(!$result=pg_query($this->db,$sql)){
            return False;
        }

        $combined=array();
        while ($row = pg_fetch_assoc($result)) {
            $combined[0] = $row['id_pedido'];
        }   

        pg_close($this->db);

        $json = array('status' => 200, 'id_pedido' => $combined[0]);
        echo json_encode($json);
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
        $json = array('status' => 200, 'mensaje' => 'Se ah registrado el alumno');
        echo json_encode($json);
    }

    function deleteOne($params) {
        $id = $params['id_alumno'];
        $sql = "DELETE FROM alumno WHERE id_alumno = $id";
        pg_query($this->db, $sql);
        pg_close($this->db);
        $json = array('status' => 200, 'mensaje' => 'Se ah eliminado el alumno');
        echo json_encode($json);
    }

}
?>