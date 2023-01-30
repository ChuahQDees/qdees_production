<?php
session_start();
include_once("../mysql.php");
include_once("../search_new.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$category, $s, $allocation_id, $student_code
}

?>
<script>
function add2TempBusket(product_code, student_code) {
   if ((product_code!="") & (student_code!="")) {
      $.ajax({
         url : "admin/add2TempBusket.php",
         type : "POST",
         data : "product_code="+product_code+"&student_code="+student_code,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");
            if (s[0]=="1") {
               UIkit.notify(s[1]);
            }

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
            getTempBusket();
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please provide a product code and student code");
   }
}
</script>
<br>
<table id="productDatatable" class="uk-table uk-table-small">
   <thead>
      <tr class="uk-text-bold uk-text-small">
         <th>Photo</th>
         <th>Code</th>
         <th>Name</th>
         <th>Unit Price</th>
         <th>Action</th>
      </tr>
   </thead>
   <tbody class="uk-text-small">
   </tbody>
</table>