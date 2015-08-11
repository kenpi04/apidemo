<?php

require_once __DIR__ . '/db.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of key
 *
 * @author DuyThong
 */
function createRandomKey($length) {
    $key = '';
    $total = 0;
    for ($i = 0; $i < $length; $i++) {
        $range = range(0, 9);
        $total+=$range;
        $key+=(string) $range;
    }
    return ['key' => $key, 'total' => $total];
}

function getKey($email, $IME, $deviceId, $type) {
    $conn = getConnection();
    $query = sprintf("SELECT top 1 key FROM %s WHERE type=%d and email='%s' and ime_code='%s' and device_Id='%s'", KEY_TABLE, $type, mysql_real_escape_string($email), mysql_real_escape_string($IME), mysql_real_escape_string($deviceId)
    );
    $result = mysql_query($query, $conn);
    $row = mysql_fetch_row($result);
    mysql_close($conn);
    return ($row[0]);
}


function insertKey($email, $IME, $deviceId, $type) {
    
    $conn = getConnection();
    $isCreate = false;
    $keygen = "";
    //check key exits
    while (!$isCreate) {
        $keygen = createRandomKey(20)["key"];

        $query = sprintf("SELECT COUNT(1) FROM %s WHERE key='%s'", KEY_TABLE, $keygen["key"]);
        $result = mysql_query($query, $conn);

        if (!$result) {
            $isCreate = false;
            $errMsg = "Error: " . mysql_error($conn);
            mysql_close($conn);
            throw new Exception($errMsg);
            break;
        }
        if ($result->num_rows == 0) {
            $isCreate = true;
        }
    }
  //  mysql_close($conn);
    //insert key
   
  //  $conn = getConnection();
    $query = sprintf("insert into %s(email,IME_CODE,Device_id,Type,Key) values('%s','%s','%s',%d,'%s')", KEY_TABLE, $email, $IME, $deviceId, $type, $keygen);
    $result = mysql_query($query, $conn);
    if (!$result) {
        $errMsg = "Error: " . mysql_error($conn);
        mysql_close($conn);
        throw new Exception($errMsg);
    }
    mysql_close($conn);
    return key;
}

function checkIp()
{
    $ip=  getIp();
    if(!isset($_SESSION[$ip]))
    {
        $_SESSION[$ip]=['date'=> new DateTime(),count=>1];
        return true;
    }
    else
    {
        $session=$_SESSION[$ip];
          $now=new DateTime();
        $minute=($now-$session['date'])/60;
        if($minute>1)
        {
            unset($_SESSION[$ip]);
            return true;
        }
        $count=(int)$session["count"];
        if($count>=9)
        {
            return false;
        }      
        if(minute<=1)
        {
            $count++;
        }
        $session["count"]=$count;
        $_SESSION[$ip]=$session;
        return true;
        
    }
    return true;
    
}
function getIp() {
    $ip = null;
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
