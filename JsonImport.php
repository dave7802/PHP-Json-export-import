<?php
// REVIEW: To create a REST CRUD API using the belew. Only UPDATE / INSERT should be needed, we do not need to read values
// at the moment, This will be used for ORSL to add new lines to the database from a clients machine. 

// REVIEW: To add into statment to update on duplicated key, So we can post up the same record and it will update
// This is so we can ammend sales or stock records and it will replace the value without inserting a duplicated value

$connect = new PDO("mysql:host=localhost;dbname=client999", "root", "");  // DB Connection 
$filename = "header.json"; // Wont use as we will use POST Data    $data = json_decode(file_get_contents('php://input'), true);

$data = file_get_contents($filename);   // Read Content of file, wont be using. 

$array = json_decode($data, true);      // Build array from data 
foreach($array as $set)        // Run foreach on Top Node (For example, Table content Lines, and Headers)
{
    $TableName = $set['tableName'];  // Fetch the Tablename from the the Json Node
    if(count($set['0']) > 0) { /* typical to use count() for measuring array size */    // If the count is greater than 0 then run  [0] is the Node 
        $Columnlist = array_keys($set["0"][0]);  //  Get list of column names
  //     echo implode($Columnlist, ',');
        $query = "INSERT INTO `$TableName` \n";   // Insert Data into Tablename Defined from Json Array Above
        $query .= "(" . implode(", ", $Columnlist) . ")\nVALUES\n";  // Build Query with Column names
        $placeholders = implode(",", array_fill(0, count($Columnlist), "?"));  // ? is used as a holder, populated from the statement below 
        $query .= "($placeholders)"; // Placeholder from Statement below, as foreach Node it will populate the Values
        $statment = $connect->prepare($query); // Prepare the query to be ran  
        if (!$statment) {
            echo "Prepare error: $query<br/>" . $connect->errorInfo()[2];
            continue; /* give up, go to next record set */   //REVIEW: add in logger / email reporting
        }
        //  Go through the rows for this table. and execute each row into the database
        foreach($set['0'] as $row) {
            $row = array_values($row);          // collect values from array 
            $result = $statment->execute($row); // loop though each row and post values
            if ($result === TRUE) {
                echo "Invoice Number: " .$row[4]." Line:  " .$row[6]. " Imported into Table: <b>".$TableName."</b>  Successfully<br>";
            } else {
            echo   "Execute error:". $statment->queryString."<br/>" . $statment->errorInfo()[2];  // Eror handling / email 
            }
        }
    } else {
        echo "<p>No rows to insert for" .$TableName."</p>";
    }
}