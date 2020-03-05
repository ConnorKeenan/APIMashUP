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
<th>Id</th>
<th>Username</th>
<th>Password</th>
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


$sql = "SELECT county_ID, county_name, avg_value, latitude, longitude, state FROM counties";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {
echo "<tr><td>" . $row["county_ID"]. 
"</td><td>" . $row["county_name"] . 
"</td><td>" . $row["avg_value"] . 
"</td><td>" . $row["latitude"] . 
"</td><td>" . $row["longitude"] . 
"</td><td>" . $row["state"]. "</td></tr>";
}
echo "</table>";
} else 
{ 
echo "0 results";
 }

$conn -> close();
?>
</table>
</body>
</html>