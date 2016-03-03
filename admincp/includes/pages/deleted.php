
<?php

 switch($_GET["sort"]){
        case "sname":       $extorder = "p.member_name";        break;
        case "sic":       $extorder = "p.member_ic";        break;
        case "spob":       $extorder = "p.member_pob";        break;
        case "scgpa":
            if ($re->studies_status ==0){
                    $extorder = "a.examination_cgpa";
                }else{
                    $extorder = "a.college_cgpa";
                }
        break;
        case "spoints":       $extorder = "m.total_points";        break;
        default:            $extorder = "m.member_id";        break;
    }
    
     if($_GET["asc"]==""){
        $extorder .= " DESC";
    } else{
        $extorder .= " ASC";
    }

    if($_GET["page"]!=""){
        $extPage .= "&page=".$_GET["page"];
    }

    if($_GET["id"]!="" && $_GET['fund_status']!=""){
        tep_query("UPDATE ".MEMBER." SET fund_status='".$_GET["fund_status"]."', member_status = 1 WHERE member_id='".$_GET["id"]."'");
        redirect("index.php?pages=deleted&succ=update".$extPage);
    }


   
   $ddl_status = '<option value="DELETED">Deleted</option>
                              <option value="SHORTLISTED">Shorlisted</option>
                              <option value="DISQUALIFIED">Disqualified</option>';

       if($_GET["asc"]==""){
        $extPage .= "&asc=1";
    }

?>

<div class="page_title" style="float: left"><?php echo $page_title; ?>
    
</div>

<div class="cls"></div>



<form class="list_form" action="index.php?pages=application&id=<?=$_GET["id"].$extPage?>" method="POST" enctype="multipart/form-data">
<table class="t"  width="100%" style="border-collapse: collapse">
    <thead> 
    <tr>
        <th width="35px">No.</th>
        <th width="150px"><a id="sname" href="index.php?pages=deleted&sort=sname<?=$extPage?>">Name</a></th>
        <th width="*">Mobile Phone No</th>
        <th width="*"><a id="sic" href="index.php?pages=deleted&sort=sic<?=$extPage?>">IC No</a></th>
        <th width="*"><a id="spob" href="index.php?pages=deleted&sort=spob<?=$extPage?>">Place of birth</a></th>
        <th width="200px"><a id="scgpa" href="index.php?pages=deleted&sort=scgpa<?=$extPage?>">CGPA</a></th>
        <th width="100px"><a id="spoints" href="index.php?pages=deleted&sort=spoints<?=$extPage?>">Total Points</a></th>
        <th width="100px">Status</th>
        <th width="130px" align="center">Actions</th>
    </tr>
    </thead> 
    <tbody> 

<?php

$qry = "SELECT * FROM ".MEMBER." m, ".PERSONAL." p, ".ACADEMIC." a WHERE p.member_id = m.member_id AND a.member_id = m.member_id AND m.member_status = 3 AND m.complete = 1 ORDER BY ".$extorder."";
$query = tep_query($qry);
$numRows = tep_num_rows($query);

if($numRows>0){
    $count=0;
    $query = tep_query($qry.pagination(ROWSPERPAGE));
    while ($re = tep_fetch_object($query)){
    $count++;

    if ($re->studies_status ==0){
        $cgpa = $re->examination_cgpa;
    }else{
        $cgpa = $re->college_cgpa;
    }
        
            echo '<tr id="'.$re->member_id.'" onmouseover="this.style.background=\'#D8DFEA\';this.style.cursor=\'pointer\'" onmouseout="this.style.background=\'\';">
                   <td>'.$count.'.</td>
                    <td>'.$re->member_name.'</td>
                   <td>'.$re->member_mobile_number.'</td>
                    <td>'.$re->member_ic.'</td>
                    <td>'.$re->member_pob.'</td>
                    <td>'.number_format($cgpa,3).'</td>
                    <td>'.number_format($re->total_points,2).'</td>
                    <td><select onchange="redirect(\'index.php?pages=deleted&fund_status=\'+$(this).val()+\'&id='.$re->member_id.$extPage.'\')">'.$ddl_status.'</select></td>
                    <td align="center">
                        <input type="button" value="Document" onclick="wopen(\'./index.php?pages=document&id='.$re->member_id.'\', \'\', 1000, 450)" class="btn" />
                        <input type="button" value="View Profile" onclick="window.open(\'../index.php?id='.$re->member_id.'&action=view\')" class="btn" />
                    </td>
                    
                </tr>';
        
    }  
} else{
    echo '<tr><td align="center" colspan="8"><br />No record found.<br /><br /></td></tr>';
} 

?>
        </tbody> 
</table>
</form>
<?php paging_navigation($numRows,ROWSPERPAGE) ?>


