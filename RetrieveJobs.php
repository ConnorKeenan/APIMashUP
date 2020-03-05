<!DOCTYPE html>
<html>
<head>
<title>Table with database</title>
<style>
table {
	font-family: arial, sans-serif;
	border-collapse: collapse;
	width: 100%;
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


$sql = "SELECT id, location, type, url FROM Jobs";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {
echo "<tr><td>" . $row["id"]. 
"</td><td>" . $row["location"] . 
"</td><td>" . $row["type"] . 

"</td><td><a href='" . $row["url"]. "'>" . $row["url"] . "</a></td></tr>";
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