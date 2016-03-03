<?php  
    session_start();
         include('../functions/database.php');
        include('../configuration.php');
  
    include('../functions/general.php');

    tep_db_connect();
    
    tep_query("SET time_zone = '+8:00'");
    date_default_timezone_set("Asia/Kuala_Lumpur");
    mysql_set_charset("utf8");
    
    if($_POST["submit"]){
        switch ($_POST["submit"]){
            case "getAdvisor":
                if($_POST["advisor"]!=""){
                    $arr = arrToDDL(tep_get_advisor($_POST["advisor"]));
                }
                break;
             case "getCity":
                if($_POST["city"]!=""){
                    $arr = arrToDDL(tep_get_city($_POST["city"]));
                }
                break;
            case "categoryState":
                if ($_POST['state']!=""){
                    $arr = arrToDDL(tep_get_categoryState($_POST["state"]));
                }
                break;
            case "getMerchant":
                if ($_POST['merchant']!="" && $_POST['category']!=""){
                    $arr = arrToDDL(tep_get_merchant($_POST["merchant"], $_POST['category']));
                }
           
                
           
        }
    }
    echo $arr;
?>