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

        
    $inData = getInput();
    $conn = new mysqli("db-contact-manager-do-user-13563441-0.b.db.ondigitalocean.com", $Admin_Username, $Admin_Password, $Database_Name, 25060);

	if ($conn -> connect_error) {
		returnError($conn -> connect_error);
		return;
	} 

    // Get input from javascript
    $searchCount = 0;
    $IN_UserID = $inData["UserID"];
    $IN_Offset = $inData["Start"];
    $IN_Limit = $inData["End"] - $IN_Offset;
    

    if ($IN_Limit <= 0)
    {
        returnError("Invalid end index. Must be greater than start index.");
    }

    $query = $conn->prepare("SELECT * FROM " . $Contact_Table .
        " WHERE " . $CT_UserID . " = ?" .
        " ORDER BY " . $CT_ContactID . 
        " LIMIT " . $IN_Limit . 
        " OFFSET " . $IN_Offset
    );
    
    $query->bind_param("s", $IN_UserID);
    $query->execute();
    $result = $query->get_result();

    // Save each result to an array
    while($row = $result->fetch_assoc())
    {
        if( $searchCount > 0 )
        {
            $searchResults .= ",";
        }
        $searchCount++;
        $searchResults .= '{"id":"' . $row[$CT_ContactID] . '", "firstName":"' . $row[$CT_FirstName] . '", "lastName":"' . $row[$CT_LastName] . '", "email":"' . $row[$CT_Email] . '", "phone":"' . $row[$CT_Phone] . '"}';
    }

    // Return results if there were any.
    if( $searchCount == 0 )
    {
        returnWithError( "No Records Found" );
    }
    else
    {
        returnResult( $searchResults );
    }

    $query->close();
    $conn->close();


	function getInput()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

    function returnError( $err )
    {
        $retValue = '{"error":"' . $err . '"}';
        
        header('Content-type: application/json');
        echo $retValue;
    }
	
	function returnResult( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . ']}';
       
        header('Content-type: application/json');
		echo $retValue;
	}
?>