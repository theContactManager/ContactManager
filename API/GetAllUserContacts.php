<?php

	$inData = getRequestInfo();

	$userID = $inData["userID"];
	
	$searchResults = "";
	
	$conn = new mysqli("localhost", "contactManager", "Exceptions123?", "POOP_Project");

	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$sql = "SELECT ContactID,FirstName,LastName,EmailAddress,PhoneNumber" .
			" FROM Contacts WHERE UserID=" . $userID;
			
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
			    $searchResults .= $row["ContactID"] . ',';
				$searchResults .= '"' . $row["FirstName"] . '",';
				$searchResults .= '"' . $row["LastName"] . '",';
				$searchResults .= '"' . $row["EmailAddress"] . '",';
				$searchResults .= '"' . $row["PhoneNumber"] . '",';
			}
		}
		else
		{
			returnWithError( "No Records Found" );
			return;
		}
		$conn->close();
	}
	
	$searchResults = substr($searchResults, 0, -1);
	returnWithInfo( $searchResults );

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $searchResults )
	{
		$retValue = '{"id":1,"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>