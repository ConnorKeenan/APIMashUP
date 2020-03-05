<!DOCTYPE html>
<html>
<head>
<title>Table with database</title>
<style>
table {
	font-family: arial, sans-serif;
	border-collapse: collapse;
	widyh: 100%;
}
td, th {
	border: 1px solid #dddddd
	text-align: left;
	padding: 8px;
}
tr:nth-child(even) {
	background-color: #dddddd;
}
</style>
</head>
<body>
<table>
<tr>
<th>id</th>
<th>Place</th>
<th>Latitude</th>
<th>Longitude</th>
<th>Address</th>
<th>Rating</th>
</tr>

<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT id, name, lat, lng, address, rating FROM Places";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
// output data of each row
echo "<table>";
while($row = $result->fetch_assoc()) {
//print(json_encode($row)); //prints a json equivilant for every row
echo "<tr><td>" . $row["id"]. 
"</td><td>" . $row["name"] . 
"</td><td>" . $row["lat"] . 
"</td><td>" . $row["lng"] . 
"</td><td>" . $row["address"] . 
"</td><td>" . $row["rating"]. "</td></tr>";
$marks = array("type"=>"Feature", "markerType" => 'resizedbluemarker.png', "properties" => array("rating"=>$row["rating"], "place" => $row["name"], "address" => $row["address"]),
"geometry" => array("type" => "Point", "coordinates" => array((double)$row["lat"], (double)$row["lng"])), "id" => $row["id"]);
$test[] = $marks;
}
echo "</tr></table>";
} else 
{ 
echo "0 results";
 }

$sql = "SELECT county_id, county_name, avg_value, latitude, longitude, state FROM counties";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	echo "<table>";
	echo "<th>id</th>";
	echo "<th>County</th>";
	echo "<th>Latitude</th>";
	echo "<th>Longitude</th>";
	echo "<th>State</th>";
while($row = $result->fetch_assoc()) {
//print(json_encode($row)); //prints a json equivilant for every row
echo "<tr><td>" . $row["county_id"]. 
"</td><td>" . $row["county_name"] . 
"</td><td>" . $row["avg_value"] . 
"</td><td>" . $row["latitude"] . 
"</td><td>" . $row["longitude"] . 
"</td><td>" . $row["state"]. "</td></tr>";
$marks = array("type"=>"Feature", "markerType" => 'redmarker.png', "properties" => array("avg_value"=>$row["avg_value"], "place" => $row["county_name"], "state" => $row["state"]),
"geometry" => array("type" => "Point", "coordinates" => array((double)$row["latitude"], (double)$row["longitude"])), "county_id" => $row["county_id"]);
$test[] = $marks;
}

echo "</tr></table>";
} else 
{ 
echo "0 results";
 }

$master = array("type"=>"FeatureCollection", 
                "features"=>$test);
echo json_encode($master);

$fh = fopen("testALLJSON.json", 'w')
      or die("Error opening output file");
fwrite($fh, "eqfeed_callback(");
fwrite($fh, json_encode($master));


fwrite($fh, ");");
fclose($fh); 

$conn -> close();
?>
</table>
</body>
</html>