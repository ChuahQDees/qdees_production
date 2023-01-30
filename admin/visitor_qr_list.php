<a href="/index.php?p=visitor_hq">
         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Visitor Registration</span>
</span>
<style>
 .fa{ cursor: pointer; }
</style>
 <?php
session_start();
include_once("mysql.php");
include_once("search_new.php");
?>

<script>
function dlgRegisteredChilds(id) {
   $.ajax({
      url : "admin/dlgRegisteredChilds.php",
      type : "POST",
      data : "id="+id,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         if (response!="") {
            $("#dlgRegisteredChilds").html(response);
            $("#dlgRegisteredChilds").dialog({
               dialogClass:"no-close",
               title:"Children",
               modal:true,
               height:'auto',
               width:'60%',
            });
         }
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
function doDelete(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#del_id").val(id);
      $("#frmDelete").submit();
   });
}

function generateQR() {
   $("#dlgQRCode").html("");
//   $("#dlgQRCode").qrcode("<?php echo $_SERVER['HTTP_HOST']."/qdees/visitor_qr.php?centre_code=".$_SESSION["CentreCode"]?>")
   $("#dlgQRCode").qrcode("http://www.webhyper.com");
   $("#dlgQRCode").dialog({
      title:"QRCode",
      modal:true,
      height:'auto',
      width:'auto',
      // position: {my: "center top", at: "center top", of: $("#theBody")}
   });
}

</script>

<?php
if (($_GET["del_id"]!="") & ($_GET["action"]=="DEL")) {
   $sql="DELETE from visitor where id='".$_GET["del_id"]."'";
   $result=mysqli_query($connection, $sql);
}
?>

<div class="uk-margin-left uk-margin-top uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <div class="uk-grid">
     <div class="uk-width-3-10"></div>
         <div class="uk-width-4-10">
            <h2 class="uk-text-center myheader-text-color myheader-text-style">Visitor Registration Listing</h2>
         </div>
         <div class="uk-width-3-10 uk-text-left">

         </div>
      </div>
   </div>
    <div class="nice-form">
   <form class="uk-form" method="post" action="">
       <div class="row">
           <div class="col-sm-12 col-md-3">
               <input name="name" id="name" class="uk-width-1-1" placeholder="Visitor's Name" type="text" value="<?php echo $_POST["name"]?>">
           </div>
		   <div class="col-sm-12 col-md-2">
               <input name="child_name" id="child_name" class="uk-width-1-1" placeholder="Child name" type="text" value="<?php  echo $_POST["child_name_2"];?>">
           </div>
		   <div class="col-sm-12 col-md-2">
               <input type="text" class="uk-width-1-1" name="start_date" id="start_date" placeholder="Select Date" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="" autocomplete="off">
           </div>
           <div class="col-sm-12 col-md-3">
               <input name="tel" id="tel" class="uk-width-1-1" placeholder="Visitor's Tel" type="text" value="<?php echo $_POST["tel"]?>">
           </div>
           <div class="col-sm-12 col-md-2">
               <button class="uk-button full-width-blue">Search</button>
           </div>
       </div>
   </form>
    <div style="width:100%;overflow-x:auto;margin-top:15px">
<?php
$name=$_POST["name"];
$child_name=$_POST["child_name"];
//$child_name2=$_POST["child_name"];
$tel=$_POST["tel"];
$year=$_SESSION["Year"];
$current_year=date("Y");

//$base_sql="SELECT * from visitor inner join visitor_student";
$base_sql="SELECT visitor.*, visitor_student.child_1_student_id, visitor_student.child_2_student_id, visitor_student.child_3_student_id, visitor_student.child_4_student_id, visitor_student.child_5_student_id, visitor_student.child_6_student_id from visitor left join visitor_student on visitor.id=visitor_student.visitor_id where 1=1 ";
//$base_sql="SELECT * from visitor where 1=1 ";                     
//echo $base_sql;                                                     
if($child_name !=""){                                               
	$base_sql.=" and (child_name_1 like '%$child_name%' or child_name_2 like '%$child_name%' or child_name_3 like '%$child_name%' or  child_name_4 like '%$child_name%' or child_name_5 like '%$child_name%' or child_name_6 like '%$child_name%') ";
}                                                                   
$name_token=ConstructToken("name", "%".$_POST["name"]."%", "like"); 
$start_date_token=ConstructToken("date_created", "%".$_POST["start_date"]."%", "like");
$centre_token=ConstructToken("centre_code", $_SESSION["CentreCode"], "=");
$tel_token=ConstructToken("tel", "%".$_POST["tel"]."%", "like");    
$year_token=ConstructToken("year(date_created)", "$year", "=");     
$final_token=ConcatToken($name_token, $tel_token, "and");           
$final_token=ConcatToken($final_token, $centre_token, "and");
$final_token=ConcatToken($final_token, $start_date_token, "and");   
$final_token=ConcatToken($final_token, $year_token, "and");         
$final_sql=ConcatWhere($base_sql, $final_token);                    
$final_sql=ConcatOrder($final_sql, "date_created desc");            
                                                
$result=mysqli_query($connection, $final_sql);                      
$num_row=mysqli_num_rows($result);                                  
?>                                                                  
                                                                    
   <table class="uk-table q-table" style="width: 100%; min-width: 880px">
      <tr class="uk-text-small uk-text-bold">                       
        <th style="width: 10%; text-align: center;">Date</th>       
         <th style="width: 11%;">Visitor's Name</th>                
         <th style="width: 11%;">IC / Passport</th>                 
         <th style="width: 7%; text-align: center;">Phone</th>      
         <th style="width: 11%; text-align: center;">Email</th>     
         <th style="width: 7%;">No. of Children</th>                
         <th style="width: 10%;">Child's Name</th>                  
         <th style="width: 6%;">Birth Year</th>                     
         <th style="width: 6%;">Child Converted</th>                
         <th style="width: 5%; text-align: center;" data-uk-tooltip="{pos:top}" title="How do you find out of Q-dees">Find Out</th>
         <th style="width: 22%; text-align: center;">Action</th>    
      </tr>                                                         
<?php                                                               
if ($num_row>0) {                                                   
   while ($row=mysqli_fetch_assoc($result)) {                       
?>                                                                  
      <tr class="uk-text-small">                                    
        <td style="width: 19%;"><?php echo $row["date_created"]?></td>
         <td style="width: 11%;"><?php echo $row["name"]?></td>     
         <td style="width: 11%;"><?php echo $row["nric"]?></td>     
         <td style="width: 7%;"><?php echo $row["country_code"].$row["tel"]?></td>       
         <td style="width: 11%;"><?php echo $row["email"]?></td>    
         <td style="width: 7%;"><?php echo $row["number_of_children"]?></td>
         <td style="width: 6%;"><?php                               
         echo ($row["child_name_1"] ? $row["child_name_1"] : '')    
         .  ($row["child_name_2"] ? ', ' ."<br>". $row["child_name_2"] : '') 
         .  ($row["child_name_3"] ? ', ' ."<br>". $row["child_name_3"] : '') 
         .  ($row["child_name_4"] ? ', ' ."<br>". $row["child_name_4"] : '') 
         .  ($row["child_name_5"] ? ', ' ."<br>". $row["child_name_5"] : '') 
         .  ($row["child_name_6"] ? ', ' ."<br>". $row["child_name_6"] : '')
         ?>                                                         
         </td>                                                      
		 <td style="width: 6%;"><?php                               
         echo ($row["child_birth_year_1"] ? $row["child_birth_year_1"] : '')
         .  ($row["child_birth_year_2"] ? ', '."<br>" . $row["child_birth_year_2"] : '')
         .  ($row["child_birth_year_3"] ? ', ' ."<br>". $row["child_birth_year_3"] : '')
         .  ($row["child_birth_year_4"] ? ', ' ."<br>". $row["child_birth_year_4"] : '')
         .  ($row["child_birth_year_5"] ? ', ' ."<br>". $row["child_birth_year_5"] : '')
         .  ($row["child_birth_year_6"] ? ', ' ."<br>". $row["child_birth_year_6"] : '')
         ?>                                                         
         </td>                                                      
         <td style="width: 2%;">                                    
                                                                    
			<?php                                                               
					$i=0;
				   for($count=1; $count <= 6; $count++){                        
						//if(!$row["child_birth_year_".$count]) continue;       
						$student_found=0;
						
						if($row["child_".$count."_student_id"]){                
							$sql1="SELECT * from student where id='".$row["child_".$count."_student_id"]."' ";
							//echo $sql; // die;                                   
							$result1=mysqli_query($connection, $sql1);            
							$student_found=mysqli_num_rows($result1);            
						}                                                       
						 //$student_found = $student_found > 0 ? 'Registered' : 'Not registered';
						        
						if($student_found > 0){
							if($row["child_name_".$count]!=""){
								$i++;
								if($i==1){
									echo $row["child_name_".$count] ;	
								}else{
								echo ',' . "<br>" . $row["child_name_".$count];	
								}	
							}							
						}
						
				   }                                                            
			?>                                                                                                                               
		</td>                                                               
         <td style="width: 5%;">
		 <?php 
			 echo str_replace("|||",", ",$row["find_out"]);
			 ?>
		  </td>  
         <td style="width: 23%; white-space: nowrap;">              
<?php                                                               
if (hasRightGroupXOR($_SESSION["UserName"], "VisitorEdit")) {       
  if ($current_year == $year) {                                     
?>                                                                  
                                                                    
        <a href="index.php?p=visitor_update&id=<?php echo sha1($row["id"]) ?>" data-uk-tooltip title="Edit Visitor"><i class="fa fa-edit" style="font-size: 1.1em;"></i></a>
                                                                    
<?php                                                               
  }                                                                 
?>                                                                  
<i class="fa fa-user-plus text-success" onclick="dlgRegisteredChilds('<?php echo $row["id"]?>')" data-uk-tooltip title="Register child as student" style="font-size: 1.1em;"></i>
                                                                    
                                                                    
        <i class="fa fa-trash-alt text-danger" onclick="doDelete('<?php echo $row["id"]?>')" data-uk-tooltip title="Delete <?php echo $row['name']; ?>" style="font-size: 1.1em;"></i>
<?php                                                               
}                                                                   
?>                                                                  
         </td>                                                      
      </tr>                                                         
<?php                                                               
   }                                                                
} else {                                                            
?>                                                                  
      <tr>                                                          
         <td colspan="6">No Record Found</td>                       
      </tr>                                                         
<?php                                                               
}                                                                   
?>                                                                  
   </table>                                                         
   </div>                                                           
   </div>                                                           
</div>                                                              
                                                                    
<form id="frmDelete" method="get" action="">                        
   <input type="hidden" name="p" id="p" value="visitor_qr_list">    
   <input type="hidden" name="del_id" id="del_id" value="">         
   <input type="hidden" name="action" id="action" value="DEL">      
</form>                                                             
                                                                    
<div id="dlgQRCode"></div>                                          
<div id="dlgRegisteredChilds"></div>                                
                                                                    