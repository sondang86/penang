<style>
    .details_table {width:100%}
</style>
<div style="text-align:center">
<img src="./images/logo.png"> <p style="font-weight:bold;font-size:18px;text-align:center">Scholarship Application Form</p>
</div>
<div class="form_container">
    
    <div style="background-color:rgb(231, 98, 123);text-align:center;padding:5px;font-size:14px;margin-bottom:10px"><b>A. PERSONAL PARTICULARS</b></div>
    <?php
    
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
        $actual_year = "19".$year;
        $member_dob = $day."/".$month."/".$actual_year;
        $currentyear = date("Y");
        $count_age = $currentyear - $actual_year;
        
  $qry = tep_fetch_object(tep_query("SELECT * FROM ".PERSONAL." p, ".MEMBER." m WHERE p.member_id = m.member_id AND (m.member_id = '".$_SESSION['member_id']."' OR m.member_id = '".$_GET['id']."')"));
  $submit_time = $qry->submission_created;
  $chk_complete = $qry->complete;
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
    $dob = $member_dob;
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
       
  
?>
    <div class="form_content">
        <table class="main_form" width="100%">
            <tr>
                <td width="200px">Name (as per MyKad)</td><td width="500px"><?=$name?></td>
            </tr>
            <tr>
                <td>NRIC No.</td><td><?=$ic?></td>
            </tr>
            <tr>
                <td>Citizenship</td><td><?=$citizenship?></td>
            </tr>
            <tr>
                <td>Sex</td><td><?=$sex?></td>
            </tr>
            <tr>
                <td>Marital Status</td><td><?=$marital_status?></td>
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
                        <tr><td>Home: </td><td width="120px"><?=$home_phone?></td><td>Mobile: </td><td><?=$mobile_phone?></td></tr>
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
        </table>
    </div>
    
    <!--part b-->
    <style>
    #stpm_result {display:block}
    #others_result {display:none}
</style>
<?php

 $qry = tep_fetch_object(tep_query("SELECT * FROM ".ACADEMIC." WHERE (member_id = '".$_SESSION['member_id']."' OR member_id = '".$_GET['id']."')"));
 $status = tep_fetch_object(tep_query("SELECT * FROM ".PERSONAL." WHERE (member_id = '".$_SESSION['member_id']."' OR member_id = '".$_GET['id']."')"));

 
 $examination_row = count(explode("|",$qry->examination_subject));
 $college_row = count(explode("|",$qry->college_subject));


        if ($status->studies_status=='0'){
                $academic_state = strtoupper($qry->qualification_state);
                $academic_school = strtoupper($qry->qualification_school);
                $year_commenced = $qry->qualification_year_commenced;
                $year_completed = $qry->qualification_year_completed;
                $academic_qualification = strtoupper($qry->qualification_obtained);
                $stpm_year = '<td rowspan="4" valign="top">'.$qry->examination_year.'</td>';
                
                $examination_subject = explode('|',$qry->examination_subject);
                $examination_grades = explode("|",$qry->examination_grades);
                if ($qry->qualification_obtained =="STPM"){
                   $highest_qualification = strtoupper($qry->qualification_obtained);
                    $stpm_sub1 = strtoupper($examination_subject[0]);
                    $stpm_sub2 = strtoupper($examination_subject[1]);
                    $stpm_sub3 = strtoupper($examination_subject[2]);
                    $stpm_sub4 = strtoupper($examination_subject[3]);
                    $stpm_grade1 = strtoupper($examination_grades[0]);
                    $stpm_grade2 = strtoupper($examination_grades[1]);
                    $stpm_grade3 = strtoupper($examination_grades[2]);
                    $stpm_grade4 = strtoupper($examination_grades[3]);
                }else{?>
                    <style>#stpm_result {display:none}
                                #others_result {display:block}
                    </style>
                <?php
                    
                    $examination_subject = explode('|',$qry->examination_subject);
                    $examination_grades = explode("|",$qry->examination_grades);
                    $qualification_sub = strtoupper($examination_subject[0]);
                    $qualification_grade = strtoupper($examination_grades[0]);
                    $rowspan = count($examination_subject);
                    $highest_qualification = '<td rowspan="'.$rowspan.'" valign="top">'.strtoupper($qry->qualification_obtained).'</td>';
                    $qualification_year = '<td rowspan="'.$rowspan.'" valign="top">'.$qry->examination_year.'</td>';
             }
        }else{
            $college_name = strtoupper($qry->college_name);
            $college_course = "BACHELOR'S DEGREE IN ".strtoupper($qry->college_course);
//            $college_subject = '<input type="text" name="subject_1" style="width:250px" value="'.$_POST['subject_1'].'"/>';
//            $college_grade = '<input type="text" name="grade_1" style="width:45px" value="'.$_POST['grade_1'].'"/>';
//            $college_cgpa = '<input type="text" name="college_cgpa" value="'.$_POST['college_cgpa'].'"/>';
             
        }
        
        $qualification_cgpa =$qry->examination_cgpa;
        $college_cgpa =$qry->college_cgpa;

    
    
?>

  
    <div style="background-color:rgb(231, 98, 123);text-align:center;padding:5px;font-size:14px;margin:10px 0"><b>B. ACADEMIC DETAILS</b></div>
    <div class="form_content">
        <div class="main_form">
            <?php
            
                if ($_SESSION['studies_status']=='0' || $status->studies_status=='0'){
                 
                    echo '<p>Please specify only the highest qualification obtained (STPM / Matriculation / HSC / Diploma / A-Level / Foundation and other similar qualification) </p>
                    
                            <table class="details_table">
                                    <tr>
                                            <th align="center" width="100px">State</th>
                                            <th align="center" width="300px">School / Institution Name</th>
                                            <th align="center" width="150px">Year Commenced</th>
                                            <th align="center" width="150px">Year Completed</th>
                                            <th align="center" width="250px">Qualifications Obtained</th>
                                    </tr>
                                    <tr>
                                             <td valign="top">'.$academic_state.'</td>
                                             <td valign="top">'.$academic_school.'</td>
                                             <td valign="top">'.$year_commenced.'</td>
                                             <td valign="top">'.$year_completed.'</td>
                                             <td valign="top">'.$academic_qualification.'</td>
                                    </tr>
                            </table>
                    
                    <p>Certified true copy of result slip is to be attached at the end of the application.</p>
                    <hr/>
                    <p>Examination Results</p>
                    
                        
                    <div id="stpm_result" class="divHide">
                                <table class="details_table">
                                    <tr>
                                            <th align="center" width="150px">Highest Qualification</th>
                                            <th align="center" width="150px">Year</th>
                                            <th align="center" width="500px">Subjects</th>
                                            <th align="center" width="50px">Grades</th>
                                    </tr>
                                    <tr>
                                            <td  rowspan="5" valign="top">STPM</td>
                                            '.$stpm_year.'
                                            <td>'.$stpm_sub1.'</td>
                                            <td valign="top">'.$stpm_grade1.'</td>    
                                    </tr>
                                    <tr><td>'.$stpm_sub2.'</td>
                                            <td valign="top">'.$stpm_grade2.'</td>    
                                    </tr>
                                    <tr><td>'.$stpm_sub3.'</td>
                                            <td valign="top">'.$stpm_grade3.'</td>    
                                    </tr>
                                    <tr><td>'.$stpm_sub4.'</td>
                                            <td valign="top">'.$stpm_grade4.'</td>    
                                    </tr>';
                                     
                                echo'</table>
                </div>
                
<div id="others_result" class="divShow">
                                <table class="details_table" id="examination_result">
                                    <tr>
                                            <th align="center" width="150px">Highest Qualification</th>
                                            <th align="center" width="150px">Year</th>
                                            <th align="center" width="440px">Subjects</th>
                                            <th align="center" width="50px">Grades</th>
                                    </tr>
                                    <tr>
                                            '.$highest_qualification.'
                                            '.$qualification_year.'
                                            <td>'.$qualification_sub.'</td>
                                            <td>'.$qualification_grade.'</td>    
                                    </tr>';
                    echo'<input type="hidden" name="oCount" id="oCount" value="'.$sub.'"/>';
                  
                        for ($i=1;$i<count($examination_subject);$i++){
                            echo '<tr><td>'.strtoupper($examination_subject[$i]).'</td><td>'.strtoupper($examination_grades[$i]).'</td></tr>';
                        }
                        echo'</table>
                            </div>
                    <p>Cumulative Grade Point Average(CGPA):&nbsp;&nbsp;&nbsp;'.$qualification_cgpa.'</p>';
          
                }else{
//                           for undergraduate student
                    echo '<p>For candidates who are currently pursuing undergraduate courses.</p>
                              <p>University/College examination results and cumulative grade point average, if applicable.</p>
                              
                                            <table class="details_table" id="college_result">
                                                    <tr>
                                                            <th align="center" width="300px">University/College</th>
                                                            <th align="center" width="300px">Degree Title</th>
                                                            <th align="center" width="300px">Subject Taken</th>
                                                            <th align="center">Grades</th>
                                                    </tr>
                                         <tr>';
                  
                        $subject = explode("|",$qry->college_subject);
                        $grade = explode("|",$qry->college_grades);
                        $row = count($subject);
                        echo '<td id="name_row" rowspan="'.$row.'" valign="top">'.strtoupper($college_name).'</td>
                                            <td id="course_row" rowspan="'.$row.'"  valign="top">'.strtoupper($college_course).'</td>
                                            <td>'.strtoupper($subject[0]).'</td><td>'.strtoupper($grade[0]).'</td></tr>';
                                for ($i=1;$i<count($subject);$i++){
                                   echo '<tr><td>'.strtoupper($subject[$i]).'</td><td>'.strtoupper($grade[$i]).'</td></tr>';
                               }
                        echo'</tr></table></td>
                              <p>Cumulative Grade Point Average(CGPA):&nbsp;&nbsp;&nbsp;'.$college_cgpa.'</p> ';

             
                }                        
                                            
         echo'
                  
                    <p>Details of co-curricular responsibilities</p>           
                            <table class="details_table">
                                    <tr>
                                            <th align="center">Year</th>
                                            <th align="center" width="300px">Club/Association/Sports</th>
                                            <th align="center" width="200px">Position Held</th>
                                            <th align="center" width="400px">School/Institution</th>
                                    </tr>';
         
              $cyear = explode("|",$qry->co_year);
              $cclub = explode("|",$qry->co_club);
              $cposition = explode("|",$qry->co_position);
              $cschool = explode("|",$qry->co_school);
                 for ($c=0;$c<3;$c++){
                       echo '<tr>
                                                <td>'.strtoupper($cyear[$c]).'</td>
                                                <td>'.strtoupper($cclub[$c]).'</td>
                                                <td>'.strtoupper($cposition[$c]).'</td>
                                                <td>'.strtoupper($cschool[$c]).'</td>
                                         </tr>';
                 }
            
                            echo'</table>
                                
                                <p>Certified true copies of Co-curricular transcripts/ records are to be attached at the end of the application.</p>
                                    <table class="details_table">
                                            <tr>
                                                    <th align="center" width="400px">Awards/Achievements</th>
                                                    <th align="center" width="150px">Prize</th>  
                                                    <th align="center" width="200px">Level</th>
                                                    <th align="center" width="150px">Year</th>
                                            </tr>';
                            
                           
                                 $aname = explode("|",$qry->awards_name);
                                 $aprize = explode("|",$qry->awards_prize);
                                 $alevel = explode("|",$qry->awards_level);
                                 $ayear = explode("|",$qry->awards_year);
                                  for ($a=0;$a<3;$a++){
                                        echo '<tr>
                                                                 <td>'.strtoupper($aname[$a]).'</td>
                                                                 <td>'.strtoupper($aprize[$a]).'</td>
                                                                 <td>'.strtoupper($alevel[$a]).'</td>
                                                                 <td>'.$ayear[$a].'</td>
                                                          </tr>';
                                  }
                            
                                   echo' </table>
                                       <p><span style="color:red")*List max three awards obtained in two preceding years.*</span></p>
                                       <p>Copies of relevant certificates are to be attached at the end of the application.</p>
                                
                                                            ';
                
             
            ?>
            
            <div class="cls"></div>
        </div>
    </div>
   
    <!--part c-->


<?php

    $qry = tep_fetch_object(tep_query("SELECT * FROM ".COURSE." WHERE (member_id = '".$_SESSION['member_id']."' OR member_id = '".$_GET['id']."')"));
    
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
      
?>

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
 
    <!--part d-->
    <style>
    #financial_assistance {display:none}
    .guardian_info {display:none}
</style>

       
<?php

    $qry = tep_fetch_object(tep_query("SELECT * FROM ".FINANCIAL." f, ".MEMBER." m WHERE f.member_id = m.member_id AND (f.member_id = '".$_SESSION['member_id']."' OR f.member_id = '".$_GET['id']."')"));
 
        if ($qry->financial_status =="1"){
             $financial_assistant = 'Yes' ;
             ?>
            <style>#financial_assistance {display:block}</style>
            <?php
            $type_of_financial = strtoupper($qry->type_financial);
            $name_of_financial =strtoupper($qry->name_financial);
            $financial_amount = number_format($qry->amount_financial,2);
            $financial_period = strtoupper($qry->financial_period);
        }else{
            $financial_assistant = 'No' ;
        }
     
       $father_name= strtoupper($qry->father_name);
       $mother_name= strtoupper($qry->mother_name);
       $father_ic= $qry->father_ic;
       $mother_ic=$qry->mother_ic;
       switch ($qry->father_status){
           case "full_time":
                $father_status = "FULL TIME EMPLOYMENT";
               break;
           case "part_time":
               $father_status = "PART TIME EMPLOYMENT";
               break;
           case "own_business":
               $father_status = "OWN BUSINESS";
               break;
           case "retired":
               $father_status = "RETIRED/UNEMPLOYMENT";
               break;
           case "nil":
               $father_status = "NIL ANNUAL INCOME";
               break;
           case "deceased":
               $father_status = "DECEASED";
               break;
           
       }
        switch ($qry->mother_status){
           case "full_time":
               $mother_status = "FULL TIME EMPLOYMENT";
               break;
           case "part_time":
               $mother_status = "PART TIME EMPLOYMENT";
               break;
           case "own_business":
               $mother_status = "OWN BUSINESS";
               break;
           case "retired":
               $mother_status = "RETIRED/UNEMPLOYMENT";
               break;
           case "nil":
               $mother_status = "NIL ANNUAL INCOME";
               break;
           case "deceased":
               $mother_status = "DECEASED";
               break;
       }

       $father_tel= $qry->father_phone;
       $mother_tel= $qry->mother_phone;
       $father_position= strtoupper($qry->father_position);
       $mother_position= strtoupper($qry->mother_position);
       $f_address = explode("|",$qry->father_address);
       $m_address = explode("|",$qry->mother_address);
       $father_address= '<table class="in_table">
                        <tr><td colspan="4">'.strtoupper($f_address[0]).'</td></tr>
                        <tr><td colspan="4">'.strtoupper($f_address[1]).'</td></tr>
                        <tr><td width="60px">Postcode: </td><td style="text-align:left">'.$f_address[2].'</td><td>State:</td><td style="text-align:left">'.strtoupper($f_address[3]).'</td></tr>
                        </table>';
       $mother_address= '<table class="in_table">
                        <tr><td colspan="4">'.strtoupper($m_address[0]).'</td></tr>
                        <tr><td colspan="4">'.strtoupper($m_address[1]).'</td></tr>
                        <tr><td width="60px">Postcode: </td><td style="text-align:left">'.$m_address[2].'</td><td>State:</td><td style="text-align:left">'.strtoupper($m_address[3]).'</td></tr>
                        </table>';
       $father_income = explode("|",$qry->father_income);
      $f_2014 = explode(",",$father_income[0]);
      if ($f_2014[0]==""){
          $f2014 = $f_2014[1];
      }else{
          $f2014 = $father_income[0];
      }
      $f_2013 = explode(",",$father_income[1]);
      if ($f_2013[0]==""){
          $f2013 = $f_2013[1];
      }else{
          $f2013 = $father_income[1];
      }
       $father_annual ='<table class="in_table">
                                            <tr><td></td></tr>
                                            <tr><td>'.$f2014.'.00</td></tr>
                                            <tr><td>'.$f2013.'.00</td></tr>
                                    </table>';
       $mother_income = explode("|",$qry->mother_income);
        $m_2014 = explode(",",$mother_income[0]);
      if ($m_2014[0]==""){
          $m2014 = $m_2014[1];
      }else{
          $m2014 = $mother_income[0];
      }
      $m_2013 = explode(",",$mother_income[1]);
      if ($m_2013[0]==""){
          $m2013 = $m_2013[1];
      }else{
          $m2013 = $mother_income[1];
      }
       $mother_annual ='<table class="in_table">
                                            <tr><td></td></tr>
                                            <tr><td>'.$m2014.'.00</td></tr>
                                            <tr><td>'.$m2013.'.00</td></tr>
                                    </table>';
       $declare = "disabled checked";
       if ($qry->guardian_name!=""){
       $guardian_name=strtoupper($qry->guardian_name);
       $guardian_ic=$qry->guardian_ic;
        switch ($qry->guardian_status){
           case "full_time":
               $guardian_status = "FULL TIME EMPLOYMENT";
               break;
           case "part_time":
               $guardian_status = "PART TIME EMPLOYMENT";
               break;
           case "own_business":
               $guardian_status = "OWN BUSINESS";
               break;
           case "retired":
               $guardian_status = "RETIRED/UNEMPLOYMENT";
               break;
           case "nil":
               $guardian_status = "NIL ANNUAL INCOME";
               break;
           case "deceased":
               $guardian_status = "DECEASED";
               break;
       }
        
        $guardian_tel=$qry->guardian_phone;
        $guardian_position=strtoupper($qry->guardian_position);
        $g_address = explode("|",$qry->guardian_address);
        $guardian_address='<table class="in_table">
                        <tr><td colspan="4">'.strtoupper($g_address[0]).'</td></tr>
                        <tr><td colspan="4">'.strtoupper($g_address[1]).'</td></tr>
                        <tr><td width="60px">Postcode: </td><td>'.$g_address[2].'</td><td>State:</td><td>'.strtoupper($g_address[3]).'</td></tr>
                        </table>';
        $guardian_income = explode("|",$qry->guardian_income);
         $g_2014 = explode(",",$guardian_income[0]);
      if ($g_2014[0]==""){
          $g2014 = $g_2014[1];
      }else{
          $g2014 = $guardian_income[0];
      }
      $g_2013 = explode(",",$guardian_income[1]);
      if ($g_2013[0]==""){
          $g2013 = $g_2013[1];
      }else{
          $g2013 = $guardian_income[1];
      }
        $guardian_annual ='<table class="in_table">
                                            <tr><td></td></tr>
                                            <tr><td>'.$g2014.'.00</td></tr>
                                            <tr><td>'.$g2013.'.00</td></tr>
                                    </table>';?>
            <style>.guardian_info {display:table-cell} </style>
            
      <?php }
       
    
?>

    <div style="background-color:rgb(231, 98, 123);text-align:center;padding:5px;font-size:14px;margin-bottom:10px"><b>D. FINANCIAL INFORMATION</b></div>
    <div class="form_content">
        <div class="main_form">
            <p>Are you currently receiving or have accepted any financial assistance to support your study, eg. scholarship, grant, loan:</p>
            <?=$financial_assistant?>
            <div id='financial_assistance' >
            <p>Financial Assistance Information:</p>
            <table class='details_table'>
                <tr>
                    <td>Type of Financial Assistance</td>
                    <td><?=$type_of_financial?></td>
                </tr>
                <tr>
                    <td>Name of Financial Assistance</td>
                    <td><?=$name_of_financial?></td>
                </tr>
                <tr>
                    <td>Amount Received (RM)</td>
                    <td><?=$financial_amount?></td>
                </tr>
                <tr>
                    <td>Receiving period</td>
                    <td><?=$financial_period?></td>
                </tr>
            </table>
            </div>
            <p>Information on household income:</p>
            <table class='details_table'>
                <tr>
                    <th align="center" style="width:210px">Details</th>
                    <th align="center" style="width:310px">Father</th>
                    <th align="center" style="width:310px">Mother</th>
                    <th align="center" style="width:310px" class="guardian_info">Guardian</th>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><?=$father_name?></td>
                    <td><?=$mother_name?></td>
                    <td class="guardian_info"><?=$guardian_name?></td>
                </tr>
                <tr>
                    <td>NRIC number</td>
                    <td><?=$father_ic?></td>
                    <td><?=$mother_ic?></td>
                    <td class="guardian_info"><?=$guardian_ic?></td>
                </tr>
                <tr>
                    <td valign="top">Current Status</td>
                    <td><?=$father_status?></td>
                    <td><?=$mother_status?></td>
                    <td class="guardian_info"><?=$guardian_status?></td>
                </tr>
                <tr>
                    <td>Telephone no.</td>
                    <td><?=$father_tel?></td>
                    <td><?=$mother_tel?></td>
                    <td class="guardian_info"><?=$guardian_tel?></td>
                </tr>
                <tr>
                    <td>Occupation / Job Title</td>
                    <td><?=$father_position?></td>
                    <td><?=$mother_position?></td>
                    <td class="guardian_info"><?=$guardian_position?></td>
                </tr>
                <tr>
                    <td valign="top">Company Address</td>
                    <td><?=$father_address?></td>
                    <td><?=$mother_address?></td>
                    <td class="guardian_info"><?=$guardian_address?></td>
                </tr>
                <tr>
                    <td>
                        <table class="in_table" style="margin-top:-20px">
                            <tr><td valign="top">Annual Income (RM)</td></tr>
                            <tr><td>2014</td></tr>
                            <tr><td>2013</td></tr>
                        </table>
                    </td>
                    <td><?=$father_annual?></td>
                    <td><?=$mother_annual?></td>
                    <td class="guardian_info"><?=$guardian_annual?></td>
                </tr>
            </table>
            <p style="font-weight:bold">Certified true copy of 2 years tax return form for both parents to be attached at the end of application. </p>
            
            
            
            <p style="font-weight:bold;text-decoration:underline">**Checklist of Documents required:-**</p>
            <p>1) Identification Card (I.C.)</p>
            <p>2) Passport size photo (must be in color)</p>
            <p>3) Birth Certificate</p>
            <p>4) School Leaving Certificate</p>
            <p>5) Result Slip(s)</p>
            <p>6) Certification(s) of co-curricular activities</p>
            <p>7) Award/ Achievement Certificate(s)</p>
            <p>8) 2 years tax return forms</p>
            
           <?php  
             if ($editable =="0" || $editable =="admin"){
                $document = explode(",",$qry->member_document);
                    echo '<p style="text-decoration:underline;font-weight:bold">Document List</p>
                            <ul>';
                            for ($i=0;$i<count($document);$i++){
                                echo '<li><a href="./uploads/document/'.$document[$i].'" target="_blank">'.$document[$i].'</a></li>';
                            }
                    echo'</ul>';


                  }
?>
           	
            
                <p style="font-weight:bold">Declaration</p>
                <table style="border:1px solid #9E9E9E;padding:10px;margin-bottom:10px">
                    <tr>
                         <td>I, hereby declare that: <br/> 
                            I have not been convicted of any criminal offence or relating to dishonesty or fraud under any written law within or outside Malaysia; and I have not been declared a bankrupt, insolvent or un-discharged bankrupt.<br/>
                                All the information furnished by me is true and correct to the best of my knowledge and belief; I undertake the responsibility to promptly inform you of any changes therein.  <br/>
                               I am aware and understand that should information provided found to be distorted, untrue or misrepresenting, actions may be taken against me including immediate disqualification from the application. <br/>
                               I have read and agreed to the <a href="http://www.penangfuturefoundation.my/option/mod_content_article/cid/6557" target="_blank">Privacy Policy</a> of Penang Future Foundation. 
                        </td>
                    </tr>
                    <tr><td style="text-align:center"><input type="checkbox" value="1" name="chk_declare" <?=$declare?> /> Agree</td></tr>
                </table>
           
                <div style="text-align:center"><img src="./images/icon_print.gif" onClick="window.print()" id="print" style="margin-bottom:10px" title="Print"></div>
            <div class="cls"></div>
        </div>
        <?php
            if ($chk_complete=="1"){
                echo '<div class="submit_time" style="float:right;font-style:italic;margin:10px 0">Submit on : '.$submit_time.'</div>';
            }
        ?>
        
</div>


    
</div>