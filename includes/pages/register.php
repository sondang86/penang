<?php

function createRandomPassword() { 

    $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 

    while ($i <= 7) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 

    return $pass; 

} 

if($_POST['submit']!=""){
            
                 
                if ($_POST['email']!="" && $_POST['password']!=""){
                    $query = tep_query("SELECT * FROM ".MEMBER." WHERE member_email='".$_POST['email']."' OR member_ic = '".$_POST['ic']."'");
                   if ($_POST['password'] != $_POST['conf_password']){
                         echo "<script>alert('Password not match!')</script>";
                   }else if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $_POST['password'])){
                              echo "<script>alert('Password must be alphanumeric!')</script>";  
                    }else if (strlen($_POST['password']) < 8){
                              echo "<script>alert('Password must be more than 8 characters!')</script>";  
                    }else{
                        if(tep_num_rows($query)>0){
                             while ($re=tep_fetch_object($query)){
                                 if ($re->member_email == $_POST['email']){
                                     redirect('index.php?pages=login&email=registered');
                                 }else{
                                     redirect('index.php?pages=login&ic=registered');
                                 }
                             }
                        }else{
                            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){ 
                              if ($_POST['ic'][0]=='9' || $_POST['ic'][0] =='0' || ($_POST['ic'][0] == '8' && $_POST['ic'][1] =='9')){
                               $verificationCode = createRandomPassword();
                               
                               tep_query("INSERT INTO ".MEMBER."(member_email,member_password,member_ic,member_status,member_created,fund_status) 
                                       VALUES('".trim($_POST['email'])."','".md5($_POST['password'])."','".$_POST['ic']."',0 ,NOW(),'PENDING')");
                               
                               $_SESSION['insertId'] = mysql_insert_id();
                               $insertId = mysql_insert_id();
                               $verication_link = 'index.php?pages=register&id='."$insertId".'&code='."$verificationCode";
                               
                               //Insert data to account registration
                               tep_query("INSERT INTO account_registration (member_email,status, verify_string,member_created,member_status, member_id, verification_link) 
                                       VALUES('".trim($_POST['email'])."',0 , '".$verificationCode."',NOW(),'PENDING', $insertId, '$verication_link')");
                               
                               //Get user account registration information
                               $query = tep_query("SELECT * FROM account_registration WHERE member_id=$insertId");
                                while ($row = mysql_fetch_array($query)) {
                                $account_info = $row;
                                } 
                                $query_member = tep_query("SELECT * FROM member WHERE member_id=$insertId");
                                while ($row = mysql_fetch_array($query_member)) {
                                $member_info = $row;
                                }
                                
//                               Sending message to user production
//                                $email = trim($_POST['email']);  
//                                $content_title = "Penang Future Foundation Account Verification";
//                                $template = "Hi, \r\n \r\n Thank you for your registration in Penang Future Foundation \r\n "
//                                            . "Please click on the link below to verify your account \r\n http://form.penangfuturefoundation.my/".$verication_link." \r\n \r\n"
//                                            . "========= User Account Log ========= \r\n".$account_info['member_created']."             Account Registration \r\n "
//                                            . "\r\n \r\n -- \r\n \r\n"
//                                            . "Best regards,\r\n Penang Future Foundation \r\n \r\n \r\n \r\n";
//                                
//                                $headers = 'From: info@penangfuturefoundation.my' . "\r\n";
//
//                                mail($email, $content_title, $template, $headers);
                                
                                
//                                Localhost send mail method
                                $email = trim($_POST['email']);
                                $headers = 'From: info@penangfuturefoundation.my' . "\r\n";
                                $content_title = "Penang Future Foundation Account Verification";
                                $template = "Hi, <br/><br/>Thank you for your registration in Penang Future Foundation<br><br>
                                    Please click on the link below to verify your account<br>
                                    <a href=\"http://localhost/penangfuture/" .$verication_link ."\">http://localhost/penangfuture/".$verication_link."</a><br><br>
                                        ========= User Account Log ========= <br>
                                       ".$account_info['member_created']."             Account Registration <br><br>"
                                        ."<br><br><br><br>    
                                    --<br><br/>
                                                                                                Best regards,<br/>
                                    Penang Future Foundation\n\n\n<br/>";    
                                
                                   
                                   PHPMail($email,$template,$content_title );    

                             redirect('index.php?pages=login&register=verify');
                              }else{
                                  echo "<script>alert('Age limit is 25 years or younger as at 1st of January!')</script>";
                              }
                           }else{
                               echo "<script>alert('Please key in valid email!')</script>";
                           }
                    
                    }
                   }
                } else{
                        echo "<script>alert('Please key in all the require input!')</script>";
            }

}

//Update user status after verified by email
 if ($_GET['code']!="" && $_GET['id']!=""){
      tep_query ("UPDATE ".MEMBER." SET member_status = 1 WHERE member_id = '".$_GET['id']."'");
      tep_query ("UPDATE account_registration SET status = 1 WHERE member_id = '".$_SESSION['insertId']."'");
      unset($_SESSION['insertId']);
      redirect('index.php?pages=login?register=success');
 }
?>

<html>
<head>
    <title><?=SITENAME?></title>
    <script type="text/javascript" src="includes/modules/jquery.js"></script>
    <link rel="shortcut icon" href="<?php echo HTTP_SERVER; ?>/images/operion_icon.png" />
    <link rel="stylesheet" type="text/css" href="includes/css/style.css">
    <script type="text/javascript" src="includes/modules/j_tooltip/tipr.min.js"></script>
    <link rel="stylesheet" type="text/css" href="includes/modules/j_tooltip/tipr.css" />
</head>
<style>
      html, body{background-color: #F7F6F1;background:url(./images/bg1.png)}
    
    
</style>
<script>
$(document).ready(function(){
    $('#ic').focus();
    $('.tip').tipr();
});

</script>
<body>
<form action="index.php?pages=register" method="post">
    <div style="text-align:center;width:100%;">
        <div id="main" style="margin-top: 103px">
        <table border="0" cellpadding="5" cellspacing="0" style="margin: 0 auto; border: 1px solid #e5e5e5; background-color: #F7F6F1; font-size: 14px; line-height: 25px; padding: 15px 20px 20px 20px; box-shadow: 0 0 15px 5px rgba(207, 207, 207, 0.8)">
            <tr><th align="center"><img src="./images/logo.png" /></th></tr>
            <tr><td align="center"><h3>Register</h3></td></tr>
            <tr><td><input type="text" name="ic" id="ic" maxlength="12" value="<?=$_POST["ic"]?>" placeholder="NRIC No" style="width:318px; height: 35px; padding: 1px 6px; font-size: 14px;" /></td></tr>
            <tr><td><input type="text" name="email" id="email" value="<?=$_POST["email"]?>" placeholder="Email" style="width:318px; height: 35px; padding: 1px 6px; font-size: 14px;" /></td></tr>
            <tr><td><div class="tip" data-tip="Password must be with 8 alphanumeric"><input type="password" name="password" placeholder="Password" style="width:318px; height: 35px; padding: 1px 6px; font-size: 14px;"/></div></td></tr>
            <tr><td><input type="password" name="conf_password" placeholder="Confirm Password" style="width:318px; height: 35px; padding: 1px 6px; font-size: 14px;" /></td></tr>
         
            <tr><td>
                    <div style="color: #DB1111;margin-top:-23px"><?=$errormessage?></div>
                    <input type="submit" name="submit" value="Register" class="btn btn-blue">
                </td>
            </tr>
            <tr>
                <td align="left"><a href="index.php?pages=login">Login</a> | <a href="index.php?pages=forget_password">Forget Password?</a></td>
                
            </tr>
        </table><br />
        <div style="text-align:center;font-size:11px;"><?=SITENAME?>
            <br />Powered by <a href="http://www.operion.com.my" target="_blank">Operion Ecommerce & Software Sdn Bhd</a>
        </div>
        </div>
    </div>
</form>
</body>
</html>

<?php
//    $query = tep_query("SELECT * FROM account_registration WHERE member_id=2281");
//    while ($account_info = mysql_fetch_array($query)) {
//         $my_data = $account_info;
//     }
//    
//    print_r($my_data);
?>