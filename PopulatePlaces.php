<?PHP
//Grabs connection from file
require_once('./Connect.php');
$dbh = ConnectDB();

//Creates table Places
/*$sql = "DROP TABLE IF EXISTS Places;
CREATE TABLE Places (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(60) NOT NULL,
lat VARCHAR(40) NOT NULL,
lng VARCHAR(40) NOT NULL,
address VARCHAR(100) NOT NULL,
rating DECIMAL(6, 2) NOT NULL
)";
*/
$sql = "Truncate Table Places";

$sql = $dbh->prepare($sql);
$sql->execute();

$latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];
$radius = $_REQUEST['radius'];
$type = $_REQUEST['type'];
$keyword = $_REQUEST['keyword'];
$key = 'AIzaSyBp5EZUdopVi9BeUSylSkKURbxhj4Ahjsk';

//Creates link to API to receive query for JSON file
$url = 'https://' .
        'maps.googleapis.com/maps/api/place/nearbysearch/json?' .
        'location=' . $latitude . ',' . $longitude .
        '&radius=' . $radius .
		'&type=' . $type .
		'&keyword' . $keyword .
		'&key=' . $key;		
//print ($url);

//Grabs JSON into String
$json = file_get_contents($url);
//Decodes JSON string to Array
$array = json_decode($json, true); //Four elements
//Grabs inner array with desired data, "results"
$myArray = $array['results'];
//print_r($myArray);
//print_r($myArray[0]['name']);


$fh = fopen("testJSON.json", 'w')
      or die("Error opening output file");
fwrite($fh, json_encode($myArray));
fclose($fh); 

//Prepared statement to execute within for each loop
//This inserts every entry into the database
$sql = $dbh->prepare("INSERT INTO Places (name, lat, lng, address, rating) 
VALUES (:name, :lat, :lng, :address, :rating)");
$sql->bindParam(':name', $name);
$sql->bindParam(':lat', $lat);
$sql->bindParam(':lng', $lng);
$sql->bindParam(':address', $address);
$sql->bindParam(':rating', $rating);

//Counts elements
//$count = 1;
foreach($myArray as $item)
{
//Variable assignment to redeem parameter binding	
$name = $item["name"];
$lat = $item["geometry"]["location"]["lat"];
$lng = $item["geometry"]["location"]["lng"];
$address = $item["vicinity"];
$rating = $item["rating"];
//Executes prepared statement
$sql->execute();

//Prints data onto webpage
/*echo $count, ":"; 
echo nl2br("\n");
print_r($name);
echo nl2br("\n");
print_r($rating);
echo nl2br("\n");
print_r($lat);
echo nl2br("\n");
print_r($lng);
echo nl2br("\n");
print_r($address);
echo nl2br("\n");
echo nl2br("\n\n");	
$count++;	*/
}
$sql->close();
?>