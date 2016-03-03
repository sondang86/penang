<?php
    $bday = new DateTime($_POST['dob']);
    $today = new DateTime(date('F.j.Y', time())); // for testing purposes
    $diff = $today->diff($bday);
//    printf('%d years, %d month, %d days', $diff->y, $diff->m, $diff->d); 
    if ($diff->y > 25){
        echo "<label style='width:100px; color:red'><span style='float:left'>Age : </span><div id='applicant-age'>$diff->y</div></label>";
    } else {
        echo "<label style='width:100px'><span style='float:left'>Age : </span><div id='applicant-age'>$diff->y</div></label>";
    }