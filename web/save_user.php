<?php

$author = htmlspecialchars($_REQUEST['author']);
$title = htmlspecialchars($_REQUEST['title']);
$genre = htmlspecialchars($_REQUEST['genre']);
$location = htmlspecialchars($_REQUEST['location']);
$notes = htmlspecialchars($_REQUEST['notes']);

include 'conn.php';

$sql = "insert into books(author,title,genre,location,notes) values(\"$author\",\"$title\",\"$genre\",\"$location\",\"$notes\")";
$result = @mysql_query($sql);
if ($result){
	echo json_encode(array(
		'id' => mysql_insert_id(),
		'author' => $author,
		'title' => $title,
		'genre' => $genre,
		'location' => $location,
		'notes' => $notes
	));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
