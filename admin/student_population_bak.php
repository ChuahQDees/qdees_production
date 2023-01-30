<a href="/">
				 <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Student Population-1.png">Student Population</span>
</span>

<?php
include_once("admin/functions.php");
echo "abcc".$browse_sql;

if( ! defined('STUDENT_PHOTO_URL') ){
  define('STUDENT_PHOTO_URL', '/admin/student_photo/');
}

if( ! defined('STUDENT_PHOTO_PATH') ){
  define('STUDENT_PHOTO_PATH', __DIR__ . '/student_photo/');
}

function getTotalStudents($centre_code){
  global $connection;

  $sql = "SELECT COUNT(id) as total FROM student WHERE centre_code ='". mysqli_real_escape_string($connection,$centre_code) ."' AND deleted=0";
  $result=mysqli_query($connection, $sql);
  $row=mysqli_fetch_assoc($result);

  return $row['total'];
}

function getStudentPhotoSrc($file_name, $size = 'small'){
  $file_url = '';

  if( $file_name ){
    switch($size){
      case 'medium':
        $size = '300x400';
        break;
      default:
        $size = '60x80';
        break;
    }
    $file_path = STUDENT_PHOTO_PATH . $file_name . '_' . $size . '.jpg';

    if( file_exists($file_path) ):
      $file_url = STUDENT_PHOTO_URL . $file_name . '_' . $size . '.jpg';
    endif;
  }

  return $file_url;
}

function displayStudentStatus($status){
	switch($status){
		case "A":
 	  	return "Active";
    case "D":
 	  	return "Deferred";
    case "G":
 	  	return "Graduated";
    case "O":
 	  	return "Dropout";
    case "S":
 	  	return "Suspended";
    case "T":
 	  	return "Transferred";
		default:
			return $status;
	}
}

if ($_SESSION["isLogin"]==1) {
    if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
        (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))) {

        include_once("lib/pagination/pagination.php");
        $p=$_GET["p"];
        $m=$_GET["m"];
        $get_sha1_id=$_GET["id"];
        $pg=$_GET["pg"];
        $mode=$_GET["mode"];

        if ($mode=="") {
            $mode="ADD";
        }

        include_once("student_func.php");

        ?>

        <script>
            function doDeleteRecord(id) {
                UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
                    $("#id").val(id);
                    $("#frmDeleteRecord").submit();
                });
            }
        </script>

        <div class="uk-margin-right">
            <h3>Total Students: <?php echo getTotalStudents($centre_code); ?></h3>
            <div class="uk-width-1-1 myheader">
                <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
            </div>
            <?php
            $numperpage=20;
            $query="p=$p&m=$m&s=$s";

            $order_query = $query;
            $order_arrow = "&uarr;";
            if ($_GET['order'] == 'asc') {
              $order_query.="&order_by=name&order=desc";
              $order_arrow = "&uarr;";
            }else if ($_GET['order'] == 'desc') {
              $order_query.="&order_by=name&order=asc";
              $order_arrow = "&darr;";
            }else{
              $order_query.="&order_by=name&order=desc";
              $order_arrow = "&uarr;";
            }

            if (isset($_GET["order_by"]) && isset($_GET["order"])) {
              $order_by=mysqli_real_escape_string($connection, $_GET["order_by"]);
              $order=mysqli_real_escape_string($connection,$_GET["order"]);
              $browse_sql.=" ORDER BY " . $order_by . " " . $order . " ";

              $query.="&order_by=" .  $_GET["order_by"] . "&order=" . $_GET['order'];
            }else{
              $browse_sql.=" ORDER BY name asc ";
            }

            $pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
            $browse_sql.=" limit $start_record, $numperpage";

            $browse_result=mysqli_query($connection, $browse_sql);
            $browse_num_row=mysqli_num_rows($browse_result);

            echo $pagination;
            ?>
            <style>
                table tr:not(:first-child):nth-of-type(even) {
                    background: #f5f6ff;
                }

                table td {
                    color: grey;
                    font-size: 1.2em;
                }

                table td {border: none!important;}
            </style>
            <form class="uk-form" name="frmStudentSearch" id="frmStudentSearch" method="get">
                <input type="hidden" name="mode" id="mode" value="BROWSE">
                <input type="hidden" name="p" id="p" value="<?php echo $p?>">
                <input type="hidden" name="m" id="m" value="<?php echo $m?>">
                <input type="hidden" name="pg" value="">

                <div class="uk-grid">
                    <div class="uk-width-7-10 uk-text-small">
                        <input class="uk-width-1-1" placeholder="Student Code/Name/Tel/Email" name="s" id="s" value="<?php echo $_GET['s']?>">
                    </div>
                    <div class="uk-width-3-10">
                        <button class="uk-button uk-width-1-1 full-width-blue">Search</button>
                    </div>
                </div>
            </form>

            <div class="uk-width-1-1 myheader">
                <h2 class="uk-text-center myheader-text-color myheader-text-style">Listing</h2>
            </div>
            <div class="uk-overflow-container">
                <table class="uk-table">
                    <tr class="uk-text-bold uk-text-small">
                        <td>Photo</td>
                        <td data-uk-tooltip="{pos:top}" title="Student Code">S. Code</td>
                        <td><a href="index.php?<?php echo $order_query; ?>">Name <?php echo $order_arrow; ?></a></td>
                        <td>Gender</td>
                        <td>Age</td>
                        <td>Mykid / Passport No.</td>
                        <td>Status</td>
                        <td>Action</td>
                    </tr>
                    <?php
                    if ($browse_num_row>0) {
                        while ($browse_row=mysqli_fetch_assoc($browse_result)) {
                            $sha1_id=sha1($browse_row["id"]);
                            ?>
                            <tr class="uk-text-small">
                                <td>
                                  <?php
                                    $student_photo_src = getStudentPhotoSrc($browse_row['photo_file_name']);
                                    if( $student_photo_src ){
                                      echo '<img src="' . $student_photo_src . '" width="60px" height="80px">';
                                    }
                                  ?>
                                </td>
                                <td><?php echo $browse_row["student_code"]?></td>
                                <td><?php echo $browse_row["name"]?></td>
                                <td><?php echo $browse_row["gender"]?></td>
                                <td>
                                  <?php
                                    $age = date('Y',strtotime($year_start_date)) - date('Y',strtotime($browse_row['dob'])); 

                                    echo $age; 
                                  ?>
                                </td>
                                <td><?php echo $browse_row["nric_no"]?></td>
                                <td><?php echo displayStudentStatus($browse_row["student_status"])?></td>
                                <td>
                                    <?php
                                    if (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit")) {
                                        ?>
                                        <a href="index.php?p=student_reg&m=<?php echo $m?>&id=<?php echo $sha1_id?>&mode=EDIT"><i class="fas fa-user-edit" style="font-size: 1.3em;"></i></a>
                                        <a href="index.php?p=dropout&sid=<?php echo sha1($browse_row['id'])?>" data-uk-tooltip title="Dropout <?php echo $browse_row['name']?>" id="btnDelete"><i style="font-size: 1.3em; color: #FF6e6e;" class="fas fa-box-open"></i></a>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='8'>No Record Found</td></tr>";
                    }
                    ?>
                </table>
            </div>
            <?php
            echo $pagination;
            ?>

        </div>

        <form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
            <input type="hidden" name="p" value="<?php echo $p?>">
            <input type="hidden" name="m" value="<?php echo $m?>">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="mode" value="DEL">
        </form>

        <?php
        if ($msg!="") {
            echo "<script>UIkit.notify('$msg')</script>";
        }
    } else {
        echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
    }
} else {
    header("Location: index.php");
}
?>
