<?php 

class logincontroller {

    public $db = null;

    function __construct() {
        require_once('../config/DB.php');
        $database = new DB();
        $this->db = $database -> getConnection();
    }

    function getUsuario($params) {
        $usuario = $params['usuario'];
        $pass = $params['password'];
        
        $sql = "SELECT * FROM usuario where usuario = '$usuario' and password = '$pass';";        
            if(!$result=pg_query($this->db,$sql)){
                return False;
            }
            $combined=array();
            while ($row = pg_fetch_assoc($result)) {
                    $id = $row['id_usuario'];
                    $usuario = $row['usuario'];
                    $nombre_completo = $row['nombre_completo'];
                   
                    $combined[] = array('id_usuario' => $id,
                    'usuario' => $usuario,
                    'nombre_completo' => $nombre_completo);
            }

            pg_close($this->db);

            if (empty($combined)) {
                // list is empty.
                $json = array('status' => 200, 'usuario' => $combined);
                echo json_encode($json);
            } else {
                $json = array('status' => 200, 'usuario' => $combined[0]);
                echo json_encode($json);
            }
            

    }

}

?>