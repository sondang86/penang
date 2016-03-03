<script type="text/javascript">
$(function() {
	//normal datepicker
	$('.datepicker').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd/mm/yy',
                yearRange: "-50:+10",
	});	//blur selections

})
</script>


<?php
//    if($_GET["op"]!=""){
//        $extPage .= "&op=".$_GET["op"];
//        $ext .= " AND u.user_name LIKE '%".$_GET["op"]."%'";
//    }

    if($_GET["fdate"]!=""){
        $extPage .= "&fdate=".$_GET["fdate"];
        $ext .= " AND DATE(t.temp_created)>='".date_htmltomysql($_GET["fdate"])."'";
    }
    if($_GET["tdate"]!=""){
        $extPage .= "&tdate=".$_GET["tdate"];
        $ext .= " AND DATE(t.temp_created)<='".date_htmltomysql($_GET["tdate"])."'";
    }
    
    if ($_POST['submit']!=""){
          tep_query("UPDATE ".TEMP." SET `temp_remarks`='".tep_input($_POST['temp_remarks'])."'
                                            WHERE temp_id='".$_POST['id']."'");
               
                redirect("index.php?pages=log_report&succ=update".$extPage);
    }

  $searchClick = "'index.php?pages=log_report&op='+$('#op').val()+'&fdate='+$('#fdate').val()+'&tdate='+$('#tdate').val()";
?> 

<div class="cls"></div>


<div style="width:95%;padding:0 30px">
 <div class="page_title">Operation Logs</div>
 
    <!-- ############# SEARCH PART ###############-->

        <div style="text-align:center;margin:10px 0">
         
<!--        <input type="text" name="op" id="op" placeholder="Search by operation name" value="<?=$_GET["op"]?>" style="width:200px; margin-top:3px;" />-->
        <input type="text" name="fdate" id="fdate" class="datepicker" placeholder="From date" value="<?=$_GET["fdate"]?>" style="width:80px; margin-top:3px;" />
        <input type="text" name="tdate" id="tdate"class="datepicker" placeholder="To date" value="<?=$_GET["tdate"]?>" style="width:80px; margin-top:3px;" />
          
        <input type="submit" style="background-color:#F02020;width:100px;height:27px;border:0;color:#FFF;cursor:pointer" name="btnSearch" value="Search" onclick="redirect(<?=$searchClick?>)" />
          
        </div>

<!-- ############# SEARCH PART ENDS HERE ###############-->
<form class="list_form" action="index.php?pages=log_report" method="POST">

    <table class="log_table" width="100%" style="border-collapse:collapse;border:1px solid #DDD;">
        <tr style="background-color:#EFEFEF">
        <th width="100px">Date</th>
        <th width="150px">Action</th>
        <th width="150px">Details</th>
        <th width="150px">Action By</th>
        <th width="100px"></th>
       
        </tr>
        
            <?php
           
             $qry = "SELECT * FROM ".TEMP." t, ".MEMBER." m  WHERE  t.member_id = m.member_id $ext ORDER BY t.temp_id DESC";
   
             $query = tep_query($qry);
    $numRows = tep_num_rows($query);
    if($numRows>0){
      $query = tep_query($qry.pagination(ROWSPERPAGE));
         $count=0;
        while($re=tep_fetch_object($query)){
            if ($_GET['id']==$re->temp_id){
                 echo '<tr id="'.$re->temp_id.'" onmouseover="this.style.background=\'#D8DFEA\';this.style.cursor=\'pointer\'" onmouseout="this.style.background=\'\';">
                    
                    <td colspan="4">
                   
                    <table style="float: left; margin-left: 20px">
                      
                        <tr><td align="right" valign="top">Remarks:</td><td><div class="c_article"><textarea id="temp_remarks" name="temp_remarks">'.stripslashes($re->temp_remarks).'</textarea></div></td></tr>       
                 </table>';
                 
                  echo "<script type=\"text/javascript\">
                        CKEDITOR.replace('temp_remarks',
                        {
                                filebrowserUploadUrl  :'".HTTP_SERVER."/includes/ckeditor/filemanager/connectors/php/upload.php?Type=File',
                                filebrowserImageUploadUrl : '".HTTP_SERVER."/includes/ckeditor/filemanager/connectors/php/upload.php?Type=Image',
                                height : \"250px\",
                                width : \"800px\"
                        });
                        
                      </script>";     
                 echo'<div class="cls"></div>
                    </td>
                    <td valign="top" align="center">
                        <input type="hidden" name="id" value="'.$re->temp_id.'" />
                        <input type="submit" name="submit" value="Update" class="btn" />
                        <input type="button" value="Cancel" onclick="redirect(\'index.php?pages=log_report'.$extPage.'\')" class="btn" />
                    </td>
                </tr>';
            }else{
                if ($re->temp_group == "1"){
                    $member = tep_fetch_object(tep_query("SELECT * FROM ".MEMBER." WHERE member_id = '$re->temp_createdby'"));
                    $email = $member->member_email;
                }else{
                    $user = tep_fetch_object(tep_query("SELECT * FROM ".USERS." WHERE user_id = '$re->temp_createdby'"));
                    $email = $user->user_email;
                }


                  $count++;
                  echo'<tr id="'.$re->temp_id.'" style="border-top:1px solid #ddd;" onmouseover="this.style.background=\'#D8DFEA\';" onmouseout="this.style.background=\'\';">
                      <td>'.$re->temp_created.'</td>

                      <td>'.$re->action.'</td>
                       <td>'.$re->member_email.'</td> 
                      <td>'.$email.'</td>
                      <td><input type="button" value="Add Remarks" onclick="redirect(\'index.php?pages=log_report&id='.$re->temp_id.$extPage.'#'.$re->temp_id.'\')" class="btn" /></td></tr>';
        
            }
    }
    }
    if ($numRows==0){
        echo'<tr>
    <td colspan="5" class="center" style="font-style:italic"><br />No record(s) found.<br /><br /></td>
</tr>';
    }
            ?>
       
    </table>
</form>
<div style="margin-left:28px;margin-bottom:10px">
<?php paging_navigation($numRows,ROWSPERPAGE);  ?>
</div>

 


