<?php
  $servername = "127.0.0.1:3306";
  $username = "root";
  $password = "jzhang1030";
  $dbname = "book_restaurant";
  $infostring = json_decode($_POST["infostring"],true);
  $result = "";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    exit;
  }

  // is the customer name in the db
  $customer = "SELECT * FROM customer where cname = '". $infostring['inputname'] ."'";
  $customerresult = $conn->query($customer);
  if ($customerresult->num_rows <= 0) {
    $result = "customer error";
  }
  echo $result;
?>
