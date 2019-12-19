<?php

//register.php

include('database_connection.php');

$form_data = json_decode(file_get_contents("php://input"));

$message = '';
$validation_error = '';

if(empty($form_data->username))
{
 $error[] = 'Username is Required';
}
else
{
 $data[':username'] = $form_data->username;
}

if(empty($form_data->name))
{
 $error[] = 'Name is Required';
}
else
{
 $data[':name'] = $form_data->name;
}

if(empty($form_data->address))
{
 $error[] = 'Address is Required';
}
else
{
 $data[':address'] = $form_data->address;
}

if(empty($form_data->contact))
{
 $error[] = 'Contact No. is Required';
}
else
{
 $data[':contact'] = $form_data->contact;
}

if(empty($form_data->password))
{
 $error[] = 'Password is Required';
}
else
{
 $data[':password'] = password_hash($form_data->password, PASSWORD_BCRYPT);
}

if(empty($error))
{
 $query = "
 INSERT INTO users (username, name, address, contact, password) VALUES (:username, :name, :address, :contact, :password)
 ";
 $statement = $connect->prepare($query);
 if($statement->execute($data))
 {
  $message = 'Registration Completed';
 }
}
else
{
 $validation_error = implode(", ", $error);
}

$output = array(
 'error'  => $validation_error,
 'message' => $message
);

echo json_encode($output);


?>
