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
		try
		{
			// Get input from javascript
			$IN_UserID = $inData["UserID"];
			$IN_ContactID = $inData["ContactID"];

			$IN_NEW_FirstName = $inData["FirstName"];
			$IN_NEW_LastName = $inData["LastName"];
			$IN_NEW_Email = $inData["Email"];
			$IN_NEW_Phone = $inData["Phone"];
		}
		catch (Exception $e) 
		{
			returnWithError("Invalid Input");
		}

		try
		{
			// Request a contact
			$query = $conn->prepare("SELECT * FROM " . $Contact_Table . " WHERE " . $CT_ContactID . " = ? AND " . $CT_UserID . " = ?");
			$query->bind_param("ss", $IN_ContactID, $IN_UserID);
			$query->execute();
			$result = $query->get_result();
		}
		catch (Exception $e) 
		{
			returnWithError("Error Finding Contact");
		}

		try
		{
			// Check if the contact exists
			if ($result->fetch_assoc())
			{
				// Update the contact
				$stmt = $conn->prepare("UPDATE " . $Contact_Table . " SET " . $CT_FirstName . " = ?, " . $CT_LastName . " = ?, " . $CT_Email . " = ?, " . $CT_Phone . " = ? WHERE " . $Contact_Table . "." . $CT_ContactID . " = ?");
				$stmt->bind_param("sssss", $IN_NEW_FirstName, $IN_NEW_LastName, $IN_NEW_Email, $IN_NEW_Phone, $IN_ContactID);
				$stmt->execute();

				$stmt->close();
				returnResult();	
			}
			else
			{
				returnWithError("Contact Not Found");
			}
		}
		catch (Exception $e)
		{
			returnWithError("Error Updating Contact");
		}

		$query->close();
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