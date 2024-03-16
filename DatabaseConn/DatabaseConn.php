<?php 
$conn = mysqli_connect("localhost", "root", "", "db_rumah_sakit");
// $conn = mysqli_connect("localhost", "id21976915_rizal", "Rizal123$$", "id21976915_db_rumah_sakit");

	function query ($query) {
	global $conn;
	$result = mysqli_query ($conn , $query);
	$rows = [];
	while ($row = mysqli_fetch_assoc ($result)) {
	$rows[] = $row;
	}	
		return $rows; 
}
?>