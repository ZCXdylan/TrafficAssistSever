<?php
require_once('../dbManager.php');
class Service{

    public function login($username, $password) {
        $login_sql = "select * from user_info where Uusername = '" . $username . "' and Upassword = '" . $password . "'";
        $db = DBManager::getInstance();
        $conn = $db->connect();

        $result = $conn->query($login_sql);
        if($result->num_rows >= 1) {
            $conn->close();
            return true;
        }
        echo $conn->error;
        $db->close();
        return false;
    }

    public function register($username, $password) {
        $reg_sql = "insert into user_info(Uusername, Upassword) values('" . $username . "','" . $password . "') ";
        $db = DBManager::getInstance();
        $conn = $db->connect();

        $result = $conn->query($reg_sql);
        if($result === true) {
            $db->close();
            return true;
        }
        echo $conn->error;
        $db->close();
        return false;
    }

    public function uploadHistory($json) {
        $save_sql = "insert into user_history(Uusername, Unickname, longitude, latitude, Detail, PicturePath, isVaild)" .
            "values('" . $json->username . "','" . $json->nickname . "','" . $json->longitude . "','" 
            . $json->latitude . "','" . $json->accidentTags . "','" . $json->filenames . "','1') ";
        $db = DBManager::getInstance();
        $conn = $db->connect();

        $result = $conn->query($save_sql);
        if($result === true) {
            $db->close();
            return true;
        }
        echo $conn->error;
        $db->close();
        return false;
    }

    public function downloadHistory($username) {
        $downloadHistory_sql = "select * from user_history where Uusername = '" . $username . "'";
        $db = DBManager::getInstance();
        $conn = $db->connect();

        $result = $conn->query($downloadHistory_sql);
        $arrays = array();
        while (!! $_row = $result ->fetch_object()) {
            $Detail = $_row->Detail;
            $fileNames = $_row->PicturePath;
            $fileNamesarr = explode("/", $fileNames);
            // $tags = explode("/", $Detail);
            $tmp = array(
                'detail' => $Detail,
                'fileNames' => $fileNamesarr
            );
            array_push($arrays, $tmp);
        }
        $db->close();
        $allDetails = array(
            'allDetails' => $arrays
        );
        return json_encode($allDetails);
    }

    public function uploadLocation($json) {
        $saveRealtime_sql = "insert into accident_realtime(username, location_x, location_y)" .
            "values('" . $json->username . "','" . $json->longitude . "','" . $json->latitude . "') ";
        $db = DBManager::getInstance();
        $conn = $db->connect();

        $result = $conn->query($saveRealtime_sql);
        if($result === true) {
            $db->close();
            return true;
        }
        echo $conn->error;
        $db->close();
        return false;
    }
}