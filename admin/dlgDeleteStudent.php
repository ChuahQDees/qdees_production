<?php
    session_start();

    include_once("../mysql.php");

    $student_sid = $_REQUEST['student_sid'];
    $request = $_REQUEST['request'];

    if($request == 'view_request')
    {
      $student_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `id`,`delete_request`,`delete_document`,`delete_remarks` FROM `student` WHERE id = '".$student_sid."'"));
?>
      <table class="uk-table uk-table-small uk-table-striped">

         <input type="hidden" name="student_sid" value="<?php echo $student_sid; ?>">
         <input type="hidden" name="request" value="<?php echo $request; ?>">
         <tr class="uk-text-small">

            <td class="uk-text-bold">Document</td>
            <td>
               <?php if($student_data['delete_document'] != '') { ?>
                  <a href="admin/uploads/<?php echo $student_data['delete_document']; ?>" target="_blank" ><img src="admin/uploads/<?php echo $student_data['delete_document']; ?>" style="width:100%;"></a>
               <?php } else { ?>
                  -
               <?php } ?>
            </td>

         </tr>

         <tr class="uk-text-small"> 

            <td class="uk-text-bold">Remarks</td>
            <td><?php echo $student_data['delete_remarks']; ?></td>

         </tr>
         <tr class="uk-text-small"> 
            <td colspan="2" style="text-align:center;" >
         <?php
            if($student_data['delete_request'] == 3) 
            {
				//CHS: Notification ID to tell that the notification is done
				$notification_id = $_REQUEST['notification_id'];
         ?>
               <span class="btn btn-rounded btn-success ml-1  delete<?php echo sha1($student_data['id']); ?>" onclick="doDeleteStudentRequest('<?php echo sha1($student_data['id']); ?>','approve_request','<?php echo $notification_id ?>')" data-uk-tooltip="{pos:top}" title="Approve Request"><i style="cursor:pointer;" class="fas fa-check" ></i> Approve</span>

               <span class="btn btn-rounded btn-danger ml-1 delete<?php echo sha1($student_data['id']); ?>" onclick="doDeleteStudentRequest('<?php echo sha1($student_data['id']); ?>','reject_request', '<?php echo $notification_id ?>')" data-uk-tooltip="{pos:top}" title="Reject Request"><i style="cursor:pointer;" class="fas fa-close "></i> Reject</span>
                  
         <?php
            }
            else if($student_data['delete_request'] == 1)
            {
         ?>
               <h3 style="color:green;">Approved</h3>
         <?php
            }
            else if($student_data['delete_request'] == 2)
            {
         ?>
               <h3 style="color:red;">Rejected</h3>
         <?php
            }
         ?>
            </td>
         </tr>
      </table>
<?php
    }
    else
    {
?>
      <script>

         function doDeleteStudentRequest(id) {

            var theform = $("#frmDeleteStudent")[0];

            var formdata = new FormData(theform);
            var filesize = 0;
            var validate = true;

            var remarks = $('delete_remarks').val();
            if(remarks == '')
            {
               UIkit.notify('Please enter remarks');
               validate = false;
            }

            if(validate){
               $.ajax({

                  url : "admin/delete_student_request.php",

                  type : "POST",

                  data : formdata,

                  enctype: 'multipart/form-data',

                  processData: false,

                  contentType: false,

                  beforeSend : function(http) {

                  },

                  success : function(response, status, http) {
                     var s=response.split("|");
                     if (s[0]=="1") {
                     UIkit.notify(s[1]);
                     $('#dlgDeleteStudent').dialog('close');
                     //location.reload();
                        $('#delete'+id).remove();
                     } else {

                        UIkit.notify(s[1]);
                     }
                  },
                  error : function(http, status, error) {
                     UIkit.notify("Error:"+error);
                  }

               });
            }
         }

         </script>

         <form class="uk-form" name="frmDeleteStudent" id="frmDeleteStudent">

         <table class="uk-table uk-table-small uk-table-striped">

            <input type="hidden" name="student_sid" value="<?php echo $student_sid; ?>">
            <input type="hidden" name="request" value="<?php echo $request; ?>">
            <tr class="uk-text-small">

               <td class="uk-text-bold">Document</td>

               <td><input type="file" id="delete_document" name="delete_document" accept=".doc, .docx, .pdf, .png, .jpg, .jpeg, .csv, .xls, .xlsx"></td>

            </tr>

            <tr class="uk-text-small"> 

               <td class="uk-text-bold">Remarks</td>

               <td><textarea class="uk-width-1-1" id="delete_remarks" name="delete_remarks"></textarea></td>

            </tr>

         </table>

         <div class="uk-text-center">

            <a onclick="doDeleteStudentRequest('<?php echo $student_sid; ?>')" class="uk-button uk-button-primary"  style="padding: 0 12px;">Submit</a>

            <a onclick="$('#dlgDeleteStudent').dialog('close');" class="uk-button">Cancel</a>

         </div>

         </form>
<?php
    }
?>
