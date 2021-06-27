<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
    
<title>Untitled Document</title>
</head>

<body>
<div class="container">
<?php
    require_once('database.php');
	
	function test_input($data)
		{
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
	    }
	
	$pdo=Database::connect();

	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		
		$room_num=test_input($_POST['room_num']);
		$min_days=test_input($_POST['min_days']);
		$price_day=test_input($_POST['price_day']);
		$stop_res=test_input($_POST['stop_res']);
		if(test_input($_POST['stop_res'])){
			$sql="insert into rooms (room_number,min_days,price_day,created_at,updated_at,stop_res) values (?,?,?,now(),now(),1)";
			$result=$pdo->prepare($sql);
		}
		else{
			
			$sql="insert into rooms (room_number,min_days,price_day,created_at,updated_at,stop_res) values (?,?,?,now(),now(),0)";
			$result=$pdo->prepare($sql);

		}
		
		try
		{
			$result->execute(array($room_num,$min_days,$price_day));
				echo "<div class='alert alert-success alert-dismissable'>";
				echo "<a  class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
			 	echo "<strong>Success!</strong> Added One Record</div>";
				 

		}
		catch(Exception $e)
		{
			echo "<div class='alert alert-danger alert-dismissable'>";
			echo "<a  class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
			echo "<strong>ERROR!</strong> {$e->getMessage()}</div>";
		}
		  
	}
	
	$sql="select * from rooms";
	$result=$pdo->prepare($sql);
	$result->execute();
	?>


<div >
 <form method="post" name="myform">
 <a href ='home.php'>Go back</a>

            <div class="form-group">
                <label class="control-label">Room Number:</label>
                <input type="text" name="room_num" required id='room_num' class="form-control" placeholder="Room numbr" require>
            </div>
            
            <div class="form-group">
                <label class="control-label">Minimum number of days:</label>
                <input type="number" name="min_days" required class="form-control" require>
            </div>
           
            
            <div class="form-group">
            <label class="control-label">Price per Day:</label>
            <input type="number" name="price_day" class="form-control" require>
            </div>

			<div class="form-group">
			<label class="control-label" for="stop_res">stop reservation :</label>
			<input type="checkbox" id="stop_res" name="stop_res" value="1" class="form-control">
            </div>
            
            <div class="form-group">
            <input type="submit" value="Save" class="btn btn-success">
            </div>
        </form>
</div>
        
<div>
<table cellspacing="0" class="table table-hover">
    <tr>
    <th>Room Number</th>
    <th>Minimum days</th>
    <th>price/day</th>
    <!-- <th>stop reservation </th> -->
    <th>Action</th>
    </tr>
	<?php
	 	  while ($row=$result->fetch(PDO::FETCH_ASSOC))
		{
				echo "<tr>";
				echo "<td>{$row['room_number']}</td>";
				echo "<td>{$row['min_days']}</td>";
				echo "<td>{$row['price_day']}</td>";
				echo "<td>{$row['stop_res']}</td>";
				echo "<td><form method='post'>";
				echo "<input type='hidden' value='{$row['room_number']}' name='room_num'/>";
				echo "<input type='submit' value='Edit' formaction='sedit.php' class='btn btn-warnning'>";
				echo "</form></td>";
				echo "</tr>";
				
		}		
	?> 
</table>
</div>



</body>
</html>