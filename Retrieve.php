<?php

//Grabs connection from file
require_once('./Connect.php');
$dbh = ConnectDB();

echo "1";
// Start XML file, create parent node
$doc = "./Markers.xml";
$node = $doc->create_element("markers");
$parnode = $doc->append_child($node);
echo "2";

// Set the active MySQL database
$db_selected = mysql_select_db('ckcapstone', $dbh);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}
echo "3";
// Select all the rows in the markers table
$query = "SELECT * FROM Places WHERE 1";
$result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}
echo "4";
header("Content-type: text/xml");
echo "5";
// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  // Add to XML document node
  $node = $doc->create_element("marker");
  $newnode = $parnode->append_child($node);
echo "0";
  $newnode->set_attribute("id", $row['id']);
  $newnode->set_attribute("name", $row['name']);
  $newnode->set_attribute("lat", $row['lat']);
  $newnode->set_attribute("lng", $row['lng']);
  $newnode->set_attribute("address", $row['address']);
  $newnode->set_attribute("rating", $row['rating']);
}
echo "6";
$xmlfile = $doc->dump_mem();
echo $xmlfile;
echo "7";
?>