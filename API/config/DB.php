<?php

class DB {

    var $db = null;

    function __construct() { }

    function getConnection () {
        $host        = "host = 127.0.0.1";
        $port        = "port = 5432";
        $dbname      = "dbname = cafeteriakipling";
        $credentials = "user = postgres password=123456";
        
        // print "Successfully connected from database";
        return $db = pg_connect("$host $port $dbname $credentials");
    }

    function closeConnection() {
        if(!pg_close($db)) {
            print "Failed to close connection to " . pg_host($db) . ": " .
            pg_last_error($db) . "<br/>\n";
        } else {
            print "Successfully disconnected from database";
        }
    }

    function validateConnection () {
        if($this->getConnection()) {
            return true;
         } else {
            return false;
         }
    }
}

?>