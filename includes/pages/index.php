
<?php

        $toyear = date("Y") - 16;
        $fromyear = date("Y") - 25;
        
        if ($_GET['ic']=="registered"){
            echo "<script>alert('The NRIC No. had been registered!')</script>";
        }
        
         if ($_GET['permission']=="denied"){
              echo '<script>alert("You don\'t have permission to do this action")</script>';
        }
        
        if ($_GET['age']=="over"){
              echo '<script>alert("You are over-aged!")</script>';
        }
        
         if ($_GET['citizen']=="disqualified"){
              echo '<script>alert("You are disqualified to apply!")</script>';
              tep_query("UPDATE ".MEMBER." SET member_status = 3 WHERE member_id = '".$_SESSION['member_id']."'");
              unset ($_SESSION['member_id']);
        }
        
        if ($_GET['pob']=="dash"){
              echo '<script>alert("Please select a state for your place of birth!")</script>';
        }
        
         if ($_GET['home']=="dash"){
              echo '<script>alert("Please select a state for your home address!")</script>';
        }
        
        if ($_GET['postal']=="blank"){
              echo '<script>alert("Please do not leave your postal address blank!")</script>';
        }
      
        if ($_SESSION['member_ic']!=""){
            $icno = $_SESSION['member_ic'];
        }else{
              $admin_check = tep_fetch_object(tep_query("SELECT * FROM ".PERSONAL." p, ".MEMBER." m  WHERE m.member_id = p.member_id AND (p.member_id = '".$_SESSION['member_id']."' OR p.member_id = '".$_GET['id']."')"));
                $icno = $admin_check->member_ic;
        }
        
        for ($x=0;$x<=5;$x++){
            $ic1.= $icno[$x];
        }
        for ($i=6;$i<8;$i++){
        $ic2 .= $icno[$i];
        }
        for ($c=8;$c<=11;$c++){
        $ic3 .= $icno[$c];
        }
        
        $split_ic =$icno;
      
        $year = $split_ic[0].$split_ic[1];
        $month = $split_ic[2].$split_ic[3];
        $day = $split_ic[4].$split_ic[5];
        if ($split_ic[0]=="0"){
            $actual_year = "20".$year;
        }else{
            $actual_year = "19".$year;
        }
        $member_dob = $day."/".$month."/".$actual_year;
        $currentyear = date("Y");
        $count_age = $currentyear - $actual_year;
        
       
?>
<script type="text/javascript">

 function PopStatus(val){
 if(val=="Other")
     $("#marital_status").css('display','inline-block');
 else  
    $("#marital_status").hide();
}


 function studyStatus(val){
     if (val=="1")
     $("#academic").show();
 else
     $("#academic").hide();
}

$(document).ready(function(){
    
    $('#non_malaysian').click(function() {
        alert('We\'re sorry, Penang Future Foundation Scholarship is open for Malaysia Citizen only.');
        
});

    $('#same_address').click(function(){
      if($("#same_address").is(':checked'))
          $("#postal_address").hide();
      else
          $("#postal_address").show();
    });
    
 
});
</script>

<?php
 
    $check = tep_query("SELECT * FROM ".PERSONAL." p, ".MEMBER." m  WHERE m.member_id = p.member_id AND (p.member_id = '".$_SESSION['member_id']."' OR p.member_id = '".$_GET['id']."')");
    if (tep_num_rows($check)>0){
         while ($re = tep_fetch_object($check)){
             $complete = $re->complete;
             $personal = $re->personal;
             
         }
      if ($complete =="1"){
//          admin view
          if ($_SESSION['member_id']=="" && $_SESSION['user_id']!="" && $_SESSION['user_group']=="SYSTEM_ADMIN"){
              if ($_GET['action']=="edit"){
                  $editable = 1;
              }else{
                    $editable = "admin";
              }
          }else{
//              cant edit anymore
              $editable = 0;
          }
      }
//      not yet submit 
      else{
//          done this form but still can edit
          if ($personal =="1"){
              if ($_GET['action']=="edit"){
                  $editable = 1;
              }else{
                  $editable = "view";
              }
          }else{
//              not inside database
              $editable = "blank";
          }
      }
    }

 
    if ($_POST['submit']=="Save"){
   
    if ($_POST[member_marital_status]=="Other"){
        $marital_status = tep_input($_POST['other_status']);
    }else{
        $marital_status = $_POST[member_marital_status];
    }
    $home_address = tep_input($_POST['home_address'])."|".tep_input($_POST['home_address2'])."|".$_POST['home_postcode']."|".$_POST['home_state'];
    if ($_POST['same_address'] ==1){
        $postal_address = $home_address;
    }else{
        $postal_address =  tep_input($_POST['postal_address'])."|".tep_input($_POST['postal_address2'])."|".$_POST['postal_postcode']."|".$_POST['postal_state'];
    }
    
    $home_phone = $_POST['member_home_number']."-".$_POST['member_home_number2'];
    $mobile_phone = $_POST['member_mobile_number']."-".$_POST['member_mobile_number2'];
       

   if($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){
     
       if ($editable=="1" && $_SESSION['user_id']=="1"){
           $id = $_GET['id'];
       }else{
           $id = $_SESSION['member_id'];
       }
    
       if($_POST['member_citizenship']=="0"){
            redirect("index.php?citizen=disqualified");
        }else{
        tep_query("UPDATE ".PERSONAL." SET  member_name = '".tep_input($_POST['member_name'])."', 
                                                                         member_ic = '".$member_ic."', 
                                                                         member_citizenship =  '".$_POST['member_citizenship']."',
                                                                         member_sex = '".$_POST['member_sex']."',
                                                                         member_marital_status = '".$marital_status."',
                                                                         member_dob ='".date_htmltomysql($member_dob)."',
                                                                         member_pob = '".$_POST["member_pob"]."',
                                                                         member_home_address =  '".$home_address."',
                                                                         member_postal_address =  '".$postal_address."',
                                                                         member_home_number = '".$home_phone."',
                                                                         member_mobile_number = '".$mobile_phone."' ,
                                                                         studies_status = '".tep_input($_POST['studies_status'])."',
                                                                         current_year = '".$_POST['study_year']."',
                                                                         current_semester = '".$_POST['study_semester']."' WHERE member_id = '".$id."'");
        
                        if ($_GET['action']=="edit" && $_SESSION['user_id']!=""){
                             tep_query("INSERT INTO ".TEMP."(member_id,action,temp_group,temp_createdby,temp_created) 
                                                  VALUES('".$_GET['id']."', 'Edit Personal Details', '0', '".$_SESSION['user_id']."',NOW())");
                 
                     redirect('index.php?pages=index&id='.$_GET['id'].'');
                 }else{
                      redirect('index.php?pages=index');
                 }
    }
 
    
    }else{
    if($_POST['member_citizenship']=="0"){
            redirect("index.php?citizen=disqualified");
        }else{
            tep_query("INSERT INTO ".PERSONAL."(member_id,member_name,member_ic,member_citizenship,member_sex,member_marital_status,member_dob,member_pob,
                                                                                   member_home_address,member_postal_address,member_home_number,member_mobile_number,studies_status,current_year,current_semester) 
                                 VALUES('".$_SESSION['member_id']."', '".tep_input($_POST['member_name'])."', '".$member_ic."','".$_POST['member_citizenship']."', '".$_POST['member_sex']."','".$marital_status."','".date_htmltomysql($member_dob)."','".$_POST["member_pob"]."',
                                    '".$home_address."', '".$postal_address."', '".$home_phone."', '".$mobile_phone."' ,'".tep_input($_POST['studies_status'])."',  '".$_POST['study_year']."','".$_POST['study_semester']."')");

            tep_query("UPDATE ".MEMBER." SET personal = 1 WHERE member_id = '".$_SESSION['member_id']."'");
    } 
                redirect('index.php?pages=index');
            
    }
    }
    if ($_GET['tempid']!=""){
          $qry = tep_fetch_object(tep_query("SELECT * FROM temp_".PERSONAL." p, ".MEMBER." m WHERE p.member_id = m.member_id AND temp_id = '".$_GET['tempid']."'"));
    }else{
          $qry = tep_fetch_object(tep_query("SELECT * FROM ".PERSONAL." p, ".MEMBER." m WHERE p.member_id = m.member_id AND (m.member_id = '".$_SESSION['member_id']."' OR m.member_id = '".$_GET['id']."')"));

    }

  if ($editable =="0" || $editable =="admin" || $editable=="view"){
    $name = strtoupper($qry->member_name);
    $ic = $ic1."-".$ic2."-".$ic3;
    
    $citizenship = 'Malaysian';
    switch ($qry->member_sex){
        case 'M':
            $sex = "MALE";
            break;
        case 'F':
            $sex ="FEMALE";
            break;
    }
    $marital_status = strtoupper($qry->member_marital_status);
    $dob = $qry->member_dob;
    $pob = strtoupper($qry->member_pob);
    
    $age = "<div style='margin-left:25px;display:inline-block'>Age : " .$count_age."</div>";
    $h_address = explode('|',$qry->member_home_address);
    $home_address = strtoupper($h_address[0]);
    $home_address2 = strtoupper($h_address[1]);
    $home_postcode = $h_address[2];
    $home_state = strtoupper($h_address[3]);
    $p_address = explode("|",$qry->member_postal_address);
    $postal_address = '<tr><td colspan="4">'.strtoupper($p_address[0]).'</td></tr>
                        <tr><td colspan="4">'.strtoupper($p_address[1]).'</td></tr>
                        <tr><td width="60px">Postcode: </td><td>'.$p_address[2].'</td><td>State:</td><td>'.strtoupper($p_address[3]).'</td></tr>';

    $home_phone = $qry->member_home_number;
    $mobile_phone = $qry->member_mobile_number;
    $email = strtoupper($qry->member_email);
    switch ($qry->studies_status){
        case 0:
            $studies_status = '<tr><td colspan="2">Applying to enter university / college for undergraduate program</td></tr>';
            break;
        case 1:
            $studies_status = '<tr><td colspan="2">Currently pursuing undergraduate studies</td></tr>
                        <tr><td>Current Academic Year</td><td>'.$qry->current_year.'</td></tr>   
                        <tr><td>Current Semester</td><td>'.$qry->current_semester.'</td></tr>';
            break;
    }
    
        if ($editable=="0"){
            $btn = '';
        }else{
            if ($editable=="view"){
                if ($_GET['tempid']!=""){
                $btn = '<input type="button" value="Next" onclick="location.href=\'index.php?pages=academic_details&id='.$_GET['id'].'&tempid='.$_GET['tempid'].'\'" style="padding:3px;width:100px"/>';
                }else{
                  $btn = '<input type="button" value="Edit" style="padding:3px;width:100px" onclick="redirect(\'index.php?action=edit\')"/> <input type="button" value="Next" onclick="location.href=\'index.php?pages=academic_details\'" style="padding:3px;width:100px"/>';
                }
            }else{
                   $btn = '<input type="button" value="Edit" style="padding:3px;width:100px" onclick="redirect(\'index.php?id='.$_GET['id'].'&action=edit\')"/>  <input type="button" value="Next" onclick="location.href=\'index.php?pages=academic_details&id='.$_GET['id'].'\'" style="padding:3px;width:100px"/>';
            }
            
        }
  }else if ($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){
   
      if ($editable =="0" || $editable=="blank"){
        
          redirect ("index.php?permission=denied");
      }else{
            
            $name = '<input type="text" name="member_name" value="'.strtoupper($qry->member_name).'" style="width:300px" required/>';
            $ic = '<input type="text" name="member_ic1" id="ic" maxlength="6" style="width:45px" value="'.$ic1.'" disabled/> - <input type="text" name="member_ic2" maxlength="2" style="width:15px" value="'.$ic2.'" disabled/> - <input type="text" name="member_ic3" maxlength="4" style="width:30px" value="'.$ic3.'" disabled/>';
            $citizenship = '<label><input type="radio" name="member_citizenship" value="1" id="malaysia" checked required/>Malaysian</label>  <label><input type="radio" name="member_citizenship" value="0" id="non_malaysian" />Non- Malaysian</label>';
            if($qry->member_sex =="M"){
                $m_checked = "checked";
                $f_checked = "";
            }else if($qry->member_sex =="F"){
                $m_checked = "";
                $f_checked = "checked";
            }
            $sex = '<label><input type="radio" name="member_sex" value="M" '.$m_checked.' required/>Male</label> <label><input type="radio" name="member_sex" value="F" '.$f_checked.'/>Female</label>';
            if ($qry->member_marital_status =="Single"){
                $single_checked = "checked";
                $married_checked ="";
                $other_status = "";
            }else if($qry->member_marital_status == "Married"){
                $married_checked = "checked";
                $single_checked ="";
                $others_status ="";
            }else{
                 $married_checked = "checked";
                $single_checked ="";
                $other_status ="checked";
                $status = $qry->member_marital_status;
            }
            $marital_status = '<label><input type="radio" name="member_marital_status" value="Single" '.$single_checked.' onclick="PopStatus(this.value);" required/>Single</label> <label><input type="radio" name="member_marital_status" value="Married" '.$married_checked.' onclick="PopStatus(this.value);"/>Married</label> <label><input type="radio" id="selection_status" name="member_marital_status" value="Other" '.$other_status.' onclick="PopStatus(this.value);"/>Other</label> <div id="marital_status" style="display:none"> , please specify : <input type="text" name="other_status"/></div>';
            $dob = '<input type="text" name="member_dob"  value="'.$member_dob.'"  disabled/>';
            $pob ='<select name="member_pob" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_state()),$qry->member_pob).'</select>';

            $h_address = explode('|',$qry->member_home_address);
            $home_address ='<input type="text" name="home_address" value="'.strtoupper($h_address[0]).'" style="width:450px;margin-left:-3px" required/>';
            $home_address2 ='<input type="text" name="home_address2" value="'.strtoupper($h_address[1]).'" style="width:450px;margin-left:-3px"/>';
            $home_postcode = '<input type="text" name="home_postcode" maxlength="5" style="width:100px" value="'.$h_address[2].'" required/>';
            $home_state = '<select name="home_state" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_state()),$h_address[3]).'</select>';

            if ($qry->member_postal_address == $qry->member_home_address){
               $postal_address = '<tr><td><label><input type="checkbox" name="same_address"  id="same_address" style="margin-left:-3px" value="1" checked/>Same as above</label></td></tr>
                                <tr><td><div id="postal_address" style="display:none"><table style="margin-top:-17px;margin-bottom:-20px"><tr><td colspan="4"><input type="text" name="postal_address" value="'.strtoupper($p_address[0]).'" style="width:450px;margin-left:-3px" required/></td></tr>
                                <tr><td colspan="4"><input type="text" name="postal_address2" value="'.strtoupper($p_address[1]).'" style="width:450px;margin-left:-3px"/></td></tr>
                                <tr><td width="60px">Postcode: </td><td><input type="text"  name="postal_postcode" maxlength="5" style="width:100px" value="'.$p_address[2].'" required/></td><td>State:</td><td><select name="postal_state" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_state()),$p_address[3]).'</select></td></tr>
                                </table></div></td></tr>';
            }else{
                $p_address = explode("|",$qry->member_postal_address);
               $postal_address = '<tr><td><label><input type="checkbox" name="same_address"  id="same_address" style="margin-left:-3px" value="1"/>Same as above</label></td></tr>
                                <tr><td><div id="postal_address" style="margin:0">
                                <table style="margin-top:-17px;margin-bottom:-20px;margin-left:-4px;"><tr><td colspan="4"><input type="text" name="postal_address" value="'.strtoupper($p_address[0]).'" style="width:450px;margin-left:-3px" required/></td></tr>
                                <tr><td colspan="4"><input type="text" name="postal_address2" value="'.strtoupper($p_address[1]).'" style="width:450px;margin-left:-3px"/></td></tr>
                                <tr><td width="60px">Postcode: </td><td><input type="text"  name="postal_postcode" maxlength="5" style="width:100px" value="'.$p_address[2].'" required/></td><td>State:</td><td><select name="postal_state" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_state()),$p_address[3]).'</select></td></tr>
                               </table>
                                </td></tr>';
            }
            $h_phone = explode("-",$qry->member_home_number);
            $m_phone = explode("-",$qry->member_mobile_number);
            
            $home_phone ='<input type="text" name="member_home_number" value="'.$h_phone[0].'" style="width:30px" maxlength="4"/> - <input type="text" name="member_home_number2" value="'.$h_phone[1].'"/>';
            $mobile_phone = '<input type="text" name="member_mobile_number" value="'.$m_phone[0].'" style="width:30px" maxlength="4"/> - <input type="text" name="member_mobile_number2" value="'.$m_phone[1].'"/>';
            $email ='<input type="text" name="member_email" value="'.strtoupper($qry->member_email).'" disabled style="width:250px"/>';
            if ($qry->studies_status == 0){
                $applying = "checked";
                $undergraduate = "";
            }else{
                $applying = "";
                $undergraduate = "checked";
            }

            if ($undergraduate =="checked"){
                $academic = '<div id="academic"><table><tr id="academic_year"><td width="180px">Current Academic Year</td><td><select name="study_year">'.ddlReplace(arrToDDL(tep_academic_year()),$qry->current_year).'</select></td></tr>   
                                <tr id="academic_semester"><td>Current Semester</td><td><select name="study_semester" required>'.ddlReplace(arrToDDL(tep_academic_semester()),$qry->current_semester).'</select></td></tr></table></div>';
            }else{
                $academic='<div id="academic" style="display:none"><table><tr id="academic_year"><td width="180px">Current Academic Year</td><td><select name="study_year">'.ddlReplace(arrToDDL(tep_academic_year()),$qry->current_year).'</select></td></tr>   
                                <tr id="academic_semester"><td>Current Semester</td><td><select name="study_semester">'.ddlReplace(arrToDDL(tep_academic_semester()),$qry->current_semester).'</select></td></tr></table></div>';
            }
            $studies_status = ' <tr><td colspan="2"><label><input type="radio" name="studies_status" style="margin-left:-3px" value="0" '.$applying.' onclick="studyStatus(this.value);"/>Applying to enter university / college for undergraduate program</label></td></tr>
                                <tr><td colspan="2"><label><input type="radio" name="studies_status" id="pursuing_studies" style="margin-left:-3px" value="1" '.$undergraduate.' onclick="studyStatus(this.value);"/>Currently pursuing undergraduate studies</label></td></tr>
                                <tr><td>'.$academic.'</td></tr>';
            
            $age = '<div id="age" style="display:inline-block">Age : '.$count_age.'</div>';
            
                    $btn = '<input type="button" value="Cancel" onclick="redirect(\'index.php\')" style="padding:3px;width:100px"/> <input type="submit" name="submit" value="Save" style="padding:3px;width:100px"/> ';
            
  }
  }else{
    $name = '<input type="text" name="member_name" value="'.strtoupper($_POST['member_name']).'" style="width:300px" required/>';
    $ic = '<input type="text"  maxlength="6" style="width:45px" value="'.$ic1.'" disabled/> - <input type="text" name="member_ic2" maxlength="2" style="width:15px" value="'.$ic2.'" disabled/> - <input type="text" name="member_ic3" maxlength="4" style="width:30px" value="'.$ic3.'" disabled/>';
    $citizenship = '<label><input type="radio" name="member_citizenship" value="1" id="malaysia" required/>Malaysian</label>  <label><input type="radio" name="member_citizenship" value="0" id="non_malaysian" />Non- Malaysian</label> <label for="member_citizenship" class="error">Please select your gender</label>';
    $sex = '<label><input type="radio" name="member_sex" value="M" required/>Male</label> <label><input type="radio" name="member_sex" value="F"/>Female</label> <label for="member_sex" class="error">Please select your gender</label>';
    $marital_status = '<label><input type="radio" name="member_marital_status" value="Single"  onclick="PopStatus(this.value);" required/>Single</label> <label><input type="radio" name="member_marital_status" value="Married" onclick="PopStatus(this.value);"/>Married</label> <label><input type="radio" id="selection_status" name="member_marital_status" value="Other" onclick="PopStatus(this.value);"/>Other</label> <div id="marital_status" style="display:none"> , please specify : <input type="text" name="other_status"/></div> <label for="member_marital_status" class="error">Please select your gender</label>';
    $dob = '<input type="text" name="member_dob"  value="'.$member_dob.'" id="dob" disabled/>';
    $pob ='<select name="member_pob" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_state()),$_POST['member_pob']).'</select>';
    $home_address ='<input type="text" name="home_address" value="'.strtoupper($_POST['home_address']).'" style="width:345px;margin-left:-3px" required/>';
    $home_address2='<input type="text" name="home_address2" value="'.strtoupper($_POST['home_address2']).'" style="width:345px;margin-left:-3px"/>';
    $home_postcode = '<input type="text" name="home_postcode" maxlength="5" style="width:100px" value="'.strtoupper($_POST['home_postcode']).'" required/>';
    $home_state = '<select name="home_state" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_state()),$_POST['home_state']).'</select>';
    $postal_address = '<tr><td><label><input type="checkbox" name="same_address" id="same_address" style="margin-left:-3px" value="1" />Same as above</label></td></tr>
                        <tr><td><div id="postal_address" style="display:inline-block;margin:0"><table><tr><td colspan="4"><input type="text" name="postal_address" value="'.strtoupper($_POST['postal_address']).'" style="width:345px;margin-left:-3px" required/></td></tr>
                            <tr><td colspan="4"><input type="text" name="postal_address2" value="'.strtoupper($_POST['postal_address2']).'" style="width:345px;margin-left:-3px"/></td></tr>
                        <tr><td width="60px">Postcode: </td><td><input type="text"  name="postal_postcode" maxlength="5" style="width:100px" value="'.$_POST['postal_postcode'].'" required/></td><td>State:</td><td><select name="postal_state" required><option value="">-</option>'.arrToDDL(tep_state($_POST['postal_state'])).'</select></td</tr>
                        </table></div></td></tr>';
    $home_phone ='<input type="text" name="member_home_number" value="'.$_POST['member_home_number'].'" style="width:30px" maxlength="4"/> - <input type="text" name="member_home_number2" value="'.$_POST['member_home_number2'].'"/>';
    $mobile_phone = '<input type="text" name="member_mobile_number" value="'.$_POST['member_mobile_number'].'" style="width:30px" maxlength="4"/> - <input type="text" name="member_mobile_number2" value="'.$_POST['member_mobile_number2'].'"/>';
    $email ='<input type="text" name="member_email" value="'.strtoupper($_SESSION['member_email']).'" disabled style="width:250px"/>';
    $studies_status = ' <fieldset><tr><td colspan="2"><label><input type="radio" name="studies_status" style="margin-left:-3px" value="0"  onclick="studyStatus(this.value);" required/>Applying to enter university / college for undergraduate program</label></td></tr>
                        <tr><td colspan="2"><label><input type="radio" name="studies_status" id="pursuing_studies" style="margin-left:-3px" value="1"  onclick="studyStatus(this.value);"/>Currently pursuing undergraduate studies</label></td></tr>
                        <tr><td><div id="academic" style="display:none"><table><tr id="academic_year"><td width="180px">Current Academic Year</td><td><select name="study_year">'.ddlReplace(arrToDDL(tep_academic_year()),$_POST['study_year']).'</select></td></tr>   
                        <tr id="academic_semester"><td>Current Semester</td><td><select name="study_semester">'.ddlReplace(arrToDDL(tep_academic_semester()),$_POST['study_semester']).'</select></td></tr></table></div></td></tr><tr><td><label for="studies_status" class="error">Please select your gender</label></td></tr></fieldset>';
    $age = '<div id="age">Age : '.$count_age.'</div>';
    $btn = '<input type="submit" name="submit" value="Save" style="padding:3px;width:100px"/> <input type="button" value="Next" onclick="location.href=\'index.php?pages=academic_details\'" style="padding:3px;width:100px"/>';
  }
?>

<p style="font-weight:bold;font-size:18px;text-align:center">Scholarship Application</p>

<div class="form_container">
    <div style="background-color:rgb(240, 240, 240);text-align:center;padding:5px;font-size:14px;"><b>CLOSING DATE: 30 April 2015</b></div>
    
    <form method="post" id="form">
    <div style="background-color:rgb(231, 98, 123);text-align:center;padding:5px;font-size:14px;margin-bottom:10px"><b>A. PERSONAL PARTICULARS</b></div>
    <div class="form_content">
        <table class="main_form" width="100%">
            <tr>
                <td width="200px">Name (as per MyKad)</td><td width="500px"><?=$name?></td>
            </tr>
            <tr>
                <td>NRIC No.</td><td><?=$ic?></td>
            </tr>
                <tr>
                    <td>Citizenship</td><td><fieldset><?=$citizenship?></fieldset></td>
                </tr>
                <tr>
                    <td>Sex</td><td><fieldset><?=$sex?></fieldset></td>
                </tr>
            <tr>
                <td>Marital Status</td><td><fieldset><?=$marital_status?></fieldset></td>
            </tr>
            <tr>
                <td>Date of Birth</td><td><?=$dob?>    <?=$age?></td>
            </tr>
            <tr>
                <td>Place of Birth</td><td><?=$pob?></td>
            </tr>
            <tr>
                <td valign="top">Home Address (Permanent)</td>
                <td valign="top">
                    <table style="margin-top:-17px;margin-bottom:-20px;">
                        <tr><td colspan="4"><?=$home_address?></td></tr>
                        <tr><td colspan="4"><?=$home_address2?></td></tr>
                        <tr><td width="60px">Postcode: </td><td><?=$home_postcode?></td><td>State:</td><td><?=$home_state?></td></tr>
                        <tr></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top">Postal Address</td>
                <td>
                    <table style="margin-top:-17px;margin-bottom:-20px;">
                        <?=$postal_address?>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td>
                    <table style="margin-top:-17px;margin-bottom:-17px;">
                        <tr><td>Home: </td><td width=""><?=$home_phone?></td><td>Mobile: </td><td><?=$mobile_phone?></td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Email</td><td><?=$email?></td>
            </tr>
            <tr>
                <td valign="top">Present status at time of application</td>
                <td>
                    <table style="margin-top:-17px;margin-bottom:-20px;margin-left:-4px">
                       <?=$studies_status?>
          
                    </table>
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="right"><?=$btn?></td> 
            </tr>
        </table>
    </div>
    </form>
</div>