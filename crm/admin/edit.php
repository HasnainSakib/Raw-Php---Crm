<?php 
 

if (isset($_GET["peid"])) {

     $id = $_GET["peid"];
       include"header.php";
	   
	   ?>
 
 
 <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                   <center> <h2> products List </h2></center> <hr/>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			
			
			
			
								
								
								
								
								
								
							<table class="table">
    
    <tbody>

							<?php 
							
							$product_query = "SELECT * FROM products where product_id = $id ";
							$run_query = mysqli_query($con,$product_query);
							if(mysqli_num_rows($run_query) > 0){
		$sl = 0;
										while($row = mysqli_fetch_array($run_query)){
											$sl++;
											$id   = $row['product_id'];
											$product_cat   = $row['product_cat'];
											$product_title = $row['product_title'];
											$product_price = $row['product_price'];
											$product_image = $row['product_image'];
											$quantity = $row['quantity'];
											$status = $row['status'];
											?>
							
							
							
							<form action="" method="post">
										
											<tr> <td> serial </td> <td><?php echo $sl; ?></td></tr>
											<tr><td> product iamge </td><td><img src="../images/<?php echo $product_image; ?>" style="height:60px;width:100px;"></td></tr>
											
											<tr><td> product Tittle </td><td><input type="text" name="tittle" value="<?php echo $product_title; ?>"></td></tr>
											<tr><td> product category </td><td><input type="text" name="cat" value="<?php echo $product_cat; ?>"></td></tr>
											
											<tr><td> product price </td><td><input type="text" name="price" value="<?php echo $product_price; ?>"></td></tr>
										
											<tr><td> product quantity </td><td><input type="text" name="qty" value="<?php echo $quantity; ?>"></td></tr>
											
											<tr><td> product status </td><td><input type="text" name="status" value="<?php echo $status; ?>"></td></tr>
											<tr><td>  </td><td>
											
											<input type="hidden" name="prodid" class="btn btn-danger" value="<?php echo $id;?>">
											<input type="submit" name="save" class="btn btn-danger" value="save">
											
											
											
											</td>
										  </tr>
										</form>  
							
							
							<?php
		}
							}
							?>
				
					
					 </tbody>
  </table>

  
  
                              <?php
							  
						if (isset($_POST["save"])) {

									$pid = $_POST["prodid"];	
									$title = $_POST["tittle"];
									
									 $cat = $_POST["cat"];	
									  $price = $_POST["price"];	
									   $qty = $_POST["qty"];	 
									   $sts = $_POST["status"];	
 
							  
							  $sql = "UPDATE products SET product_title ='$title',product_cat ='$cat',product_price ='$price',quantity ='$qty',status ='$sts'
							  WHERE product_id = $pid ";
										
										if(mysqli_query($con,$sql)){
											echo "<div class='alert alert-info'>
															<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
															<b>Order is updated</b>
													</div>";
											
											
											?>
										
									<script type="text/javascript">
									<!--
									function Redirect() {
									window.location="products.php";
									}
									document.write("<center>You will be redirected to main page in 2 sec.</center>");
									setTimeout('Redirect()', 2000);
									//-->
									</script>
									<?php
										}
										
										
						}
							  
							  ?>
            
                        <!-- /.panel-body -->
						
						
						
						 <?php include"footer.php";
       

	
	
}




?>