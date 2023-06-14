<meta charset="utf-8">
<?php
require_once('tcpdf/tcpdf-main/tcpdf.php');

ob_start();
// // create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set font
$pdf->SetFont('msungstdlight', '', 10);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

$html = 'TEST<br>
<img src="screenshot.jpg"><br>

<table border="1">
<tr>
<td>No.</td>
<td>Name</td>
<td>ID</td>
<td>Department</td>
</tr>
';

$link=mysqli_connect("localhost","root","","school");

mysqli_set_charset($link, "utf8");
$SQL="SELECT * FROM student";

if($result=mysqli_query($link, $SQL)) {
    while($row=mysqli_fetch_assoc($result)){       
        $No=$row['No'];
        $Name=$row['Name'];
        $Id=$row['Id'];
        $Dept=$row['Department'];
        $html.='<tr>
        <td>'.$No.'</td>
        <td>'.$Name.'</td>
        <td>'.$Id.'</td>
        <td>'.$Dept.'</td>
        </tr>';
    }  
}else{
    echo "資料庫查詢失敗";
}


$html.='</table>';

// // output the HTML content
$pdf->writeHTML($html);
//file send to file address
$path = "/docs/123info.pdf";
//Close and output PDF document
$pdf->Output(__DIR__ .$path, 'F');
//$pdf->Output($No."-tableinfo.pdf", 'D');
ob_end_flush();


?>