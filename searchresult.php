<!DOCTYPE html>
<?php
  // get data from form
  $inputname = $_POST['inputname'];
  $inputnumber = $_POST['inputnumber'];
  $inputkeyword = trim($_POST['inputkeyword']);
  $inputdate = $_POST['inputdate'];
  $inputtime = substr($_POST['inputtime'], 0, strpos($_POST['inputtime'],":")).":00:00";
?>
<html>
<head>
<title>Search Result</title>
<link rel="stylesheet" href="bootstrap.css">
<script type="text/javascript" src="jquery-3.2.1.js"></script>
<script type="text/javascript">
function goBack() {
  var url = document.location.toString();
  var homepage = url.substring(0,url.lastIndexOf("/"))+"/home.php";
  window.location.href = homepage;
}
</script>
</head>
<body>
<h1 align= "center">Search Results</h1>
<?php
  $servername = "127.0.0.1:3306";
  $username = "root";
  $password = "jzhang1030";
  $dbname = "book_restaurant";
  $customerinfo = array(array());
  $customercount = 0; 
  $restaurantinfo = array(array()); 
  $restaurantcount = 0;

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
      exit;
  }


  $customersql = "SELECT * FROM customer where cname = '". $inputname ."'";
  $customer = $conn->query($customersql);
  if ($customer -> num_rows > 0) {
    while ($row = $customer->fetch_assoc()) {
      $customerinfo[$customercount][0] = $row['cid'];
      $customerinfo[$customercount][1] = $row['cname'];
      $customercount++;
    }
  } else {
    exit;
  }

  $ressql = "SELECT r.rid AS rid, rname, raddress, description,
  (CASE WHEN seatsnum IS NULL THEN capacity ELSE capacity-seatsnum END) AS lefts
  FROM (SELECT * FROM restaurant WHERE description LIKE '%" .$inputkeyword. "%' OR rname LIKE '%".$inputkeyword."%') AS r
  LEFT JOIN (SELECT rid, SUM(quantity) AS seatsnum FROM booking WHERE btime ='". $inputdate." ".$inputtime."' GROUP BY rid) AS b
  ON r.rid = b.rid WHERE (seatsnum IS NOT NULL AND capacity-seatsnum >=".$inputnumber.
  ") OR (seatsnum IS NULL AND capacity >=".$inputnumber.")";
  $restaurant = $conn->query($ressql);
  if ($restaurant->num_rows > 0) {
    echo "<div class='container'>
          <div class='row'>
          <div class='center-block' style='width:100%;margin-top:2%;'>
          <div class='page-header'>
          <h2 align = 'center'>Restaurant Lists</h2><br/>
          </div>";
    echo "<table class= 'table'><tr><th>Id</th><th>Name</th><th>Address</th>
    <th>Description</th><th>Capacity</th></tr>";
    while ($row = $restaurant->fetch_assoc()) {
      $restaurantinfo[$restaurantcount][0] = $row['rid'];
      $restaurantinfo[$restaurantcount][1] = $row['rname'];
      $restaurantinfo[$restaurantcount][2] = $row['raddress'];
      $restaurantinfo[$restaurantcount][3] = $row['description'];
      $restaurantinfo[$restaurantcount][4] = $row['lefts'];
      echo "<tr>";
      echo "<td>" . $restaurantinfo[$restaurantcount][0] . "</td>";
      echo "<td>" . $restaurantinfo[$restaurantcount][1] . "</td>";
      echo "<td>" . $restaurantinfo[$restaurantcount][2] . "</td>";
      echo "<td>" . $restaurantinfo[$restaurantcount][3] . "</td>";
      echo "<td>" . $restaurantinfo[$restaurantcount][4] . "</td>";
      echo "</tr>";
      $restaurantcount++;
    }
    echo "</table><br/>";

    echo "<form id= 'orderid' name= 'order' action= 'orderresult.php' method= 'post'>";

    // customer
    echo "<div class='form-group row'>
          <label class='col-sm-2 form-control-label'>This is you</label><div class='col-sm-10'>
          <select class = 'form-control' id= 'cid' name= 'cid'>";
    for ($i = 0; $i < $customercount; $i++) {
      echo "<option value ='".$customerinfo[$i][0]. "'>".$customerinfo[$i][0]." ".$customerinfo[$i][1]." ".$customerinfo[$i][2]. "</option>";
    }
    echo "</select></div></div>";

    // restaurant
    echo "<div class='form-group row'>
          <label class='col-sm-2 form-control-label'>Choose a restaurant</label><div class='col-sm-10'>
          <select class = 'form-control' id= 'rid' name= 'rid'>";
    for ($i = 0; $i < $restaurantcount; $i++) {
      echo "<option value ='" . $restaurantinfo[$i][0] . "'>".$restaurantinfo[$i][0]." ".$restaurantinfo[$i][1]."</option>";
    }
    echo "</select></div></div>";

    // other
    echo "<div class='form-group row'>
    <label class='col-sm-2 form-control-label'>Number of Seats</label>
    <div class='col-sm-10'>
    <p>". $inputnumber."</p>
    </div>
    </div><input type= 'hidden' name= 'inputnumber' value= '". $inputnumber ."'>";
    
    echo "<div class='form-group row'>
    <label class='col-sm-2 form-control-label'>Ordered Date</label>
    <div class='col-sm-10'>
    <p>". $inputdate."</p>
    </div>
    </div><input type= 'hidden' name= 'inputdate' value= '". $inputdate ."'>";
    
    echo "<div class='form-group row'>
    <label class='col-sm-2 form-control-label'>Ordered Time</label>
    <div class='col-sm-10'>
    <p>". $inputtime."</p>
    </div>
    </div><input type= 'hidden' name= 'inputtime' value= '". $inputtime ."'>";
   
    echo "<div class='form-group row'>
    <div class='col-sm-4'></div>
    <div class='col-sm-4' align='center'>
    <button type='submit' class='btn btn-primary'>Submit Your Order</button>
    </div>
    <div class='col-sm-4'></div>
    </div>";

    echo "<div class='form-group row'>
    <div class='col-sm-4'></div>
    <div class='col-sm-4' align='center'>
    <input type= 'button' class='btn btn-secondary' onclick= 'goBack()' value= 'Goback'/>
    </div>
    <div class='col-sm-4'></div>
    </div>";

    echo "</form></div></div></div>";

  } else {
    echo "<p style= 'color: #FF0000'>No Restaurants Available.</p><br/>";
    echo "<input type= 'button' class='btn btn-secondary' onclick= 'goBack()' value= 'Goback'/>";
  }

  $conn->close();
?>
</body>
</html>
