<!DOCTYPE html>
<?php
  $cid = $_POST['cid']; 
  $rid = $_POST['rid'];
  $inputnumber = $_POST['inputnumber'];
  $inputdate = $_POST['inputdate'];
  $inputtime = $_POST['inputtime'];
?>
<html>
<head>
<title>Order Submit Result</title>
<link rel="stylesheet" href="bootstrap.css">
<script type="text/javascript" src="jquery-3.2.1.js"></script>
</head>
<body>
<h1 align= "center">Order Results</h1>
<?php
  $servername = "127.0.0.1:3306";
  $username = "root";
  $password = "jzhang1030";
  $dbname = "book_restaurant";
  $ordercount = 0;

  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
      exit;
  }

  $order = "SELECT COUNT(*) AS ordernumber FROM booking";
  $orderresult = $conn->query($order);
  if ($orderresult->num_rows > 0) {
    while ($row = $orderresult->fetch_assoc()) {
      $ordercount = $row['ordernumber'];
    }
  }
  $ordercount++;

  // restaurant name
  $restaurantname = "";
  $rinfo = "SELECT rname FROM restaurant WHERE rid ='".$rid."'";
  $rresult = $conn->query($rinfo);
  if ($rresult->num_rows > 0) {
    while ($row = $rresult->fetch_assoc()) {
      $restaurantname = $row['rname'];
    }
  }

  echo "<div class='container'>
  <div class='row'>
  <div class='center-block' style='width:100%;margin-top:5%;'>";
  // insert the new order into booking
  $neworder = "INSERT INTO booking (bid, cid, rid, btime, quantity) VALUES ('". $ordercount ."','".
  $cid ."', '". $rid ."','". $inputdate." ".$inputtime. "','". $inputnumber ."')";
  $insert = $conn->query($neworder);
  if ($insert === TRUE) {
    echo "<p>Your order is submitted successfully.</p><br/><p>New Order:</p>";
    echo "<table class= 'table'><tr><th>RestaurantId</th><th>Name</th><th>Time</th>
    <th>Seats</th></tr><tr>";
    echo "<td>". $rid ."</td>";
    echo "<td>". $restaurantname ."</td>";
    echo "<td>". $inputdate." ".$inputtime ."</td>";
    echo "<td>". $inputnumber ."</td>";
    echo "</tr></table>";
  } else {
    echo "Error: " . $neworder . "<br>" . $conn->error;
    exit;
  }

  // history order
  $oldorder = "SELECT * FROM booking NATURAL JOIN restaurant WHERE cid='".$cid."' AND bid<>'".$ordercount."'";
  $query = $conn->query($oldorder);
  if ($query->num_rows > 0) {
    echo "<br/><p>History Orders:</p>";
    echo "<table class= 'table'><tr><th>RestaurantId</th><th>Name</th><th>Time</th>
    <th>Seats</th></tr>";
    while ($row = $query->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row['rid'] . "</td>";
      echo "<td>" . $row['rname'] . "</td>";
      echo "<td>" . $row['btime'] . "</td>";
      echo "<td>" . $row['quantity'] . "</td>";
      echo "</tr>";
    }
    echo "</table></div>";
  } else {
    echo "<br/><p>No history orders.</p>";
  }

  $conn->close();
?>
</body>
</html>
