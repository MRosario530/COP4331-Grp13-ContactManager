<?php
	// Replace the variables to match your database setup:

	$Admin_Username = "doadmin";
	$Admin_Password = "AVNS_uREDxrVmOqodnvyI1YS";
	$Database_Name = "CM_db";

	$Account_Table = "Users";			// Name of the table storing accounts
		$AT_UserID = "ID";
		$AT_Username = "Login";		// Name of the username column
		$AT_Password = "Password";		// Name of the password column



    $inData = getRequestInfo();
    $conn = new mysqli("db-contact-manager-do-user-13563441-0.b.db.ondigitalocean.com", $Admin_Username, $Admin_Password, $Database_Name, 25060);

	if ($conn->connect_error) 
	{
		returnWithError($conn->connect_error);
	} 
	else
	{
		// Get input from javascript
		$IN_Username = $inData["username"];
        $IN_Password = $inData["password"];

		// Send a database request to get the password hash of the input username
		$query = $conn->prepare("SELECT * FROM " . $Account_Table . " WHERE " . $AT_Username . "=?");
		$query->bind_param("s", $IN_Username);
		$query->execute();
		$result = $query->get_result();

		// If an account was found
		if ($retrievedInfo = $result->fetch_array())
		{
			// Verify the password
			if (password_verify($IN_Password, $retrievedInfo[$AT_Password]))
			{
				returnResult( $retrievedInfo[$AT_UserID] );
			}
			else
			{
				returnWithError("Incorrect password");
			}
		}
   		else
   		{
     		returnWithError("Username not found");
   		}

		$query->close();
		$conn->close();
	}
	

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function returnResult( $id )
	{
		$retValue = '{"id":"' . $id . '"}';
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