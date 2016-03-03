
<?php

 switch($_GET["sort"]){
        case "sname":       $extorder = "name";        break;
        case "sref":       $extorder = "ref";        break;
        case "sic":       $extorder = "ic";        break;
        case "spob":       $extorder = "pob";        break;
        case "scgpa":   $extorder = "cgpa"; break;
        case "spoints":       $extorder = "total_points";        break;
        case "sdate":           $extorder = "submission_created";        break;
    
        default:            $extorder = "member_id";        break;
    }
    
 if($_GET["asc"]==""){
        $extorder .= " DESC";
        $asc = "DESC";
    } else{
        $extorder .= " ASC";
        $asc = "ASC";
    }
    
    if($_GET["page"]!=""){
        $extPage .= "&page=".$_GET["page"];
    }

    if($_GET["status"]!=""){
        $ext .= " AND m.fund_status='".$_GET["status"]."'";
        $extPage .= "&status=".$_GET["status"];
    }else{
        $ext .=" AND m.fund_status ='PENDING'";
        $_GET['status']='PENDING';
    }
    
    if($_GET["keywords"]!=""){
        $ext .= " AND (m.member_email LIKE '%".$_GET["keywords"]."%' OR m.member_ic LIKE '%".$_GET['keywords']."%' OR p.member_name LIKE '%".$_GET['keywords']."%' OR p.member_home_number LIKE '%".$_GET['keywords']."%' OR p.member_mobile_number LIKE '%".$_GET['keywords']."%' OR m.submission_ref LIKE  '%".$_GET['keywords']."%')";
        $extPage .= "&keywords=".$_GET["keywords"];
    }
    
//      if($_GET["complete"]==""){
//            $ext .=" AND m.complete ='1'";
//            $_GET['complete']=1;
//    } else{
//        if ($_GET['complete']=="all"){
//            $ext .="";
//            $extPage .= "&complete=".$_GET["complete"];
//        }elseif ($_GET['complete']=="0"){
//            $ext .=" AND m.complete !='1'";
//             $extPage .= "&complete=".$_GET["complete"];
//        }else{
//            $ext .= "AND m.complete=".$_GET["complete"];
//             $extPage .= "&complete=".$_GET["complete"];
//        }
//        
//    }
    
    
    if($_GET["fdate"]!=""){
        $extPage .= "&fdate=".$_GET["fdate"];
        $ext .= " AND DATE(m.member_created)>='".date_htmltomysql($_GET["fdate"])."'";
    }
    if($_GET["tdate"]!=""){
        $extPage .= "&tdate=".$_GET["tdate"];
        $ext .= " AND DATE(m.member_created)<='".date_htmltomysql($_GET["tdate"])."'";
    }

//    
    if($_GET["id"]!="" && $_GET['fund_status']!=""){
        tep_query("UPDATE ".MEMBER." SET fund_status='".$_GET["fund_status"]."' WHERE member_id='".$_GET["id"]."'");
        tep_query("INSERT INTO ".TEMP." (member_id, action, temp_group, temp_createdby, temp_created) VALUES 
                 (".$_GET['id']." , 'Change status to ".$_GET['fund_status']."', '2','".$_SESSION['user_id']."',NOW())");
        
        $temp_id = tep_insert_id();
        
        if ($_GET['fund_status']=="SHORTLISTED"){
               $chk_ref = tep_fetch_object(tep_query("SELECT COUNT(member_id) AS counter FROM ".MEMBER." WHERE submission_ref != '' "));
                $chk_ref->counter++;
                 $sub_ref = "15A".str_pad($chk_ref->counter, 5, '0', STR_PAD_LEFT); 
                 tep_query("UPDATE ".MEMBER." SET submission_ref ='".$sub_ref."' WHERE member_id='".$_GET["id"]."'");
        }
        if ($_GET['fund_status']=="PENDING"){
            tep_query("UPDATE ".MEMBER." SET complete='0' WHERE member_id='".$_GET["id"]."'");
            
           $personal = tep_fetch_object(tep_query("SELECT * FROM ".PERSONAL." WHERE member_id = '".$_GET['id']."'"));
            tep_query("INSERT INTO temp_personal_details (temp_id,member_id,member_name,member_citizenship,member_sex, member_marital_status, member_dob, member_pob, member_home_address, member_postal_address,
                                member_home_number, member_mobile_number, studies_status, current_year, current_semester) VALUES
                                ('".$temp_id."' , '".$_GET['id']."', '".$personal->member_name."','".$personal->member_citizenship."', '".$personal->member_sex."' , '".$personal->member_marital_status."', '".$personal->member_dob."', '".$personal->member_pob."', '".$personal->member_home_address."', '".$personal->member_postal_address."',
                                  '".$personal->member_home_number."', '".$personal->member_mobile_number."', '".$personal->studies_status."', '".$personal->current_year."', '".$personal->current_semester."')");
            
            $academic = tep_fetch_object(tep_query("SELECT * FROM ".ACADEMIC." WHERE member_id = '".$_GET['id']."'"));
            tep_query("INSERT INTO temp_academic (temp_id, qualification_state,qualification_school,qualification_year_commenced, qualification_year_completed, qualification_obtained, examination_year,
                            examination_subject, examination_grades, examination_cgpa, college_name, college_course, college_subject, college_grades, college_cgpa,
                                co_year, co_club, co_position, co_school, awards_name, awards_prize, awards_level, awards_year, member_id) VALUES
                              ('".$temp_id."', '".$academic->qualification_state."', '".$academic->qualification_school."', '".$academic->qualification_year_commenced."', '".$academic->qualification_year_completed."', '".$academic->qualification_obtained."', '".$academic->examination_year."',
                               '".$academic->examination_subject."', '".$academic->examination_grades."', '".$academic->examination_cgpa."', '".$academic->college_name."', '".$academic->college_course."', '".$academic->college_subject."', '".$academic->college_grades."', '".$academic->college_cgpa."',
                               '".$academic->co_year."', '".$academic->co_club."', '".$academic->co_position."', '".$academic->co_school."', '".$academic->awards_name."', '".$academic->awards_prize."', '".$academic->awards_level."', '".$academic->awards_year."',  '".$_GET['id']."')");
                     
            $financial = tep_fetch_object(tep_query("SELECT * FROM ".FINANCIAL." WHERE member_id = '".$_GET['id']."'"));
            tep_query("INSERT INTO temp_financial_details (temp_id, member_id, financial_status, type_financial, name_financial, amount_financial, financial_period,
                    father_name, father_ic, father_status, father_phone, father_position, father_address, father_income,
                    mother_name, mother_ic, mother_status, mother_phone, mother_position, mother_address, mother_income,
                    guardian_name, guardian_ic, guardian_status, guardian_phone, guardian_position, guardian_address, guardian_income) VALUES
                    ('".$temp_id."', '".$_GET['id']."', '".$financial->financial_status."', '".$financial->type_financial."', '".$financial->name_financial."', '".$financial->amount_financial."', '".$financial->financial_period."',
                     '".$financial->father_name."', '".$financial->father_ic."', '".$financial->father_status."', '".$financial->father_phone."', '".$financial->father_position."', '".$financial->father_address."', '".$financial->father_income."',
                     '".$financial->mother_name."', '".$financial->mother_ic."', '".$financial->mother_status."', '".$financial->mother_phone."', '".$financial->mother_position."', '".$financial->mother_address."', '".$financial->mother_income."',
                     '".$financial->guardian_name."', '".$financial->guardian_ic."', '".$financial->guardian_status."', '".$financial->guardian_phone."', '".$financial->guardian_position."', '".$financial->guardian_address."', '".$financial->guardian_income."')");
            
            $course = tep_fetch_object(tep_query("SELECT * FROM ".COURSE." WHERE member_id = '".$_GET['id']."'"));
            tep_query("INSERT INTO temp_course_details (temp_id, member_id, course_name, college_name, course_enrollment, course_completion, scholarship_duration, scholarship_apply) VALUES
                    ('".$temp_id."', '".$_GET['id']."', '".$course->course_name."', '".$course->college_name."', '".$course->course_enrollment."', '".$course->course_completion."', '".$course->scholarship_duration."', '".$course->scholarship_apply."') ");
        }
        redirect("index.php?pages=application&succ=update".$extPage);
    }

   
    if($_GET["id"]!="" && $_GET["del"]==1){
        tep_query("UPDATE ".MEMBER." SET member_status=3 WHERE member_id='".$_GET["id"]."'");
        tep_query("INSERT INTO ".AUDITS."(`audit_page`, `audit_key`, `audit_value`, `audit_reference`,`audit_details`,`action`, `action_by`) 
                                    VALUES('Application', 'member_id', '".$_GET["id"]."', 0, 'Delete Applicant', NOW(), '".$_SESSION["user_id"]."')");
        redirect("index.php?pages=application&succ=update".$extPage);
    }
    $extRedirect = "'index.php?pages=application&status='+$('#ddlStatus').val()+'&keywords='+$('#keywords').val()+'&fdate='+$('#fdate').val()+'&tdate='+$('#tdate').val()";

    $ddl_status = '<option value="PENDING">Pending</option>
                              <option value="SUBMITTED">Submitted</option>
                              <option value="SHORTLISTED">Shorlisted</option>
                              <option value="APPROVED">Approved</option>
                              <option value="REJECTED">Rejected</option>';
    
   

    if($_GET["asc"]==""){
        $extPage .= "&asc=1";
    }
?>

<div class="page_title" style="float: left"><?php echo $page_title; ?></div>

<div style="float: right; margin-top: 3px;">
    <input type="text" name="keywords" id="keywords" placeholder="Search Keywords" value="<?=$_GET["keywords"]?>" style="width:200px; margin-top:3px;" />
    <select id="ddlStatus" onchange="redirect(<?=$extRedirect?>)"><option value="">- Status -</option><?= ddlReplace($ddl_status, $_GET['status'])?></select>
   
    <input type="text" name="fdate" id="fdate" class="datepicker" placeholder="From date" value="<?=$_GET["fdate"]?>" style="width:80px; margin-top:3px;" />
    <input type="text" name="tdate" id="tdate"class="datepicker" placeholder="To date" value="<?=$_GET["tdate"]?>" style="width:80px; margin-top:3px;" />
    <input type="submit" style="background-color:#F02020;width:100px;height:27px;border:0;color:#FFF;cursor:pointer" name="btnSearch" value="Search" onclick="redirect(<?=$extRedirect?>)" />
      
    <div id="export" style="float:right;margin:5px">
<input type="button" onclick="window.location='index.php?pages=report_export&export=<?=$_GET['status']?>'" value="Export" title="Export to Excel">
</div>
</div>

<div class="cls"></div>



<form class="list_form" action="index.php?pages=application&id=<?=$_GET["id"].$extPage?>" method="POST" enctype="multipart/form-data">
<table class="t"  width="100%" style="border-collapse: collapse">
    <thead> 
    <tr>
        <th width="35px">No.</th>
        <th width="*"><a id="sname" href="index.php?pages=application&sort=sref<?=$extPage?>">Application No</a></th>
        <th width="150px"><a id="sname" href="index.php?pages=application&sort=sname<?=$extPage?>">Name</a></th>
        <th width="*">Mobile Phone No</th>
        <th width="*"><a id="sic" href="index.php?pages=application&sort=sic<?=$extPage?>">IC No</a></th>
        <th width="*"><a id="spob" href="index.php?pages=application&sort=spob<?=$extPage?>">Place of birth</a></th>
        <th><a id="scgpa" href="index.php?pages=application&sort=scgpa<?=$extPage?>">CGPA</a></th>
        <th width="100px"><a id="spoints" href="index.php?pages=application&sort=spoints<?=$extPage?>">Total Points</a></th>
        <th width="100px"><a id="spoints" href="index.php?pages=application&sort=sdate<?=$extPage?>">Date Submitted</a></th>
        <th width="100px">Status</th>
        <th width="130px" align="center">Actions</th>
    </tr>
    </thead> 
    <tbody> 

<?php
$extPage = str_replace("&asc=1","",$extPage);
if($_GET["asc"]!=""){
    $extPage .= "&asc=".$_GET["asc"];
}

if($_GET["sort"]!=""){
    $extPage .= "&sort=".$_GET["sort"];
}

  
    
$qry = "(SELECT m.complete AS complete, p.studies_status AS studies_status, m.member_ic AS ic, m.submission_ref AS ref, p.member_name AS name, p.member_mobile_number AS mobile_number, p.member_pob AS pob, a.examination_cgpa AS cgpa, m.total_points AS total_points, m.submission_created AS submission_created, m.fund_status AS fund_status, m.member_id AS member_id, m.member_remarks AS remarks FROM ".MEMBER." m LEFT JOIN ".PERSONAL." p ON  m.member_id = p.member_id LEFT JOIN ".ACADEMIC." a  ON  m.member_id = a.member_id  WHERE  m.member_status = 1 AND p.studies_status = 0".$ext." GROUP BY member_id )
        UNION (SELECT m.complete AS complete,  p.studies_status AS studies_status, m.member_ic AS ic, m.submission_ref AS ref, p.member_name AS name, p.member_mobile_number AS mobile_number, p.member_pob AS pob, a.college_cgpa AS cgpa, m.total_points AS total_points, m.submission_created AS submission_created, m.fund_status AS fund_status, m.member_id AS member_id, m.member_remarks AS remarks FROM ".MEMBER." m LEFT JOIN ".PERSONAL." p ON  m.member_id = p.member_id LEFT JOIN ".ACADEMIC." a  ON  m.member_id = a.member_id  WHERE  m.member_status = 1 AND p.studies_status = 1".$ext." GROUP BY member_id )
         ORDER BY ".$extorder." ";

$query = tep_query($qry);
$numRows = tep_num_rows($query);

if($numRows>0){
    $count=0;
    $query = tep_query($qry.pagination(ROWSPERPAGE));
    while ($re = tep_fetch_object($query)){
    $count++;

    if ($re->studies_status ==0){
        $cgpa = $re->cgpa;
    }else{
        $cgpa = $re->cgpa;
    }
    
    if ($re->complete !="1"){
        $status = "disabled";
    }else{
        $status ="";
    }
    
    
    if ($re->total_points < 0){
        $total_points = 0;
    }else{
        $total_points = $re->total_points;
    }
    
    if ($re->submission_created == "0000-00-00 00:00:00"){
        $submission = "-";
    }else{
        $submission = date("d/m/Y", strtotime($re->submission_created));
    }
    
    
    

  

            echo '<tr id="'.$re->member_id.'" onmouseover="this.style.background=\'#D8DFEA\';this.style.cursor=\'pointer\'" onmouseout="this.style.background=\'\';">
                    <td>'.$count.'.</td>
                    <td>'.$re->ref.'</td>
                    <td>'.strtoupper($re->name).'</td>
                   <td>'.$re->mobile_number.'</td>
                    <td>'.$re->ic.'</td>
                    <td>'.strtoupper($re->pob).'</td>
                    <td>'.number_format($cgpa,4).'</td>
                    <td>'.number_format($total_points,2).'</td>
                    <td>'.$submission.'</td>
                    <td><select onchange="redirect(\'index.php?pages=application&fund_status=\'+$(this).val()+\'&id='.$re->member_id.$extPage.'\')" '.$status.'>'.ddlReplace($ddl_status, $re->fund_status).'</select></td>
                    
                    <td align="center">
                        <input type="button" value="Remarks" onclick="wopen(\'./index.php?pages=remarks&id='.$re->member_id.'\', \'\', 1000, 450)" class="btn"/>
                        <input type="button" value="History" onclick="wopen(\'./index.php?pages=history&id='.$re->member_id.'\', \'\', 1000, 450)" class="btn" />
                        <input type="button" value="Document" onclick="wopen(\'./index.php?pages=document&id='.$re->member_id.'\', \'\', 1000, 450)" class="btn" />
                        <input type="button" value="View Profile" onclick="window.open(\'../index.php?id='.$re->member_id.'\')" class="btn" />';
            if (isAdmin()){
                        echo ' <input type="button" value="Delete" onclick="if(confirm(\'Are you sure to delete?\')){ redirect(\'index.php?pages=application&id='.$re->member_id.'&del=1'.$extPage.'\') }" class="btn" />';
            }
            
                    echo'</td>
                </tr>';
        
                    
    }  
} else{
    echo '<tr><td align="center" colspan="11"><br />No record found.<br /><br /></td></tr>';
} 

?>
        </tbody> 
</table>
</form>
<?php paging_navigation($numRows,ROWSPERPAGE) ?>




