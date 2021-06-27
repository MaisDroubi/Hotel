<?php  
 require_once('database.php');
 $pdo=Database::connect();

 $sql = "SELECT * FROM customers";  
 $result=$pdo->prepare($sql);
 $result->execute();
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>Search</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>  
           <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">  
      </head>  
      <body>  
           <br /><br />  
           <div class="container" style="width:900px;">  
           <a href ='home.php'>Go back</a>

                <h3 align="center">Customers Reserves</h3><br />  
                <div class="col-md-3">  
                     <label>From Date</label>
                     <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" />  
                </div>  
                <div class="col-md-3">  
                    <label>To Date</label>
                     <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" />  
                </div>  
                <div class="col-md-3">  
                    <label>Customer Name</label>
                     <input type="text" name="name" id="name" class="form-control" placeholder="Search for a customer" />  
                </div>  
                <div class="col-md-3">  
                     <input type="button" name="filter" id="filter" value="RUN REPORT" class="btn btn-info" />  
                </div>  
                <div style="clear:both"></div>                 
                <br />  
                <div id="reserve_table">  
                     <table class="table table-bordered">  
                          <tr>  
                               <th width="20%">User Name</th>  
                               <th width="20%">Creation Date</th>  
                               <th width="20%">Start Date</th>  
                               <th width="20%">To Date</th>  
                               <th width="10%">Number of Days</th>
                               <th width="10%">Price</th>  
                          </tr>  
                     <?php  
                     while($row=$result->fetch(PDO::FETCH_ASSOC))  
                     {  
                     ?>  
                          <tr>  
                               <td><?php echo $row["customer_name"]; ?></td>  
                               <td><?php echo $row["created_at"]; ?></td>  
                               <td><?php echo $row["from_date"]; ?></td>  
                               <td><?php echo $row["to_date"]; ?></td>
                               <td><?php echo $row["num_days"]; ?></td> 
                               <td><?php echo $row["total_price"]; ?>$</td>  
                          </tr>  
                     <?php  
                     }  
                     ?>  
                     </table>  
                </div>  
           </div>  
      </body>  
 </html>  
 <script>  
      $(document).ready(function(){ 
      
           $.datepicker.setDefaults({  
                dateFormat: 'yy-mm-dd'   
           });  
           $(function(){  
                $("#from_date").datepicker();  
                $("#to_date").datepicker();  
           });  
           $('#filter').click(function(){  
                var from_date = $('#from_date').val();  
                var to_date = $('#to_date').val(); 
                var name = $('#name').val(); 
                if(from_date != '' && to_date != '')  
                {  
                     $.ajax({  
                          url:"filter.php",  
                          method:"POST",  
                          data:{from_date:from_date, to_date:to_date,name:name},  
                          success:function(data)  
                          {  
                               $('#reserve_table').html(data);  
                          }  
                     });  
                }  
                else  
                {  
                     alert("Please Select Date");  
                }  
           });  
          
      });  
 </script>
