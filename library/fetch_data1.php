<?php

//fetch_data.php

include('database_connection.php');
$condition = '';

$query = "SELECT * FROM books ORDER BY id desc";
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