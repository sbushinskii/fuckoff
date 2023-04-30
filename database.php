<?php

class Database {
    public $con;
    public function __construct()
    {
        //$this->con = mysqli_connect("localhost", "root", "yvgWrM6IT2ZVr66f", "u2036503_default");
        $this->con = mysqli_connect("localhost", "root", "123", "today");
        if(!$this->con)
        {
            echo 'Database Connection Error ' . mysqli_connect_error($this->con);die;
        }
        mysqli_set_charset($this->con,"utf8");
    }

    public function getTags(){
        $sql="SELECT * FROM  tags";
        $tags = [];
        $result = mysqli_query($this->con, $sql);
        while($row = mysqli_fetch_array($result)) {
            $tags[] = [
                'id'=>$row['id'],
                'title'=>$row['title']
            ];
        }
        return $tags;
    }

    public function clearTags($video_id){
        $query = "DELETE FROM video_tag WHERE video_id='$video_id'";
        mysqli_query($this->con, $query);
    }

    public function getVideoTagsIds($resource_id) {
        $query = "SELECT vt.tag_id FROM tags t LEFT JOIN video_tag vt on vt.tag_id = t.id WHERE vt.video_id = '$resource_id'";
        $result = mysqli_query($this->con, $query);

        $tags = [];
        while($res = mysqli_fetch_array($result)){
            $tags[] = $res['tag_id'];
        }
        $tags = array_unique($tags);
        return $tags;
    }

    public function getVideoTags($resource_id) {
        $query = "SELECT * FROM tags t LEFT JOIN video_tag vt on vt.tag_id = t.id WHERE vt.video_id = '$resource_id'";
        $result = mysqli_query($this->con, $query);
        $tags = [];
        while($res = mysqli_fetch_array($result)){
            $tags[] = [
                'id'=>$res['tag_id'],
                'name'=>$res['title']
            ];
        }
        return $tags;
    }

    public function insert($table_name, $data, $debug = false)
    {
        $string = "INSERT INTO ".$table_name." (";
        $string .= implode(",", array_keys($data)) . ') VALUES (';
        $string .= "'" . implode("','", array_values($data)) . "')";
        if($debug) {
            echo $string;die;
        }
        if(mysqli_query($this->con, $string))
        {
            return mysqli_insert_id($this->con);
        }
        else
        {
            echo mysqli_error($this->con);
        }
    }

    public function getVideo($resource_id){
        $result = mysqli_query($this->con,"SELECT * FROM `videos` WHERE resource_id='$resource_id'");
        $record = mysqli_fetch_array($result);
        return$record;
    }

    public function getTagName($tag_name){
        $result_count = mysqli_query($this->con,"SELECT * FROM `tags` WHERE title='$tag_name'");
        var_dump($result_count);die;
        $record = mysqli_fetch_array($result_count);
        if($record){
            return$record['id'];
        }
        return false;
    }

    public function getTagById($tag_id){
        $result_count = mysqli_query($this->con,"SELECT * FROM `tags` WHERE id='$tag_id'");
        $record = mysqli_fetch_array($result_count);
        if($record){
            return $record;
        }
        return false;
    }

    public function update($table_name, $resource_id, $key, $value) {
        $query = "UPDATE `$table_name` SET $key='$value' WHERE resource_id='$resource_id'";
        if(mysqli_query($this->con, $query))
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->con);die;
        }
    }


}