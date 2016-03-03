<style>
    div#error {  color: #c00;
  font-size: 100%;
  font-weight: bold;
  font-variant: small-caps;
  width: 260px;
  padding: 3px 0px 0px 5px;
  clear: both;}
</style>
<?php

 if ($_GET['permission']=="denied"){
              echo '<script>alert("You don\'t have permission to do this action")</script>';
}

if ($_GET['course']=="empty"){
              echo '<script>alert("Please fill in your program applied!")</script>';
}

?>
<script type="text/javascript">
function calculateDuration(){
    
    var year1= $("#enrollment_year").val();
    var year2= $("#completion_year").val();
    var month1=$("#enrollment_month").val();
    var month2= $("#completion_month").val();
    if(month1===0){ 
      month1++;
      month2++;
    }
    
var numberOfMonths; 
        numberOfMonths = (year2 - year1) * 12 + (month2 - month1) + 1;
    $("#course_duration").text(numberOfMonths+" months");
    
 
}

function chk_function(){
        if ($("#1").is(":checked")){
            if ($("#input-1").val() ===""){
                $("#error").show();
                 return false;
            }
            }else if ($("#2").is(":checked")){
            if ($("#input-2").val() ===""){
                $("#error").show();
               
                return false;
            }
        }else if ($("#3").is(":checked")){
            if ($("#input-3").val() ===""){
                $("#error").show();
               
                return false;
            }
        }
            else  if ($("#4").is(":checked")){
            if ($("#input-4").val() ===""){
                $("#error").show();
              
                return false;
            }
        }else  if ($("#5").is(":checked")){
            if ($("#input-5").val() ===""){
                $("#error").show();
                
                return false;
            }
                }

            }
</script>

<?php

$check = tep_query("SELECT * FROM ".COURSE." c, ".MEMBER." m  WHERE c.member_id = m.member_id AND (c.member_id = '".$_SESSION['member_id']."' OR c.member_id = '".$_GET['id']."')");
    if (tep_num_rows($check)>0){
      while ($re = tep_fetch_object($check)){
             $complete = $re->complete;
             $course = $re->course;
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
          if ($course =="1"){
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
      
        if ($_POST['fees_applied']>100000){
            echo '<script>alert("Total tuition fees cannot exceed RM100,000!")</script>';
        }else{
            switch ($_POST['faculty']){
                case "science":
                    $course_name = "Science|".$_POST['science_field'];
                    break;
                case "technology":
                    $course_name="Technology|".$_POST['technology_field'];
                    break;
                case "engineering":
                    $course_name="Engineering|".$_POST['engineering_field'];
                    break;
                case "mathematics":
                    $course_name="Mathematics|".$_POST['mathematics_field'];
                    break;
                case "accountancy":
                    $course_name="Accountancy|".$_POST['accountancy_field'];
                    break;
                default:
                    $course_name ="";
            }
            
          
            $course_enrollment = $_POST['enrollment_month']."|".$_POST['enrollment_year'];
            $course_completion = $_POST['completion_month']."|".$_POST['completion_year'];
            
          if ($editable =="1"){
                        if ($editable=="1" && $_SESSION['user_id']=="1"){
                                   $id = $_GET['id'];
                               }else{
                                   $id = $_SESSION['member_id'];
                               }
                 
                tep_query("UPDATE ".COURSE." SET course_name = '".$course_name."',
                                                                     college_name = '".tep_input($_POST['institution'])."', 
                                                                     course_enrollment = '".$course_enrollment."', 
                                                                     course_completion =  '".$course_completion."',
                                                                     scholarship_duration = '".tep_input($_POST['duration_applied'])."',
                                                                     scholarship_apply ='".tep_input($_POST['fees_applied'])."'
                                                                     WHERE member_id = '".$id."'");
                
                   if ($_GET['action']=="edit" && $_SESSION['user_id']!=""){
                        tep_query("INSERT INTO ".TEMP."(member_id,action,temp_group,temp_createdby,temp_created) 
                                                  VALUES('".$_GET['id']."', 'Edit Course Details', '0', '".$_SESSION['user_id']."',NOW())");
                        redirect('index.php?pages=course_details&id='.$_GET['id'].'');
                    }else{
                         redirect('index.php?pages=course_details');
                    }
                 
          }else{
                 
                  tep_query("INSERT INTO ".COURSE."(member_id,course_name,college_name,course_enrollment,course_completion,scholarship_duration,scholarship_apply) 
                          VALUES('".$_SESSION['member_id']."', '".$course_name."', '".tep_input($_POST['institution'])."', '".$course_enrollment."', '".$course_completion."' ,'".tep_input($_POST['duration_applied'])."','".tep_input($_POST['fees_applied'])."' )");
                    
                  tep_query("UPDATE ".MEMBER." SET course = 1  WHERE member_id = '".$_SESSION['member_id']."'");
                   
                                    redirect("index.php?pages=course_details");
                
               }
        
        }        
    }
        
    if ($_GET['tempid']!=""){
            $qry = tep_fetch_object(tep_query("SELECT * FROM temp_".COURSE." WHERE  temp_id = '".$_GET['tempid']."'"));
    }else{
            $qry = tep_fetch_object(tep_query("SELECT * FROM ".COURSE." WHERE (member_id = '".$_SESSION['member_id']."' OR member_id = '".$_GET['id']."')"));
    }
    
    
    if ($editable =="0" || $editable =="admin" || $editable=="view"){
        $course_study = explode("|",$qry->course_name);
        $program_applied = strtoupper($course_study[0]).' | BACHELOR\'S DEGREE IN  '.strtoupper($course_study[1]);
        $institution = strtoupper($qry->college_name);
        $enroll = explode('|',$qry->course_enrollment);
        $complete = explode('|',$qry->course_completion);
        
       switch ($enroll[0]){
           case "1":
               $menroll = "January";
               break;
           case "2":
              $menroll = "February";
               break;
           case "3":
              $menroll = "March";
               break;
           case "4":
              $menroll = "April";
               break;
           case "5":
              $menroll = "May";
               break;
           case "6":
              $menroll = "June";
               break;
           case "7":
              $menroll = "July";
               break;
           case "8":
              $menroll = "August";
               break;
           case "9":
              $menroll = "September";
               break;
           case "10":
              $menroll = "October";
               break;
           case "11":
              $menroll = "November";
               break;
           case "12":
              $menroll = "December";
               break;
       }
       
        switch ($complete[0]){
           case "1":
               $mcom = "January";
               break;
           case "2":
              $mcom = "February";
               break;
           case "3":
              $mcom = "March";
               break;
           case "4":
              $mcom = "April";
               break;
           case "5":
              $mcom = "May";
               break;
           case "6":
              $mcom = "June";
               break;
           case "7":
              $mcom = "July";
               break;
           case "8":
              $mcom = "August";
               break;
           case "9":
              $mcom = "September";
               break;
           case "10":
              $mcom = "October";
               break;
           case "11":
              $mcom = "November";
               break;
           case "12":
              $mcom = "December";
               break;
       }
         
 
        
        $date_enrollment = strtoupper($menroll)."  ".$enroll[1];
        $date_completion = strtoupper($mcom)."   ".$complete[1];
        $duration = ($complete[1] - $enroll[1]) * 12 + ($mcom - $menroll) + 1;
        $duration_applied = $qry->scholarship_duration." months";
        $fees_applied = '<table style="margin-left:-3px">
                                    <tr><td>'.number_format($qry->scholarship_apply,2).'</td></tr>
                                    <tr><td>(Note: For tuition fee, maximum scholarship awarded is up to RM100,000 for the entire course of study. Any additional costs will be borne by scholars.
                                    PFF scholarship only takes effect upon execution of agreement and does not cover retrospective tuition fees and subsistence allowance)</td></tr>
                                    </table>';
        $course_duration = $duration;
       if ($editable=="0"){
            $btn = '';
        }else{
             if ($editable=="view"){
                 if ($_GET['tempid']!=""){
                    $btn = '<input type="button" value="Previous" onclick="location.href=\'index.php?pages=academic_details&id='.$_GET['id'].'&tempid='.$_GET['tempid'].'\'" style="padding:3px;width:100px"/>  <input type="button" value="Next" onclick="location.href=\'index.php?pages=financial_information&id='.$_GET['id'].'&tempid='.$_GET['tempid'].'\'" style="padding:3px;width:100px"/>';
                 }else{
                    $btn = '<input type="button" value="Previous" onclick="location.href=\'index.php?pages=academic_details\'" style="padding:3px;width:100px"/> <input type="button" value="Edit" style="padding:3px;width:100px" onclick="redirect(\'index.php?pages=course_details&action=edit\')"/> <input type="button" value="Next" onclick="location.href=\'index.php?pages=financial_information\'" style="padding:3px;width:100px"/>';
                 }
            }else{
            $btn = '<input type="button" value="Previous" onclick="location.href=\'index.php?pages=academic_details&id='.$_GET['id'].'\'" style="padding:3px;width:100px"/>   <input type="button" value="Edit" style="padding:3px;width:100px" onclick="redirect(\'index.php?pages=course_details&id='.$_GET['id'].'&action=edit\')"/>   <input type="button" value="Next" onclick="location.href=\'index.php?pages=financial_information&id='.$_GET['id'].'\'" style="padding:3px;width:100px"/>';
            }
        }
    
    }elseif($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){
        
         if ($editable =="0" || $editable=="blank"){
          redirect ("index.php?pages=course_details&permission=denied");
      }else{
                $course = explode("|",$qry->course_name);

                switch ($course[0]){
                    case "Science":
                        $schecked = "checked";
                        $sfield = $course[1];
                        $tchecked = "";
                        $tfield ="";
                        $echecked = "";
                        $efield="";
                        $mchecked = "";
                        $mfield="";
                        $achecked = "";
                        $afield="";
                        break;
                     case "Technology":
                        $schecked = "";
                        $sfield = "";
                        $tchecked = "checked";
                        $tfield =$course[1];
                        $echecked = "";
                        $efield="";
                        $mchecked = "";
                        $mfield="";
                        $achecked = "";
                        $afield="";
                        break;
                     case "Engineering":
                        $schecked = "";
                        $sfield = "";
                        $tchecked = "";
                        $tfield ="";
                        $echecked = "checked";
                        $efield=$course[1];
                        $mchecked = "";
                        $mfield="";
                        $achecked = "";
                        $afield="";
                        break;
                     case "Mathematics":
                        $schecked = "";
                        $sfield = "";
                        $tchecked = "";
                        $tfield ="";
                        $echecked = "";
                        $efield="";
                        $mchecked = "checked";
                        $mfield=$course[1];
                        $achecked = "";
                        $afield="";
                        break;
                     case "Accountancy":
                        $schecked = "";
                        $sfield = "";
                        $tchecked = "";
                        $tfield ="";
                        $echecked = "";
                        $efield="";
                        $mchecked = "";
                        $mfield="";
                        $achecked = "checked";
                        $afield=$course[1];
                        break;
                    default:
                        $schecked = "";
                        $sfield = "";
                        $tchecked = "";
                        $tfield ="";
                        $echecked = "";
                        $efield="";
                        $mchecked = "";
                        $mfield="";
                        $achecked = "";
                        $afield="";
                }
                $program_applied = '<fieldset><table>
                                    <tr><td colspan="2">(Note: Only MQA recognized courses in Science, Technology, Engineering, Mathematics and Accountancy are eligible to apply)</td></tr>
                                    <tr><td colspan="2">Field of study and degree title:</td></tr>
                                    <tr><td width="105px"><label><input type="radio" name="faculty" value="science" '.$schecked.' required id="1"/>Science </td><td>| Bachelor’s Degree in <input type="text" name="science_field" style="width:330px" value="'.strtoupper($sfield).'" id="input-1"/></label></td></tr>
                                    <tr><td><label><input type="radio" name="faculty"  value="technology" '.$tchecked.' id="2"/>Technology </td><td>| Bachelor’s Degree in <input type="text" name="technology_field" style="width:330px" value="'.strtoupper($tfield).'" id="input-2"/></label></td></tr>
                                    <tr><td><label><input type="radio" name="faculty" value="engineering" '.$echecked.' id="3"/>Engineering </td><td>| Bachelor’s Degree in <input type="text" name="engineering_field" style="width:330px" value="'.strtoupper($efield).'" id="input-3"/></label></td></tr>
                                    <tr><td><label><input type="radio" name="faculty" value="mathematics" '.$mchecked.' id="4"/>Mathematics </td><td>| Bachelor’s Degree in <input type="text" name="mathematics_field" style="width:330px" value="'.strtoupper($mfield).'" id="input-4"/></label></td></tr>
                                    <tr><td><label><input type="radio" name="faculty" value="accountancy" '.$achecked.' id="5"/>Accountancy  </td><td>| Bachelor’s Degree in <input type="text" name="accountancy_field" style="width:330px" value="'.strtoupper($afield).'" id="input-5"/></label></td></tr>
                                   <label for="faculty" class="error">Please select your gender</label><div id="error" style="display:none">Course subject must not be blank</div>
                                    </table></fieldset>';
                $institution = '<input type="text" name="institution" value="'.strtoupper($qry->college_name).'" style="width:580px" required/>';
                $enroll = explode('|',$qry->course_enrollment);
                $complete = explode('|',$qry->course_completion);
                $date_enrollment = '<select name="enrollment_month" onchange="calculateDuration()" id="enrollment_month" required><option value="">-</option>'.ddlReplace(arrToDDLWithKey(tep_month()),$enroll[0]).'</select>  <select name="enrollment_year" id="enrollment_year" onchange="calculateDuration()" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_courseYear()),$enroll[1]).'</select>';
                $date_completion = '<select name="completion_month" onchange="calculateDuration()" id="completion_month" required><option value="">-</option>'.ddlReplace(arrToDDLWithKey(tep_month()),$complete[0]).'</select>  <select name="completion_year" id="completion_year" onchange="calculateDuration()" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_CompletionYear()),$complete[1]).'</select>';
               $duration = ($complete[1] - $enroll[1]) * 12 + ($complete[0] - $enroll[0]) + 1;
                $course_duration = '<div id="course_duration">'.$duration.' months</div>';
                $duration_applied = '<select name="duration_applied" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_durationApplied()),$qry->scholarship_duration).'</select>';
                $fees_applied = '<table style="margin-left:-3px">
                                            <tr><td><input type="text" name="fees_applied" value="'.number_format($qry->scholarship_apply,2,".","").'" style="width:200px" required/></td></tr>
                                            <tr><td>(Note: For tuition fee, maximum scholarship awarded is up to RM100,000 for the entire course of study. Any additional costs will be borne by scholars.
                                                PFF scholarship only takes effect upon execution of agreement and does not cover retrospective tuition fees and subsistence allowance)</td></tr>
                                            </table>';
               
                $btn = '<input type="button" value="Cancel" onclick="redirect(\'index.php?pages=course_details\')" style="padding:3px;width:100px"/>  <input type="submit" name="submit" value="Save" style="padding:3px;width:100px" />';
               
    }   
    }else{
    
        $program_applied = '<fieldset><table>
                            <tr><td colspan="2">(Note: Only MQA recognized courses in Science, Technology, Engineering, Mathematics and Accountancy are eligible to apply)</td></tr>
                            <tr><td colspan="2">Field of study and degree title:</td></tr>
                            <tr><td width="105px"><label><input type="radio" name="faculty" value="science" required id="1"/>Science </td><td>| Bachelor’s Degree in <input type="text" name="science_field" style="width:330px" value="'.strtoupper($_POST['science_field']).'" id="input-1"/></label></td></tr>
                            <tr><td><label><input type="radio" name="faculty"  value="technology" id="2"/>Technology </td><td>| Bachelor’s Degree in <input type="text" name="technology_field" style="width:330px" value="'.strtoupper($_POST['technology_field']).'" id="input-2"/></label></td></tr>
                            <tr><td><label><input type="radio" name="faculty" value="engineering" id="3"/>Engineering </td><td>| Bachelor’s Degree in <input type="text" name="engineering_field" style="width:330px" value="'.strtoupper($_POST['engineering_field']).'" id="input-3"/></label></td></tr>
                            <tr><td><label><input type="radio" name="faculty" value="mathematics" id="4"/>Mathematics </td><td>| Bachelor’s Degree in <input type="text" name="mathematics_field" style="width:330px" value="'.strtoupper($_POST['mathematics_field']).'" id="input-4"/></label></td></tr>
                            <tr><td><label><input type="radio" name="faculty" value="accountancy"  id="5"/>Accountancy  </td><td>| Bachelor’s Degree in <input type="text" name="accountancy_field" style="width:330px" value="'.strtoupper($_POST['accountancy_field']).'"  id="input-5"/></label></td></tr>
                        </table><label for="faculty" class="error">Please select your gender</label><div id="error" style="display:none">Course subject must not be blank</div></fieldset>';
        $institution = '<input type="text" name="institution" value="'.strtoupper($_POST['institution']).'" style="width:586px" required/>';
        $date_enrollment = '<select name="enrollment_month" onchange="calculateDuration()" id="enrollment_month" required><option value="">-</option>'.ddlReplace(arrToDDLWithKey(tep_month()),$_POST['enrollment_month']).'</select>  <select name="enrollment_year" id="enrollment_year" onchange="calculateDuration()" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_courseYear()),$_POST['enrollment_year']).'</select>';
        $date_completion = '<select name="completion_month" onchange="calculateDuration()" id="completion_month" required><option value="">-</option>'.ddlReplace(arrToDDLWithKey(tep_month()),$_POST['completion_month']).'</select>  <select name="completion_year" id="completion_year" onchange="calculateDuration()" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_CompletionYear()),$_POST['completion_year']).'</select>';
        $duration_applied = '<select name="duration_applied" required><option value="">-</option>'.arrToDDL(tep_durationApplied($_POST['duration_applied'])).'</select>';
        $fees_applied = '<table style="margin-left:-3px">
                                    <tr><td><input type="text" name="fees_applied" value="'.strtoupper($_POST['fees_applied']).'" style="width:200px" required/></td></tr>
                                    <tr><td>(Note: For tuition fee, maximum scholarship awarded is up to RM100,000 for the entire course of study. Any additional costs will be borne by scholars.
PFF scholarship only takes effect upon execution of agreement and does not cover retrospective tuition fees and subsistence allowance)</td></tr>
                                    </table>';
        $course_duration = '<div id="course_duration"></div>';
        $btn = '<input type="button" value="Previous" onclick="location.href=\'index.php?pages=academic_details\'" style="padding:3px;width:100px"/> <input type="submit" name="submit" value="Save" style="padding:3px;width:100px"/> <input type="button" value="Next" onclick="location.href=\'index.php?pages=financial_information\'" style="padding:3px;width:100px"/>';
    }
   
    
?>
<p style="font-weight:bold;font-size:18px;text-align:center">Scholarship Application</p>
<div class="form_container">
    
    <form method="post" id="form" onsubmit="return chk_function()">
    <div style="background-color:rgb(231, 98, 123);text-align:center;padding:5px;font-size:14px;margin-bottom:10px"><b>C. COURSE DETAILS</b></div>
    <div class="form_content">
        <div class="main_form">
            <table>
                <tr>
                    <td valign="top" width="300px">Program Applied / Attending</td>
                    <td><?=$program_applied?></td>
                </tr>
                <tr>
                    <td>Name of Institution</td>
                    <td><?=$institution?></td>
                </tr>
                <tr>
                    <td>Date of Enrollment</td>
                    <td><?=$date_enrollment?></td>
                </tr>
                <tr>
                    <td>Expected Date of Completion</td>
                    <td><?=$date_completion?></td>
                </tr>
                
                <tr>
                    <td valign="top">Tuition Fees (RM) applied excluding monthly subsistence allowance</td>
                    <td><?=$fees_applied?></td>
                </tr>
            </table>
            
            
            <div style="float:right;margin-bottom:10px"><?=$btn?> </div>
            <div class="cls"></div>
        </div>
    </div>
    </form>
</div>