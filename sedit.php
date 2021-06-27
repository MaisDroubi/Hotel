<!DOCTYPE html">

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

	if(!empty($_POST['submit'])&& $_POST['submit']=='Save')
	{
		
		$room_num=test_input($_POST['room_num']);
		$min_days=test_input($_POST['min_days']);
		$price_day=test_input($_POST['price_day']);
		$stop_res=test_input($_POST['stop_res']);	
		if(test_input($_POST['stop_res'])=='1'){
			$sql="update rooms set min_days=?,price_day=?,stop_res=? where room_number=?";
			$result=$pdo->prepare($sql);

		}	
		else{
			$sql="update rooms set min_days=?,price_day=?,stop_res=? where room_number=?";
		$result=$pdo->prepare($sql);
		}
		// $sql="update rooms set min_days=?,price_day=?,stop_res=? where room_number=?";
		// $result=$pdo->prepare($sql);
		try
		{
			$result->execute(array($min_days,$price_day,$stop_res,$room_num));
				echo "<div class='alert alert-success alert-dismissable'>";
				echo "<a  class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
			 	echo "<strong>Success!</strong> Updated Successfully";
				echo "   <a href ='index.php'>Go back</a>";
				echo "</div>";
				

		}
		catch(Exception $e)
		{
			echo "<div class='alert alert-danger alert-dismissable'>";
			echo "<a  class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
			echo "<strong>ERROR!</strong> {$e->getMessage()}</div>";
		}
		  
	}
	
	$sql="select * from rooms where room_number=?";
	$result=$pdo->prepare($sql);
	$result->execute(array($_POST['room_num']));
	$row=$result->fetch(PDO::FETCH_ASSOC);
	?>


        <div>
            <form method="post" name="myform">

                <div class="form-group">
                    <label class="control-label">Room Number</label>
                    <input readonly="readonly" type="text" name="room_num" required id='room_num' class="form-control"
                        value="<?php echo $row['room_number'];?>">
                </div>

                <div class="form-group">
                    <label class="control-label">Minimum Days:</label>
                    <input type="text" name="min_days" required class="form-control"
                        value="<?php echo $row['min_days'];?>">
                </div>

                <div class="form-group">
                    <label class="control-label">price per day</label>
                    <input type="text" name="price_day" class="form-control" value="<?php echo $row['price_day'];?>">
                </div>
                <div class="form-group">
                    <label class="control-label" for="stop_res">stop reservation :</label>
                    <input type="checkbox" id="stop_res" name="stop_res" class="form-control" value="1"
                        <?php if($row['stop_res'] == '1') echo 'checked'; ?>>
                </div>

                <div class="form-group">
                    <input type="submit" name="submit" value="Save" class="btn btn-success">
                </div>
            </form>
        </div>




</body>

</html>