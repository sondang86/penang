<div id="column_left">
    <img src="./images/logo.png" style="width:180px;padding:10px">
    <ul id="menu">
        <?php
        
        switch ($_GET['pages']){
            case "index":
                $index = "selected";
                break;
            case "academic_details":
                $academic = "selected";
                break;
            case "course_details":
                $course = "selected";
                break;
            case "financial_information":
                $financial = "selected";
                break;
             case "print_preview":
                $print = "selected";
                break;
            default:
                $index = "selected";
                break;
                    
                
        }
        
            $mcheck = tep_fetch_object(tep_query("SELECT * FROM ".MEMBER." WHERE  (member_id = '".$_SESSION['member_id']."' OR member_id = '".$_GET['id']."')"));
            $register_year = substr($mcheck->member_created, 0, 4);
            
            if ($register_year == "2016") {
                $register_form = '';
            } else {
                if($_GET['id']!=""){
                    $register_form = '<li><i class="fa fa-print"></i><a href="index.php?pages=print_preview&id='.$_GET['id'].'" class="'.$print.'" target="_blank">Print 2015 form</a></li>';
                } else {
                    $register_form = '<li><i class="fa fa-print"></i><a href="index.php?pages=print_preview" class="'.$print.'" target="_blank">Print 2015 form</a></li>';    
                }
            }
            
             if($_GET['id']!=""){
                    echo '  <li><i class="fa fa-user"></i><a href="index.php?id='.$_GET['id'].'" class="'.$index.'">Personal Details</a></li>
                            <li><i class="fa fa-graduation-cap"></i><a href="index.php?pages=academic_details&id='.$_GET['id'].'" class="'.$academic.'">Academic Details</a></li>
                            <li><i class="fa fa-th-list"></i><a href="index.php?pages=course_details&id='.$_GET['id'].'" class="'.$course.'">Course Details</a></li>
                            <li><i class="fa fa-credit-card"></i><a href="index.php?pages=financial_information&id='.$_GET['id'].'" class="'.$financial.'">Financial Information</a></li>'
                           .$register_form;
                } else {          
                    echo '  <li><i class="fa fa-user"></i><a href="index.php" class="'.$index.'">Personal Details</a></li>
                            <li><i class="fa fa-graduation-cap"></i><a href="index.php?pages=academic_details" class="'.$academic.'">Academic Details</a></li>
                            <li><i class="fa fa-th-list"></i><a href="index.php?pages=course_details" class="'.$course.'">Course Details</a></li>
                            <li><i class="fa fa-credit-card"></i><a href="index.php?pages=financial_information" class="'.$financial.'">Financial Information</a></li>'
                           .$register_form. 
                           '<li><i class="fa fa-sign-out"></i><a href="index.php?log=out">Log Out</a></li>';
                }
        ?>
      
            
    </ul>

    
</div>
<div class="cls"></div>

<?php
    $fklajsfkla ="flsakl;fas";
    $register_year = substr($mcheck->member_created, 0, 4);
    $fklajsfkla2 ="flsakl;fas";
    $fklajsfkla3 ="flsakl;fas";
?>