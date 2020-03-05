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


$sql = "SELECT id, name, lat, lng, address, rating FROM Places";
$result = $conn->query($sql);

/* 
$temp="SELECT id, name, lat, lng, address, rating FROM Places";
while($ro=mysql_fetch_assoc($temp)){
   print(json_encode($ro)); 
} */


	/* while($row = $result->fetch_assoc()) 
    $test[] = $row; 
print json_encode($test); */


/* $jsontext = "[";
foreach($data as $key => $value) {
    $jsontext .= "{oV: '".addslashes($key)."', oT: '".addslashes($value)."'},";
}
$jsontext = substr_replace($jsontext, '', -1); // to get rid of extra comma
$jsontext .= "]"; */

/*  $lat = 5;
 $lng = 7;
     $marks = array("type"=>"Feature",
               "geometery"=>array("type" => "Point", "coordinates:" => array($lat, $lng)),
                "properties" => array("prop"=>"this"));
 
 $test[] = $marks;
 $test[] = $marks;
 
 
 $master = array("type"=>"FeatureCollection", 
                "features"=>array($test));
echo json_encode($master); */



//file_put_contents("testJSON.json", json_encode($result));

if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {
	//$test[] = $row; 
//print(json_encode($row)); //prints a json equivilant for every row
echo "<tr><td>" . $row["id"]. 
"</td><td>" . $row["name"] . 
"</td><td>" . $row["lat"] . 
"</td><td>" . $row["lng"] . 
"</td><td>" . $row["address"] . 
"</td><td>" . $row["rating"]. "</td></tr>";
$marks = array("type"=>"Feature", "properties" => array("rating"=>$row["rating"], "place" => $row["name"], "address" => $row["address"]),
"geometry"=>array("type" => "Point", "coordinates" => array((double)$row["lat"], (double)$row["lng"])), "id" => $row["id"]);
$test[] = $marks;

//$json string = {$row["name"], 
}
echo "</table>";
} else 
{ 
echo "0 results";
 }
//print json_encode($test);

$master = array("type"=>"FeatureCollection", 
                "features"=>$test);
echo json_encode($master);

$fh = fopen("testJSON.json", 'w')
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