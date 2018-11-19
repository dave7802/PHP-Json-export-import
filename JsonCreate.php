<?php 

//$mssqldriver = '{SQL Server}'; 
//$mssqldriver = '{SQL Server Native Client 11.0}';
$mssqldriver = 'ODBC Driver 11 for SQL Server';

print_r(PDO::getAvailableDrivers()); 

$hostname='DAVIDLAPTOP';
$dbname='Year001';
$username='sa';
$password='';
$dbh = new PDO("odbc:Driver=$mssqldriver;Server=$hostname;Database=$dbname", $username, $password);

try {
  //  $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();                 // Not needed but a quick connection test
}
                      // Query for Lines - All but Order as Order needs to be wrapped in [] because its a command.... ;@
/* $sth = $dbh->query("SELECT Branch, Date, Week, Period, Invoice_No, Invoice_Reference, Line_No, Till_No, Line_Status, Operator, Supervisor, Sales_Person, Collection, Stock_Code, Barcode, Line_Quantity, Weight, Weight_Unit, Man_Weighed, 
                         Unit_ID, Text_Entry, Text_Entered, Return_Quantity, Return_Inv, Return_Date, Return_Line, Line_Price_Band, Original_Sell, Actual_Sell, Cost, Vat_Rate, Vat_Changed, Discount_Rate, Value_Goods, Value_VAT, Value_Sale, 
                         Value_Cost, Value_Discounts, Value_End_Sale_Disc, Value_Loyalty_Disc, Value_Promotions, Value_Promotions_Apportion, Promotion_Method, Promotion_Type, Promotion_Scheme, Promotion_Rec_Text, Returns_Voucher, 
                         Voucher_No, Promo_No, Mix_Match_No, Kit_Code, Kit_Start, Kit_End, Kit_Text, Kit_ID, Kit_Price, Price_Override_Amount, Price_Overrided, Discounted, Voided, Price_Over_Reason_1, Price_Over_Reason_2, 
                         Price_Over_Reason_3, Discounted_Reason_1, Discounted_Reason_2, Discounted_Reason_3, Return_Reason_1, Return_Reason_2, Return_Reason_3, Dumped_Reason_1, Dumped_Reason_2, Dumped_Reason_3, 
                         Account_No, Sub_Account_No, Customer_Account, Sub_Customer_Account, Cust_Type_ID, Super_Department_ID, Department_ID, Group_ID, Sub_Group_ID, Retail_Location_ID, Retail_Sub_Location_ID, 
                         Retail_Location_Position, Range_ID, Sub_Range_ID, Category1_ID, Category2_ID, Category3_ID, Category4_ID, Entry_Method, Extra_Data, Extra_Data_Type, Delivery_Charge, Weight_Sales, Linked_Stock_Code, Comment, 
                         End_Sale_Discount_Rate, Loyalty_Discount_Rate, Order_Qty, Deliver, Deliver_Qty, Date_Time_Stamp FROM LINES WHERE Date = '2018-05-01 00:00:00'");  // SQL Statment
*/

$sth = $dbh->query("SELECT * from Header") ;

$result = $sth->fetchAll(PDO::FETCH_ASSOC); // Fetches the results and columns i.e Colunm = Value 

// $name = $sth->getColumnMeta(0)["table"];  // Fetch the Name of the tabe from the above query. 
 
$table = array('tableName' => 'Header');   // Declare Table name so the importer knows where to put the data
                                            // Code designed around one statement fits all       
array_push($table, $result);                // Push the data above table to the top of the array 
                                            //REVIEW: to change to a switch statement 

print_r(json_encode($table));               // Encode the data to a Json format
$fp = fopen('header.json', 'w');             // Create Json File and set to write mode 
fwrite($fp, json_encode(array($table)));    // write out the encoded json to the file
fclose($fp);                                // close the file 


