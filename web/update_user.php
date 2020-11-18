<?php

$id = intval($_REQUEST['id']);
$author = htmlspecialchars($_REQUEST['author']);
$title = htmlspecialchars($_REQUEST['title']);
$genre = htmlspecialchars($_REQUEST['genre']);
$location = htmlspecialchars($_REQUEST['location']);
$notes = htmlspecialchars($_REQUEST['notes']);

include 'conn.php';
$sql = "update books set author=\"$author\",title=\"$title\",genre=\"$genre\",location=\"$location\", notes=\"$notes\" where id=$id";
$result = @mysql_query($sql);
if ($result){
	echo json_encode(array(
		'id' => $id,
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
