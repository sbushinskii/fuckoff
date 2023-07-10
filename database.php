<?php

class Database {
    public $con;
    public function __construct()
    {
        if(file_exists(__DIR__ . '/dev')){
            $this->con = mysqli_connect("localhost", "root", "123", "today");
        } else {
            $this->con = mysqli_connect("localhost", "u2036503_default", "yvgWrM6IT2ZVr66f", "u2036503_default");
        }

        if(!$this->con)
        {
            echo 'Database Connection Error ' . mysqli_connect_error($this->con);die;
        }
        mysqli_set_charset($this->con,"utf8");
    }

    function getLastMomentImported(){
        $sql = "select date, name from videos where type='moments' order by id DESC LIMIT 1;";
        $result = mysqli_query($this->con, $sql);
        $last_moment_imported = mysqli_fetch_array($result);
        return $last_moment_imported;
    }

    function exportDB($backup_name=false )
    {
        if(file_exists(__DIR__ . '/dev')) {
            $mysqli = new mysqli('localhost', 'root', '123', 'today');
        } else {
            $mysqli = new mysqli('localhost', 'u2036503_default', 'yvgWrM6IT2ZVr66f', 'u2036503_default');
        }
//        $queryTables    = $mysqli->query('SHOW TABLES');
//        while($row = $queryTables->fetch_row())
//        {
//            $target_tables[] = $row[0];
//        }
        $target_tables = ['videos'];

        foreach($target_tables as $table)
        {
            $mysqli->query('DROP '.$table);

            $result         =   $mysqli->query('SELECT * FROM '.$table);
            $fields_amount  =   $result->field_count;
            $rows_num=$mysqli->affected_rows;
            $res            =   $mysqli->query('SHOW CREATE TABLE '.$table);
            $TableMLine     =   $res->fetch_row();
            $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

            for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0)
            {
                while($row = $result->fetch_row())
                { //when started (and every after 100 command cycle):
                    if ($st_counter%100 == 0 || $st_counter == 0 )
                    {
                        $content .= "\nINSERT INTO ".$table." VALUES";
                    }
                    $content .= "\n(";
                    for($j=0; $j<$fields_amount; $j++)
                    {
                        $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) );
                        if (isset($row[$j]))
                        {
                            $content .= '"'.$row[$j].'"' ;
                        }
                        else
                        {
                            $content .= '""';
                        }
                        if ($j<($fields_amount-1))
                        {
                            $content.= ',';
                        }
                    }
                    $content .=")";
                    //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                    if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num)
                    {
                        $content .= ";";
                    }
                    else
                    {
                        $content .= ",";
                    }
                    $st_counter=$st_counter+1;
                }
            } $content .="\n\n\n";
        }
        //$backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
        $backup_name = date('Y-m-d H:i:s').".sql";
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"".$backup_name."\"");
        echo $content; exit;
    }

    public function getTodayVideos($scan_day){
        $sql="SELECT * FROM videos WHERE unique_date = '$scan_day'";
        $videos = [];
        $result = mysqli_query($this->con, $sql);
        while($row = mysqli_fetch_array($result)) {
            $videos[] = $row;
        }
        return $videos;
    }

    public function searchVideosByTitle($title, $offset, $total_records_per_page){
        $order = isset($_GET['order']) ? $_GET['order'] : "DESC";
        $sql = "select * from videos where `name` like '%$title%' ORDER BY `timestamp` $order  LIMIT $offset, $total_records_per_page";
        $result = mysqli_query($this->con, $sql);
        $vids  = [];
        while($row = mysqli_fetch_array($result)) {
            $vids[] = $row;
        }
        return $vids;
    }

    public function getVideosNoPreview(){
        $sql="SELECT * FROM videos WHERE preview IS NULL AND skip_preview=0";
        $videos = [];
        $result = mysqli_query($this->con, $sql);
        while($row = mysqli_fetch_array($result)) {
            $videos[] = [
                'path'=>$row['path'],
                'resource_id'=>$row['resource_id'],
                'name'=>$row['name']
            ];
        }
        return $videos;
    }

    public function getVideos(){
        $sql="SELECT * FROM videos WHERE is_deleted = 0";
        $videos = [];
        $result = mysqli_query($this->con, $sql);
        while($row = mysqli_fetch_array($result)) {
            $videos[] = $row;
        }
        return $videos;
    }

    public function recalculateTagsUsage(){
        $sql = 'UPDATE tags t SET counter = (select count(*) as cnt from video_tag vt where vt.tag_id = t.id) where 1;';
        mysqli_query($this->con, $sql);
    }

    public function getTags(){
        $sql="SELECT * FROM  tags ORDER BY counter DESC";
        $tags = [];
        $result = mysqli_query($this->con, $sql);
        while($row = mysqli_fetch_array($result)) {
            $tags[] = [
                'id'=>$row['id'],
                'title'=>$row['title'],
                'counter'=>$row['counter']
            ];
        }
        return $tags;
    }

    public function getTag($tag_id){
        $sql="SELECT * FROM  tags where id = ".$tag_id;
        $tags = [];
        $result = mysqli_query($this->con, $sql);
        while($row = mysqli_fetch_array($result)) {
            $tag = [
                'id'=>$row['id'],
                'title'=>$row['title'],
                'counter'=>$row['counter']
            ];
        }
        return $tag;
    }

    public function findVideosByTag($tag_id){
        $sql = "select * from videos where resource_id in ( SELECT video_id FROM `video_tag` vt WHERE vt.tag_id=$tag_id)";

        $result = mysqli_query($this->con, $sql);
        while($row = mysqli_fetch_array($result)) {

            $vids[] = [
                'path'=>$row['path'],
                'public_url'=>$row['public_url'],
                'name'=>$row['name'],
                'id'=>$row['id'],
                'date'=>$row['date'],
                'resource_id'=>$row['resource_id'],
                'preview'=>$row['preview'],
            ];
        }
        return $vids;
    }

    public function getTopTags($limit=4){
        $sql="SELECT * FROM  tags ORDER BY counter DESC LIMIT 0,$limit";
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

    public function getTopTagsIds($limit=4){
        $sql="SELECT * FROM  tags ORDER BY counter DESC LIMIT 0,$limit";
        $tags = [];
        $result = mysqli_query($this->con, $sql);
        while($row = mysqli_fetch_array($result)) {
            $tags[] = $row['id'];
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

    public function getVideoByPath($path){
        $result = mysqli_query($this->con,"SELECT * FROM `videos` WHERE path='$path'");
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

    public function update($table_name, $where_key, $where_value, $key, $value) {
        $query = "UPDATE `$table_name` SET $key='$value' WHERE $where_key='$where_value'";
        if(mysqli_query($this->con, $query))
        {
            return true;
        }
        else
        {
            echo mysqli_error($this->con);die;
        }
    }

    public function delete($table_name, $key, $value) {
        $query = "DELETE FROM `$table_name` WHERE $key='$value'";
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