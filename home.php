<!DOCTYPE html>
<html>
<head>
<title>Booking HomePage</title>
<link rel="stylesheet" href="bootstrap.css">
<script type="text/javascript" src="jquery-3.2.1.js"></script>
<script type="text/javascript" src="bootstrap.js"></script>
<script type="text/javascript">
function refreshPage() {
  document.getElementById("customerErr").style.visibility = "hidden";
  document.forms["search"]["inputname"].style.borderColor = "rgba(0,0,0,0.15)";

	// info sent to server
	var inputname = document.forms["search"]["inputname"].value;
	var infojson = {"inputname":inputname};
	var infostring = JSON.stringify(infojson);
  var err = 0;
  $.ajax({  type: "post",
            url: "customer.php",
            async : false,
            data: {"infostring" : infostring},
            success: function(data){
                if (data == "customer error") {
                  document.getElementById("customerErr").style.visibility = "visible";
                  document.forms["search"]["inputname"].style.borderColor = "#ff0000";
                  err = 1;
                } 
            }
        });
  if (err == 1) { return false;}
}
</script>
</head>
<body>
<div class="container">
<div class="row">
<div class="center-block" style="width:100%;margin-top:5%;">
<div class="page-header">
	<h2 align = "center">Search For Tables</h2><br/>
</div>
<form id= "search" action= "searchresult.php" onsubmit= "return refreshPage();" method= "post">
  <div class="form-group row">
    <label for= "inputname" class="col-sm-2 form-control-label">Name</label>
    <div class= "col-sm-10">
      <input type= "text" class= "form-control" name= "inputname" placeholder= "Please Input Your Name" required>
      <p id = "customerErr" style = "visibility: hidden;color: #ff0000">Please Input a Valid Customer Name.</p>
    </div>
  </div>
  <div class="form-group row">
    <label for="inputkeyword" class="col-sm-2 form-control-label">Keyword</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="inputkeyword" placeholder="Keyword">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputnumber" class="col-sm-2 form-control-label">Number of Seats</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="inputnumber" placeholder="Numer of Seats" pattern="^[1-9]\d*$"
      oninvalid="this.setCustomValidity('Please input a valid number');" oninput= "setCustomValidity('')" required>
    </div>
  </div>
  <div class="form-group row">
    <label for="inputdate" class="col-sm-2 form-control-label">Booking Date</label>
    <div class="col-sm-10">
      <input type="date" class="form-control" name="inputdate" placeholder="Number of Seats" value= "<?= isset($_POST['bookdate']) ? $_POST['bookdate'] : ''; ?>" name= "bookdate" required>
    </div>
  </div>
  <div class="form-group row">
    <label for="inputtime" class="col-sm-2 form-control-label">Booking Time</label>
    <div class="col-sm-10">
      <input type="time" class="form-control" name="inputtime" placeholder="Numer of Seats" value= "17:00" required>
    </div>
  </div>
  <div class="form-group row">
  	<div class="col-sm-4"></div>
    <div class="col-sm-4" align="center">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
    <div class="col-sm-4"></div>
  </div>

</form>
</div>
</div>
</div>
</body>
</html>
