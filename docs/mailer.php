<?php
require './connect_db.php';


$response = array( 'success' => false );

$formData = file_get_contents( 'php://input' );

$data = json_decode( $formData );

if($data->name!=''&&$data->number!=''&&$data->college!=''&&$data->email!=''){

	if($data->year=="Other")
		$year = 0;
	else{
		$year = (int)substr($data->year, 0,1);
	}
	$number = (string)$data->number;

	$query = "INSERT INTO `engineer2015`(`name`, `mobile`, `email`, `college`, `location`, `branch`,`year`,`rfor`) VALUES ('$data->name','$number','$data->email','$data->college','$data->location','$data->stream','$year','$data->rfor')";
	//$mail->SMTPDebug = 3;                               // Enable verbose debug output
    if(mysql_query($query)){                          // Set email format to HTML
		$subject = "Engineer Campus Ambassador Registration";
	    $headers = "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8";
		$body = "Hey dude,\r\n".$data->name." from ".$data->college." has registered\r\n "."Email: ".$data->email."\r\nNumber: ".$data->number."\r\nyear: ".$data->year."\r\nstream: ".$data->stream."\r\nlocation: ".$data->location."\r\nHas held any position: ".$data->held."\r\nDescription if yes: ".$data->desc."\r\nFacebook :".$data->flink."\r\nTwitter :".$data->tlink;
		$address = "mahendrabohra28@gmail.com";
		if(mail($address, $subject, $body,$headers)) {
			$response[ 'success' ] = true;
			$contacter_name = $data->name;
			$contacter_mail = $data->email;
			$contacter_subject = "Campus Ambassador Registration";
			$contacter_body = "Dear ".$contacter_name.",\r\nThank you for registering as NITK Engineer 2015 Campus Ambassador\r\nWe will contact you again\r\nFor queries feel free to drop a mail to ca@engineer.org.in .\r\nThis is an auto generated mail please do not reply to this mail.";
			mail($contacter_mail, $contacter_subject , $contacter_body,$headers);
		} else {
		   $response[ 'success' ] = false;
		}
	}
	echo json_encode( $response );
}
?>