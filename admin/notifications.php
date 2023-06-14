 <span>
   <span class="page_title"><img src="/images/title_Icons/Current Status Order.png">Notifications</span>
</span>
<?php 
session_start();
include_once("../mysql.php");
if(isset($_GET['flag']) && $_GET['flag'] ==1){
	$where_n = " HAVING important = 1";
}
$where_request = '';
if(isset($_GET['request']) && $_GET['request'] ==1){
	$where_request = " AND `type` = 'student_delete_request' AND hide_from_view = '0'";
}

if($_SESSION["UserType"] == "A"){ 
	   $r_sql="SELECT *, (
			SELECT  Count(user_id) As user_id
			FROM notification_important
			WHERE user_id='".$_SESSION['CentreCode']."' AND `notification_id`=`id`) AS `important`
			from notifications where send_to='".$_SESSION['CentreCode']."' $where_n order by created_at DESC";
}else{ 
	$r_sql="SELECT *, (
			SELECT  Count(user_id) As user_id
			FROM notification_important
			WHERE user_id='".$_SESSION['CentreCode']."' AND `notification_id`=`id`) as important 
			from notifications where send_to='hq' $where_request $where_n order by created_at DESC";
}
$result=mysqli_query($connection, $r_sql); 
  
?> <span>
	<?php if(!isset($_GET['request']) && $_SESSION["UserType"] == "S") { ?>
      <a href="index.php?p=notifications&request=1" class="btn btn-danger">Delete Student Request</a>
	<?php } if(!isset($_GET['flag'])) { ?>
		<a href="index.php?p=notifications&flag=1" class="btn btn-success">View Important Notifications</a>
	<?php } if((isset($_GET['flag']) && $_GET['flag'] ==1) || isset($_GET['request']) && $_GET['request'] ==1){ ?>
		<a href="index.php?p=notifications" class="btn btn-info">View All Notifications</a>
   <?php } ?>
   </span>
    <div class="uk-overflow-container">
			
               <table class="uk-table" id="mydatatable">
      
                  <tbody>
                     <?php
                     while ($row = mysqli_fetch_assoc($result)) { 
					 
                     ?>
                        <tr class="uk-text-small">
                          
                           <td> 
							  <div class=" preview-list" >
							<?php 
							$is_read = 0;
							if($_SESSION["UserType"] == "A" ){
								$is_read = $row['is_center_read'];
							}else{
								$is_read = $row['is_hq_read'];
							}
							?>
							<div class="preview-item p-2">
							  <div class="preview-thumbnail">
								<div class="preview-icon bg-success">
								  <i class="mdi mdi-bell-outline mdi-24px" style="line-height: 20px;"></i>
								</div>
							  </div>
							  
							  <div class="preview-item-content"> 
							  <?php   if ($row['type'] == 'student_delete_request') { ?>
								  <a class="" href="index.php?p=notifications&request=1">
							  <?php } else if($row['type'] == 'fee_structure_adjusted') { ?>
                           <a class="" href="index.php?p=fee_approve&id=<?php echo sha1($row['action_id']); ?>&mode=EDIT">
                        <?php } else if($row['type'] == 'outstanding_report_pdf') { ?>
                           
                       <?php } else { ?>
								<a class="" href="index.php?p=order_status&sOrderNo=<?php if (substr($row['action_id'], 0, 1) != '0') { echo 0; } echo $row['action_id']; ?>">
							  <?php } ?>
									<p class="preview-subject<?php if ($is_read == 0) { echo ""; }?>">
                              <?php 
                                 echo $row['subject'];  
                                 
                                 if($row['type'] == 'outstanding_report_pdf') { 

                                    $report_pdf = mysqli_fetch_array(mysqli_query($connection,"SELECT `pdf_name` FROM `outstanding_pdf` WHERE `id` = '".$row['action_id']."'"));
                              ?> 
                                    <a href="admin/outstanding_report_pdf/<?php echo $report_pdf['pdf_name']; ?>" download > Download </a>
                              <?php 
                                 } 
                              ?> 
                           </p>
									<p class="mt-1 text-muted text-small ellipsis"> <?php echo date("d M Y h:s A",strtotime($row['created_at'])); ?> </p>
                           <?php if($row['type'] != 'outstanding_report_pdf') { ?>
                           </a>
                           <?php } ?>
							  </div>
							 
							  <div class="preview-thumbnail pl-2">
								  <?php if ($is_read == 0) { ?>
								  <i class="mdi mdi-checkbox-marked-circle text-black notification__action mdi-24px" onclick="mark_read('<?php echo $row['id']; ?>',this)" data-uk-tooltip="{pos:top}" title="Mark as Read"></i> 
								  <?php } ?>
								  <i class="fa <?php if ($row['important'] == 1) { echo "fa-flag"; } else { echo "fa-flag-o"; }?>  text-danger flag_action ml-1" style=" font-size: 20px !important; " onclick="mark_important('<?php echo $row['id']; ?>',this)" data-uk-tooltip="{pos:top}" title="Mark as Important "></i> 

                           <?php                           
                              if ($row['type'] == 'student_delete_request') 
                              { 
                           ?>
                                 <span class="btn btn-rounded btn-primary ml-1  delete" onclick="dlgDeleteStudent('<?php echo $row['action_id']; ?>', '<?php echo  $row['id'] ?>')" data-uk-tooltip="{pos:top}" title="View Request"><i style="cursor:pointer;" class="fas fa-eye" ></i> View</span>
                           <?php
                                 $student_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `delete_request` FROM `student` WHERE id = '".$row['action_id']."'"));

                                 if($student_data['delete_request'] == 3) 
                                 {
                           ?>
                                    <!-- <span class="btn btn-rounded btn-success ml-1  delete<?php echo sha1($row['action_id']); ?>" onclick="doDeleteStudentRequest('<?php echo sha1($row['action_id']); ?>','approve_request')" data-uk-tooltip="{pos:top}" title="Approve Request"><i style="cursor:pointer;" class="fas fa-check" ></i> Approve</span>

                                    <span class="btn btn-rounded btn-danger ml-1 delete<?php echo sha1($row['action_id']); ?>" onclick="doDeleteStudentRequest('<?php echo sha1($row['action_id']); ?>','reject_request')" data-uk-tooltip="{pos:top}" title="Reject Request"><i style="cursor:pointer;" class="fas fa-close "></i> Reject</span>
 -->
								   <?php
                                 }
                              }
                           ?>

							  </div>
							</div>
							</div> 
						</td>
                           
                        </tr>
                     <?php 
						}
                     ?>
                  </tbody>
               </table>
         </div>
<div id="dlgDeleteStudent"></div>

<script type="text/javascript">
   $(document).ready(function() {
      $('#mydatatable').DataTable({
         language: {
            // searchPlaceholder: "Search Class Status",
            //  search: ""
         },
         'columnDefs': [{
            'targets': [5], // column index (start from 0)
            'orderable': false, // set orderable false for selected columns
         }],
         "bInfo": false,
         order: [
            [5, "asc"]
         ],
         "pageLength": 20,
         "bPaginate": false,
      });
   });

   function doDeleteStudentRequest(id,request,notid) {

      var action = (request == 'approve_request') ? 'approve' : 'reject';

      UIkit.modal.confirm("<h2>Are you sure you want to " + action + " request?</h2>", function () {
         $.ajax({
            url : "admin/delete_student_request.php",
            type : "GET",
            data : "request="+request+"&student_sid="+id+"&notification_id="+notid,
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {
               var s=response.split("|");
               UIkit.notify(s[1]);
               $('.delete'+id).remove();
               $('#dlgDeleteStudent').dialog('close');
            },
            error : function(http, status, error) {
               UIkit.notification("Error:"+error);
            }
         });
      })
   }

   function dlgDeleteStudent(id, notid) {
      $.ajax({
         url : "admin/dlgDeleteStudent.php",
         type : "GET",
         data : "request=view_request&student_sid="+id+"&notification_id="+notid,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
         if (response != "") {
            $("#dlgDeleteStudent").html(response);
            $("#dlgDeleteStudent").dialog({
                  dialogClass: "q-dialog",
                  title: "View Request",
                  modal: true,
                  height: '450',
                  width: "60%",
            });
         }
         },
         error : function(http, status, error) {
            UIkit.notification("Error:"+error);
         }
      });
   }

</script>
 <style>
                  .uk-text-small {
                     font-size: 14px;
                  }

                  .d_n {
                     display: none;
                  }

                  #frmStatus input {
                     width: 100%;
                  }

                  .q-table.q-table-light tr:first-child {
                     background: transparent;
                     color: darkgrey;
                  }

                  .q-table.q-table-light {
                     box-shadow: none !important;
                     background: white !important;
                  }

                  .uk-overflow-container {
                     padding: 1.5em 0 1.5em 0;
                  }

                  #mydatatable_length {
                     display: none;
                  }

                  #mydatatable_filter {
                     display: none;
                  }

                  #mydatatable_paginate {
                     float: initial;
                     text-align: center
                  }

                  #mydatatable_paginate .paginate_button {
                     display: inline-block;
                     min-width: 16px;
                     padding: 3px 5px;
                     line-height: 20px;
                     text-decoration: none;
                     -moz-box-sizing: content-box;
                     box-sizing: content-box;
                     text-align: center;
                     background: #f7f7f7;
                     color: #444444;
                     border: 1px solid rgba(0, 0, 0, 0.2);
                     border-bottom-color: rgba(0, 0, 0, 0.3);
                     background-origin: border-box;
                     background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee);
                     background-image: linear-gradient(to bottom, #ffffff, #eeeeee);
                     text-shadow: 0 1px 0 #ffffff;
                     margin-left: 3px;
                     margin-right: 3px
                  }

                  #mydatatable_paginate .paginate_button.current {
                     background: #009dd8;
                     color: #ffffff !important;
                     border: 1px solid rgba(0, 0, 0, 0.2);
                     border-bottom-color: rgba(0, 0, 0, 0.4);
                     background-origin: border-box;
                     background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5);
                     background-image: linear-gradient(to bottom, #00b4f5, #008dc5);
                     text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);
                  }

                  #mydatatable_filter {
                     width: 100%
                  }

                  #mydatatable_filter label {
                     width: 100%;
                     display: inline-flex
                  }

                  #mydatatable_filter label input {
                     height: 30px;
                     width: 100%;
                     padding: 4px 6px;
                     border: 1px solid #dddddd;
                     background: #ffffff;
                     color: #444444;
                     -webkit-transition: all linear 0.2s;
                     transition: all linear 0.2s;
                     border-radius: 4px;
                  }
               </style>