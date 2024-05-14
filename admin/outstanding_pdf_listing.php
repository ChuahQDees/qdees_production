<?php 
session_start();
include_once("../mysql.php"); 
?>

<thead>
    <tr class="uk-text-bold uk-text-small">
        <td>Generated Date & Time </td>
        <td>Month</td>
        <td>Student Code/Name</td>
        <td>PDF</td>
    </tr>
</thead>

<tbody>
    <?php
			
        $result = mysqli_query($connection,"SELECT * FROM `outstanding_pdf` WHERE `centre_code` = '".$_SESSION['CentreCode']."' AND `year` = '".$_SESSION['Year']."' ORDER BY `id` DESC");
						
        $num_row = mysqli_num_rows($result);

        if ($num_row>0) {
            while ($browse_row=mysqli_fetch_assoc($result)) {
                $sha1_id=sha1($browse_row["id"]);
    ?>
                <tr class="">
                    <td><?php echo $browse_row["created_at"]?></td>
                    
                    <td><?php echo ($browse_row["month"] == '13') ? 'All Months' : date('M Y',strtotime($browse_row["month"])); ?></td>
                    <td><?php echo $browse_row["student"]?></td>
                    <td style="width:120px;text-align:centre;">
                        <?php if($browse_row['is_generated'] == 1) { ?>

                            <a href="admin/outstanding_report_pdf/<?php echo $browse_row['pdf_name']; ?>" data-uk-tooltip download
                            title="Download Pdf"><i class="fas fa-download"></i></a>

                        <?php } else if($browse_row['is_generated'] == 0) { echo 'Processing...'; } ?>

                    </td>
                </tr>
    <?php
            }
        } else {
            echo "<tr><td colspan='4'>No Record Found</td></tr>";
        }
    ?>
</tbody>