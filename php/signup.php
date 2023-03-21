<?php
	// Replace the variables to match your database setup:

	$Admin_Username = "doadmin";
	$Admin_Password = "AVNS_uREDxrVmOqodnvyI1YS";
	$Database_Name = "CM_db";

	$Account_Table = "Users";		// Users	
		$AT_Username = "Login"; 	// Login
		$AT_Password = "Password"; 	// Password


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

		// Check if the account already exists
		if ($result->fetch_assoc())
		{
			returnWithError("Already Exists");
		}
		else
		{
			// Generate a hash for the password
			$IN_Password_Hash = password_hash($IN_Password, PASSWORD_DEFAULT);

			// Insert the username and hashed password into the database
			$stmt = $conn->prepare("INSERT into " . $Account_Table . " (" . $AT_Username . ", " . $AT_Password . ") VALUES (?,?)");
			$stmt->bind_param("ss", $IN_Username, $IN_Password_Hash);
			$stmt->execute();

			$stmt->close();
			returnResult();
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