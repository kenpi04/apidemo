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

function generateRandomString() {
     $key = md5(microtime());
    /* $new_key = '';
     $ids=  str_split((string)$id,2);
     $idlength=count($ids);
     $keySplit=  str_split($key,4);
     $d=0;
     for($i=0; $i < count($keySplit); $i ++ ){   
          $new_key.=$keySplit[$i];
         if($d<$idlength)
         {
            $number=$ids[$d];
            if(strlen($number)<2)
            {
                $number='W'.$number;
            }
             $new_key.=$number;
             $d++;
         }
     }*/
  return strtoupper($key);
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
function getIdFromKey($key)
{
    $keyLength=strlen($key);
    if($keyLength<=32)
    {
        return 0;
    }
   $arrCount=(int)(($keyLength-32)/2);
   $groupStr= str_split($key,6);
   $idStr="0";
   for($i=0;$i<$arrCount;$i++)
   {
       $idStr.=$groupStr[$i][4];
       $idStr.=$groupStr[$i][5];
   }

  return (int)str_replace('W','',$idStr);    
}
function updateKey($id,$paymentState,$payerId=null,$paymentStatetext=null)
{ 
    if($paymentState)
    {
       $keyCreate= generateRandomString($id);      
        while (true) {
            if(checkKeyExist($keyCreate))
            {
                $keyCreate=generateRandomString(25);
            }
            else
            {
                break;
            }
        }
        $query=sprintf("update %s set Key_Code='%s',payerid='%s',PaymentState='%s' WHERE id=%d",KEY_TABLE,$keyCreate,$payerId,$paymentState,$id); 
         $conn=getConnection();
        $result = mysql_query($query, $conn);
        if (!$result) {
                $errMsg = "Error: " . mysql_error($conn);
                mysql_close($conn);
                throw new Exception($errMsg);
              
            }
         mysql_close($conn);
        return $keyCreate;
    }
    else{
        $query=sprintf("delete %s WHERE id=%d",KEY_TABLE,$id);
        
        $result = mysql_query($query, $conn);
        if (!$result) {
                $errMsg = "Error: " . mysql_error($conn);
                mysql_close($conn);
                throw new Exception($errMsg);
              
            }
         mysql_close($conn);
    }
    return null;
}
function checkKeyExist($key)
{
    $conn=getConnection();
    $query=sprintf("select count(1) from %s where Key_Code='%s'",KEY_TABLE,$key);
    $result=mysql_query($query,$conn);
     if (!$result) {           
            $errMsg = "Error: " . mysql_error($conn);
            mysql_close($conn);
            throw new Exception($errMsg);
          
     }
        $row = mysql_fetch_row($result);
    mysql_close($conn);
    return ($row[0] > 0);
}

function insertKey($email, $IME, $deviceId, $type,$payerId,$dayLimit,$price) {
    
   $keyCreate= generateRandomString();      //231
        while (true) {
            if(checkKeyExist($keyCreate))
            {
                $keyCreate=generateRandomString();
            }
            else
            {
                break;
            }
        }
    $conn = getConnection();
    $keygen = null;
    //check key exits  
    $query = sprintf("insert into %s(email,IME_CODE,Device_id,Type,CreateDate,Key_Code,PayerId,DateLimit,Price) values('%s','%s','%s',%d,now(),'%s','%s',DATE_ADD(now(),INTERVAL %d DAY ),%f)", KEY_TABLE, mysql_real_escape_string($email), mysql_real_escape_string($IME),
     mysql_real_escape_string($deviceId), mysql_real_escape_string($type), 
     mysql_real_escape_string($keyCreate), mysql_real_escape_string($payerId),
     $dayLimit,$price);
    $result = mysql_query($query, $conn);    
    if (!$result) {
          
        $errMsg = "Error: " . mysql_error($conn);
        mysql_close($conn);
        throw new Exception("Error Processing Request:".$errMsg, 1);
        
    }
    $recordId= mysql_insert_id($conn);   
     mysql_close($conn);
    if($recordId>0)
    {
        return $keyCreate;
    }
    return null;


    
}

function checkIp()
{
    $sessionLimitName='LIMIT_SESSION';
    $ip=  getIp();
    if(!isset($_SESSION[$sessionLimitName]))
    {
        $_SESSION[$sessionLimitName]=['date'=> new DateTime(),'count'=>1,'ip'=>getIp()];
        return true;
    }
    else
    {

        $session=$_SESSION[$sessionLimitName];
        if($session['ip']!=getIp())
        {
            unset($_SESSION[$sessionLimitName]);
            return true;
        }
          $now=new DateTime();
        $minute=($now-$session['date'])/60;
        return $session['date'];
        if($minute>=5)
        {
            unset($_SESSION[$sessionLimitName]);
            return true;
        }
        if($minute>1)
        {
            unset($_SESSION[$sessionLimitName]);
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
             $session["count"]=$count;
            $_SESSION[$sessionLimitName]=$session;
        }
       
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
function getkeyByKey($id)
{
        $conn = getConnection();
    $query = sprintf("SELECT * FROM %s WHERE Key_Code='%s' and DATEDIFF(DateLimit,CreateDate)>0",
            KEY_TABLE,
            mysql_real_escape_string($id));
    $result = mysql_query($query, $conn);
    if(!$result) {
        $errMsg = "Error retrieving order: " . mysql_error($conn);
        mysql_close($conn);
        throw new Exception($errMsg);
    }

    $row = mysql_fetch_assoc($result);
    mysql_close($conn);
    return $row;
}
function validateLogin($username,$password)
{
    return strcmp($username,ADMIN_USER)==0&&$password==ADMIN_PASS;
}
function getListKey($searchfield,$searchvalue, $pageIndex=1,$pagesize=20,&$totalRecord)
{
    $pageIndex=$pageIndex>1? $pageIndex-1 :0;
    $conn=getConnection();
    $whereStr="";
    if(!isNull($searchfield)&&!isNull($searchvalue))
    {
         $whereStr=sprintf("where %s = '%s'",$searchfield,$searchvalue);
    }  


    $query=sprintf('Select count(1) from %s %s',KEY_TABLE,$whereStr);
     $result = mysql_query($query, $conn);
    if(!$result) {
        $errMsg = "Error retrieving order: " . mysql_error($conn);
        mysql_close($conn);
        throw new Exception($errMsg);
    }
    $totalRecord=mysql_num_rows($result);
    $query=sprintf("SELECT *,DATEDIFF(DateLimit,CreateDate)DateCount FROM %s %s Limit %d , %d",KEY_TABLE,$whereStr,$pageIndex*$pagesize,$pagesize);
    $result = mysql_query($query, $conn);
    if(!$result) {
        $errMsg = "Error retrieving order: " . mysql_error($conn);
        mysql_close($conn);
        throw new Exception($errMsg);
    }
  
    $rows = array();    
    while(($row = mysql_fetch_assoc($result))) {
        $rows[] = $row;
    }   
    mysql_close($conn);
    return $rows;
}
function InsertUpdatePrice($price,$numMonth, $id=0)
{
    $conn=getConnection();
    $query="";
    if($id==0)
    {
        $query=sprintf("insert into %s(MonthNumber,Price) values(%d,%f)",PRICE_TABLE,$numMonth,$price);

    }
    else
    {
        $query=sprintf("Update %s set Price=%f,Month=%d where id=%d",PRICE_TABLE,$price,$numMonth,$id);
    }
    $result = mysql_query($query, $conn);
    if(!$result) {
        $errMsg = "Error retrieving order: " . mysql_error($conn);
        mysql_close($conn);
        throw new Exception($errMsg);
    }
    mysql_close();
    return true;
}
function DeletePrice($id)
{
    $conn=getConnection();
    $query=sprintf("Delete %s where id=%d",PRICE_TABLE,$id);
    $result = mysql_query($query, $conn);
    if(!$result) {
        $errMsg = "Error retrieving order: " . mysql_error($conn);
        mysql_close($conn);
        throw new Exception($errMsg);
    }
    mysql_close();
    return true;
}
function getPriceList()
{
    $conn=getConnection();
    $query=sprintf("SELECT * FROM %s ",PRICE_TABLE);
    $result = mysql_query($query, $conn);
    if(!$result) {
        $errMsg = "Error retrieving order: " . mysql_error($conn);
        mysql_close($conn);
        throw new Exception($errMsg);
    }
    $rows = array();    
    while(($row = mysql_fetch_assoc($result))) {
        $rows[] = $row;
    }   
    mysql_close($conn);
    return $rows;
}
function getPriceById($id)
{
     $conn = getConnection();
    $query = sprintf("SELECT * FROM %s WHERE id=%d limit 1",PRICE_TABLE,$id);
    $result = mysql_query($query, $conn);
     if(!$result) {
        $errMsg = "Error retrieving order: " . mysql_error($conn);
        mysql_close($conn);
        throw new Exception($errMsg);
    }
     $row = mysql_fetch_assoc($result);
    mysql_close($conn);
    return $row;
}