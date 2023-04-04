<?php
class Disk
{
    private $token;


    public function __construct()
    {
        $token = file_get_contents( __DIR__ . '/token.txt');
        //let's try to fetch it from Cookie;
        if(!trim($token) && isset($_COOKIE['yandex_token'])) {
            $token = $_COOKIE['yandex_token'];
            file_put_contents('token.txt', $token);
        }
        if(!trim($token)){
            $this->auth();
        } else {
            $this->token = $token;
        }
    }

    public function auth(){
        $client_id = 'f640cec955cf4fd99617c7c21942de34';
        $redirect = 'https://oauth.yandex.ru/authorize?response_type=token&client_id='.$client_id;
die('wwtf');
        header('location:'.$redirect);
        exit;
    }

    function removeFile($path){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cloud-api.yandex.net/v1/disk/resources?path='.$path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
                ': ',
                'Authorization: OAuth '.$this->token
            ),
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        //$resp = json_decode($response);
    }

    function copyFile($from, $to){
        $curl = curl_init();
        $overwrite = true;
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cloud-api.yandex.net/v1/disk/resources/copy?from='.$from.'&path='.$to.'&overwrite='.$overwrite,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                ': ',
                'Authorization: OAuth '.$this->token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $resp = json_decode($response);
        return $resp;
    }

    function getFiles($path, $limit = 30, $offset = 0)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cloud-api.yandex.net/v1/disk/resources?path=' . $path . '&offset=' . $offset . "&limit=".$limit,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                ': ',
                'Authorization: OAuth ' . $this->token
            ),
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        $response_obj = json_decode($response);
        if(isset($response_obj->error)){
            if($response_obj->error == 'DiskNotFoundError'){
                echo "Skip: ". urldecode($path).PHP_EOL;
            }
            return [];
        }
        if(!isset($response_obj->_embedded)){
            var_dump($response_obj);die;
        }
        return $response_obj->_embedded->items;
    }

    function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }



    function setPubicUrl($video) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cloud-api.yandex.net/v1/disk/resources/publish?path='.$video,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_HTTPHEADER => array(
                ': ',
                'Authorization: OAuth ' . $this->token
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        if(!$response) {
            exit('fail');
        }
    }

    public function getPlaylistUploadURL(){
        $curl = curl_init();
        $path = "Видео/playlist.json";
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cloud-api.yandex.net/v1/disk/resources/upload?path='.urlencode($path).'&overwrite=true',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                ': ',
                'Authorization: OAuth '.$this->token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response)->href;
    }

    public function uploadPlaylist($uploadURL, $content){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $uploadURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS =>$content,
            CURLOPT_HTTPHEADER => array(
                ': ',
                'Content-Type: text/plain'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public function downloadPlaylist(){
        $curl = curl_init();

        $path = "Видео/playlist.json";
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cloud-api.yandex.net/v1/disk/resources/download?path='.urlencode($path),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                ': ',
                'Authorization: OAuth '.$this->token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $href = json_decode($response)->href;
        if(trim($href)){
            @unlink(__DIR__ .'/json/history.json');
            file_put_contents(__DIR__ . '/json/history.json', file_get_contents($href));
        } else {
            exit('Error playlist download');
        }
    }
}

function listErrors(){
    $errors = json_decode(file_get_contents(__DIR__ . '/json/errors.json'));
    foreach ($errors as $error) {
        echo $error->path . PHP_EOL . "<br>";
    }
}

function countVids(){
    $vids = json_decode(file_get_contents(__DIR__ . '/json/history.json'));
    return count($vids);
}

function findVids($scan_day){
    $files = json_decode(file_get_contents(__DIR__ . '/json/history.json'));
    $this_day_vids = [];
    foreach($files as $resource) {
        if (strtotime($resource->unique_date) >= strtotime($scan_day) && strtotime($resource->unique_date)<=strtotime($scan_day)) {
            $this_day_vids[] = $resource;
        }
    }
    return $this_day_vids;
}

function getUniqueDate($real_date){
    $date_parts = explode('-', $real_date);
    return str_replace($date_parts[0],'1970', $real_date);
}

function formatMessage($vids, $title, &$message){
    if(!empty($vids)){
        $message .= PHP_EOL . $title . PHP_EOL.PHP_EOL;
    }

    foreach ($vids as $vid) {
        $message .= $vid->name .' ('.$vid->public_url . ") "
	. " ".sprintf("(Миша: %s, Вера: %s)", $vid->Misha, $vid->Vera)
	 . PHP_EOL . PHP_EOL;
    }
}

//telegram
function sendMessage($message) {
    $token = "6102912824:AAFxSg-DO6VoUCQ-TD3QPPcIN3X_pVh4KlI";
    $chatID = "5701250226";

    echo "sending message to " . $chatID . "\n";

    $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
    $url = $url . "&text=" . urlencode($message);
    $ch = curl_init();
    $optArray = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);
    print_r(json_encode($result));
    return $result;
}

function dateDiff($date1, $date2){
    $diff = abs(strtotime($date2) - strtotime($date1));

    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    //$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    if($years) {
        switch ($years){
            case ($years==1) : {
                return formatMonthString("%d год", $years, $months);
            }
            break;
            case ($years>1 && $years <5) : {
                return formatMonthString("%d года", $years, $months);
            }
            break;
            default: {
                return formatMonthString("%d лет", $years, $months);
            }
        }
    } else {
        return formatMonthString("", $years, $months);
    }
}

function formatMonthString($years_template, $years, $months){
    if($months == 1){
        if(trim($years_template)) {
            return sprintf($years_template . " и %d месяц", $years, $months);
        } else {
            return sprintf("%d месяц", $months);
        }
    }
    if($months > 1 && $months < 5){
        if(trim($years_template)) {
            return sprintf($years_template . " и %d месяца", $years, $months);
        } else {
            return sprintf("%d месяца", $months);
        }
    }
    if($months >= 5){
        if(trim($years_template)) {
            return sprintf($years_template . " и %d месяцев", $years, $months);
        } else {
            return sprintf("%d месяцев", $months);
        }
    }
}
