<?php

//insert.php

include('database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));

$error 		= '';
$message 	= '';
$validation_error = '';
$title 		= '';
$author 	= '';
$genre		= '';
$section	= '';

if($form_data->action == 'fetch_single_data')
{
	$query = "SELECT * FROM books WHERE id='".$form_data->id."'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output['title'] 	= $row['title'];
		$output['author'] 	= $row['author'];
		$output['genre'] 	= $row['genre'];
		$output['section']  = $row['section'];
	}
}
elseif($form_data->action == "Delete")
{
	$query = "
	DELETE FROM books WHERE id='".$form_data->id."'
	";
	$statement = $connect->prepare($query);
	if($statement->execute())
	{
		$output['message'] = 'Book Deleted';
	}
}
elseif($form_data->action == "UpdateStat")
{
	$message = '';
	if($form_data->stat == 'Borrowed'){
		$message = 'Book Borrowed.';
	}else{
		$message = 'Book Returned.';		
	}
	$query = "
	UPDATE books SET status = '".$form_data->stat."', user_id = '".$form_data->user_id."' WHERE id='".$form_data->id."'";
	$statement = $connect->prepare($query);
	if($statement->execute())
	{
		$output['message'] = $message;
	}
}
else
{
	if(empty($form_data->title))
	{
		$error[] = 'Title is Required';
	}
	else
	{
		$title = $form_data->title;
	}

	if(empty($form_data->author))
	{
		$error[] = 'Author is Required';
	}
	else
	{
		$author = $form_data->author;
	}

	if(empty($form_data->genre))
	{
		$error[] = 'Genre is Required';
	}
	else
	{
		$genre = $form_data->genre;
	}

	if(empty($form_data->section))
	{
		$error[] = 'Section is Required';
	}
	else
	{
		$section = $form_data->section;
	}

	if(empty($error))
	{
		if($form_data->action == 'Submit')
		{
			$data = array(
				':title'		=>	$title,
				':author'		=>	$author,
				':genre'		=>  $genre,
				':section'		=>  $section,
				':status'		=>  'Available'
			);
			$query = "
			INSERT INTO books 
				(title, author, genre, section, status) VALUES 
				(:title, :author, :genre, :section, :status)
			";
			$statement = $connect->prepare($query);
			if($statement->execute($data))
			{
				$message = 'Book added.';
			}
		}
		if($form_data->action == 'Update')
		{
			$data = array(
				':title'	=>	$title,
				':author'	=>	$author,
				':genre'	=>	$genre,
				':section'	=>	$section,
				':id'			=>	$form_data->id
			);
			$query = "
			UPDATE books 
			SET title = :title, author = :author, genre = :genre, section = :section
			WHERE id = :id
			";

			$statement = $connect->prepare($query);
			if($statement->execute($data))
			{
				$message = 'Book Updated.';
			}
		}
	}
	else
	{
		$validation_error = implode(", ", $error);
	}

	$output = array(
		'error'		=>	$validation_error,
		'message'	=>	$message
	);

}



echo json_encode($output);

?>