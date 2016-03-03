<?php

    $ddl_active = '<option value="-1">View All</option>
                   <option value="1">Active</option>
                   <option value="0">Inactive</option>';
    
    if($_GET["active"]==""){
        $_GET["active"] = "1";
    } else{
            $extUrl = "&active=".$_GET["active"];
        
    }
?>
<style>
    .addnew_form span, .t span {color:red}
    </style>
<div class="page_title" style="float: left"><?php echo $page_title; ?>

<?php if(isAdmin()){ ?>
    <a class="add_new" title="Add New" href="" onclick="$('.addnew_form').show(); return false;">Add New</a>
</div>
<div style="float: right; margin-top: 3px;">
    <select onchange="redirect('index.php?pages=users&active='+$(this).val())" style="padding: 0; width: 80px">
        <?=str_replace('value="'.$_GET["active"].'"', 'value="'.$_GET["active"].'" SELECTED', $ddl_active)?>
    </select>
<?php } ?>

</div>
<div class="cls"></div>

<?php

    if($_GET["id"]!="" && $_GET["pb"]!=""){
        tep_query("UPDATE ".USERS." SET user_status='".$_GET["pb"]."' WHERE user_id='".$_GET["id"]."'");
        tep_query("INSERT INTO ".AUDITS."(`audit_page`, `audit_key`, `audit_value`, `audit_reference`,`audit_details`,`action`, `action_by`) 
                                    VALUES('users', 'user_id', '".$_GET["id"]."', '".$_GET["pb"]."', 'Active/Inactive User', NOW(), '".$_SESSION["user_id"]."')");
        
        redirect("index.php?pages=users".$extUrl);
    }
    
     if($_GET["id"]!="" && $_GET["del"]==1){
        tep_query("DELETE FROM ".USERS."  WHERE user_id='".$_GET["id"]."'");
        tep_query("INSERT INTO ".AUDITS."(`audit_page`, `audit_key`, `audit_value`, `audit_reference`,`audit_details`,`action`, `action_by`) 
                                    VALUES('User', 'user_id', '".$_GET["id"]."', 0, 'Delete User', NOW(), '".$_SESSION["user_id"]."')");
        redirect("index.php?pages=users&succ=update".$extPage);
    }
    
    switch($_POST['submit']){
        case "Add New":
            if($_POST['user_name']!="" && $_POST['user_email']!="" && $_POST['password']!="" && $_POST["passwordconf"]){
                if($_POST['password'] == $_POST["passwordconf"]){
                    $checkexist = tep_result(tep_query("SELECT COUNT(*) FROM ".USERS." WHERE user_email LIKE '".trim($_POST['user_email'])."'"));
                    if($checkexist>0){
                        echo errMsg("Username already existed.");
                    } else{
                        tep_query("INSERT INTO ".USERS." (`user_name`,`user_email`,`user_password`, `user_group`, `user_created`)
                                    VALUES ('".$_POST['user_name']."', '".trim($_POST['user_email'])."', '".md5($_POST['password'])."', '".$_POST['user_group']."', NOW())");
                        
                        tep_query("INSERT INTO ".AUDITS."(`audit_page`, `audit_key`, `audit_value`, `audit_reference`,`audit_details`,`action`, `action_by`) 
				    VALUES('users','user_id', '".tep_insert_id()."', '', 'Add User', NOW(), '".$_SESSION["user_id"]."')");
                
                        redirect("index.php?pages=users&succ=add");
                    }
                }else{
                    echo errMsg("Password does not match.");
                }
            } else{
                echo errMsg("Some input is not completed.");
            }
        break;
        case "Update":
             if(isAdmin()){
                if($_POST['user_name']!="" && $_POST['user_email']!=""){
                    if ($_POST['password']==$_POST['passwordconf'] && $_POST['password']!=""){
                            $extra = ",user_password='".md5($_POST['password'])."' ";
                    } else {
                            if ($_POST['password']!=""){
                            $err = "Password does not match.";
                            }
                            $extra = "";
                    }
                    
                    tep_query("UPDATE ".USERS." SET user_name='".$_POST['user_name']."', 
                                                    user_email='".trim($_POST['user_email'])."', 
                                                    user_group='".$_POST["user_group"]."' $extra
                                              WHERE user_id='".$_POST['id']."'");

                    tep_query("INSERT INTO ".AUDITS."(`audit_page`, `audit_key`, `audit_value`, `audit_reference`,`audit_details`,`action`, `action_by`) 
                                VALUES('users','user_id', '".$_POST['id']."', '', 'Update User', NOW(), '".$_SESSION["user_id"]."')");
                    
                    if($err == ""){
                        redirect("index.php?pages=users&succ=update".$extUrl);
                    }else{
                        echo errMsg($err);
                    }
                } else{
                    echo errMsg("Some input is not completed.");
                }
            } else{
                if ($_POST['password']==$_POST['passwordconf'] && $_POST['password']!=""){
                    mysql_query("UPDATE ".USERS." SET user_password='".md5($_POST['password'])."' WHERE user_id='".$_POST['id']."'");
                    tep_query("INSERT INTO ".AUDITS."(`audit_page`, `audit_key`, `audit_value`, `audit_reference`,`audit_details`,`action`, `action_by`) 
                                VALUES('users','user_id', '".$_POST['id']."', '', 'Update User', NOW(), '".$_SESSION["user_id"]."')");
                    
                    redirect("index.php?pages=users&succ=update");
                } else{
                    echo errMsg("Some input is not completed.");
                }
            }
            break;
        }
    
    if($_GET["succ"]=="add"){
        echo succMsg("User added successfully.");
    }
    else if($_GET["succ"]=="update"){
        echo succMsg("User updated successfully.");
    }
    
    $ddl_group = '<option value="SYSTEM_ADMIN">System Admin</option>
                              <option value="NORMAL_USER">Normal User</option>';
?>
<form action="index.php?pages=users" method="POST" enctype="multipart/form-data">
<div class="addnew_form">
    <table style="float:left">
        <tr><td>Name:<span>*</span></td><td><input type="text" name="user_name" value="<?=$_POST["user_name"]?>" style="width: 160px" /></td></tr>
        <tr><td>Username:<span>*</span></td><td><input type="text" name="user_email" value="<?=$_POST["user_email"]?>" style="width: 160px" /></td></tr>
        <tr><td>Group:<span>*</span></td>
            <td><select name="user_group" style="width: 166px">
                    <?=str_replace('value="'.$_POST["user_group"].'"', 'value="'.$_POST["user_group"].'" SELECTED', $ddl_group)?>">
                </select>
            </td>
        </tr>
        <tr><td>Password:</td><td><input type="password" name="password" style="width: 160px" /></td></tr>
        <tr><td>Password Confirm:</td><td><input type="password" name="passwordconf" style="width: 160px" /></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
           <tr><td></td><td>
            <input type="submit" name="submit" value="Add New" class="btn" />
            <input type="button" name="cancel" value="Cancel" class="btn" onclick="$('.addnew_form').hide()"></td>
        </tr>
    </table>

    
</div>
</form>

<form action="index.php?pages=users<?=$extUrl?>" method="POST" enctype="multipart/form-data">
<table class="t" width="100%" style="border-collapse: collapse">
    <tr>
        <th width="35px">No.</th>
        <th width="100px">Name</th>
        <th width="*">Username</th>
        <th width="100px" align="center">Group</th>
        <th width="100px" align="center">Added Date</th>
        <th width="60px" align="center">Status</th>
        <th width="200px" align="center">Actions</th>
    </tr>
<?php

$count = 0;
if(!isAdmin()){
    $ext = " AND user_id='".$_SESSION["user_id"]."'";
}

if($_GET["active"]>=0){
    $ext .= " AND user_status='".$_GET["active"]."'";
}
$qry = "SELECT * FROM ".USERS." WHERE 1 $ext ORDER BY FIELD(user_group, 'SYSTEM_ADMIN', 'NORMAL_USER'), user_name ASC";

$query = tep_query($qry);
$numRows = tep_num_rows($query);

if($numRows>0){
    $query = tep_query($qry.pagination(ROWSPERPAGE));
    while($re=tep_fetch_object($query)){
        $count++;
        if($_GET["id"]==$re->user_id){
            
                if(isAdmin()){
                $extEdit = '<table style="float: left">
                                <tr><td>Name:<span>*</span></td><td><input type="text" name="user_name" value="'.$re->user_name.'" style="width: 160px" /></td></tr>
                                <tr><td>Username:<span>*</span></td><td><input type="text" name="user_email" value="'.$re->user_email.'" style="width: 160px" /></td></tr>
                                <tr><td>Group:<span>*</span></td>
                                    <td><select name="user_group" style="width: 166px">
                                            '.str_replace('value="'.$re->user_group.'"', 'value="'.$re->user_group.'" SELECTED', $ddl_group).'
                                        </select></td></tr>
                            </table>
                            
                            <table style="float: left; margin-left: 20px">
                                <tr><td>Password:</td><td><input type="password" name="password" value="" style="width: 160px" /></td></tr>
                                <tr><td>Password Confirm:</td><td><input type="password" name="passwordconf" value="" style="width: 160px" /></td></tr>
                            </table>';
            } else{
                $extEdit = '<table>
                                <tr><td>Password:<span>*</span></td><td><input type="password" name="password" value="'.$re->password.'" style="width: 160px" /></td></tr>
                                <tr><td>Password Confirm:<span>*</span></td><td><input type="password" name="passwordconf" value="'.$re->passwordconf.'" style="width: 160px" /></td></tr>
                            </table>';
            }
            
               
            echo '<tr id="'.$re->user_id.'" onmouseover="this.style.background=\'#D8DFEA\';this.style.cursor=\'pointer\'" onmouseout="this.style.background=\'\';">
                <td valign="top">'.$count.'.</td>
                <td colspan="5">
                    '.$extEdit.'
                </td>
                <td align="center" valign="top">
                    <input type="hidden" name="id" value="'.$re->user_id.'" />
                    <input type="submit" name="submit" value="Update" class="btn" />
                    <input type="button" value="Cancel" onclick="redirect(\'index.php?pages=users'.$extUrl.'\')" class="btn" />
                </td>
            </tr>';
        } else{        
         
            
            if($re->user_status=="1"){
                $publish = "tick";
                $pb = "0";
            } else{
                $publish = "cross";
                $pb = "1";
            }
            
            if ($re->user_group == "SYSTEM_ADMIN"){
                $user_group = "System Admin";
            }else{
                $user_group = "Normal User";
            }
         
                $extPublish = ' onclick="redirect(\'index.php?pages=users&id='.$re->user_id.'&pb='.$pb.$extUrl.'\')"';
           
            
            echo '<tr id="'.$re->user_id.'" onmouseover="this.style.background=\'#D8DFEA\';this.style.cursor=\'pointer\'" onmouseout="this.style.background=\'\';">
                    <td>'.$count.'.</td>
                    <td>'.$re->user_name.'</td>
                    <td>'.$re->user_email.'</td>
                    <td align="center">'.$user_group.'</td>
                    <td align="center">'.date(FORMAT_DATE, strtotime($re->user_created)).'</td>
                    <td align="center"><img src="./images/icon_'.$publish.'.png" '.$extPublish.' /></td>
                    <td align="center">
                        <input type="button" value="Edit" onclick="redirect(\'index.php?pages=users&id='.$re->user_id.$extUrl.'#'.$re->user_id.'\')" class="btn" /> ';
            
                        echo'<input type="button" value="Delete" onclick="if(confirm(\'Are you sure to delete?\')){ redirect(\'index.php?pages=users&id='.$re->user_id.'&del=1\') }" class="btn" />';
          
                   echo' </td>
                </tr>';
        }
    }
    
} else{
    echo '<tr><td align="center" colspan="9"><br />No record found.<br /><br /></td></tr>';
} 

?>
</table>
</form>
<?php paging_navigation($numRows,ROWSPERPAGE) ?>