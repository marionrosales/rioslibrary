<?php

//fetch_data.php

include('database_connection.php');
$user_id = $_GET['id'];
$query = "SELECT * FROM books WHERE status = 'Borrowed' AND user_id = '$user_id' ORDER BY id desc";
$statement = $connect->prepare($query);
if($statement->execute())
{
	while($row = $statement->fetch(PDO::FETCH_ASSOC))
	{
		$data[] = $row;
	}

	echo json_encode($data);
}

?>