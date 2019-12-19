<?php

//fetch_data.php

include('database_connection.php');

$query = "SELECT * FROM books WHERE status = 'Available' ORDER BY id desc";
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