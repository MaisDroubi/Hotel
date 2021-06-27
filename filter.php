
<?php  
 //filter.php  
 require_once('database.php');
 $pdo=Database::connect();

 if(isset($_POST["from_date"], $_POST["to_date"]))  
 {  
    $output = '';  
    if(!isset($_POST["name"])){  
        $sql = "SELECT * FROM customers 
        WHERE from_date BETWEEN '".$_POST["from_date"]."' AND '".$_POST["to_date"]."' AND to_date BETWEEN '".$_POST["from_date"]."' AND '".$_POST["to_date"]."'  
   ";        
        }
        else{

      $sql = "SELECT * FROM customers 
           WHERE from_date BETWEEN '".$_POST["from_date"]."' AND '".$_POST["to_date"]."' AND to_date BETWEEN '".$_POST["from_date"]."' AND '".$_POST["to_date"]."' AND customer_name LIKE '%".$_POST["name"]."%'
      ";  
        }
        $sum=0;
      $result=$pdo->prepare($sql);
	$result->execute();
      $output .= '  
           <table class="table table-bordered">  
                <tr>  
                <th width="20%">User Name</th>  
                <th width="20%">Creation Date</th>  
                <th width="20%">Start Date</th>  
                <th width="20%">To Date</th>  
                <th width="10%">Number of Days</th>
                <th width="10%">Price</th>  
                </tr>  
      ';  
      if($result->fetch(PDO::FETCH_ASSOC) > 0)  
      {  
           while($row=$result->fetch(PDO::FETCH_ASSOC))  
           {  
               $sum+=$row["total_price"];
                $output .= '  
                     <tr>  
                          <td>'. $row["customer_name"] .'</td>  
                          <td>'. $row["created_at"] .'</td>  
                          <td>'. $row["from_date"] .'</td>  
                          <td> '.$row["to_date"] .'</td>  
                          <td>'. $row["num_days"] .'</td> 
                          <td>'. $row["total_price"] .'</td> 
                          
                     </tr>  
                ';  
           }  
           $output .= '  
           <tr>  
                <td colspan="5">Sum</td>  
                <td>'.$sum.'</td>
           </tr>  
      ';  

           
      }  
      else  
      {  
           $output .= '  
                <tr>  
                     <td colspan="5">No customer Found</td>  
                </tr>  
           ';  
      }  
      $output .= '</table>';  
      echo $output;  
}  
?>
