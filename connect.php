<?php

$servername = "localhost";  /* enter server name*/
$username = "root";  /* enter server user name*/
$password = "";   /* enter server user password*/
$dbname = "map";   /* enter database name to access table*/

/**
* This function is used for connect to MySql with PDO method.
*/
function connect($host, $db, $user, $password)
{
	$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

	try {
		$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
		return new PDO($dsn, $user, $password, $options);
	} catch (PDOException $e) {
		print_r($e->getMessage());
		die($e->getMessage());
	}
}

/**
* This function is used for fetch device date list based on device names
*/
function fetch_device_data(\PDO $pdo): array
{
	$sql = 'SELECT device_name 
		FROM map_details 
		GROUP BY device_name 
		ORDER BY device_name ASC';
	try {
	   $statement = $pdo->prepare($sql);

	   $statement->execute();
	} catch (PDOException $e) {
	   die($e->getMessage());
	}

	return  $statement->fetchAll(PDO::FETCH_ASSOC);
}

/**
* This function is used for fetch device date list based on device names
*/
function fetch_device_by_data(\PDO $pdo, string $deviceName): array
{
	$sql = 'SELECT device_name, DATE(signal_time) as signal_time
	  	FROM map_details
	  	WHERE device_name LIKE :deviceName Group BY device_name, DATE(signal_time)';
	try {
	  	$statement = $pdo->prepare($sql);

	  	$statement->execute([':deviceName' =>  '%' .$deviceName. '%']);
	} catch (PDOException $e) {
	   	die($e->getMessage());
	}

	return  $statement->fetchAll(PDO::FETCH_ASSOC);
}

/**
* fetch location details list by device name and signal date.
*/
function fetch_location_list(\PDO $pdo, string $deviceName, string $date): array
{
	$sql = 'SELECT longitude, latitude
      		FROM map_details 
      		WHERE device_name LIKE :deviceName and DATE(signal_time) = :signalDate';
	try {
  		$statement = $pdo->prepare($sql);

  		$statement->execute([':deviceName' => '%' . $deviceName . '%', ':signalDate' => $date]);
  
  	} catch (PDOException $e) {
     		die($e->getMessage());
   	}

  	return  $statement->fetchAll(PDO::FETCH_ASSOC);
}

return connect($servername, $dbname, $username, $password);