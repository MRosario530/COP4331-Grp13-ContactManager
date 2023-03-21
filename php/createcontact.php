<?php
	// Replace the below variables to match your database setup:

	$Admin_Username = "doadmin";
	$Admin_Password = "AVNS_uREDxrVmOqodnvyI1YS";
	$Database_Name = "CM_db";

	$Contact_Table = "Contacts";
        $CT_ContactID = "ID";
		$CT_UserID = "UserID";
		$CT_FirstName = "FirstName";
		$CT_LastName = "LastName";
		$CT_Email = "Email";
		$CT_Phone = "Phone";

	// Take in the JSON input and connect to the database, returning an error if there is an issue
    $inData = getRequestInfo();
    $conn = new mysqli("db-contact-manager-do-user-13563441-0.b.db.ondigitalocean.com", $Admin_Username, $Admin_Password, $Database_Name, 25060);
	
	if ($conn->connect_error) 
	{
		 returnWithError($conn->connect_error);
	} 
	else
	{
		// Convert the JSON input into variables
		$IN_LastName = $inData["LastName"];
		$IN_FirstName = $inData["FirstName"];
		$IN_Email = $inData["Email"];
		$IN_Phone = $inData["PhoneNumber"];
		$IN_UserID = $inData["UserID"];

		// Insert the Contact into the table using a parameterized statement
		$stmt = $conn->prepare("INSERT into " . $Contact_Table . " (" 
			. $CT_LastName . ", " 
			. $CT_FirstName . ", " 
			. $CT_Email . ", " 
			. $CT_Phone . ", " 
			. $CT_UserID 
			. ") VALUES(?,?,?,?,?)");
		$stmt->bind_param("ssssi", $IN_LastName, $IN_FirstName, $IN_Email, $IN_Phone, $IN_UserID);
		$stmt->execute();
		$stmt->close();
		returnResult();
		$conn->close();
	}
	

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function returnResult()
	{
		$retValue = '{"error":null}';
		header('Content-type: application/json');
		echo $retValue;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		header('Content-type: application/json');
		echo $retValue;
	}
?>