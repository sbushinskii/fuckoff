<?php

class Database {
    public $con;
    public $error;
    public function __construct()
    {
        $this->con = mysqli_connect("localhost", "u2036503_default", "yvgWrM6IT2ZVr66f", "u2036503_default");
        if(!$this->con)
        {
            echo 'Database Connection Error ' . mysqli_connect_error($this->con);
        }
        mysqli_set_charset($this->con,"utf8");
    }
    public function insert($table_name, $data)
    {
        $string = "INSERT INTO ".$table_name." (";
        $string .= implode(",", array_keys($data)) . ') VALUES (';
        $string .= "'" . implode("','", array_values($data)) . "')";

        if(mysqli_query($this->con, $string))
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->con);
        }
    }

    public function update($table_name, $key, $data) {

    }

    public function process($table_name, $key, $data) {
        //todo if found by het then insert else update
    }
}