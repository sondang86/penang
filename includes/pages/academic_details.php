<style>
    .details_table {width:100%}
</style>
<script>
    //Alert if exceeded defined min max value
    $(document).ready(function(){
        $('#examination_result').on("keyup",'#qualification_grade', function(){
            if($(this).val()>=4) {
                alert("Your CGPA cannot exceed 4.00. Thank you.");
            } ;
        });

        $('#examination_result').on("keyup",'#qualification_grade', function(){
            if($(this).val() < 3) {
                alert("Sorry to inform that you are not quality for scholarship application because your CGPA is less than 3.00");
            } ;
        });
    });
</script>

<?php

if ($_GET['qualified']=="not"){
     echo '<script>alert("You are not qualified to apply this fund!")</script>';
}

 if ($_GET['permission']=="denied"){
              echo '<script>alert("You don\'t have permission to do this action")</script>';
}
if ($_GET['tempid']!=""){
     $qry = tep_fetch_object(tep_query("SELECT * FROM temp_".ACADEMIC." WHERE  temp_id = '".$_GET['tempid']."'"));
     $status = tep_fetch_object(tep_query("SELECT * FROM temp_".PERSONAL." WHERE   temp_id = '".$_GET['tempid']."'"));
}else{
    $qry = tep_fetch_object(tep_query("SELECT * FROM ".ACADEMIC." WHERE (member_id = '".$_SESSION['member_id']."' OR member_id = '".$_GET['id']."')"));
 $status = tep_fetch_object(tep_query("SELECT * FROM ".PERSONAL." WHERE (member_id = '".$_SESSION['member_id']."' OR member_id = '".$_GET['id']."')"));
}
 
 $check = tep_query("SELECT * FROM ".ACADEMIC." a, ".MEMBER." m WHERE a.member_id = m.member_id AND (a.member_id = '".$_SESSION['member_id']."' OR a.member_id = '".$_GET['id']."')");
    if (tep_num_rows($check)>0){
      while ($re = tep_fetch_object($check)){
             $complete = $re->complete;
             $academic = $re->academic;
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
          if ($academic =="1"){
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
 
 $examination_row = count(explode("|",$qry->examination_subject));
 $college_row = count(explode("|",$qry->college_subject));

if($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){
     $sub = $examination_row;
     if ($college_row < 5){
         $csub = 5;
     }else{
         $csub = $college_row;
     }
     if ($examination_row < 5){
         $sub = 5;
     }else{
         $sub = $examination_row;
     }
 }else{
      $sub = 5;
      $csub = 5;
 }

// $_SESSION['examination'] = 5;

 ?>
<script type="text/javascript">
$(function() {
	//normal datepicker
	$('.datepicker').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd/mm/yy',
                yearRange: "-25:+0",
	});	//blur selections


<?php if ($status->studies_status == "1"){ ?>
//university/college examination result
var getCourseRow = document.getElementById("course_row");
var count_examination = "<?php echo $csub?>";
 <?php if($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){?>
         var row = getCourseRow.getAttribute("rowspan");
    <?php }else{?>
            var row = "5";
    <?php }?>
$('#hCount').val(count_examination);
    $('#btnAdd').click(function(){
        count_examination++;
        row++;
        $('#hCount').val(count_examination);
        var rowData = $('#row_2').html().replace('_2', '_'+count_examination);
        var regex = new RegExp('_2', 'g');
        rowData = rowData.replace(regex, '_'+count_examination);
         document.getElementById("name_row").rowSpan = row;
        document.getElementById("course_row").rowSpan = row;
        $('#college_result').append('<tr id="row_'+count_examination+'" class="row">'+rowData+'</tr>');
        return false;
    });
    
<?php }else{  ?>
  //others qualification  
    var getRow = document.getElementById("year_row");
    var count = "<?php echo $sub?>";
    <?php if($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){?>
         var rowspan = getRow.getAttribute("rowspan");
    <?php }else{?>
            var rowspan = "5";
    <?php }?>
//    $('#oCount').val(count);
    $('#btnAdd_examination').click(function(){
        count++;
        $('#oCount').val(count);
        rowspan++;
        var rowData = $('#erow_2').html().replace('_2', '_'+count);
        var regex = new RegExp('_2', 'g');
        rowData = rowData.replace(regex, '_'+count);
        document.getElementById("stpm_row").rowSpan = rowspan;
        document.getElementById("year_row").rowSpan = rowspan;
        $('#examination_result').append('<tr id="erow_'+count+'">'+rowData+'</tr>');
        
        return false;
    });
<?php } ?>   
})


function OthersQualification(val){
 $(".highest_qualification").text(val);
 if(val=='Other'){
   $("#others_qualification").show();
   $('#input_others').on("input", function() {
  var dInput = this.value;
 <?php if ($_GET['action']== "edit" || $editable=="1" ){?>
     $(".highest_qualification").val(dInput);   
    <?php }else { ?>
    $(".highest_qualification").text(dInput);
    <?php } ?>
});

 }else{  
    $("#others_qualification").hide();
    <?php if ($_GET['action']== "edit" || $editable=="1" ){?>
     $(".highest_qualification").val(val);   
    <?php }else { ?>
    $(".highest_qualification").text(val);
    <?php } ?>
    }
    
   if(val=="STPM"){
       $("#others_result").hide();
       $("#stpm_result").show();
        }else{
       $("#others_result").show();
        $("#stpm_result").hide();
    }
}

//for stpm subject
function OthersSubject1(val){
 if(val=='Others')
     $("#others_subject1").show();
 else  
    $("#others_subject1").hide();

}

function OthersSubject2(val){
 if(val=='Others')
     $("#others_subject2").show();
 else  
    $("#others_subject2").hide();
}

function OthersSubject3(val){
if(val=='Others')
     $("#others_subject3").show();
 else  
    $("#others_subject3").hide();
}

function OthersSubject4(val){
 if(val=='Others')
     $("#others_subject4").show();
 else  
    $("#others_subject4").hide();
}

function hideSTPM() {
       $("#others_result").show();
        $("#stpm_result").hide();
}

$("#qualification_grade").validate({
    rules: {
        number: {required: true, min: 3, max: 4}
    }
});
</script>

<?php

    
    if ($_POST['submit']=="Save"){
          
            if ($_POST['coclub_1']!="" && $_POST['coschool_1']!=""){
                                     $co_year = $_POST['coyear_1']."|".$_POST['coyear_2']."|".$_POST['coyear_3'];
                                     $co_club = tep_input($_POST['coclub_1'])."|".tep_input($_POST['coclub_2'])."|".tep_input($_POST['coclub_3']);
                                     $co_position = $_POST['coposition_1']."|".$_POST['coposition_2']."|".$_POST['coposition_3'];
                                     $co_school = tep_input($_POST['coschool_1'])."|".tep_input($_POST['coschool_2'])."|".tep_input($_POST['coschool_3']);
                                 }
                                 if ($_POST['awards_1']!=""){
                                     $awards = tep_input($_POST['awards_1'])."|".tep_input($_POST['awards_2'])."|".tep_input($_POST['awards_3']);
                                     $aw_prize = $_POST['awprize_1']."|".$_POST['awprize_2']."|".$_POST['awprize_3'];
                                     $aw_level = $_POST['awlevel_1']."|".$_POST['awlevel_2']."|".$_POST['awlevel_3'];
                                     $aw_year = $_POST['awyear_1']."|".$_POST['awyear_2']."|".$_POST['awyear_3'];
                                 }
                                 
//                        calculate points         
                        if ($_POST['college_cgpa']!="" || $_POST['qualification_cgpa']!="" && (($_POST['qualification_cgpa']<'3.0' || $_POST['qualification_cgpa']>'4.0') || ($_POST['college_cgpa']<'3.0' || $_POST['college_cgpa']>'4.0'))){
                            if ($status->studies_status=='0'){
                                $academicPoints = ($_POST['qualification_cgpa'] - 3.0) * 75;
                            }else{
                                $academicPoints = ($_POST['college_cgpa'] - 3.0) * 75;
                            }
                        }
                        
                       
                       if ($_POST['coclub_1']!="" && $_POST['coschool_1']!=""){
                           switch ($_POST['coposition_1']){
                               case "President":
                                   $club1 = 3;
                                   break;
                               case "Vice President":
                                   $club1 = 2;
                                   break;
                               case "Treasurer":
                                   $club1 = 1;
                                   break;
                               case "Secretary":
                                   $club1 = 1;
                                   break;
                               case "Prefect":
                                   $club1 = 3;
                                   break;
                               case "Monitor":
                                   $club1 = 3;
                                   break;
                               default:
                                   $club1 = 0;
                                   break;
                           }
                       }
                       if ($_POST['coclub_2']!="" && $_POST['coschool_2']!=""){
                           switch ($_POST['coposition_2']){
                               case "President":
                                   $club2 = 3;
                                   break;
                               case "Vice President":
                                   $club2 = 2;
                                   break;
                               case "Treasurer":
                                   $club2 = 1;
                                   break;
                               case "Secretary":
                                   $club2 = 1;
                                   break;
                               case "Prefect":
                                   $club2 = 3;
                                   break;
                               case "Monitor":
                                   $club2 = 3;
                                   break;
                               default:
                                   $club2 = 0;
                                   break;
                           }
                       }
                       if ($_POST['coclub_3']!="" && $_POST['coschool_3']!=""){
                           switch ($_POST['coposition_3']){
                               case "President":
                                   $club3 = 3;
                                   break;
                               case "Vice President":
                                   $club3 = 2;
                                   break;
                               case "Treasurer":
                                   $club3 = 1;
                                   break;
                               case "Secretary":
                                   $club3 = 1;
                                   break;
                               case "Prefect":
                                   $club3 = 3;
                                   break;
                               case "Monitor":
                                   $club3 = 3;
                                   break;
                               default:
                                   $club3 = 0;
                                   break;
                           }
                       }
                     $total_club = $club1 + $club2 + $club3;     
                     
                     if ($_POST['awards_1']!=""){
                          switch ($_POST['awprize_1']){
                               case "First":
                                   $awprize1 = 2;
                                   break;
                               case "Second":
                                   $awprize1 = 1;
                                   break;
                               case "Third":
                                   $awprize1 = 1;
                                   break;
                               default:
                                   $awprize1 = 0;
                                   break;
                           }
                            switch ($_POST['awlevel_1']){
                               case "State":
                                   $awlevel1 = 1;
                                   break;
                               case "National":
                                   $awlevel1 = 2;
                                   break;
                               case "International":
                                   $awlevel1 = 3;
                                   break;
                               default:
                                   $awlevel1 = 0;
                                   break;
                           }
                     $award1 = $awprize1 * $awlevel1;
                     }
                     
                     if ($_POST['awards_2']!=""){
                          switch ($_POST['awprize_2']){
                               case "First":
                                   $awprize2 = 2;
                                   break;
                               case "Second":
                                   $awprize2 = 1;
                                   break;
                               case "Third":
                                   $awprize2 = 1;
                                   break;
                               default:
                                   $awprize2 = 0;
                                   break;
                           }
                            switch ($_POST['awlevel_2']){
                               case "State":
                                   $awlevel2 = 1;
                                   break;
                               case "National":
                                   $awlevel2 = 2;
                                   break;
                               case "International":
                                   $awlevel2 = 3;
                                   break;
                               default:
                                   $awlevel2 = 0;
                                   break;
                           }
                     $award2 = $awprize2 * $awlevel2;
                     }
                     
                     if ($_POST['awards_3']!=""){
                          switch ($_POST['awprize_3']){
                               case "First":
                                   $awprize3 = 2;
                                   break;
                               case "Second":
                                   $awprize3 = 1;
                                   break;
                               case "Third":
                                   $awprize3 = 1;
                                   break;
                               default:
                                   $awprize3 = 0;
                                   break;
                           }
                            switch ($_POST['awlevel_3']){
                               case "State":
                                   $awlevel3 = 1;
                                   break;
                               case "National":
                                   $awlevel3 = 2;
                                   break;
                               case "International":
                                   $awlevel3 = 3;
                                   break;
                               default:
                                   $awlevel3 = 0;
                                   break;
                           }
                     $award3 = $awprize3 * $awlevel3;
                     }
                     
                   $total_award = $award1 + $award2 + $award3;
                   
                   $sub_total = $academicPoints + $total_club + $total_award; 
                  
                   
          if ($status->studies_status=='0'){
        
              if ($_POST['qualification_cgpa']<'3.0' || $_POST['qualification_cgpa']>'4.0' ){
                 
                  redirect('index.php?pages=academic_details&qualified=not');
              }else{
                  
                       if ($_POST['academic_qualification']=="Other"){
                            $qualification_obtained = $_POST['others_qualification'];
                        }else{
                            $qualification_obtained = $_POST['academic_qualification'];
                        }
                 
                      
                      for($i=0;$i<=$_POST["oCount"];$i++){
                                    if($_POST["qualification_sub_".$i]!="" && $_POST["qualification_grade_".$i]!=""){
                                        $subjectsArray[] = $_POST["qualification_sub_".$i];
                                        $gradesArray[] = $_POST["qualification_grade_".$i];
                                    }
                                }
                                
                                if ($subjectsArray !="" || $gradesArray!=""){
                                    $qualification_subject = implode("|",$subjectsArray);
                                    $qualification_grades = implode("|",$gradesArray);
                                }
           
                
                        if ($editable == "1"){
                               if ($editable=="1" && $_SESSION['user_id']=="1"){
                                        $id = $_GET['id'];
                                    }else{
                                        $id = $_SESSION['member_id'];
                                    }

                        tep_query("UPDATE ".ACADEMIC." SET qualification_state = '".tep_input($_POST['academic_state'])."',
                                                                             qualification_school = '".tep_input($_POST['academic_school'])."', 
                                                                             qualification_year_commenced = '".tep_input($_POST['year_commenced'])."', 
                                                                             qualification_year_completed =  '".$_POST['year_completed']."',
                                                                             qualification_obtained = '".$qualification_obtained."',
                                                                             examination_year = '".tep_input($_POST['stpm_year'])."',
                                                                             examination_subject ='".$qualification_subject."',
                                                                             examination_grades = '".$qualification_grades."',
                                                                             examination_cgpa =  '".tep_input($_POST['qualification_cgpa'])."',
                                                                             co_year = '".$co_year."',
                                                                            co_club ='".$co_club."',
                                                                            co_position = '".$co_position."',
                                                                            co_school =  '".$co_school."',
                                                                             awards_name = '".$awards."',
                                                                             awards_prize = '".$aw_prize."',
                                                                             awards_level = '".$aw_level."',
                                                                             awards_year =  '".$aw_year."'   
                                                                             WHERE member_id = '".$id."'");

                        tep_query("UPDATE ".MEMBER." SET academic = 1, 
                                    academic_points = '".$academicPoints."',
                                    club_points = '".$total_club."' ,
                                    award_points = '".$total_award."' ,
                              total_points = '".$sub_total."'  WHERE member_id = '".$id."'");
                    
                                if ($_GET['action']=="edit" && $_SESSION['user_id']!=""){
                                     tep_query("INSERT INTO ".TEMP."(member_id,action,temp_group,temp_createdby,temp_created) 
                                                  VALUES('".$_GET['id']."', 'Edit Academic Details', '0', '".$_SESSION['user_id']."',NOW())");
                                    redirect('index.php?pages=academic_details&id='.$_GET['id'].'');
                                }else{
                                     redirect('index.php?pages=academic_details');
                                }
                        }else{
                         tep_query("INSERT INTO ".ACADEMIC."(qualification_state,qualification_school,qualification_year_commenced,qualification_year_completed,qualification_obtained,
                                                                                                examination_year,examination_subject,examination_grades,examination_cgpa,
                                                                                                 co_year,co_club,co_position,co_school,
                                                                                                awards_name,awards_prize,awards_level,awards_year,member_id) 
                                              VALUES('".tep_input($_POST['academic_state'])."', '".tep_input($_POST['academic_school'])."', '".tep_input($_POST['year_commenced'])."','".$_POST['year_completed']."', '".$qualification_obtained."',
                                                 '".tep_input($_POST['stpm_year'])."', '".$qualification_subject."', '".$qualification_grades."', '".tep_input($_POST['qualification_cgpa'])."' ,
                                                '".$co_year."', '".$co_club."', '".$co_position."', '".$co_school."', 
                                               '".$awards."', '".$aw_prize."', '".$aw_level."', '".$aw_year."', '".$_SESSION['member_id']."' )");
                         
                            tep_query("UPDATE ".MEMBER." SET academic = 1, 
                                    academic_points = '".$academicPoints."',
                                    club_points = '".$total_club."' ,
                                    award_points = '".$total_award."' ,
                              total_points = '".$sub_total."'  WHERE member_id = '".$_SESSION['member_id']."'");

                              redirect("index.php?pages=academic_details");
                       }
                
          }
          }else{
             
              if ($_POST['college_cgpa']<'3.0' || $_POST['college_cgpa']>'4.0'){
                   redirect('index.php?pages=academic_details&qualified=not');
              }else{
 
                          for($i=1;$i<=$_POST["hCount"];$i++){
                                    if($_POST["subject_".$i]!="" && $_POST["grade_".$i]!=""){
                                        $csubjecstArray[] = $_POST["subject_".$i];
                                        $cgradesArray[] = $_POST["grade_".$i];
                                    }
                                }
                              
                                 if ($csubjecstArray !="" || $cgradesArray!=""){
                                    $csubject = implode("|",$csubjecstArray);
                                     $cgrades = implode("|",$cgradesArray);
                                }
                           
             if($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){
      
                            if ($editable=="1" && $_SESSION['user_id']=="1"){
                                $id = $_GET['id'];
                            }else{
                                $id = $_SESSION['member_id'];
                            }

                tep_query("UPDATE ".ACADEMIC." SET college_name = '".tep_input($_POST['college_name'])."',
                                                                     college_course = '".tep_input($_POST['college_course'])."', 
                                                                     college_subject = '".$csubject."', 
                                                                     college_grades =  '".$cgrades."',
                                                                     college_cgpa = '".tep_input($_POST['college_cgpa'])."',
                                                                     co_year = '".$co_year."',
                                                                     co_club ='".$co_club."',
                                                                     co_position = '".$co_position."',
                                                                     co_school =  '".$co_school."',
                                                                      awards_name = '".$awards."',
                                                                      awards_prize = '".$aw_prize."',
                                                                      awards_level = '".$aw_level."',
                                                                      awards_year =  '".$aw_year."'   
                                                                     WHERE member_id = '".$id."'");
                
                    tep_query("UPDATE ".MEMBER." SET academic = 1, 
                                    academic_points = '".$academicPoints."',
                                    club_points = '".$total_club."' ,
                                    award_points = '".$total_award."' ,
                              total_points = '".$sub_total."'  WHERE member_id = '".$id."'");
                    
                     if ($_GET['action']=="edit" && $_SESSION['user_id']!=""){
                          tep_query("INSERT INTO ".TEMP."(member_id,action,temp_group,temp_createdby,temp_created) 
                                                  VALUES('".$_GET['id']."', 'Edit Academic Details', '0', '".$_SESSION['user_id']."',NOW())");
                        redirect('index.php?pages=academic_details&id='.$_GET['id'].'');
                    }else{
                         redirect('index.php?pages=academic_details');
                    }
                }else{
                  tep_query("INSERT INTO ".ACADEMIC."(college_name,college_course,college_subject,college_grades,college_cgpa,
                                                                            co_year,co_club,co_position,co_school,
                                                                            awards_name,awards_prize,awards_level,awards_year,member_id) 
                          VALUES('".tep_input($_POST["college_name"])."', '".tep_input($_POST['college_course'])."', '".$csubject."', '".$cgrades."', '".tep_input($_POST['college_cgpa'])."' ,
                              '".$co_year."', '".$co_club."', '".$co_position."', '".$co_school."', 
                               '".$awards."', '".$aw_prize."', '".$aw_level."', '".$aw_year."','".$_SESSION['member_id']."' )");
                  
                  tep_query("UPDATE ".MEMBER." SET academic = 1, 
                                    academic_points = '".$academicPoints."',
                                    club_points = '".$total_club."' ,
                                    award_points = '".$total_award."' ,
                              total_points = '".$sub_total."'  WHERE member_id = '".$_SESSION['member_id']."'");
                  
             
                     if ($_GET['action']=="edit" && $_SESSION['user_id']!=""){
                          tep_query("INSERT INTO ".TEMP."(member_id,action,temp_group,temp_createdby,temp_created) 
                                                  VALUES('".$_GET['id']."', 'Edit Academic Details', '0', '".$_SESSION['user_id']."',NOW())");
                        redirect('index.php?pages=academic_details&id='.$_GET['id'].'');
                    }else{
                         redirect('index.php?pages=academic_details');
                    }
               }
            
          }
    }
        
    }

    
    if ($editable =="0" || $editable =="admin" || $editable=="view"){
        if ($_SESSION['studies_status']=='0' || $status->studies_status=='0'){
                $academic_state = strtoupper($qry->qualification_state);
                $academic_school = strtoupper($qry->qualification_school);
                $year_commenced = $qry->qualification_year_commenced;
                $year_completed = $qry->qualification_year_completed;
                
                $examination_subject = explode('|',$qry->examination_subject);
                $examination_grades = explode("|",$qry->examination_grades);
                
                
                    $examination_subject = explode('|',$qry->examination_subject);
                    $examination_grades = explode("|",$qry->examination_grades);
                    $qualification_sub = strtoupper($examination_subject[0]);
                    $qualification_grade = strtoupper($examination_grades[0]);
                    $rowspan = count($examination_subject);
                  $academic_qualification = '<td rowspan="'.$rowspan.'" valign="top">'.strtoupper($qry->qualification_obtained).'</td>';
                
                    $qualification_year = '<td rowspan="'.$rowspan.'" valign="top">'.$qry->examination_year.'</td>';
             
        }else{
            $college_name = strtoupper($qry->college_name);
            $college_course = "BACHELOR'S DEGREE IN ".strtoupper($qry->college_course);
//            $college_subject = '<input type="text" name="subject_1" style="width:250px" value="'.$_POST['subject_1'].'"/>';
//            $college_grade = '<input type="text" name="grade_1" style="width:45px" value="'.$_POST['grade_1'].'"/>';
//            $college_cgpa = '<input type="text" name="college_cgpa" value="'.$_POST['college_cgpa'].'"/>';
             
        }
        
        $qualification_cgpa =$qry->examination_cgpa;
        $college_cgpa =$qry->college_cgpa;
        
          if ($editable=="0"){
            $btn = '';
        }else{
            if ($editable=="view"){
                if ($_GET['tempid']!=""){
                    $btn = '<input type="button" value="Previous" onclick="location.href=\'index.php?id='.$_GET['id'].'&tempid='.$_GET['tempid'].'\'" style="padding:3px;width:100px"/>  <input type="button" value="Next" onclick="location.href=\'index.php?pages=course_details&id='.$_GET['id'].'&tempid='.$_GET['tempid'].'\'" style="padding:3px;width:100px"/>';
                }else{
                    $btn = '<input type="button" value="Previous" onclick="location.href=\'index.php\'" style="padding:3px;width:100px"/> <input type="button" value="Edit" style="padding:3px;width:100px" onclick="redirect(\'index.php?pages=academic_details&action=edit\')"/> <input type="button" value="Next" onclick="location.href=\'index.php?pages=course_details\'" style="padding:3px;width:100px"/>';
                }
            }else{
            $btn = '<input type="button" value="Previous" onclick="location.href=\'index.php?id='.$_GET['id'].'\'" style="padding:3px;width:100px"/>  <input type="button" value="Edit" style="padding:3px;width:100px" onclick="redirect(\'index.php?pages=academic_details&id='.$_GET['id'].'&action=edit\')"/>   <input type="button" value="Next" onclick="location.href=\'index.php?pages=course_details&id='.$_GET['id'].'\'" style="padding:3px;width:100px"/>';
         }
        }
         
    }else if($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){
        if ($editable =="0" || $editable=="blank"){
         
          redirect ("index.php?pages=academic_details&permission=denied");
      }else{
         if ($_SESSION['studies_status']=='0' || $status->studies_status=='0'){
                $academic_state = '<select name="academic_state" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_state()),$qry->qualification_state).'</select>';
                $academic_school = '<input type="text" name="academic_school" value="'.strtoupper($qry->qualification_school).'" style="width:100%" required/>';
                $year_commenced = '<select name="year_commenced" style="width:100%" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_year()),$qry->qualification_year_commenced).'</select>';
                $year_completed = '<select name="year_completed" style="width:100%" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_year()),$qry->qualification_year_completed).'</select>';
                $academic_qualification = '<td  id="stpm_row" rowspan="'.$sub.'" valign="top"><select name="academic_qualification" id="academic_qualification" onchange="OthersQualification(this.value);" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_qualification()),$qry->qualification_obtained).'</select>  <div id="others_qualification" style="display:none;margin:5px 0">Please specify : <input type="text" id="input_others" name="others_qualification" style="width:99px"/></div></td>';
              
//                $highest_qualification = strtoupper($qry->qualification_obtained);
                
                
         
                
                    $examination_subject = explode('|',$qry->examination_subject);
                    $examination_grades = explode("|",$qry->examination_grades);
                    $qualification_sub = '<input type="text" name="qualification_sub_1" value="'.strtoupper($examination_subject[0]).'" style="width:440px" required/>';
                    $qualification_grade = '<input type="number" id="qualification_grade" name="qualification_grade_1" pattern="[3-4]" value="'.strtoupper($examination_grades[0]).'" min="3" max="4" step="0.01" required title="Your CGPA cannot exceed 4.00. Thank you."/>';
                    $qualification_year = '<td id="year_row" rowspan="'.$sub.'" valign="top" width="100px"><select name="stpm_year" style="width:100%" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_year()),$qry->examination_year).'</select></td>';

            $qualification_cgpa = '<input type="text" name="qualification_cgpa" value="'.$qry->examination_cgpa.'" required/>';

            $college_cgpa =$qry->college_cgpa;
            
        }else{
            
        $college_name = '<input type="text"  name="college_name" style="width:100%" value="'.strtoupper($qry->college_name).'"/>';
        $college_course = 'BACHELOR\'S DEGREE IN <input type="text"  name="college_course" style="width:120px" value="'.strtoupper($qry->college_course).'"/>';
       
        $college_cgpa = '<input type="text" name="college_cgpa" value="'.$qry->college_cgpa.'"/>';
        
       
            
        }
       
       
                  $btn = '<input type="button" value="Cancel" onclick="redirect(\'index.php?pages=academic_details\')" style="padding:3px;width:100px"/>  <input type="submit" name="submit" value="Save" style="padding:3px;width:100px"/>';
       
    
      }
    }else{
        $academic_state = '<select name="academic_state" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_state()),$_POST['academic_state']).'</select>';
        $academic_school = '<input type="text" name="academic_school" value="'.strtoupper($_POST['academic_school']).'" style="width:100%" required/>';
        $year_commenced = '<select name="year_commenced" style="width:100%" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_year()),$_POST['year_commenced']).'</select>';
        $year_completed = '<select name="year_completed" style="width:100%" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_year()),$_POST['year_completed']).'</select>';
        $academic_qualification = '<td rowspan="5" valign="top" id="stpm_row" required><select name="academic_qualification" id="academic_qualification" onchange="OthersQualification(this.value);"><option value="">-</option>'.ddlReplace(arrToDDL(tep_qualification()),$_POST['academic_qualification']).'</select>  <div id="others_qualification" style="display:none;margin:5px 0">Please specify : <input type="text" id="input_others" name="others_qualification" style="width:99px"/></div></td>';
        $qualification_year = '<td id="year_row" rowspan="5" valign="top"><select name="stpm_year" style="width:100%" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_year()),$_POST['stpm_year']).'</select></td>';
        $qualification_sub = '<input type="text" name="qualification_sub_1" value="'.strtoupper($_POST['qualification_sub_1']).'" style="width:465px" required/>';
        $qualification_grade = '<input type="number" name="qualification_grade_1" value="'.strtoupper($_POST['qualification_grade_1']).'" min="3" max="4" step="0.01" required/>';
        $qualification_cgpa = '<input type="text" name="qualification_cgpa" value="'.$_POST['qualification_cgpa'].'" required/>';
        $college_name = '<input type="text"  name="college_name" style="width:100%" value="'.strtoupper($_POST['college_1']).'" required/>';
        $college_course = 'BACHELOR\'S DEGREE IN <input type="text"  name="college_course" style="width:120px" value="'.strtoupper($_POST['course_1']).'" required/>';
        $college_subject = '<input type="text" name="subject_1" style="width:100%" value="'.strtoupper($_POST['subject_1']).'" required/>';
        $college_grade = '<input type="text" name="grade_1" style="width:45px" value="'.strtoupper($_POST['grade_1']).'" required/>';
        $college_cgpa = '<input type="text" name="college_cgpa" value="'.$_POST['college_cgpa'].'" required/>';
        $btn = '<input type="button" value="Previous" onclick="location.href=\'index.php\'" style="padding:3px;width:100px"/> <input type="submit" name="submit" value="Save" style="padding:3px;width:100px"/> <input type="button" value="Next" onclick="location.href=\'index.php?pages=course_details\'" style="padding:3px;width:100px"/>';
        
     
    }
   
    
?>
<p style="font-weight:bold;font-size:18px;text-align:center">Scholarship Application</p>
<div class="form_container">
    <form method="post" id="form">
    <div style="background-color:rgb(231, 98, 123);text-align:center;padding:5px;font-size:14px;margin-bottom:10px"><b>B. ACADEMIC DETAILS</b></div>
    <div class="form_content">
        <div class="main_form">
            <?php
            
                if ($_SESSION['studies_status']=='0' || $status->studies_status=='0'){
                 
                    echo '<p>Please specify only the highest qualification obtained (STPM / Matriculation / HSC / Diploma / A-Level / Foundation and other similar qualification) </p>
                    
                            <table class="details_table">
                                    <tr>
                                            <th align="center" width="100px">State</th>
                                            <th width="300px">School / Institution Name</th>
                                            <th align="center" width="150px">Year Commenced</th>
                                            <th align="center" width="150px">Year Completed</th>
                                    </tr>
                                    <tr>
                                             <td valign="top">'.$academic_state.'</td>
                                             <td valign="top">'.$academic_school.'</td>
                                             <td valign="top">'.$year_commenced.'</td>
                                             <td valign="top">'.$year_completed.'</td>
                                    </tr>
                            </table>
                    
                    <p>Certified true copy of result slip is to be attached at the end of the application.</p>
                    <hr/>
                    <p>Examination Results</p>
                    
                    <div>
                                <table class="details_table" id="examination_result">
                                    <tr>
                                            <th align="center" width="150px">Highest Qualification</th>
                                            <th align="center" width="150px">Year</th>
                                            <th  width="440px">Subjects</th>
                                            <th align="center" width="50px">Grades</th>
                                    </tr>
                                    <tr>
                                            '.$academic_qualification.'
                                            '.$qualification_year.'
                                            <td>'.$qualification_sub.'</td>
                                            <td>'.$qualification_grade.'</td>    
                                    </tr>';
                    echo'<input type="hidden" name="oCount" id="oCount" value="'.$sub.'"/>';
                    if ($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){
                        if (count($examination_subject) <5){
                                $row = 5;
                            }else{
                                $row = count($examination_subject);
                            }
                            
                                 if ($row <5){
                           $subcount = 5;
                       }else{
                           $subcount = $row;
                       }
                        $x = 0;
                         for ($i=2;$i<=$subcount;$i++){
                                   $x++;
                                $examination_row ='
                                         <tr id="erow_2">
                                                <td><input type="text" name="qualification_sub_2" value="'.strtoupper($examination_subject[$x]).'" style="width:440px"/></td>
                                                <td><input type="number" name="qualification_grade_2" value="'.strtoupper($examination_grades[$x]).'" min="3" max="4" step="0.01"/></td>
                                         </tr>
                                </tr>
                                 ';
//                                $_SESSION['subject'] = $i;
                                echo str_replace('_2', '_'.$i, $examination_row);
                            }
                         
                            echo'</table>
                           <a id="btnAdd_examination" class="add_new" href=""><img src="./images/add_row.png"></a>';
                            
                    }else if ($editable =="0" || $editable =="admin" || $editable=="view"){
                        for ($i=1;$i<count($examination_subject);$i++){
                            echo '<tr><td>'.strtoupper($examination_subject[$i]).'</td><td>'.strtoupper($examination_grades[$i]).'</td></tr>';
                        }
                        echo'</table>';
                    }else{
                        $examination_row = '
                                            <tr id="erow_2">
                                                <td><input type="text" name="qualification_sub_2" value="'.strtoupper($_POST['qualification_sub_2']).'" style="width:465px"/></td>
                                                <td><input type="number" name="qualification_grade_2" value="'.strtoupper($_POST['qualification_grade_2']).'" min="3" max="4" step="0.01"/></td>
                                         </tr>
                        </tr>
                         ';
                        
                         for($i=2;$i<=5;$i++){
//                            $_SESSION['subject'] = $i;
                                echo str_replace('_2', '_'.$i, $examination_row);
                            }
                            echo'</table>
                <a id="btnAdd_examination" class="add_new" href=""><img src="./images/add_row.png"></a>';
                    }
                       echo'</div>
                    <p>Cumulative Grade Point Average(CGPA):&nbsp;&nbsp;&nbsp;'.$qualification_cgpa.'</p>';
          
                }else{
//                           for undergraduate student
                    echo '<p>For candidates who are currently pursuing undergraduate courses.</p>
                              <p>University/College examination results and cumulative grade point average, if applicable.</p>
                              
                                            <table class="details_table examination_results" id="college_result">
                                                    <tr>
                                                            <th align="left" width="300px">University/College</th>
                                                            <th align="left" width="300px">Degree Title</th>
                                                            <th align="left" width="300px">Subject Taken</th>
                                                            <th align="center">Gradesssss</th>
                                                    </tr>
                                         <tr>';
                   if ($editable =="0" || $editable =="admin" || $editable=="view"){
                        $subject = explode("|",$qry->college_subject);
                        $grade = explode("|",$qry->college_grades);
                        $row = count($subject);
                        echo '<td id="name_row" rowspan="'.$row.'" valign="top">'.$college_name.'</td>
                                            <td id="course_row" rowspan="'.$row.'"  valign="top">'.$college_course.'</td>
                                            <td>'.strtoupper($subject[0]).'</td><td>'.strtoupper($grade[0]).'</td></tr>';
                                for ($i=1;$i<count($subject);$i++){
                                   echo '<tr><td>'.strtoupper($subject[$i]).'</td><td>'.strtoupper($grade[$i]).'</td></tr>';
                               }
                        echo'</tr></table></td>';

                    }else if ($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){
                      echo'<input type="hidden" name="hCount" id="hCount" value="'.$csub.'"/>  ';
                        $subject = explode("|",$qry->college_subject);
                        $grade = explode("|",$qry->college_grades);
                       
                        
                        if ($subject[0]!=""){
                            if (count($subject) <5){
                                $row = 5;
                            }else{
                                $row = count($subject);
                            }
                             
                            echo '<td id="name_row" rowspan="'.$row.'" valign="top">'.$college_name.'</td>
                                            <td id="course_row" rowspan="'.$row.'"  valign="top">'.$college_course.'</td>
                                            <td><input type="text" name="subject_1" style="width:100%" value="'.strtoupper($subject[0]).'" required/></td>
                                <td><input type="text" name="grade_1" style="width:45px" value="'.strtoupper($grade[0]).'" required/></td></tr>';
                            $x = 0;
                      
                       if ($row <5){
                           $subcount = 5;
                       }else{
                           $subcount = $row;
                       }
                                for ($i=2;$i<=$subcount;$i++){
                                     $x++;
                                   $college_row ='<tr id="row_'.$i.'"><td><input type="text" name="subject_'.$i.'" style="width:100%" value="'.strtoupper($subject[$x]).'"/></td>
                                           <td><input type="text" name="grade_'.$i.'" style="width:45px" value="'.strtoupper($grade[$x]).'"/></td></tr>';
                                   
                                     $_SESSION['examination'] = $i;
                                     
                                    echo str_replace('_2', '_'.$i, $college_row);
                               }
                        }else{
                           echo '<td id="name_row" rowspan="5" valign="top">'.$college_name.'</td>
                                            <td id="course_row" rowspan="5"  valign="top">'.$college_course.'</td>
                                            <td><input type="text" name="subject_1" style="width:100%" value="'.strtoupper($_POST['subject_1']).'" required/></td>
                                <td><input type="text" name="grade_1" style="width:45px" value="'.strtoupper($_POST['grade_1']).'" required/></td></tr>';
                           $college_row = '
                                                    <tr id="row_2">
                                                        <td><input type="text" name="subject_2" style="width:100%" value="'.strtoupper($_POST['subject_2']).'"/></td>
                                                        <td><input type="text" name="grade_2" style="width:45px" value="'.strtoupper($_POST['grade_2']).'"/></td>
                                                 </tr>
                            </tr>';

                        for($i=2;$i<=5;$i++){
                            $_SESSION['examination'] = $i;
                            echo str_replace('_2', '_'.$i, $college_row);

                        }
                        }
                                    echo'</tr>';
                   
          
                  echo'</table>
                    </td>
                    <a id="btnAdd" class="add_new" href=""><img src="./images/add_row.png"></a>';
                    }else{
                                echo'<input type="hidden" name="hCount" id="hCount" value="'.$sub.'"/>  ';
                            $college_subject = '<input type="text" name="subject_1" style="width:100%" value="'.strtoupper($_POST['subject_1']).'" required/>';
                                $college_grade = '<input type="text" name="grade_1" style="width:45px" value="'.strtoupper($_POST['grade_1']).'" required/>';
                                echo '<td id="name_row" rowspan="5" valign="top">'.$college_name.'</td>
                                                    <td id="course_row" rowspan="5"  valign="top">'.$college_course.'</td>
                                                    <td>'.$college_subject.'</td>
                                                    <td>'.$college_grade.'</td>    
                                            </tr>';
                            
                           $college_row = '
                                                    <tr id="row_2">
                                                        <td><input type="text" name="subject_2" style="width:100%" value="'.strtoupper($_POST['subject_2']).'"/></td>
                                                        <td><input type="text" name="grade_2" style="width:45px" value="'.strtoupper($_POST['grade_2']).'"/></td>
                                                 </tr>
                            </tr>';

                        for($i=2;$i<=5;$i++){
                            $_SESSION['examination'] = $i;
                            echo str_replace('_2', '_'.$i, $college_row);

                        }
                          echo'</table>
                            </td>
                            <a id="btnAdd" class="add_new" href=""><img src="./images/add_row.png"></a>';
                    }
                      echo '<p>Cumulative Grade Point Average(CGPA):&nbsp;&nbsp;&nbsp;'.$college_cgpa.'</p> ';                      
                }                        
         echo'
                    
                    <p>Details of co-curricular responsibilities</p>           
                            <table class="details_table">
                                    <tr>
                                            <th align="center">Year</th>
                                            <th align="left" width="300px">Club/Association/Sports</th>
                                            <th align="center" width="200px">Position Held</th>
                                            <th align="left" width="400px">School/Institution</th>
                                    </tr>';
          if ($editable =="0" || $editable =="admin" || $editable=="view"){
              $cyear = explode("|",$qry->co_year);
              $cclub = explode("|",$qry->co_club);
              $cposition = explode("|",$qry->co_position);
              $cschool = explode("|",$qry->co_school);
                 for ($c=0;$c<3;$c++){
                       echo '<tr>
                                                <td align="center">'.strtoupper($cyear[$c]).'</td>
                                                <td>'.strtoupper($cclub[$c]).'</td>
                                                <td align="center">'.strtoupper($cposition[$c]).'</td>
                                                <td>'.strtoupper($cschool[$c]).'</td>
                                         </tr>';
                 }
                  
                             
          }else if ($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){
              
               $cyear = explode("|",$qry->co_year);
              $cclub = explode("|",$qry->co_club);
              $cposition = explode("|",$qry->co_position);
              $cschool = explode("|",$qry->co_school);
              $crow = 1;
                 for ($c=0;$c<3;$c++){
                     
                     $co_row = '
                                            <tr id="corow_0">
                                                <td align="center"><select name="coyear_'.$crow.'"><option value="">-</option>'.ddlReplace(arrToDDL(tep_year()),$cyear[$c]).'</select></td>
                                                <td><input type="text"  name="coclub_'.$crow.'" style="width:100%" value="'.strtoupper($cclub[$c]).'"/></td>
                                                <td align="center"><select name="coposition_'.$crow.'" style="width:100%" ><option value="">-</option>'.ddlReplace(arrToDDL(tep_coposition()),$cposition[$c]).'</select></td>
                                                <td><input type="text" name="coschool_'.$crow.'" style="width:100%" value="'.strtoupper($cschool[$c]).'"/></td>
                                         </tr> ';
                      $crow ++;
                     echo str_replace('_0', '_'.$c, $co_row);
                 }
         
          }else{
                        $co_row = '
                                            <tr id="corow_1">
                                                <td align="center"><select name="coyear_1"><option value="">-</option>'.ddlReplace(arrToDDL(tep_year()),$_POST['coyear_1']).'</select></td>
                                                <td><input type="text"  name="coclub_1" style="width:100%" value="'.strtoupper($_POST['coclub_1']).'" /></td>
                                                <td align="center"><select name="coposition_1" style="width:100%"><option value="">-</option>'.ddlReplace(arrToDDL(tep_coposition()),$_POST['coposition_1']).'</select></td>
                                                <td><input type="text" name="coschool_1" style="width:100%" value="'.strtoupper($_POST['coschool_1']).'"/></td>
                                         </tr> ';

                                    for($i=1;$i<=3;$i++){
                                        echo str_replace('_1', '_'.$i, $co_row);
                                    }
                    }
                            echo'</table>
                                
                                <p>Certified true copies of Co-curricular transcripts/ records are to be attached at the end of the application.</p>
                                    <table class="details_table">
                                            <tr>
                                                    <th width="400px">Awards/Achievements</th>
                                                    <th align="center" width="150px">Prize</th>  
                                                    <th align="center" width="200px">Level</th>
                                                    <th align="center" width="150px">Year</th>
                                            </tr>';
                            
                            if ($editable =="0" || $editable =="admin" || $editable=="view"){
                                 $aname = explode("|",$qry->awards_name);
                                 $aprize = explode("|",$qry->awards_prize);
                                 $alevel = explode("|",$qry->awards_level);
                                 $ayear = explode("|",$qry->awards_year);
                                  for ($a=0;$a<3;$a++){
                                        echo '<tr>
                                                                 <td>'.strtoupper($aname[$a]).'</td>
                                                                 <td align="center">'.strtoupper($aprize[$a]).'</td>
                                                                 <td align="center">'.strtoupper($alevel[$a]).'</td>
                                                                 <td align="center">'.$ayear[$a].'</td>
                                                          </tr>';
                                  }
                             }else  if ($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){
                                 $aname = explode("|",$qry->awards_name);
                                 $aprize = explode("|",$qry->awards_prize);
                                 $alevel = explode("|",$qry->awards_level);
                                 $ayear = explode("|",$qry->awards_year);
                                 $arow = 1;
                                  for ($a=0;$a<3;$a++){
                                     $aw_row = '
                                            <tr id="awrow_0">
                                                <td><input type="text" name="awards_'.$arow.'" style="width:455px" value="'.strtoupper($aname[$a]).'"/></td>
                                                <td align="center"><select name="awprize_'.$arow.'" style="width:100%"><option value="">-</option>'.ddlReplace(arrToDDL(tep_awprize()),$aprize[$a]).'</select></td>
                                                <td align="center"><select name="awlevel_'.$arow.'" style="width:100%"><option value="">-</option>'.ddlReplace(arrToDDL(tep_awlevel()),$alevel[$a]).'</select></td>
                                                <td align="center"><select name="awyear_'.$arow.'" style="width:100%""><option value="">-</option>'.ddlReplace(arrToDDL(tep_awyear()),$ayear[$a]).'</select></td>
                                         </tr>
                            
                                        </tr>
                                         ';
                                     $arow++;
                                        echo str_replace('_0', '_'.$a, $aw_row);
                                  }
                             }else{
                                  $aw_row = '
                                            <tr id="awrow_1">
                                                <td><input type="text" name="awards_1" style="width:455px" value="'.strtoupper($_POST['awards_1']).'"/></td>
                                                <td align="center"><select name="awprize_1" style="width:100%"><option value="">-</option>'.ddlReplace(arrToDDL(tep_awprize()),$_POST['awprize_1']).'</select></td>
                                                <td align="center"><select name="awlevel_1" style="width:100%"><option value="">-</option>'.ddlReplace(arrToDDL(tep_awlevel()),$_POST['awlevel_1']).'</select></td>
                                                <td align="center"><select name="awyear_1" style="width:100%"><option value="">-</option>'.ddlReplace(arrToDDL(tep_awyear()),$_POST['awyear_1']).'</select></td>
                                         </tr>
                            
                                        </tr>
                                         ';

                                    for($i=1;$i<=3;$i++){
                                        echo str_replace('_1', '_'.$i, $aw_row);
                                    }
                             }
                         
                                   echo' </table>
                                       <p><span style="color:red")*List max three awards obtained in two preceding years.*</span></p>
                                       <p>Copies of relevant certificates are to be attached at the end of the application.</p>
                                
                                                            ';
                
             
            ?>
            
            
            <div style="float:right;margin-bottom:10px"><?=$btn?> </div>
            <div class="cls"></div>
        </div>
    </div>
    </form>
</div>