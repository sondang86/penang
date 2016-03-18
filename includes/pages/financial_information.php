<style>
    #financial_assistance {display:none}
    .guardian_info {display:none}
   
    .error_income, .error_numeric {  color: #c00;
  font-size: 100%;
  font-weight: bold;
  font-variant: small-caps;
  display:none;
  width: 260px;
  padding: 3px 0px 0px 5px;
  clear: both;}
 
#container {display:inline-block}
</style>
<script>
   
function PopDetails(val){
   if (val==1)
         $("#financial_assistance").show();
     else 
          $("#financial_assistance").hide();
}

function checkStatus(){
    if(($("#father_retired").is(":checked") || $("#father_nil").is(":checked") || $("#father_deceased").is(":checked")) && ($("#mother_retired").is(":checked") || $("#mother_nil").is(":checked") || $("#mother_deceased").is(":checked")))
            $(".guardian_info").show();
        else
     $(".guardian_info").hide();
}

function checkIncome(){
        var guardian2014 = $("#guardian_2014").val()+$("#guardian_2014_2").val();
        var guardian2013= $("#guardian_2013").val() + $("#guardian_2013_2").val();
        var father2014 = $("#father_2014").val()+$("#father_2014_2").val();
        var father2013= $("#father_2013").val()+$("#father_2013_2").val();
        var mother2014 = $("#mother_2014").val()+$("#mother_2014_2").val();
        var mother2013= $("#mother_2013").val()+$("#mother_2013_2").val();
        var parents2014 = parseInt(father2014) + parseInt(mother2014);
        var parents2013 = parseInt(father2013) + parseInt(mother2013);
    
        if (isNaN(father2014) || isNaN(father2013) || isNaN(mother2014) || isNaN(mother2013) || isNaN(guardian2014) || isNaN(guardian2013)){
            $(".error_numeric").show();
             $(".error_income").hide();
            $("#father_2014").focus();
            return false;
        }
        
        if (parents2014 > 180000 || parents2013 > 180000 || guardian2014 > 180000 || guardian2013 > 180000){
            $(".error_income").show();
            $(".error_numeric").hide();
            $("#father_2014").focus();
            return false;
        }

}

function checkDate(){
            
    var now     = new Date(); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    
    if (year!=2015 || month!=4){
        alert("The Application is close now.\nYour submission is not successful!");
        return false;
    }
    
    
}


</script>
       
<?php

if ($_GET['submit']=="yes"){
      echo "<script>alert('We will inform you once your application is approve!')</script>";
}

if ($_GET['submit']=="no"){
      echo "<script>alert('Please complete all the form before submit!')</script>";
}

if ($_GET['document']=="no"){
      echo "<script>alert('Please upload the related document before submit!')</script>";
}

 if ($_GET['permission']=="denied"){
      echo '<script>alert("You don\'t have permission to do this action")</script>';
}

     
//delete document
if ($_GET['doc']!=""){
    $getdoc = tep_fetch_object(tep_query("SELECT * FROM ".MEMBER." WHERE (member_id = '".$_SESSION['member_id']."' OR member_id = '".$_GET['id']."')"));
    $doclist = explode(",",$getdoc->member_document);
    
    if (($key = array_search(''.$_GET['doc'].'', $doclist)) !== false) {
    unset($doclist[$key]);
    }

   $doc = implode(",",$doclist);
   tep_query("UPDATE ".MEMBER." SET member_document = '".$doc."' WHERE (member_id = '".$_SESSION['member_id']."' OR member_id = '".$_GET['id']."')");
  
}
//end delete document

$check = tep_query("SELECT * FROM ".FINANCIAL." f, ".MEMBER." m WHERE f.member_id = m.member_id AND (f.member_id = '".$_SESSION['member_id']."' OR f.member_id = '".$_GET['id']."')");
    if (tep_num_rows($check)>0){
      while ($re = tep_fetch_object($check)){
             $complete = $re->complete;
             $financial = $re->financial;
             $personal = $re->personal;
             $academic = $re->academic;
             $course = $re->course;
             $chk_doc = $re->member_document;
             
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
          if ($financial =="1"){
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
     if ($_POST['submit']=="Submit"){
        if ($financial !="1"){
           $_POST['submit'] = "Save";
        }
        if ($chk_doc!=""){
             if ($financial == "1" && $personal=="1" && $academic == "1" && $course=="1"){
                 tep_query("INSERT INTO ".TEMP."(member_id,action,temp_group,temp_createdby,temp_created) 
                                                  VALUES('".$_SESSION['member_id']."', 'Submit Form', '1', '".$_SESSION['member_id']."',NOW())");
            
tep_query("UPDATE ".MEMBER." SET complete = 1, fund_status = 'SUBMITTED', submission_created = NOW() WHERE member_id = '".$_SESSION['member_id']."'");
                redirect ("index.php?pages=financial_information&submit=yes");
             }else{
                 redirect ("index.php?pages=financial_information&submit=no");
             }
        }else{
             redirect ("index.php?pages=financial_information&document=no");
        }
    }
    
    if ($_POST['submit']=="Save"){
        
        if ($_POST['images']!=""){
             $qry = tep_fetch_object(tep_query("SELECT * FROM ".MEMBER." WHERE  (member_id = '".$_SESSION['member_id']."' OR member_id = '".md5($_GET['id'])."')"));
             if ($qry->member_document!=""){
                 $tdocument = $qry->member_document.",".$_POST['images'];
             }else{
                 $tdocument = $_POST['images'];
             }
            $photo = ", member_document = '".$tdocument."'";
        }
        
        $father_phone = $_POST['father_phone1']."-".$_POST['father_phone'];
        $mother_phone = $_POST['mother_phone1']."-".$_POST['mother_phone'];
        $guardian_phone = $_POST['guardian_phone1']."-".$_POST['guardian_phone'];
//        income
        $father_2014 = $_POST['father_2014'].",".$_POST['father_2014_2'];
        $father_2013 = $_POST['father_2013'].",".$_POST['father_2013_2'];
        $mother_2014 = $_POST['mother_2014'].",".$_POST['mother_2014_2'];
        $mother_2013 = $_POST['mother_2013'].",".$_POST['mother_2013_2'];
        $guardian_2014 = $_POST['guardian_2014'].",".$_POST['guardian_2014_2'];
        $guardian_2013 = $_POST['guardian_2013'].",".$_POST['guardian_2013_2'];
//        total income
        $parents_2014 = $_POST['father_2014'].$_POST['father_2014_2'] + $_POST['mother_2014'].$_POST['mother_2014_2'];
        $parents_2013 = $_POST['father_2013'].$_POST['father_2013_2'] + $_POST['mother_2013'].$_POST['mother_2013_2'];
        $father_income = $father_2014."|".$father_2013;
        $mother_income = $mother_2014."|".$mother_2013;
        $guardian_income = $guardian_2014."|".$guardian_2013;
//        ic
        $father_ic = $_POST['father_ic1']."-".$_POST['father_ic2']."-".$_POST['father_ic3'];
        $mother_ic = $_POST['mother_ic1']."-".$_POST['mother_ic2']."-".$_POST['mother_ic3'];
        $guardian_ic = $_POST['guardian_ic1']."-".$_POST['guardian_ic2']."-".$_POST['guardian_ic3'];
        
//        address
        $father_address = $_POST['father_address']."|".$_POST['father_address2']."|".$_POST['father_postcode']."|".$_POST['father_state'];
        $mother_address = $_POST['mother_address']."|".$_POST['mother_address2']."|".$_POST['mother_postcode']."|".$_POST['mother_state'];
        $guardian_address = $_POST['guardian_address']."|".$_POST['guardian_address2']."|".$_POST['guardian_postcode']."|".$_POST['guardian_state'];
        
        //        no financial assistant
             if($_POST['financial_assistant']=="0"){
                if(($_POST['father_status']=="retired" || $_POST['father_status']=="nil" || $_POST['father_status']=="deceased") && ($_POST['mother_status']=="retired" || $_POST['mother_status']=="nil" || $_POST['mother_status']=="deceased")){
                                         
                                                    if ($editable =="1"){
                                                        if ($editable=="1" && $_SESSION['user_id']=="1"){
                                                                $id = $_GET['id'];
                                                            }else{
                                                                $id = $_SESSION['member_id'];
                                                            }
                                                        tep_query("UPDATE ".FINANCIAL." SET financial_status = '".tep_input($_POST['financial_assistant'])."',
                                                                    father_name = '".tep_input($_POST['father_name'])."',
                                                                     father_ic = '".$father_ic."', 
                                                                     father_status = '".$_POST['father_status']."', 
                                                                     father_phone =  '".$father_phone."',
                                                                     father_position = '".tep_input($_POST['father_position'])."',
                                                                     father_address ='".$father_address."',
                                                                     father_income = '".$father_income."', 
                                                                     mother_name = '".tep_input($_POST['mother_name'])."', 
                                                                     mother_ic =  '".$mother_ic."',
                                                                     mother_status = '".tep_input($_POST['mother_status'])."',
                                                                     mother_phone ='".$mother_phone."',
                                                                     mother_position = '".tep_input($_POST['mother_position'])."', 
                                                                     mother_address = '".$mother_address."', 
                                                                     mother_income =  '".$mother_income."',
                                                                     guardian_name = '".tep_input($_POST['guardian_name'])."',
                                                                     guardian_ic ='".$guardian_ic."',
                                                                     guardian_status = '".tep_input($_POST['guardian_status'])."', 
                                                                     guardian_phone = '".$guardian_phone."', 
                                                                     guardian_position =  '".tep_input($_POST['guardian_position'])."',
                                                                     guardian_address = '".$guardian_address."',
                                                                     guardian_income ='".$guardian_income."'
                                                                     WHERE member_id = '".$id."'");
                                                        
                                                        tep_query("UPDATE ".MEMBER." SET submission_created = NOW(), financial = 1 $photo WHERE member_id = '".$id."'");
                                                        
                                                        if ($_GET['action']=="edit" && $_SESSION['user_id']!=""){
                                                            tep_query("INSERT INTO ".TEMP."(member_id,action,temp_group,temp_createdby,temp_created) 
                                                  VALUES('".$_GET['id']."', 'Edit Financial Details', '0', '".$_SESSION['user_id']."',NOW())");
                                                            redirect('index.php?pages=financial_information&id='.$_GET['id'].'');
                                                        }else{
                                                             redirect('index.php?pages=financial_information');
                                                        }
                                                     
                                                 }else{
                                                 tep_query("INSERT INTO ".FINANCIAL."(member_id,financial_status,father_name,father_ic,father_status,father_phone,father_position,father_address,father_income,
                                                                                                                    mother_name,mother_ic,mother_status,mother_phone,mother_position,mother_address,mother_income,
                                                                                                                    guardian_name,guardian_ic,guardian_status,guardian_phone,guardian_position,guardian_address,guardian_income) 
                                                  VALUES('".$_SESSION['member_id']."', '".tep_input($_POST['financial_assistant'])."', '".tep_input($_POST['father_name'])."', '".$father_ic."','".$_POST['father_status']."', '".$father_phone."', '".tep_input($_POST['father_position'])."', '".$father_address."', '".$father_income."',
                                                     '".tep_input($_POST['mother_name'])."', '".$mother_ic."','".tep_input($_POST['mother_status'])."', '".$mother_phone."' ,'".tep_input($_POST['mother_position'])."' ,'".$mother_address."' , '".$mother_income."' ,
                                                        '".tep_input($_POST['guardian_name'])."', '".$guardian_ic."','".tep_input($_POST['guardian_status'])."', '".$guardian_phone."' ,'".tep_input($_POST['guardian_position'])."' ,'".$guardian_address."' , '".$guardian_income."')");
                                                 
                                                 tep_query("UPDATE ".MEMBER." SET submission_created = NOW(),financial = 1 $photo WHERE member_id = '".$_SESSION['member_id']."'");
                                                
                                                 redirect("index.php?pages=financial_information");
                                                 
                                            }
                      
               
          }else{
//              without guardian
                                        
                                                if ($editable =="1"){
                                                    if ($editable=="1" && $_SESSION['user_id']=="1"){
                                                            $id = $_GET['id'];
                                                        }else{
                                                            $id = $_SESSION['member_id'];
                                                        }
                                                        
                                                        tep_query("UPDATE ".FINANCIAL." SET financial_status = '".tep_input($_POST['financial_assistant'])."',
                                                                     father_name = '".tep_input($_POST['father_name'])."',
                                                                     father_ic = '".$father_ic."', 
                                                                     father_status = '".$_POST['father_status']."', 
                                                                     father_phone =  '".$father_phone."',
                                                                     father_position = '".tep_input($_POST['father_position'])."',
                                                                     father_address ='".$father_address."',
                                                                     father_income = '".$father_income."', 
                                                                     mother_name = '".tep_input($_POST['mother_name'])."', 
                                                                     mother_ic =  '".$mother_ic."',
                                                                     mother_status = '".tep_input($_POST['mother_status'])."',
                                                                     mother_phone ='".$mother_phone."',
                                                                     mother_position = '".tep_input($_POST['mother_position'])."', 
                                                                     mother_address = '".$mother_address."', 
                                                                     mother_income =  '".$mother_income."'
                                                                     WHERE member_id = '".$id."'");
                                                        
                                                        tep_query("UPDATE ".MEMBER." SET financial = 1 $photo WHERE member_id = '".$id."'");
                                                        
                                                           if ($_GET['action']=="edit" && $_SESSION['user_id']!=""){
                                                                 tep_query("INSERT INTO ".TEMP."(member_id,action,temp_group,temp_createdby,temp_created) 
                                                  VALUES('".$_GET['id']."', 'Edit Financial Details', '0', '".$_SESSION['user_id']."',NOW())");
                                                                redirect('index.php?pages=financial_information&id='.$_GET['id'].'');
                                                            }else{
                                                                 redirect('index.php?pages=financial_information');
                                                            }
                                                     
                                                 }else{
                                                 tep_query("INSERT INTO ".FINANCIAL."(member_id,financial_status,father_name,father_ic,father_status,father_phone,father_position,father_address,father_income,
                                                                                                                    mother_name,mother_ic,mother_status,mother_phone,mother_position,mother_address,mother_income) 
                                                  VALUES('".$_SESSION['member_id']."', '".tep_input($_POST['financial_assistant'])."', '".tep_input($_POST['father_name'])."', '".$father_ic."','".$_POST['father_status']."', '".$father_phone."', '".tep_input($_POST['father_position'])."', '".$father_address."', '".$father_income."',
                                                     '".tep_input($_POST['mother_name'])."', '".$mother_ic."','".tep_input($_POST['mother_status'])."', '".$mother_phone."' ,'".tep_input($_POST['mother_position'])."' ,'".$mother_address."' , '".$mother_income."')");
                                                 
                                                 tep_query("UPDATE ".MEMBER." SET submission_created = NOW(), financial = 1 $photo WHERE member_id = '".$_SESSION['member_id']."'");
                                              
                                               redirect("index.php?pages=financial_information");
                                                 }
        
          }
        }else if ($_POST['financial_assistant']=="1"){
//            with financial assistant
             if(($_POST['father_status']=="retired" || $_POST['father_status']=="nil" || $_POST['father_status']=="deceased") && ($_POST['mother_status']=="retired" || $_POST['mother_status']=="nil" || $_POST['mother_status']=="deceased")){
                                     
                                                 if ($editable =="1"){
                                                     
                                                    if ($editable=="1" && $_SESSION['user_id']=="1"){
                                                        $id = $_GET['id'];
                                                    }else{
                                                        $id = $_SESSION['member_id'];
                                                    }
    
                                                         tep_query("UPDATE ".FINANCIAL." SET   financial_status = '".tep_input($_POST['financial_assistant'])."', 
                                                                     type_financial = '".tep_input($_POST['type_of_financial'])."',
                                                                     name_financial = '".tep_input($_POST['name_of_financial'])."', 
                                                                     amount_financial = '".tep_input($_POST['financial_amount'])."', 
                                                                     financial_period =  '".tep_input($_POST['financial_period'])."',
                                                                     father_name = '".tep_input($_POST['father_name'])."',
                                                                     father_ic = '".$father_ic."', 
                                                                     father_status = '".$_POST['father_status']."', 
                                                                     father_phone =  '".$father_phone."',
                                                                     father_position = '".tep_input($_POST['father_position'])."',
                                                                     father_address ='".$father_address."',
                                                                     father_income = '".$father_income."', 
                                                                     mother_name = '".tep_input($_POST['mother_name'])."', 
                                                                     mother_ic =  '".$mother_ic."',
                                                                     mother_status = '".tep_input($_POST['mother_status'])."',
                                                                     mother_phone ='".$mother_phone."',
                                                                     mother_position = '".tep_input($_POST['mother_position'])."', 
                                                                     mother_address = '".$mother_address."', 
                                                                     mother_income =  '".$mother_income."',
                                                                     guardian_name = '".tep_input($_POST['guardian_name'])."',
                                                                     guardian_ic ='".$guardian_ic."',
                                                                     guardian_status = '".tep_input($_POST['guardian_status'])."', 
                                                                     guardian_phone = '".$guardian_phone."', 
                                                                     guardian_position =  '".tep_input($_POST['guardian_position'])."',
                                                                     guardian_address = '".$guardian_address."',
                                                                     guardian_income ='".$guardian_income."'
                                                                     WHERE member_id = '".$id."'");
                                                        
                                                        tep_query("UPDATE ".MEMBER." submission_created = NOW(), SET financial = 1 $photo WHERE member_id = '".$id."'");
                                                        
                                                         if ($_GET['action']=="edit" && $_SESSION['user_id']!=""){
                                                               tep_query("INSERT INTO ".TEMP."(member_id,action,temp_group,temp_createdby,temp_created) 
                                                  VALUES('".$_GET['id']."', 'Edit Financial Details', '0', '".$_SESSION['user_id']."',NOW())");
                                                                redirect('index.php?pages=financial_information&id='.$_GET['id'].'');
                                                            }else{
                                                                 redirect('index.php?pages=financial_information');
                                                            }
                                                     
                                                 }else{
                                                 tep_query("INSERT INTO ".FINANCIAL."(member_id, financial_status, type_financial, name_financial, amount_financial, financial_period,
                                                                                                                    father_name,father_ic,father_status,father_phone,father_position,father_address,father_income,
                                                                                                                    mother_name,mother_ic,mother_status,mother_phone,mother_position,mother_address,mother_income,
                                                                                                                    guardian_name,guardian_ic,guardian_status,guardian_phone,guardian_position,guardian_address,guardian_income) 
                                                  VALUES('".$_SESSION['member_id']."', '".tep_input($_POST['financial_assistant'])."', '".tep_input($_POST['type_of_financial'])."',  '".tep_input($_POST['name_of_financial'])."',  '".tep_input($_POST['financial_amount'])."',  '".tep_input($_POST['financial_period'])."', 
                                                    '".tep_input($_POST['father_name'])."', '".$father_ic."','".$_POST['father_status']."', '".$father_phone."', '".tep_input($_POST['father_position'])."', '".$father_address."', '".$father_income."',
                                                     '".tep_input($_POST['mother_name'])."', '".$mother_ic."','".tep_input($_POST['mother_status'])."', '".$mother_phone."' ,'".tep_input($_POST['mother_position'])."' ,'".$mother_address."' , '".$mother_income."' ,
                                                        '".tep_input($_POST['guardian_name'])."', '".$guardian_ic."','".tep_input($_POST['guardian_status'])."', '".$guardian_phone."' ,'".tep_input($_POST['guardian_position'])."' ,'".$guardian_address."' , '".$guardian_income."')");
                                                 
                                                 tep_query("UPDATE ".MEMBER." SET submission_created = NOW(), financial = 1 $photo WHERE member_id = '".$_SESSION['member_id']."'");
                                                redirect("index.php?pages=financial_information");
                                            }
                              
              
          }else{
//              without guardian
                                     
                                                  if ($editable =="1"){
      
                                                        if ($editable=="1" && $_SESSION['user_id']=="1"){
                                                            $id = $_GET['id'];
                                                        }else{
                                                            $id = $_SESSION['member_id'];
                                                        }
                                                        
                                                         tep_query("UPDATE ".FINANCIAL." SET  financial_status = '".tep_input($_POST['financial_assistant'])."', 
                                                                    type_financial = '".tep_input($_POST['type_of_financial'])."',
                                                                     name_financial = '".tep_input($_POST['name_of_financial'])."', 
                                                                     amount_financial = '".tep_input($_POST['financial_amount'])."', 
                                                                     financial_period =  '".tep_input($_POST['financial_period'])."',
                                                                     father_name = '".tep_input($_POST['father_name'])."',
                                                                     father_ic = '".$father_ic."', 
                                                                     father_status = '".$_POST['father_status']."', 
                                                                     father_phone =  '".$father_phone."',
                                                                     father_position = '".tep_input($_POST['father_position'])."',
                                                                     father_address ='".$father_address."',
                                                                     father_income = '".$father_income."', 
                                                                     mother_name = '".tep_input($_POST['mother_name'])."', 
                                                                     mother_ic =  '".$mother_ic."',
                                                                     mother_status = '".tep_input($_POST['mother_status'])."',
                                                                     mother_phone ='".$mother_phone."',
                                                                     mother_position = '".tep_input($_POST['mother_position'])."', 
                                                                     mother_address = '".$mother_address."', 
                                                                     mother_income =  '".$mother_income."'
                                                                     WHERE member_id = '".$id."'");
                                                        
                                                        tep_query("UPDATE ".MEMBER." SET submission_created = NOW(), financial = 1 $photo WHERE member_id = '".$_SESSION['member_id']."'");
                                                        
                                                          if ($_GET['action']=="edit" && $_SESSION['user_id']!=""){
                                                                tep_query("INSERT INTO ".TEMP."(member_id,action,temp_group,temp_createdby,temp_created) 
                                                  VALUES('".$_GET['id']."', 'Edit Financial Details', '0', '".$_SESSION['user_id']."',NOW())");
                                                                redirect('index.php?pages=financial_information&id='.$_GET['id'].'');
                                                            }else{
                                                                 redirect('index.php?pages=financial_information');
                                                            }
                                                     
                                                 }else{
                                                 tep_query("INSERT INTO ".FINANCIAL."(member_id,financial_status,type_financial, name_financial, amount_financial, financial_period,
                                                                                                                    father_name,father_ic,father_status,father_phone,father_position,father_address,father_income,
                                                                                                                    mother_name,mother_ic,mother_status,mother_phone,mother_position,mother_address,mother_income) 
                                                  VALUES('".$_SESSION['member_id']."', '".tep_input($_POST['financial_assistant'])."', '".tep_input($_POST['type_of_financial'])."',  '".tep_input($_POST['name_of_financial'])."',  '".tep_input($_POST['financial_amount'])."',  '".tep_input($_POST['financial_period'])."', 
                                                         '".tep_input($_POST['father_name'])."', '".$father_ic."','".$_POST['father_status']."', '".$father_phone."', '".tep_input($_POST['father_position'])."', '".$father_address."', '".$father_income."',
                                                     '".tep_input($_POST['mother_name'])."', '".$mother_ic."','".tep_input($_POST['mother_status'])."', '".$mother_phone."' ,'".tep_input($_POST['mother_position'])."' ,'".$mother_address."' , '".$mother_income."')");
                                                 
                                                tep_query("UPDATE ".MEMBER." SET submission_created = NOW(), financial = 1 $photo WHERE member_id = '".$_SESSION['member_id']."'");
                                           
                                                 redirect("index.php?pages=financial_information");
                                            }
                           
          }
            
        }
        
//Get member info

$email = $_SESSION['member_email'];
$headers = 'From: info@penangfuturefoundation.my' . "\r\n";
$content_title = "Penang Future Foundation Account Verification";        

$memberId = $_SESSION['member_id'];
$query_member = tep_query("SELECT * FROM member WHERE member_id=$memberId");
while ($row = mysql_fetch_array($query_member)) {
    $member_info = $row;
}        
//        Sending message to user production
//        $template = "Hi, \r\n \r\n Your schoolarship application form has submmited. Thank you. \r\n \r\n "
//                    . "========= User Account Log ========= \r\n".$member_info['member_created']."             Account Registration \r\n "
//                    . $member_info['submission_created']. "             Form submission \r\n \r\n -- \r\n \r\n"
//                    . "Best regards,\r\n Penang Future Foundation \r\n \r\n \r\n \r\n";
//
//
//        mail($email, $content_title, $template, $headers);


//Localhost send mail method
        
        $template = "Hi, <br/><br/>Your schoolarship application form has submmited. Thank you.<br><br>
                ========= User Account Log ========= <br><br>
               ".$member_info['member_created']."             Account Registration <br><br>"
                .$member_info['submission_created']."             Form submission <br><br> <br><br>    
            --<br><br/>
                                                                        Best regards,<br/>
            Penang Future Foundation\n\n\n<br/>";    


        PHPMail($email,$template,$content_title );  
        
}
    
    
if ($_GET['tempid']!=""){
    $qry = tep_fetch_object(tep_query("SELECT * FROM temp_".FINANCIAL." f, ".MEMBER." m WHERE f.member_id = m.member_id AND  temp_id = '".$_GET['tempid']."'"));
}else{
        $qry = tep_fetch_object(tep_query("SELECT * FROM ".FINANCIAL." f, ".MEMBER." m WHERE f.member_id = m.member_id AND (f.member_id = '".$_SESSION['member_id']."' OR f.member_id = '".$_GET['id']."')"));
}

  if ($editable =="0" || $editable =="admin" || $editable=="view"){
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
                        <tr><td width="60px">Postcode: </td><td>'.$f_address[2].'</td><td>State:</td><td>'.strtoupper($f_address[3]).'</td></tr>
                        </table>';
       $mother_address= '<table class="in_table">
                        <tr><td colspan="4">'.strtoupper($m_address[0]).'</td></tr>
                        <tr><td colspan="4">'.strtoupper($m_address[1]).'</td></tr>
                        <tr><td width="60px">Postcode: </td><td>'.$m_address[2].'</td><td>State:</td><td>'.strtoupper($m_address[3]).'</td></tr>
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
       if ($editable=="0"){
            $btn = '';
        }else{
             if ($editable=="view"){
                 if ($_GET['tempid']!=""){
                    $btn = '<input type="button" value="Previous" onclick="location.href=\'index.php?pages=course_details&id='.$_GET['id'].'&tempid='.$_GET['tempid'].'\'" style="padding:3px;width:100px"/>';
                 }else{
                    $btn = '<input type="button" value="Previous" onclick="location.href=\'index.php?pages=course_details\'" style="padding:3px;width:100px"/> <input type="button" value="Edit" style="padding:3px;width:100px" onclick="redirect(\'index.php?pages=financial_information&action=edit\')"/> <input type="submit" name="submit" value="Submit" style="padding:3px;width:100px"/>';
                 }
            }else{
            $btn = '<input type="button" value="Previous" onclick="location.href=\'index.php?pages=course_details&id='.$_GET['id'].'\'" style="padding:3px;width:100px"/>   <input type="button" value="Edit" style="padding:3px;width:100px" onclick="redirect(\'index.php?pages=financial_information&id='.$_GET['id'].'&action=edit\')"/>  ';
        }
        }
    }else if ($_GET['action']=="edit" || $editable == "1"){
         if ($editable =="0" || $editable=="blank"){
          redirect ("index.php?pages=financial_information&permission=denied");
      }else{
         if ($qry->financial_status =="1"){
             $Withfinancial = "checked";
             $Nofinancial = "";
              ?>
            <style>#financial_assistance {display:block}</style>
            <?php
         }else{
             $Withfinancial = "";
             $Nofinancial = "checked";
         }
       $financial_assistant = '<fieldset><label><input type="radio" name="financial_assistant" id="getFinancial" onclick="PopDetails(this.value);" value="1" '.$Withfinancial.' required/>Yes</label>  <label><input type="radio" name="financial_assistant"  value="0" '.$Nofinancial.' onclick="PopDetails(this.value);"/>No</label> <label for="financial_assistant" class="error">Please select your gender</label></fieldset>' ;
       $type_of_financial = '<input type="text" name="type_of_financial" style="width:340px" value="'.strtoupper($qry->type_financial).'" required/>';
       $name_of_financial ='<input type="text" name="name_of_financial" style="width:340px" value="'.strtoupper($qry->name_financial).'" required/>';
       $financial_amount = '<input type="text" name="financial_amount" style="width:340px" value="'.number_format($qry->amount_financial,2,'.', '').'" required/>';
       $financial_period = '<input type="text" name="financial_period" style="width:340px" value="'.strtoupper($qry->financial_period).'" required/>';
       
       $father_name='<input type="text" name="father_name" style="width:98%" value="'.strtoupper($qry->father_name).'" required/>';
       $mother_name='<input type="text" name="mother_name" style="width:98%" value="'.strtoupper($qry->mother_name).'" required/>';
       $f_ic = explode("-",$qry->father_ic);
       $m_ic = explode("-",$qry->mother_ic);
       $father_ic ='<input type="text" name="father_ic1" style="width:45px" maxlength="6" value="'.$f_ic[0].'" required/> - <input type="text" name="father_ic2" style="width:15px" maxlength="2" value="'.$f_ic[1].'" required/> - <input type="text" name="father_ic3" style="width:30px" maxlength="4" value="'.$f_ic[2].'" required/>';
       $mother_ic ='<input type="text" name="mother_ic1" style="width:45px" maxlength="6" value="'.$m_ic[0].'" required/> - <input type="text" name="mother_ic2" style="width:15px" maxlength="2" value="'.$m_ic[1].'" required/> - <input type="text" name="mother_ic3" style="width:30px" maxlength="4" value="'.$m_ic[2].'" required/>';
        switch ($qry->father_status){
           case "full_time":
               $f_fulltime = "checked";
               $f_parttime="";
               $f_ownbusiness = "";
               $f_retired = "";
               $f_nil ="";
               $f_deceased = "";
               break;
           case "part_time":
               $f_fulltime = "";
               $f_parttime="checked";
               $f_ownbusiness = "";
               $f_retired = "";
               $f_nil ="";
               $f_deceased = "";
               break;
           case "own_business":
               $f_fulltime = "";
               $f_parttime="";
               $f_ownbusiness = "checked";
               $f_retired = "";
               $f_nil ="";
               $f_deceased = "";
               break;
           case "retired":
               $f_fulltime = "";
               $f_parttime="";
               $f_ownbusiness = "";
               $f_retired = "checked";
               $f_nil ="";
               $f_deceased = "";
               break;
           case "nil":
               $f_fulltime = "";
               $f_parttime="";
               $f_ownbusiness = "";
               $f_retired = "";
               $f_nil ="checked";
               $f_deceased = "";
               break;
           case "deceased":
               $f_fulltime = "";
               $f_parttime="";
               $f_ownbusiness = "";
               $f_retired = "";
               $f_nil ="";
               $f_deceased = "checked";
               break;
           
       }
       
       switch ($qry->mother_status){
           case "full_time":
               $m_fulltime = "checked";
               $m_parttime="";
               $m_ownbusiness = "";
               $m_retired = "";
               $m_nil ="";
               $m_deceased = "";
               break;
           case "part_time":
               $m_fulltime = "";
               $m_parttime="checked";
               $m_ownbusiness = "";
               $m_retired = "";
               $m_nil ="";
               $m_deceased = "";
               break;
           case "own_business":
               $m_fulltime = "";
               $m_parttime="";
               $m_ownbusiness = "checked";
               $m_retired = "";
               $m_nil ="";
               $m_deceased = "";
               break;
           case "retired":
               $m_fulltime = "";
               $m_parttime="";
               $m_ownbusiness = "";
               $m_retired = "checked";
               $m_nil ="";
               $m_deceased = "";
               break;
           case "nil":
               $m_fulltime = "";
               $m_parttime="";
               $m_ownbusiness = "";
               $m_retired = "";
               $m_nil ="checked";
               $m_deceased = "";
               break;
           case "deceased":
               $m_fulltime = "";
               $m_parttime="";
               $m_ownbusiness = "";
               $m_retired = "";
               $m_nil ="";
               $m_deceased = "checked";
               break;
       }
       
       $father_status ='<fieldset><table class="in_table">
                                            <tr><td><label><input type="radio" name="father_status" value="full_time" '.$f_fulltime.' onclick="checkStatus();" required>Full time employment</label></td></tr>
                                            <tr><td><label><input type="radio" name="father_status" value="part_time" '.$f_parttime.' onclick="checkStatus();">Part time employment</label></td></tr>
                                            <tr><td><label><input type="radio" name="father_status" value="own_business" '.$f_ownbusiness.' onclick="checkStatus();">Own business</label></td></tr>
                                            <tr><td><label><input type="radio" name="father_status" value="retired" id="father_retired" onclick="checkStatus();" '.$f_retired.'>Retired/Unemployed</label></td></tr>
                                            <tr><td><label><input type="radio" name="father_status" value="nil" id="father_nil" onclick="checkStatus();" '.$f_nil.'>NIL Annual Income</label></td></tr>
                                            <tr><td><label><input type="radio" name="father_status" value="deceased" id="father_deceased" onclick="checkStatus();" '.$f_deceased.'>Deceased</label></td></tr>
                                    </table><label for="father_status" class="error">Please select your gender</label></fieldset>';
       $mother_status ='<fieldset><table class="in_table">
                                            <tr><td><label><input type="radio" name="mother_status" value="full_time" '.$m_fulltime.' onclick="checkStatus();" required>Full time employment</label></td></tr>
                                            <tr><td><label><input type="radio" name="mother_status" value="part_time" '.$m_parttime.' onclick="checkStatus();">Part time employment</label></td></tr>
                                            <tr><td><label><input type="radio" name="mother_status" value="own_business" '.$m_ownbusiness.' onclick="checkStatus();">Own business</label></td></tr>
                                            <tr><td><label><input type="radio" name="mother_status" value="retired" id="mother_retired" onclick="checkStatus();" '.$m_retired.'>Retired/Unemployed</label></td></tr>
                                            <tr><td><label><input type="radio" name="mother_status" value="nil" id="mother_nil" onclick="checkStatus();" '.$m_nil.'>NIL Annual Income</label></td></tr>
                                            <tr><td><label><input type="radio" name="mother_status" value="deceased" id="mother_deceased" onclick="checkStatus();" '.$m_deceased.'>Deceased</label></td></tr>
                                    </table><label for="mother_status" class="error">Please select your gender</label></fieldset>';
       
       $father_phone = explode("-",$qry->father_phone);
       $mother_phone = explode("-",$qry->mother_phone);
       $guardian_phone = explode("-",$qry->guardian_phone);
       
       $father_tel='<input type="text" name="father_phone1" style="width:30px" maxlength="4" value="'.$father_phone[0].'" required/> - <input type="text" name="father_phone" style="width:200px" value="'.$father_phone[1].'" required/>';
       $mother_tel='<input type="text" name="mother_phone1" style="width:30px" maxlength="4" value="'.$mother_phone[0].'" required/> - <input type="text" name="mother_phone" style="width:200px" value="'.$mother_phone[1].'" required/>';
       $father_position='<input type="text" name="father_position" style="width:98%" value="'.strtoupper($qry->father_position).'" required/>';
       $mother_position='<input type="text" name="mother_position" style="width:98%" value="'.strtoupper($qry->mother_position).'" required/>';
       $f_address = explode("|",$qry->father_address);
       $m_address = explode("|",$qry->mother_address);
       $father_address='<table class="in_table"><tr><td colspan="4"><input type="text" name="father_address" value="'.strtoupper($f_address[0]).'" style="width:280px;margin-left:-3px" required/></td></tr>
                                <tr><td colspan="4"><input type="text" name="father_address2" value="'.strtoupper($f_address[1]).'" style="width:280px;margin-left:-3px"/></td></tr>
                                <tr><td width="60px">Postcode: </td><td><input type="text"  name="father_postcode" maxlength="5" style="width:40px" value="'.$f_address[2].'" required/></td><td>State:</td><td><select name="father_state" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_state()),$f_address[3]).'</select></td></tr>
                               </table>';
       $mother_address='<table class="in_table"><tr><td colspan="4"><input type="text" name="mother_address" value="'.strtoupper($m_address[0]).'" style="width:280px;margin-left:-3px" required/></td></tr>
                                <tr><td colspan="4"><input type="text" name="mother_address2" value="'.strtoupper($m_address[1]).'" style="width:280px;margin-left:-3px"/></td></tr>
                                <tr><td width="60px">Postcode: </td><td><input type="text"  name="mother_postcode" maxlength="5" style="width:40px" value="'.$m_address[2].'" required/></td><td>State:</td><td><select name="mother_state" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_state()),$m_address[3]).'</select></td></tr>
                               </table>';
       
       $father_income = explode("|",$qry->father_income);
      $f_2014 = explode(",",$father_income[0]);
       $f_2013 = explode(",",$father_income[1]);
       $father_annual ='<table class="in_table">
                                            <tr><td></td></tr>
                                            <tr><td><input type="text" name="father_2014" style="width:25px;text-align:right" maxlength="3" value="'.$f_2014[0].'" id="father_2014"/> , <input type="text" name="father_2014_2" style="width:25px;text-align:right" maxlength="3" value="'.$f_2014[1].'" required id="father_2014_2"/> .00</td><label class="error_income">Household income must not be more than RM180,000</label><label class="error_numeric">All income must be numeric</label></tr>
                                            <tr><td><input type="text" name="father_2013" style="width:25px;text-align:right" maxlength="3" value="'.$f_2013[0].'" id="father_2013"/> , <input type="text" name="father_2013_2" style="width:25px;text-align:right" maxlength="3" value="'.$f_2013[1].'" required id="father_2013_2"/> .00</td></tr>
                                    </table>';
       $mother_income = explode("|",$qry->mother_income);
       $m_2014 = explode(",",$mother_income[0]);
       $m_2013 = explode(",",$mother_income[1]);
       $mother_annual ='<table class="in_table">
                                            <tr><td></td></tr>
                                            <tr><td><input type="text" name="mother_2014" style="width:25px;text-align:right" maxlength="3" value="'.$m_2014[0].'" id="mother_2014"/> , <input type="text" name="mother_2014_2" style="width:25px;text-align:right" maxlength="3" value="'.$m_2014[1].'" required id="mother_2014_2"/> .00</td><label class="error_income">Household income must not be more than RM180,000</label><label class="error_numeric">All income must be numeric</label></tr>
                                            <tr><td><input type="text" name="mother_2013" style="width:25px;text-align:right" maxlength="3" value="'.$m_2013[0].'" id="mother_2013"/> , <input type="text" name="mother_2013_2" style="width:25px;text-align:right" maxlength="3" value="'.$m_2013[1].'" required id="mother_2013_2"/> .00</td></tr>
                                    </table>';
       $declare = "checked";
        if ($qry->guardian_name!=""){?>
            <style>.guardian_info {display:table-cell} </style>
            
        <?php }
        
       $guardian_name='<input type="text" name="guardian_name" style="width:98%" value="'.strtoupper($qry->guardian_name).'" required/>';
       $g_ic = explode("-",$qry->guardian_ic);
       $guardian_ic ='<input type="text" name="guardian_ic1" style="width:45px" maxlength="6" value="'.$g_ic[0].'" required/> - <input type="text" name="guardian_ic2" style="width:15px" maxlength="2" value="'.$g_ic[1].'" required/> - <input type="text" name="guardian_ic3" style="width:30px" maxlength="4" value="'.$g_ic[2].'" required/>';
        switch ($qry->guardian_status){
           case "full_time":
               $g_fulltime = "checked";
               $g_parttime="";
               $g_ownbusiness = "";
               $g_retired = "";
               $g_nil ="";
               $g_deceased = "";
               break;
           case "part_time":
               $g_fulltime = "";
               $g_parttime="checked";
               $g_ownbusiness = "";
               $g_retired = "";
               $g_nil ="";
               $g_deceased = "";
               break;
           case "own_business":
               $g_fulltime = "";
               $g_parttime="";
               $g_ownbusiness = "checked";
               $g_retired = "";
               $g_nil ="";
               $g_deceased = "";
               break;
           case "retired":
               $g_fulltime = "";
               $g_parttime="";
               $g_ownbusiness = "";
               $g_retired = "checked";
               $g_nil ="";
               $g_deceased = "";
               break;
           case "nil":
               $g_fulltime = "";
               $g_parttime="";
               $g_ownbusiness = "";
               $g_retired = "";
               $g_nil ="checked";
               $g_deceased = "";
               break;
           case "deceased":
               $g_fulltime = "";
               $g_parttime="";
               $g_ownbusiness = "";
               $g_retired = "";
               $g_nil ="";
               $g_deceased = "checked";
               break;
       }
       $guardian_status ='<fieldset><table class="in_table">
                                            <tr><td><label><input type="radio" name="guardian_status" value="full_time" '.$g_fulltime.' required>Full time employment</label></td></tr>
                                            <tr><td><label><input type="radio" name="guardian_status" value="part_time" '.$g_parttime.'>Part time employment</label></td></tr>
                                            <tr><td><label><input type="radio" name="guardian_status" value="own_business" '.$g_ownbusiness.'>Own business</label></td></tr>
                                            <tr><td><label><input type="radio" name="guardian_status" value="retired" '.$g_retired.'>Retired/Unemployed</label></td></tr>
                                            <tr><td><label><input type="radio" name="guardian_status" value="nil" '.$g_nil.'>NIL Annual Income</label></td></tr>
                                            <tr><td><label><input type="radio" name="guardian_status" value="deceased" '.$g_deceased.'>Deceased</label></td></tr>
                                    </table><label for="guardian_status" class="error">Please select your gender</label></fieldset>';
        $guardian_tel='<input type="text" name="guardian_phone1" style="width:30px" maxlength="4" value="'.$guardian_phone[0].'" required/> - <input type="text" name="guardian_phone" style="width:200px" value="'.$guardian_phone[1].'" required/>';
        $guardian_position='<input type="text" name="guardian_position" style="width:98%" value="'.strtoupper($qry->guardian_position).'" required/>';
        $g_address = explode("|",$qry->guardian_address);
        $guardian_address='<table class="in_table"><tr><td colspan="4"><input type="text" name="guardian_address" value="'.strtoupper($g_address[0]).'" style="width:280px;margin-left:-3px" required/></td></tr>
                                <tr><td colspan="4"><input type="text" name="guardian_address2" value="'.strtoupper($g_address[1]).'" style="width:280px;margin-left:-3px"/></td></tr>
                                <tr><td width="60px">Postcode: </td><td><input type="text"  name="guardian_postcode" maxlength="5" style="width:40px" value="'.$g_address[2].'" required/></td><td>State:</td><td><select name="guardian_state" required><option value="">-</option>'.ddlReplace(arrToDDL(tep_state()),$g_address[3]).'</select></td></tr>
                               </table>';
        $guardian_income = explode("|",$qry->guardian_income);
        $g_2014 = explode(",",$guardian_income[0]);
        $g_2013 = explode(",",$guardian_income[1]);
        $guardian_annual ='<table class="in_table">
                                            <tr><td></td></tr>
                                            <tr><td><input type="text" name="guardian_2014" style="width:25px;text-align:right" maxlength="3" value="'.$g_2014[0].'" id="guardian_2014"/> , <input type="text" name="guardian_2014_2" style="width:25px;text-align:right" maxlength="3" value="'.$g_2014[1].'" required id="guardian_2014_2"/> .00</td><label class="error_income">Household income must not be more than RM180,000</label><label class="error_numeric">All income must be numeric</label></tr>
                                            <tr><td><input type="text" name="guardian_2013" style="width:25px;text-align:right" maxlength="3" value="'.$g_2013[0].'" id="guardian_2013"/> , <input type="text" name="guardian_2013_2" style="width:25px;text-align:right" maxlength="3" value="'.$g_2013[1].'" required id="guardian_2013_2"/> .00</td></tr>
                                    </table>';
         if ($editable == "1"){
          $btn = '<input type="button" value="Cancel" onclick="redirect(\'index.php?pages=financial_information\')" style="padding:3px;width:100px"/>  <input type="submit" name="submit" value="Save" style="padding:3px;width:100px"/>  ';
       } else{
          $btn = '<input type="button" value="Cancel" onclick="redirect(\'index.php?pages=financial_information\')" style="padding:3px;width:100px"/>  <input type="submit" value="Save" name="submit" style="padding:3px;width:100px" onclick="checkDate();"/> ';
       }
      }
    }else{
        $declare ="";
       $financial_assistant = '<fieldset><label><input type="radio" name="financial_assistant" id="getFinancial" onclick="PopDetails(this.value);" value="1" required/>Yes</label>  <label><input type="radio" name="financial_assistant"  value="0" onclick="PopDetails(this.value);"/>No</label><label for="financial_assistant" class="error">Please select your gender</label></fieldset>' ;
       $type_of_financial = '<input type="text" name="type_of_financial" style="width:340px" value="'.strtoupper($_POST['type_of_financial']).'" required/>';
       $name_of_financial ='<input type="text" name="name_of_financial" style="width:340px" value="'.strtoupper($_POST['name_of_financial']).'" required/>';
       $financial_amount = '<input type="text" name="financial_amount" style="width:340px" value="'.$_POST['financial_amount'].'" required/>';
       $financial_period = '<input type="text" name="financial_period" style="width:340px" value="'.strtoupper($_POST['financial_period']).'" required/>';
       
       $father_name='<input type="text" name="father_name" style="width:98%" value="'.strtoupper($_POST['father_name']).'" required/>';
       $mother_name='<input type="text" name="mother_name" style="width:98%" value="'.strtoupper($_POST['mother_name']).'" required/>';
       $father_ic='<input type="text" name="father_ic1" style="width:45px" maxlength="6" value="'.$_POST['father_ic1'].'" required/> - <input type="text" name="father_ic2" style="width:15px" maxlength="2" value="'.$_POST['father_ic2'].'" required/> - <input type="text" name="father_ic3" style="width:30px" maxlength="4" value="'.$_POST['father_ic3'].'" required/>';
       $mother_ic='<input type="text" name="mother_ic1" style="width:45px" maxlength="6" value="'.$_POST['mother_ic1'].'" required/> - <input type="text" name="mother_ic2" style="width:15px" maxlength="2" value="'.$_POST['mother_ic2'].'" required/> - <input type="text" name="mother_ic3" style="width:30px" maxlength="4" value="'.$_POST['mother_ic3'].'" required/>';
       $father_status ='<fieldset><table class="in_table">
                                            <tr><td><label><input type="radio" name="father_status" value="full_time" onclick="checkStatus();" required>Full time employment</label></td></tr>
                                            <tr><td><label><input type="radio" name="father_status" value="part_time" onclick="checkStatus();">Part time employment</label></td></tr>
                                            <tr><td><label><input type="radio" name="father_status" value="own_business" onclick="checkStatus();">Own business</label></td></tr>
                                            <tr><td><label><input type="radio" name="father_status" value="retired" id="father_retired" onclick="checkStatus();">Retired/Unemployed</label></td></tr>
                                            <tr><td><label><input type="radio" name="father_status" value="nil" id="father_nil" onclick="checkStatus();">NIL Annual Income</label></td></tr>
                                            <tr><td><label><input type="radio" name="father_status" value="deceased" id="father_deceased" onclick="checkStatus();">Deceased</label></td></tr>
                                    </table><label for="father_status" class="error">Please select your gender</label></fieldset>';
       $mother_status ='<fieldset><table class="in_table">
                                            <tr><td><label><input type="radio" name="mother_status" value="full_time" onclick="checkStatus();" required>Full time employment</label></td></tr>
                                            <tr><td><label><input type="radio" name="mother_status" value="part_time" onclick="checkStatus();">Part time employment</label></td></tr>
                                            <tr><td><label><input type="radio" name="mother_status" value="own_business" onclick="checkStatus();">Own business</label></td></tr>
                                            <tr><td><label><input type="radio" name="mother_status" value="retired" id="mother_retired" onclick="checkStatus();">Retired/Unemployed</label></td></tr>
                                            <tr><td><label><input type="radio" name="mother_status" value="nil" id="mother_nil" onclick="checkStatus();">NIL Annual Income</label></td></tr>
                                            <tr><td><label><input type="radio" name="mother_status" value="deceased" id="mother_deceased" onclick="checkStatus();">Deceased</label></td></tr>
                                    </table><label for="mother_status" class="error">Please select your gender</label></fieldset>';
       $father_tel='<input type="text" name="father_phone1" style="width:30px" maxlength="4" required/> - <input type="text" name="father_phone" style="max-width:200px" value="'.$_POST['father_phone'].'" required/>';
       $mother_tel='<input type="text" name="mother_phone1" style="width:30px" maxlength="4" required/> - <input type="text" name="mother_phone" style="max-width:200px" value="'.$_POST['mother_phone'].'" required/>';
       $father_position='<input type="text" name="father_position" style="width:98%" value="'.strtoupper($_POST['father_position']).'" required/>';
       $mother_position='<input type="text" name="mother_position" style="width:98%" value="'.strtoupper($_POST['mother_position']).'" required/>';
       $father_address='<table class="in_table"><tr><td colspan="4"><input type="text" name="father_address" value="'.strtoupper($_POST['father_address']).'" style="width:280px;margin-left:-3px" required/></td></tr>
                            <tr><td colspan="4"><input type="text" name="father_address2" value="'.strtoupper($_POST['father_address2']).'" style="width:280px;margin-left:-3px"/></td></tr>
                        <tr><td width="60px">Postcode: </td><td><input type="text"  name="father_postcode" maxlength="5" style="width:40px" value="'.$_POST['father_postcode'].'" required/></td><td>State:</td><td><select name="father_state" required><option value="">-</option>'.arrToDDL(tep_state($_POST['father_state'])).'</select></td</tr>
                        </table>';
       $mother_address='<table class="in_table"><tr><td colspan="4"><input type="text" name="mother_address" value="'.strtoupper($_POST['mother_address']).'" style="width:280px;margin-left:-3px" required/></td></tr>
                            <tr><td colspan="4"><input type="text" name="mother_address2" value="'.strtoupper($_POST['mother_address2']).'" style="width:280px;margin-left:-3px"/></td></tr>
                        <tr><td width="60px">Postcode: </td><td><input type="text"  name="mother_postcode" maxlength="5" style="width:40px" value="'.$_POST['mother_postcode'].'" required/></td><td>State:</td><td><select name="mother_state" required><option value="">-</option>'.arrToDDL(tep_state($_POST['mother_state'])).'</select></td</tr>
                        </table>';
       $father_annual ='<table class="in_table">
                                            <tr><td></td></tr>
                                            <tr><td><input type="text" name="father_2014" style="width:25px;text-align:right" maxlength="3" value="'.$_POST['father_2014'].'" id="father_2014"/> , <input type="text" name="father_2014_2" style="width:25px;text-align:right" maxlength="3" value="'.$_POST['father_2014_2'].'"  id="father_2014_2" required/> .00</td><label class="error_income">Annual income should be less than RM180,000</label><label class="error_numeric">All income must be numeric</label></tr>
                                            <tr><td><input type="text" name="father_2013" style="width:25px;text-align:right" maxlength="3" value="'.$_POST['father_2013'].'" id="father_2013"/> , <input type="text" name="father_2013_2" style="width:25px;text-align:right" maxlength="3" value="'.$_POST['father_2013_2'].'" id="father_2013_2" required/> .00</td></tr>
                                    </table>';
       $mother_annual ='<table class="in_table">
                                            <tr><td></td></tr>
                                            <tr><td><input type="text" name="mother_2014" style="width:25px;text-align:right" maxlength="3" value="'.$_POST['mother_2014'].'" id="mother_2014"/> , <input type="text" name="mother_2014_2" style="width:25px;text-align:right" maxlength="3" value="'.$_POST['mother_2014_2'].'" id="mother_2014_2"required/> .00</td><label class="error_income">Annual income should be less than RM180,000</label><label class="error_numeric">All income must be numeric</label></tr>
                                            <tr><td><input type="text" name="mother_2013" style="width:25px;text-align:right" maxlength="3" value="'.$_POST['mother_2013'].'" id="mother_2013"/> , <input type="text" name="mother_2013_2" style="width:25px;text-align:right" maxlength="3" value="'.$_POST['mother_2013_2'].'" id="mother_2013_2" required/> .00</td></tr>
                                    </table>';
       $guardian_name='<input type="text" name="guardian_name" style="width:98%" value="'.strtoupper($_POST['guardian_name']).'" required/>';
       $guardian_ic ='<input type="text" name="guardian_ic1" style="width:45px" maxlength="6" value="'.$_POST['guardian_ic1'].'" required/> - <input type="text" name="guardian_ic2" style="width:15px" maxlength="2" value="'.$_POST['guardian_ic2'].'" required/> - <input type="text" name="guardian_ic3" style="width:30px" maxlength="4" value="'.$_POST['guardian_ic3'].'" required/>';
       $guardian_status ='<fieldset><table class="in_table">
                                            <tr><td><label><input type="radio" name="guardian_status" value="full_time">Full time employment</label></td></tr>
                                            <tr><td><label><input type="radio" name="guardian_status" value="part_time">Part time employment</label></td></tr>
                                            <tr><td><label><input type="radio" name="guardian_status" value="own_business">Own business</label></td></tr>
                                            <tr><td><label><input type="radio" name="guardian_status" value="retired">Retired/Unemployed</label></td></tr>
                                            <tr><td><label><input type="radio" name="guardian_status" value="nil">NIL Annual Income</label></td></tr>
                                            <tr><td><label><input type="radio" name="guardian_status" value="deceased">Deceased</label></td></tr>
                                    </table><label for="guardian_status" class="error">Please select your gender</label></fieldset>';
        $guardian_tel='<input type="text" name="guardian_phone1" style="width:30px" maxlength="4" required/> - <input type="text" name="guardian_phone" style="max-width:200px" value="'.$_POST['guardian_phone'].'" required/>';
        $guardian_position='<input type="text" name="guardian_position" style="width:98%" value="'.strtoupper($_POST['guardian_position']).'" required/>';
        $guardian_address='<table class="in_table"><tr><td colspan="4"><input type="text" name="guardian_address" value="'.strtoupper($_POST['guardian_address']).'" style="width:280px;margin-left:-3px" required/></td></tr>
                            <tr><td colspan="4"><input type="text" name="guardian_address2" value="'.strtoupper($_POST['guardian_address2']).'" style="width:280px;margin-left:-3px"/></td></tr>
                        <tr><td width="60px">Postcode: </td><td><input type="text"  name="guardian_postcode" maxlength="5" style="width:40px" value="'.$_POST['guardian_postcode'].'" required/></td><td>State:</td><td><select name="guardian_state" required><option value="">-</option>'.arrToDDL(tep_state($_POST['guardian_state'])).'</select></td</tr>
                        </table>';
        $guardian_annual ='<table class="in_table">
                                            <tr><td></td></tr>
                                            <tr><td><input type="text" name="guardian_2014" style="width:25px;text-align:right" maxlength="3" value="'.$_POST['guardian_2014'].'" id="guardian_2014"/> , <input type="text" name="guardian_2014_2" style="width:25px;text-align:right" maxlength="3" value="'.$_POST['guardian_2014_2'].'" id="guardian_2014_2" required/> .00</td><label class="error_income">Annual income should be less than RM180,000</label><label class="error_numeric">All income must be numeric</label></tr>
                                            <tr><td><input type="text" name="guardian_2013" style="width:25px;text-align:right" maxlength="3" value="'.$_POST['guardian_2013'].'" id="guardian_2013"/> , <input type="text" name="guardian_2013_2" style="width:25px;text-align:right" maxlength="3" value="'.$_POST['guardian_2013_2'].'" id="guardian_2013_2" required/> .00</td></tr>
                                    </table>';
        
        $btn = '<input type="button" value="Previous" onclick="location.href=\'index.php?pages=course_details\'" style="padding:3px;width:100px"/> <input type="submit" value="Save" name="submit" style="padding:3px;width:100px"/>';
    }
?>
<p style="font-weight:bold;font-size:18px;text-align:center">Scholarship Application</p>
<div class="form_container">
    <?php
        if ($editable =="0" || $editable =="admin" || $editable=="view"){
            echo '<form method="post" id="form" onclick="return checkDate();">';
        }else{
            echo '  <form method="post" id="form" onclick="return checkIncome();">';
        }
    ?>
    <div style="background-color:rgb(231, 98, 123);text-align:center;padding:5px;font-size:14px;margin-bottom:10px"><b>D. FINANCIAL INFORMATION</b></div>
    <div class="form_content">
        <div class="main_form">
            <p>Are you currently receiving or have accepted any financial assistance to support your study, eg. scholarship, grant, loan:</p>
            <?=$financial_assistant?>
            <div id='financial_assistance'>
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
                    <th align="center" style="width:110px">Details</th>
                    <th align="center" style="width:285px">Father</th>
                    <th align="center" style="width:285px">Mother</th>
                    <th align="center" style="width:285px" class="guardian_info">Guardian</th>
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
           
             if ($editable =="0" || $editable =="admin" || $editable=="view"){
                $document = explode(",",$qry->member_document);
             
                    echo '<p style="text-decoration:underline;font-weight:bold">Document List</p>
                            <ul>';
                            for ($i=0;$i<count($document);$i++){
                                if ($document[$i]!=""){
                                     echo '<li><a href="./uploads/document/'.$document[$i].'" target="_blank">'.$document[$i].'</a></li>';
                                }
                               
                            }
                    echo'</ul>';
                

                  }else if($_GET['action']=="edit" || ($_GET['action']=="edit" && $editable=="1")){
                      $document = explode(",",$qry->member_document);
                   
                    echo '<p style="text-decoration:underline;font-weight:bold">Document List</p>
                            <ul>';
                            for ($i=0;$i<count($document);$i++){
                                 if ($document[$i]!=""){
                                echo '<li><a href="./uploads/document/'.$document[$i].'" target="_blank" >'.$document[$i].'</a>    <img class="img_button"  title="delete '.$document[$i].'"src="./images/icon_cross.png" style="float:right" onclick="window.location=\'index.php?pages=financial_information&doc='.$document[$i].'\'"/> </li><div class="cls"></div>';
                            }
                            }
                    echo'</ul>';
                      
                     echo '<div>
                                        <div id="fileuploader">		
                                                    <noscript><p>Please enable JavaScript to use file uploader.</p></noscript>         
		</div>
		<br />
		<div id="message" style="color: green; font-weight: bold;">
		 <div class="qq-upload-extra-drop-area"></div>
                                            <input type="hidden" name="images" id="images" value="" />
		</div>
                                    </div>';
                  }else{
                      echo '<div>
                                        <div id="fileuploader">		
                                                    <noscript><p>Please enable JavaScript to use file uploader.</p></noscript>         
		</div>
		<br />
		<div id="message" style="color: green; font-weight: bold;">
		 <div class="qq-upload-extra-drop-area"></div>
                                            <input type="hidden" name="images" id="images" value="" />
		</div>
                                    </div>';
                  }
?>
           	
            <p style="font-weight:bold">Declaration</p>
                <table style="border:1px solid #9E9E9E;padding:10px">
                    <tr>
                        <td>I, hereby declare that: <br/> 
                            I have not been convicted of any criminal offence or relating to dishonesty or fraud under any written law within or outside Malaysia; and I have not been declared a bankrupt, insolvent or un-discharged bankrupt.<br/>
                                All the information furnished by me is true and correct to the best of my knowledge and belief; I undertake the responsibility to promptly inform you of any changes therein.  <br/>
                               I am aware and understand that should information provided found to be distorted, untrue or misrepresenting, actions may be taken against me including immediate disqualification from the application. <br/>
                               I have read and agreed to the <a href="http://www.penangfuturefoundation.my/option/mod_content_article/cid/6557" target="_blank">Privacy Policy</a> of Penang Future Foundation. 
                        </td>
                    </tr>
                    <tr><td style="text-align:center"><label><input type="checkbox" value="1" name="chk_declare" <?=$declare?> required /> Agree</label></td></tr>
                </table>
           
         
            <div style="float:right;margin-bottom:10px;margin-top:10px"><?=$btn?></div>
            <div class="cls"></div>
        </div>
    </div>
    </form>
</div>

   
   <script>        
	        $(document).ready(function () {
                                    uploader = new qq.FileUploader({
	                element: document.getElementById('fileuploader'),
	                action: './includes/modules/j_upload/upload.php',
	                //Files with following extensions are only allowed
                                    extraDropzones: [qq.getByClass(document, 'qq-upload-extra-drop-area')[0]],
	                sizeLimit: 10485760, // Maximum filesize limit which works without any problems is 30MB. Current limit is set to 10MB = 10 * 1024 * 1024
	               
                       
                                 onComplete: function(id, fileName, response){
                                          var images = $('#images').val().split(','), imagesStr='';
                                          images.push(response.filename);
                                          imagesStr = images.join(',');
                                          if(images[0]==''){
                                              imagesStr = imagesStr.substring(1);
                                          }
                                          $('#images').val(imagesStr);
                                          $('.qq-upload-list #list_'+id).attr('data-image',response.filename);
                                          
                                      }
	            });     
                                  });
                                  
			var uploader;
	    </script>    
