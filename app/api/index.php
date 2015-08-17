<?php
/*
 * Store user is redirected here once he has chosen
 * a payment method. 
 */
session_start();
session_write_close();
require_once __DIR__ . '/../bootstrap.php';


if($_SERVER['REQUEST_METHOD'] == 'POST') {	
    
	
    try {
      checkIp();
       // if(!isNull($_POST['data']))
      if(false)
        {
            $string = preg_replace("/[\r\n]+/", " ", base64_decode($_POST['data']));
            $json = utf8_encode($string);
            $data=json_decode($json);
           
          if(!isNull($data->{'user'})&&
                !isNull($data->{'password'})
                &&!isNull($data->{'email'})
                &&!isNull($data->{'ime'})
                &&!isNull($data->{'deviceid'})
                &&!isNull($data->{'key'})
                &&!isNull($data->{'type'})
                
                )
        { 
         


            if(strcmp(API_USER, $data->{'user'})!=0||strcmp(API_PASSWORD, $data->{'password'})!=0)
            {
                echo "false";
                exit;
            }
            if(!checkIp())
           {
                echo 'block';
                exit;
           }
           print_r($_SESSION["LIMIT_SESSION"]) ;
           exit();
           // $index=  getIdFromKey($_GET['key']);
           
          //  if($index==0)
           // {
           //     echo 'false';
           //     exit;
           // }
            $keys=  getkeyByKey($data->{'key'});   

            if(!$keys)
            {
                
                echo 'false';
                exit;
            }
            
            $email=$data->{'email'};
             $ime=$data->{'ime'};
              $deviceid=$data->{'deviceid'};
               $keyCode=$data->{'key'};
                $type=$data->{'type'};
               // echo $ime.$keys["IME_Code"];
              //  exit;
            if(strcmp($email, $keys["email"])==0
                &&strcmp($ime, $keys["IME_Code"])==0
                    &&strcmp($deviceid, $keys["Device_Id"])==0
                        &&strcmp($keyCode, $keys["key_code"])==0
                            &&strcmp($type, $keys["Type"])==0
                    )
            {
                
                echo 'true';
                exit;
            }
            echo 'false';
            exit;
            
        }
    }
       
    } catch (Exception $ex) {
        echo 'Error: $ex';
        exit;
    }
  // echo 'false';
}
  //echo 'false';
