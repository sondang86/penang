<?php
require('./includes/modules/mod_phpexcel/Classes/PHPExcel.php');
include "includes/configuration.php";

     $row = 2;   


            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("Penang Future Foundation")
                                        ->setLastModifiedBy("Penang Future Foundation")
                                        ->setTitle("Penang Future Foundation")
                                        
                                        ->setDescription("Report");
 
            
            if ($_GET['export']!="") {
//str_replace("|",",","$re->examination_subject.")
           $extFileName = "Penang Future Foundation Application Report".date("Ymd");
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No.')
                        ->setCellValue('B1', 'Submission Ref')
                        ->setCellValue('C1', 'Submission Date')
                        ->setCellValue('D1', 'Name')
                        ->setCellValue('E1', 'IC')
                        ->setCellValue('F1', 'Citizenship')
                        ->setCellValue('G1', 'Sex')
                        ->setCellValue('H1', 'Marital Status')
                        ->setCellValue('I1', 'Date of Birth')
                        ->setCellValue('J1', 'Place of Birth')
                        ->setCellValue('K1', 'Home Address (Permanent)')
                        ->setCellValue('L1', 'Postal Address')
                        ->setCellValue('M1', 'Home Phone Number')
                        ->setCellValue('N1', 'Mobile Phone Number')
                        ->setCellValue('O1', 'Email')
                        ->setCellValue('P1', 'Status of Application')
                        ->setCellValue('Q1', 'Current Academic Year')
                        ->setCellValue('R1', 'Current Semester')
                        ->setCellValue('S1', 'State')
                        ->setCellValue('T1', 'School / Institution Name')
                        ->setCellValue('U1', 'Year Commenced')
                        ->setCellValue('V1', 'Year Completed')
                        ->setCellValue('W1', 'Highest Qualification')
                        ->setCellValue('X1', 'Year')
                        ->setCellValue('Y1', 'Subjects')
                        ->setCellValue('Z1', 'Grades')
                        ->setCellValue('AA1', 'University')
                        ->setCellValue('AB1', 'Degree Title')
                        ->setCellValue('AC1', 'Subjects')
                        ->setCellValue('AD1', 'Grades')
                        ->setCellValue('AE1', 'CGPA')
                        ->setCellValue('AF1', 'Co-curricular Year')
                        ->setCellValue('AG1', 'Club/Association/Sports')
                        ->setCellValue('AH1', 'Position Held')
                        ->setCellValue('AI1', 'School/Institution')
                        ->setCellValue('AJ1', 'Awards/Achievements')
                        ->setCellValue('AK1', 'Prize')
                        ->setCellValue('AL1', 'Level')
                        ->setCellValue('AM1', 'Awards Year')
                        ->setCellValue('AN1', 'Program Applied')
                        ->setCellValue('AO1', 'Name of Institution')
                        ->setCellValue('AP1', 'Date of Enrollment')
                        ->setCellValue('AQ1', 'Expected Date of Completion')
                        ->setCellValue('AR1', 'Tuition Fees (RM) applied')
                        ->setCellValue('AS1', 'Type of Financial Assistance')
                        ->setCellValue('AT1', 'Name of Financial Assistance')
                        ->setCellValue('AU1', 'Amount Received (RM)')
                        ->setCellValue('AV1', 'Receiving period')
                        ->setCellValue('AW1', 'Father Name')
                        ->setCellValue('AX1', 'Father NRIC number')
                        ->setCellValue('AY1', 'Father Current Status')
                        ->setCellValue('AZ1', 'Father Telephone no')
                        ->setCellValue('BA1', 'Father Occupation / Job Title')
                        ->setCellValue('BB1', 'Father Company Address')
                        ->setCellValue('BC1', 'Father Annual Income (2014|2013)')
                        ->setCellValue('BD1', 'Mother Name')
                        ->setCellValue('BE1', 'Mother NRIC number')
                        ->setCellValue('BF1', 'Mother Current Status')
                        ->setCellValue('BG1', 'Mother Telephone no')
                        ->setCellValue('BH1', 'Mother Occupation / Job Title')
                        ->setCellValue('BI1', 'Mother Company Address')
                        ->setCellValue('BJ1', 'Mother Annual Income (2014|2013)')
                        ->setCellValue('BK1', 'Guardian Name')
                        ->setCellValue('BL1', 'Guardian NRIC number')
                        ->setCellValue('BM1', 'Guardian Current Status')
                        ->setCellValue('BN1', 'Guardian Telephone no')
                        ->setCellValue('BO1', 'Guardian Occupation / Job Title')
                        ->setCellValue('BP1', 'Guardian Company Address')
                        ->setCellValue('BQ1', 'Guardian Annual Income (2014|2013)')
                        ->setCellValue('BR1', 'Total Points')
                        ->setCellValue('BS1', 'Document')
                    ;
                    
            $sheet = $objPHPExcel->getActiveSheet();
            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(29.8);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(18);
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->getColumnDimension('H')->setWidth(18);
            $sheet->getColumnDimension('I')->setWidth(25);
            $sheet->getColumnDimension('J')->setWidth(25);
            $sheet->getColumnDimension('K')->setWidth(24);
            $sheet->getColumnDimension('L')->setWidth(20);
            $sheet->getColumnDimension('M')->setWidth(25);
            $sheet->getColumnDimension('N')->setWidth(28);
            $sheet->getColumnDimension('O')->setWidth(20);
            $sheet->getColumnDimension('P')->setWidth(20);
            $sheet->getColumnDimension('Q')->setWidth(17);
            $sheet->getColumnDimension('R')->setWidth(20);
            $sheet->getColumnDimension('S')->setWidth(20);
            $sheet->getColumnDimension('T')->setWidth(20);
            $sheet->getColumnDimension('U')->setWidth(20);
            $sheet->getColumnDimension('V')->setWidth(16);
            $sheet->getColumnDimension('W')->setWidth(28);
            $sheet->getColumnDimension('X')->setWidth(20);
            $sheet->getColumnDimension('Y')->setWidth(28);
            $sheet->getColumnDimension('Z')->setWidth(28);
            $sheet->getColumnDimension('AA')->setWidth(28);
            $sheet->getColumnDimension('AB')->setWidth(20);
            $sheet->getColumnDimension('AC')->setWidth(16);
            $sheet->getColumnDimension('AD')->setWidth(20);
            $sheet->getColumnDimension('AE')->setWidth(20);
            $sheet->getColumnDimension('AF')->setWidth(20);
            $sheet->getColumnDimension('AG')->setWidth(20);
            $sheet->getColumnDimension('AH')->setWidth(20);
            $sheet->getColumnDimension('AI')->setWidth(20);
            $sheet->getColumnDimension('AJ')->setWidth(20);
            $sheet->getColumnDimension('AK')->setWidth(20);
            $sheet->getColumnDimension('AL')->setWidth(28);
            $sheet->getColumnDimension('AM')->setWidth(28);
            $sheet->getColumnDimension('AN')->setWidth(20);
            $sheet->getColumnDimension('AO')->setWidth(20);
            $sheet->getColumnDimension('AP')->setWidth(20);
            $sheet->getColumnDimension('AQ')->setWidth(20);
            $sheet->getColumnDimension('AR')->setWidth(20);
            $sheet->getColumnDimension('AS')->setWidth(20);
            $sheet->getColumnDimension('AT')->setWidth(20);
            $sheet->getColumnDimension('AU')->setWidth(20);
            $sheet->getColumnDimension('AV')->setWidth(20);
            $sheet->getColumnDimension('AW')->setWidth(20);
            $sheet->getColumnDimension('AX')->setWidth(20);
            $sheet->getColumnDimension('AY')->setWidth(20);
            $sheet->getColumnDimension('AZ')->setWidth(20);
            $sheet->getColumnDimension('BA')->setWidth(20);
            $sheet->getColumnDimension('BB')->setWidth(20);
            $sheet->getColumnDimension('BC')->setWidth(20);
            $sheet->getColumnDimension('BD')->setWidth(20);
            $sheet->getColumnDimension('BE')->setWidth(20);
            $sheet->getColumnDimension('BF')->setWidth(20);
            $sheet->getColumnDimension('BG')->setWidth(20);
            $sheet->getColumnDimension('BH')->setWidth(20);
            $sheet->getColumnDimension('BI')->setWidth(20);
            $sheet->getColumnDimension('BJ')->setWidth(20);
            $sheet->getColumnDimension('BK')->setWidth(20);
            $sheet->getColumnDimension('BL')->setWidth(20);
            $sheet->getColumnDimension('BM')->setWidth(20);
            $sheet->getColumnDimension('BN')->setWidth(20);
            $sheet->getColumnDimension('BO')->setWidth(20);
            $sheet->getColumnDimension('BP')->setWidth(20);
            $sheet->getColumnDimension('BQ')->setWidth(20);
            $sheet->getColumnDimension('BR')->setWidth(20);
            $sheet->getColumnDimension('BS')->setWidth(30);
           
            
            $qry = "SELECT *,m.member_ic AS ic FROM ".MEMBER." m, ".PERSONAL." p, ".ACADEMIC." a, ".COURSE." c, ".FINANCIAL." f WHERE m.member_id = p.member_id AND a.member_id = m.member_id AND c.member_id = m.member_id AND f.member_id = m.member_id AND m.fund_status ='".$_GET['export']."' GROUP BY m.member_id ORDER BY m.member_id";
          
            $query = tep_query($qry);
            $count = 0;
            while($re=tep_fetch_object($query)){
                if ($re->studies_status=="1"){
                    $studies_status = "Currently pursuing undergraduate studies";
                    $cgpa = $re->college_cgpa;
                    $current_year = $re->current_year;
                    $current_semester = $re->current_semester;
                }else{
                    $studies_status = "Applying to enter university / college for undergraduate program";
                    $cgpa = $re->examination_cgpa;
                    $current_year = "";
                    $current_semester = "";
                }
             
                $enroll = explode('|',$re->course_enrollment);
                $complete = explode('|',$re->course_completion);
                if (strlen($enroll[0])=="1"){
                    $en_month = "0".$enroll[0];
                }else{
                    $en_month = $enroll[0];
                }
                
                if (strlen($complete[0])=="1"){
                    $com_month = "0".$complete[0];
                }else{
                    $en_month = $complete[0];
                }
            
                $count++;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$row, $count)
                        ->setCellValue('B'.$row, $re->submission_ref)
                        ->setCellValue('C'.$row, $re->submission_created)
                        ->setCellValue('D'.$row, $re->member_name)
                        ->setCellValue('E'.$row, str_replace("-","",$re->ic))
                        ->setCellValue('F'.$row, Malaysian)
                        ->setCellValue('G'.$row, $re->member_sex)
                        ->setCellValue('H'.$row, $re->member_marital_status)
                        ->setCellValue('I'.$row, $re->member_dob)
                        ->setCellValue('J'.$row, $re->member_pob)
                        ->setCellValue('K'.$row, str_replace("|",",",$re->member_home_address))
                        ->setCellValue('L'.$row, str_replace("|",",",$re->member_postal_address))
                        ->setCellValue('M'.$row, $re->member_home_number)
                        ->setCellValue('N'.$row, $re->member_mobile_number)
                        ->setCellValue('O'.$row, $re->member_email)
                        ->setCellValue('P'.$row, $studies_status)
                        ->setCellValue('Q'.$row, $current_year)
                        ->setCellValue('R'.$row, $current_semester)
                        ->setCellValue('S'.$row, $re->qualification_state)
                        ->setCellValue('T'.$row, $re->qualification_school)
                        ->setCellValue('U'.$row, $re->qualification_year_commenced)
                        ->setCellValue('V'.$row, $re->qualification_year_completed)
                        ->setCellValue('W'.$row, $re->qualification_obtained)
                        ->setCellValue('X'.$row, $re->examination_year)
                        ->setCellValue('Y'.$row, str_replace("|",",",$re->examination_subject))
                        ->setCellValue('Z'.$row, str_replace("|",",",$re->examination_grades))
                        ->setCellValue('AA'.$row, $re->college_name)
                        ->setCellValue('AB'.$row, "BACHELOR'S DEGREE IN ".$re->college_course)
                        ->setCellValue('AC'.$row, str_replace("|",",",$re->college_subject))
                        ->setCellValue('AD'.$row, str_replace("|",",",$re->college_grades))
                        ->setCellValue('AE'.$row, $cgpa)
                        ->setCellValue('AF'.$row, str_replace("|",",",$re->co_year))
                        ->setCellValue('AG'.$row, str_replace("|",",","$re->co_club"))
                        ->setCellValue('AH'.$row, str_replace("|",",","$re->co_position"))
                        ->setCellValue('AI'.$row, str_replace("|",",","$re->co_school"))
                        ->setCellValue('AJ'.$row, str_replace("|",",","$re->awards_name"))
                        ->setCellValue('AK'.$row, str_replace("|",",","$re->awards_prize"))
                        ->setCellValue('AL'.$row, str_replace("|",",","$re->awards_level"))
                        ->setCellValue('AM'.$row, str_replace("|",",","$re->awards_year"))
                        ->setCellValue('AN'.$row, str_replace("|",",","$re->course_name"))
                        ->setCellValue('AO'.$row, $re->college_name)
                        ->setCellValue('AP'.$row, $enroll[1].$en_month)
                        ->setCellValue('AQ'.$row, $complete[1].$com_month)
                        ->setCellValue('AR'.$row, number_format($re->scholarship_apply,2))
                        ->setCellValue('AS'.$row, $re->type_financial)
                        ->setCellValue('AT'.$row, $re->name_financial)
                        ->setCellValue('AU'.$row, number_format($re->amount_financial,2))
                        ->setCellValue('AV'.$row, $re->financial_period)
                        ->setCellValue('AW'.$row, $re->father_name)
                        ->setCellValue('AX'.$row, str_replace("-","",$re->father_ic))
                        ->setCellValue('AY'.$row, $re->father_status)
                        ->setCellValue('AZ'.$row, $re->father_phone)
                        ->setCellValue('BA'.$row, $re->father_position)
                        ->setCellValue('BB'.$row, str_replace("|",",","$re->father_address"))
                        ->setCellValue('BC'.$row, $re->father_income)
                        ->setCellValue('BD'.$row, $re->mother_name)
                        ->setCellValue('BE'.$row, str_replace("-","",$re->mother_ic))
                        ->setCellValue('BF'.$row, $re->mother_status)
                        ->setCellValue('BG'.$row, $re->mother_phone)
                        ->setCellValue('BH'.$row, $re->mother_position)
                        ->setCellValue('BI'.$row, str_replace("|",",","$re->mother_address"))
                        ->setCellValue('BJ'.$row, $re->mother_income)
                        ->setCellValue('BK'.$row, $re->guardian_name)
                        ->setCellValue('BL'.$row, str_replace("-","",$re->guardian_ic))
                        ->setCellValue('BM'.$row, $re->guardian_status)
                        ->setCellValue('BN'.$row, $re->guardian_phone)
                        ->setCellValue('BO'.$row, $re->guardian_position)
                        ->setCellValue('BP'.$row, str_replace("|",",","$re->guardian_address"))
                        ->setCellValue('BQ'.$row, $re->guardian_income)
                        ->setCellValue('BR'.$row, $re->total_points)
                        ->setCellValue('BS'.$row, $re->member_document)
                        
                        
                        
                        
                        ;
                

                $row++;
              
 
           }
           }
           
           else if ($_GET['report']=="voucher_purchase") {
         
           $extFileName = "JasHoliday Voucher Purchase Report".date("Ymd");
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No.')
                        ->setCellValue('B1', 'Member Name')
                        ->setCellValue('C1', 'Member Email')
                        ->setCellValue('D1', 'Voucher Name')
                        ->setCellValue('E1', 'Voucher Type')
                        ->setCellValue('F1', 'Voucher Price')
                        ->setCellValue('G1', 'Merchant Name')
                        ->setCellValue('H1', 'Purchase Date');
            
            $sheet = $objPHPExcel->getActiveSheet();
            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(29.8);
            $sheet->getColumnDimension('C')->setWidth(29.8);
            $sheet->getColumnDimension('D')->setWidth(25);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(14);
            $sheet->getColumnDimension('G')->setWidth(29.8);
            $sheet->getColumnDimension('H')->setWidth(20);
            
            if($_SESSION["mid"]!=""){
            $ext .= " AND m.merchant_id='".$_SESSION["mid"]."'";
            } 
            if($_SESSION["fdate"]!=""){
            $ext .= " AND DATE(vp.purchase_created)>='".date_htmltomysql($_GET["fdate"])."'";
            } 
            if($_SESSION["tdate"]!=""){
            $ext .= " AND DATE(vp.purchase_created)<='".date_htmltomysql($_GET["tdate"])."'";
            } 
            
            $qry = "SELECT * FROM ".MERCHANT." m, ".MEMBER." mem, voucher v, voucher_purchase vp WHERE vp.merchant_id = m.merchant_id AND vp.member_id = mem.member_id $ext AND vp.voucher_id = v.voucher_id ORDER BY vp.voucher_id";
          
            $query = tep_query($qry);
            $count = 0;
            while($re=tep_fetch_object($query)){
                $count++;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$row, $count)
                        ->setCellValue('B'.$row, $re->member_name)
                        ->setCellValue('C'.$row, $re->member_email)
                        ->setCellValue('D'.$row, $re->voucher_name_en)
                        ->setCellValue('E'.$row, $re->voucher_type)
                        ->setCellValue('F'.$row, $re->voucher_price)
                        ->setCellValue('G'.$row, $re->company_name)
                        ->setCellValue('H'.$row, $re->purchase_created);

                $row++;
              
           }
            
           }
           
            else if ($_GET['report']=="ads") {
         
           $extFileName = "JasHoliday Advertisement Purchase Report".date("Ymd");
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No.')
                        ->setCellValue('B1', 'Merchant Name')
                        ->setCellValue('C1', 'Merchant Email')
                        ->setCellValue('D1', 'Contact Person')
                        ->setCellValue('E1', 'Contact Person Mobile')
                        ->setCellValue('F1', 'Advertise Category')
                        ->setCellValue('G1', 'Advertise Start Date')
                        ->setCellValue('H1', 'Advertise End Date')
                        ->setCellValue('I1', 'Amount')
                        ->setCellValue('J1', 'Payment Type')
                        ->setCellValue('K1', 'Advertise Created')
                        ->setCellValue('L1', 'Advertise Status');
            
             $sheet = $objPHPExcel->getActiveSheet();
            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(29.8);
            $sheet->getColumnDimension('C')->setWidth(29.8);
            $sheet->getColumnDimension('D')->setWidth(25);
            $sheet->getColumnDimension('E')->setWidth(22);
            $sheet->getColumnDimension('F')->setWidth(19);
            $sheet->getColumnDimension('G')->setWidth(22);
            $sheet->getColumnDimension('H')->setWidth(20);
            $sheet->getColumnDimension('I')->setWidth(13);
            $sheet->getColumnDimension('J')->setWidth(18);
            $sheet->getColumnDimension('K')->setWidth(20);
            $sheet->getColumnDimension('L')->setWidth(20);
            
        
          
                $ext = "AND a.ads_status = ".$_SESSION["ads_active"]."";
        
                if ($_SESSION["ads_active"]==3){
                    $ext ="";
                }

    $qry = "SELECT * FROM ".MERCHANT." m, ads a WHERE a.ads_createdby = m.merchant_id AND a.ads_enddate >= DATE(NOW()) $ext ORDER BY a.ads_created";

            $query = tep_query($qry);
            $count = 0;
            while($re=tep_fetch_object($query)){
                if ($re->ads_status == 1){
                    $status = "PAID";
                }else
                {
                    $status = "PENDING";
                }
                $count++;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$row, $count)
                        ->setCellValue('B'.$row, $re->company_name)
                        ->setCellValue('C'.$row, $re->company_email)
                        ->setCellValue('D'.$row, $re->company_contact_person)
                        ->setCellValue('E'.$row, $re->contact_person_mobile)
                        ->setCellValue('F'.$row, $re->ads_category)
                        ->setCellValue('G'.$row, $re->ads_startdate)
                        ->setCellValue('H'.$row, $re->ads_enddate)
                        ->setCellValue('I'.$row, number_format($re->ads_amount,2))
                        ->setCellValue('J'.$row, $re->payment_type)
                        ->setCellValue('K'.$row, $re->ads_created)
                        ->setCellValue('L'.$row, $status);

                $row++;
              
           }
            
           }
           
            else if ($_GET['report']=="tour_package_purchase") {
         
           $extFileName = "JasHoliday Tour Package Purchase Report".date("Ymd");
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'No.')
                        ->setCellValue('B1', 'Purchased Date')
                        ->setCellValue('C1', 'Purchase Number')
                        ->setCellValue('D1', 'Tour Company')
                        ->setCellValue('E1', 'Payment Type')
                        ->setCellValue('F1', 'Member Name')
                        ->setCellValue('G1', 'Member Email')
                        ->setCellValue('H1', 'Member Phone No.')
                        ->setCellValue('I1', 'Tour Package')
                        ->setCellValue('J1', 'Total Amount(RM)')
                        ;
                    
            $sheet = $objPHPExcel->getActiveSheet();
            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(23);
            $sheet->getColumnDimension('C')->setWidth(23);
            $sheet->getColumnDimension('D')->setWidth(34);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(28);
            $sheet->getColumnDimension('G')->setWidth(28);
            $sheet->getColumnDimension('H')->setWidth(24);
            $sheet->getColumnDimension('I')->setWidth(35);
            $sheet->getColumnDimension('J')->setWidth(20);
            
            
            $qry = "SELECT * FROM tour_package_purchase p, ".MEMBER." m, tour_package t WHERE p.purchase_createdby = m.member_id AND p.package_id = t.package_id AND p.purchase_status = 1 ORDER BY p.purchase_created";
          
            $query = tep_query($qry);
            $count = 0;
            while($re=tep_fetch_object($query)){
                if ($re->ads_status == 1){
                    $status = "PAID";
                }else
                {
                    $status = $re->ads_status;
                }
                $count++;
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$row, $count)
                        ->setCellValue('B'.$row, $re->purchase_created)
                        ->setCellValue('C'.$row, TourPurchase_getRef($re->package_purchase_id))
                        ->setCellValue('D'.$row, $re->package_company)
                        ->setCellValue('E'.$row, $re->payment_type)
                        ->setCellValue('F'.$row, $re->member_name)
                        ->setCellValue('G'.$row, $re->member_email)
                        ->setCellValue('H'.$row, $re->member_tel)
                        ->setCellValue('I'.$row, $re->package_title_en)
                        ->setCellValue('J'.$row, number_format($re->package_amount,2))
                        ;

                $row++;
              
           }
            
           }
            

            //output
            $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="'.$extFileName.'.xls"');
            $objWriter->save('php://output');
     

?>