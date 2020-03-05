<!DOCTYPE html>
<html>
	<head>
		<title>State Search Results</title>
		<style>
			table 
			{
				font-family: arial, sans-serif;
				border-collapse: collapse;
				width: 100%;
			}
			
			td, th 
			{
				border: 1px solid #dddddd;
				text-align: center;
				padding: 8px;
			}
			
			tr:nth-child(even) 
			{
				background-color: #dddddd;
			}
		</style>
	</head>
	<body>
		<table>
			<tr>
			<th>county_ID</th>
			<th>County Name</th>
			<th>Average Home Value (USD)</th>
			<th>County Latitude</th>
			<th>County Longitude</th>
			<th>US State</th>
			</tr>

<?php
	//Databse connection credentials
	$server = '';
	$username = '';
	$password = '';
	$databaseName = '';
	$connection = new mysqli ($server, $username, $password, $databaseName);
	
	if ($connection->connect_error) {
		die("Connectioon failed: " . $connection->connect_error);
	}else{
		echo "Minty connection, bro." . "<br><br>";
	}

	//Temp Code to Create a Database
	/*$sql = "CREATE TABLE counties (
	county_ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
	county_name varchar(100) NOT NULL,
	avg_value int(11) NOT NULL,
	latitude decimal(12,9) NOT NULL,
	longitude decimal(12,9) NOT NULL,
	state varchar(2) NOT NULL
	)"; */
	
	//clears the table in the database
	$sql = "TRUNCATE TABLE counties";
	$sql = $connection->prepare($sql);
	$sql -> execute();
	
	//link to a google map of the selcted county
	$map_link = 'https://www.google.com/maps/search/?api=1&query=';
	
	//retrive the state input from the regionSearch.html <form>
	$state = $_REQUEST['state'];
	
	//Zillow-provided API Key
	$ZWSID = '[key here]';
	
	//Zillow XML file link
	$link = 'http://www.zillow.com/webservice/GetRegionChildren.htm?zws-id='
	. $ZWSID . '&state=' . $state . '&childtype=county';
	
	//read file into a string
	$xmlfile = file_get_contents($link);
	
	//convert xml string to an object
	$xml_object = simplexml_load_string($xmlfile);
	
	//convert to JSON
	$json_conversion = json_encode($xml_object);
	
	//convert to associative array
	$array = json_decode($json_conversion, true);
	
	//Trim array to get desired data
	$regionArray = $array['response']['list']['region'];

	//Number of times $stmt has executed
	$exe_counter = 0;
	
	foreach($regionArray as $row)
	{
		//assiging Array values to local variables
		$countyName = $row['name'];
		$zindex = $row['zindex'];
		$latitude = $row['latitude'];
		$longitude = $row['longitude'];
		
		//preparing SQL statement
		$sql = "INSERT INTO counties (county_name, avg_value, latitude, longitude, state) 
			VALUES ('$countyName', '$zindex', '$latitude', '$longitude', '$state');";

		$stmt = $connection->prepare($sql);
		
		//binding local variables to SQL Table columns
		$stmt->bind_param(':county_name', $countyName);
		$stmt->bind_param(':avg_value', $zindex);
		$stmt->bind_param(':latitude', $latitude);
		$stmt->bind_param(':longitude', $longitude);
		
		$stmt->execute();
		
		$exe_counter += 1;
	}
	$stmt->close();

	//preparing SQL statement to select and display ALL newly inserted data
	$sql_2 = "SELECT * FROM counties";
	$result = $connection->query($sql_2);
	
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) 
		{
			//print each element in their respective cell, one row at a time, iterating though the columns.
			echo "<tr><td>" . $row["county_ID"] . 
				"</td><td><a target='_blank' href='" . $map_link . $row["county_name"] . ", " . $row["state"] . "'>" . $row["county_name"] . "</a>" .
				"</td><td>" . $row["avg_value"] . 
				"</td><td>" . $row["latitude"] . 
				"</td><td>" . $row["longitude"] . 
				"</td><td>" . $row["state"]. 
				"</td></tr>";
		}
		echo "</table>";
	} 
	else 
	{ 
		echo "0 results";
	}
	
	

	$connection->close();
	
	echo "<br>" . "The INSERT query has been executed " . $exe_counter . " times." . "<br>";
	
	echo "If the highest county_ID is not equal to the number of executions, the average home value for some county was not recored by Zillow and is not useful for our project." . "<br>";
	
	echo $link . "<br>";
	
	echo "<br>" . "DATABASE REPOPULATED!";

	exit;	
?>
		</table>
	</body>
</html>
