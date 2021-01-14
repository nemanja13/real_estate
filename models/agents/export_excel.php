<?php
    ob_clean();
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=agents.xls");
    require_once $_SERVER["DOCUMENT_ROOT"]."/praktikum_php_sajt/config/connection.php";
    $query="SELECT * FROM user u INNER JOIN agent a ON a.idUser=u.idUser INNER JOIN type t ON t.idType=a.idType";
    $agents=ExecuteQuery($query);

    $excel = new COM("Excel.Application")or die('Connection to excel failed');
    $excel->Visible = 1;
    $excel->DisplayAlerts = 1;
    $workbook = $excel->Workbooks->Add();
    
    $sheet = $workbook->Worksheets('Sheet1');
    $sheet->activate;
    $br = 2;
    $field = $sheet->Range("A1");
    $field->activate;
    $field->value = "First name";
    $field = $sheet->Range("B1");
    $field->activate;
    $field->value = "Last name";
    $field = $sheet->Range("C1");
    $field->activate;
    $field->value = "Phone number";
    $field = $sheet->Range("D1");
    $field->activate;
    $field->value = "Email";
    $field = $sheet->Range("E1");
    $field->activate;
    $field->value = "Biography";
    $field = $sheet->Range("F1");
    $field->activate;
    $field->value = "Linkedin profile";
    $field = $sheet->Range("G1");
    $field->activate;
    $field->value = "Profession";

    foreach($agents as $a){
        $field = $sheet->Range("A{$br}");
        $field->activate;
        $field->value = $a->firstName;
        $field = $sheet->Range("B{$br}");
        $field->activate;
        $field->value = $a->lastName;
        $field = $sheet->Range("C{$br}");
        $field->activate;
        $field->value = $a->phone;
        $field = $sheet->Range("D{$br}");
        $field->activate;
        $field->value = $a->email;
        $field = $sheet->Range("E{$br}");
        $field->activate;
        $field->value = $a->description;
        $field = $sheet->Range("F{$br}");
        $field->activate;
        $field->value = $a->linkLinkedin;
        $field = $sheet->Range("G{$br}");
        $field->activate;
        $field->value = $a->type;
        $br++;
    }

    $field = $sheet->Range("H1");
    $field->activate;
    $field->value = $br-1;

    $file = tempnam(sys_get_temp_dir(), "excel");
    $workbook->SaveAs($file, -4143);
    $workbook->Save();
    $workbook->Saved=true;
    $workbook->Close;
    $excel->Workbooks->Close();
    $excel->Quit();
    

    readfile($file);
    unlink($file);

    unset($sheet);
    unset($workbook);
    unset($excel);
?>