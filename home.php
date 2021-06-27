<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>OneStopParking Hotel</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="OneStopParking" name="keywords">
    <meta content="OneStopParking hotel" name="description">

    <!-- Favicon -->
    <!-- <link href="img/favicon.ico" rel="icon"> -->

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400|Nunito:600,700" rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- <link href="lib/flaticon/font/flaticon.css" rel="stylesheet"> -->
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">


</head>
<?php
date_default_timezone_set('Asia/Jerusalem');

    require_once('database.php');
	
	function test_input($data)
		{
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
	    }
	
	$pdo=Database::connect();

    $sql="select * from rooms where stop_res=0  LIMIT 1";
	$result=$pdo->prepare($sql);
	$result->execute(array($_POST['id']));
    // if(!($result->fetch(PDO::FETCH_ASSOC)>0)){$errors[] =  "sold out <br>";}
	$row=$result->fetch(PDO::FETCH_ASSOC);
    $errors;



if($_SERVER['REQUEST_METHOD']=='POST')
	{
        if(empty($_POST['customer_name'])){ $errors[] = "Customer Name is Required<br>";}
        if(empty($_POST['phone_num'])){ $errors[] = "Phone Number is Required<br>";}
        if(empty($_POST['from_date'])){ $errors[] =  "Start Date is Required<br>";}
        if(empty($_POST['to_date'])){ $errors[] =  "End Date is Required<br>";}

		$customer_name=test_input($_POST['customer_name']);
		$phone_num=test_input($_POST['phone_num']);
		$from_date=test_input($_POST['from_date']);
		$to_date=test_input($_POST['to_date']);
        $days=(strtotime($to_date)-strtotime($from_date))/60/60/24;
        if(!empty($_POST['customer_name']) && !empty($_POST['phone_num']) && !empty($_POST['from_date']) && !empty($_POST['to_date'])){
        if($row['stop_res'] ==1){$errors[] =  "The reservation is stopped !!<br>";}		
        if($days<$row['min_days']){ $errors[] =  "Minimum booking day must be more than or equal to ".$row['min_days']."<br>";   }
        // $sql = "SELECT from_date,to_date FROM customers 
        // WHERE from_date Not BETWEEN '".$_POST["from_date"]."' AND '".$_POST["to_date"]."' AND to_date Not BETWEEN '".$_POST["from_date"]."' AND '".$_POST["to_date"]."' AND to_date Not BETWEEN '".$_POST["from_date"]."'  ";
        // $result=$pdo->prepare($sql);
        // $result->execute();
        // if($result->fetch(PDO::FETCH_ASSOC) > 0){   $errors[] =  "Sorry Sold out !!<br>";  }      
               
       }
	
    
    if(!empty($errors)){
        echo "<div class='alert alert-danger'>";
        foreach ($errors as $error) {
            echo "<span class='glyphicon glyphicon-remove'></span>&nbsp;".$error."<br>";
        }
        echo "</div>";
    }
    else if(empty($errors)){
        $sql="insert into customers (customer_name,phone_number,from_date,to_date,total_price,num_days,created_at) values (?,?,?,?,?,?,now())";
        $result=$pdo->prepare($sql);        

        try{
            $total=$row['price_day']*$days;
            $result->execute(array($customer_name,$phone_num,$from_date,$to_date,$total,$days));
      
            

            $sql="update rooms set stop_res=? where room_number=?";
		    $result=$pdo->prepare($sql);
            try{
                $result->execute(array(1,$row['room_number']));
            }
            
            catch(Exception $e){
			echo "<div class='alert alert-danger alert-dismissable'>";
			echo "<a  class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
			echo "<strong>ERROR!</strong> {$e->getMessage()}</div>";
		    }
            $messages[] = "Reservation is done successfully, you reservation id is ".$row['room_number'];



        }
        catch(Exception $e)
		{
			echo "<div class='alert alert-danger alert-dismissable'>";
			echo "<a  class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
			echo "<strong>ERROR!</strong> {$e->getMessage()}</div>";
		}  

       
    }
    if(!empty($messages)){
        echo "<div class='alert alert-success'>";
        foreach ($messages as $message) {
            echo "<span class='glyphicon glyphicon-ok'></span>&nbsp;".$message."<br>";
        }
        echo "</div>";
    }
	

}
	?>


<body>
    <!-- Nav Bar Start -->
    <div class="navbar navbar-expand-lg bg-light navbar-light">
        <div class="container-fluid">
            <a href="index.html" class="navbar-brand">OneStopParking <span>.Hotel</span></a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto">
                    <a href="home.php" class="nav-item nav-link active">Home</a>
                    <a href="index.php" class="nav-item nav-link">Setting</a>
                    <a href="search.php" class="nav-item nav-link">Report</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Nav Bar End -->


    <!-- Carousel Start -->
    <div class="carousel">
        <div class="container-fluid">
            <div class="owl-carousel">
                <div class="carousel-item">
                    <div class="carousel-img">
                        <img src="img/1.jpg" alt="Image">
                    </div>
                    <div class="carousel-text">
                        <h1>World’s <span>Best</span> Hotel</h1>
                        <p>
                            Relax in this Newark, New Jersey hotel knowing that you are just minutes from downtown New
                            York City. We serve a free hot breakfast buffet every morning. Guests can also take
                            advantage of the 24-hour fitness center and the 24-hour business center. </p>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="carousel-img">
                        <img src="img/2.jpg" alt="Image">
                    </div>
                    <div class="carousel-text">
                        <h1>fast <span>Wi-Fi</span></h1>
                        <p>
                            At your disposal in your room, fast Wi-Fi ideal for smart working </p>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="carousel-img">
                        <img src="img/3.jpg" alt="Image">
                    </div>
                    <div class="carousel-text">
                        <h1>swimming <span>pool</span></h1>
                        <p>
                            While you enjoy a cocktail by the swimming pool on the rooftop terrace, you will be stunned
                            by the breathtaking view of the bay of Isola Bella. Here, during your summer stays, our bar
                            serves traditional Sicilian dishes, snacks and salads. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- Booking Start -->
    <div class="booking">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="booking-content">
                        <div class="section-header">
                            <p>Book A Room</p>
                            <h2>Book Your Room In Our Hotel</h2>
                        </div>
                        <div class="about-text">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus nec pretium mi.
                                Curabitur facilisis ornare velit non vulputate. Aliquam metus tortor, auctor id gravida
                                condimentum, viverra quis sem.
                            </p>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus nec pretium mi.
                                Curabitur facilisis ornare velit non vulputate. Aliquam metus tortor, auctor id gravida
                                condimentum, viverra quis sem. Curabitur non nisl nec nisi scelerisque maximus. Aenean
                                consectetur convallis porttitor. Aliquam interdum at lacus non blandit.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="booking-form">
                        <form method="post" id="myform" action="home.php">
                            <div class="control-group">
                                <div class="input-group date" id="date1">
                                    <input type="date" class="form-control" placeholder="From Date" name="from_date"
                                        onchange="displayDate()" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="input-group date" id="date2">
                                    <input type="date" class="form-control" placeholder="To Date" name="to_date"
                                        min="2021-06-27" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Customer Name"
                                        name="customer_name" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="far fa-user"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Phone Number"
                                        name="phone_num" />
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-mobile-alt"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="input-group">
                                    <input class="form-control" value="Total price" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><?php echo $total;?><i
                                                class="fas fa-dollar-sign"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <!-- <button class="btn custom-btn" type="submit">RESERVE</button> -->
                                <input class="btn custom-btn" type="submit" value="RESERVE">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Booking End -->



    <!-- Contact Start -->
    <div class="contact">
        <div class="container">
            <div class="section-header text-center">
                <p>Contact Us</p>
                <h2>Contact For Any Query</h2>
            </div>
            <div class="row align-items-center contact-information">
                <div class="col-md-6 col-lg-3">
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="fa fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Address</h3>
                            <p>Rafedia Street, Nablus, Palestine</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="fa fa-phone-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Call Us</h3>
                            <p>+059 999 9999</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Email Us</h3>
                            <p>info@onestopparking.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="contact-info">
                        <div class="contact-icon">
                            <i class="fa fa-share"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Follow Us</h3>
                            <div class="contact-social">
                                <a href=""><i class="fab fa-twitter"></i></a>
                                <a href=""><i class="fab fa-facebook-f"></i></a>
                                <a href=""><i class="fab fa-youtube"></i></a>
                                <a href=""><i class="fab fa-instagram"></i></a>
                                <a href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Footer Start -->
            <div class="footer">
                <div class="copyright">
                    <div class="container">
                        <p>Copyright &copy; <a href="#">OneStopParking</a>, All Right Reserved.</p>
                    </div>
                </div>
            </div>
            <!-- Footer End -->

            <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>


            <!-- JavaScript Libraries -->
            <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
            <script src="lib/easing/easing.min.js"></script>
            <script src="lib/owlcarousel/owl.carousel.min.js"></script>
            <script src="lib/tempusdominus/js/moment.min.js"></script>
            <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
            <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
            <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>   -->
            <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />   -->
            <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
            <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">


            <!-- Template Javascript -->
            <script src="js/main.js"></script>
            <script src="js/jquery-3.5.0.min.js"></script>


</body>

</html>