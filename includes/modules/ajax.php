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
            case "getStatus":
                if($_POST["status"]!=""){
                    $arr = "  , please specify : <input type='text' name='other_status'/>";
                }
                break;
             case "getAge":
                if($_POST["age"]!=""){
                    $birthyear = explode("/",$_POST["age"]);
                    $currentyear = date("Y");
                    $age = $currentyear - $birthyear[2];
                    $arr = $age;
                }
                break;
            case "getAddress":
                if ($_POST['address']!=""){
                    $arr = '<table><tr><td colspan="2"><input type="text" name="postal_address" value="'.$_POST['postal_address'].'" style="width:450px;margin-left:-3px"/></td></tr>
                        <tr><td width="60px">Postcode: </td><td><input type="text"  name="postal_postcode" maxlength="5" style="width:100px" value="'.$_POST['postal_postcode'].'"/></td></tr>
                        <tr><td>State:</td><td><select name="postal_state">'.arrToDDL(tep_state($_POST['postal_state'])).'</select></td></tr></table>';
                }
                break;
            case "getStudies_Status":
                if ($_POST['status']!=""){
                    $arr = '<table><tr id="academic_year"><td width="180px">Current Academic Year</td><td><select name="study_year">'.arrToDDL(tep_academic_year($_POST['study_year'])).'</select></td></tr>   
                        <tr id="academic_semester"><td>Current Semester</td><td><select name="study_semester">'.arrToDDL(tep_academic_semester($_POST['study_semester'])).'</select></td></tr></table>';
                }
           break;
            
           
                
                
                
           
        }
    }
    echo $arr;
?>