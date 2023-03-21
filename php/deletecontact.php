<?php

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



	$inData = getRequestInfo();
    $conn = new mysqli("db-contact-manager-do-user-13563441-0.b.db.ondigitalocean.com", $Admin_Username, $Admin_Password, $Database_Name, 25060);

	if ($conn->connect_error) 
	{
		 returnWithError($conn->connect_error);
	} 
	else
	{
		// Get input from javascript
		$IN_ContactID = $inData["ContactID"];

		// Delete the Contact from the table through the contact's ID number
		$stmt = $conn->prepare("DELETE FROM " . $Contact_Table . " WHERE " . $CT_ContactID . " = ?");
		$stmt->bind_param("i", $IN_ContactID);
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