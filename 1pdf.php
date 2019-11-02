<?php
 // INCLUDE THE phpToPDF.php FILE
require("assets/phpToPDF.php"); 

if(isset($_GET['f']) && $_GET['f'] != '')
{
	$fecha=$_GET['f'];
	//$html = file_get_contents('http://www.mindware.com.mx/apps/webcunop/pdfcunop?f=' . $fecha);

	$c = curl_init('http://www.mindware.com.mx/apps/webcunop/pdfcunop?f=' . $fecha);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt(... other options you want...)

	$html = curl_exec($c);

	if (curl_error($c))
	    die(curl_error($c));

	// Get the status code
	$status = curl_getinfo($c, CURLINFO_HTTP_CODE);

	curl_close($c);
	//echo $html;
	// SET YOUR PDF OPTIONS
	// FOR ALL AVAILABLE OPTIONS, VISIT HERE:  http://phptopdf.com/documentation/
	$pdf_options = array(
	  "source_type" => 'html',
	  "source" => $html,
	  //"source" => 'http://www.mindware.com.mx/apps/webcunop/pdfcunop?f=' . $fecha,
	  "action" => 'view',
	  "file_name" => "websched" . $fecha . ".pdf",
	  "save_directory" => '/home/mindware/public_html/shared');

	// CALL THE phptopdf FUNCTION WITH THE OPTIONS SET ABOVE
	phptopdf($pdf_options);


	// OPTIONAL - PUT A LINK TO DOWNLOAD THE PDF YOU JUST CREATED
	echo ("<a href='../shared/websched" . $fecha . ".pdf'>Download Your PDF</a>");
}
else
{
	echo "Something went wrong";
}
?>