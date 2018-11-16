<?php 

// $dsn = new PDO("sqlsrv:Server=127.0.0.1;Database=Year001", "sa", "");
$dsn = 'mysql:dbname=client999;host=127.0.0.1';
$user = 'root';
$password = '';
try {
  //  $dbh = new PDO($dsn, $user, $password);
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();                 // Not needed but a quick connection test
}

$sth = $dbh->query("SELECT * FROM `LINES` WHERE `Date` LIKE '1/5/2018 00:00:00'");  // SQL Statment


$result = $sth->fetchAll(PDO::FETCH_ASSOC); // Fetches the results and columns i.e Colunm = Value 
 
$table = array('tableName' => 'Lines2');   // Declare Table name so the importer knows where to put the data
                                            // Code designed around one statement fits all       
array_push($table, $result);                // Push the data above table to the top of the array 
                                            //REVIEW: to change to a switch statement 

print_r(json_encode($table));               // Encode the data to a Json format
$fp = fopen('sales.json', 'w');             // Create Json File and set to write mode 
fwrite($fp, json_encode(array($table)));    // write out the encoded json to the file
fclose($fp);                                // close the file 


