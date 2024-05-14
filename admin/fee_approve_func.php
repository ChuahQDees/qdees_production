<?php
session_start();

      include_once("mysql.php");
      foreach ($_GET as $key=>$value) {
         $key=$value;
      }
	  
		$sha1_id=$_GET["id"];
		$mode=$_GET["mode"];
		$centre_code = $_SESSION["CentreCode"];
		$get_sha1_fee_id=$_POST['fee_id'];
		$from_date= $_POST['from_date'];
		$from_date=convertDate2ISO($from_date);
		$extend_year = explode('-', $from_date)[0];
      if (isset($mode) and $mode=="EDIT" && $get_sha1_fee_id!="") {
		 if(isset($_POST['fees_structure'],$_POST['subject'],$_POST['programme_package'],$_POST['from_date'],$_POST['to_date'],$_POST['school_adjust'])) {
		$actionButton =	$_POST['actionButton'];
		$remarks_master = $_POST['remarks_master'];
		$adjusted = $_POST['adjusted'];
		
		//print_r($_FILES); die;
		$tmp_document=$_FILES["file_s"]["tmp_name"];
		$file_extn = explode(".", strtolower($_FILES["file_s"]["name"]))[1];
		if($file_extn!=""){
			$files_filename=generateRandomString(8).".$file_extn";
			copy($tmp_document, 'admin/uploads/'.$files_filename);
		}
		 
		if($actionButton == 'Update')
		{
			$save_sql="update  fee_structure set 
			fees_structure='".$_POST['fees_structure']."',
			subject='".$_POST['subject']."',			
			programme_package='".$_POST['programme_package']."',
			from_date='".$_POST['from_date']."',			
			to_date='".$_POST['to_date']."', 
			school_default='".$_POST['school_default']."', 
			school_adjust='".$_POST['school_adjust']."', 
			school_collection='".$_POST['school_collection']."',
			school_default_perent='".$_POST['school_default_perent']."',
			multimedia_default='".$_POST['multimedia_default']."',
			multimedia_adjust='".$_POST['multimedia_adjust']."',
			multimedia_collection='".$_POST['multimedia_collection']."',
			multimedia_default_perent='".$_POST['multimedia_default_perent']."',
			facility_default='".$_POST['facility_default']."',
			facility_adjust='".$_POST['facility_adjust']."',
			facility_collection='".$_POST['facility_collection']."',
			facility_default_perent='".$_POST['facility_default_perent']."',
			enhanced_default='".$_POST['enhanced_default']."',
			enhanced_adjust='".$_POST['enhanced_adjust']."',
			enhanced_collection='".$_POST['enhanced_collection']."',
			enhanced_default_perent='".$_POST['enhanced_default_perent']."',
			school_total_d='".$_POST['school_total_d']."',
			school_total_f='".$_POST['school_total_f']."',
			iq_math_default='".$_POST['iq_math_default']."',
			iq_math_adjust='".$_POST['iq_math_adjust']."',
			iq_math_collection='".$_POST['iq_math_collection']."',
			iq_math_default_perent='".$_POST['iq_math_default_perent']."',
			mandarin_default='".$_POST['mandarin_default']."',
			mandarin_adjust='".$_POST['mandarin_adjust']."',
			mandarin_collection='".$_POST['mandarin_collection']."',
			mandarin_default_perent='".$_POST['mandarin_default_perent']."',
			international_default='".$_POST['international_default']."',
			international_adjust='".$_POST['international_adjust']."',
			international_collection='".$_POST['international_collection']."',
			international_perent='".$_POST['international_perent']."',
			total_default_c='".$_POST['total_default_c']."',
			total_adjust_c='".$_POST['total_adjust_c']."',
			integrated_default='".$_POST['integrated_default']."',
			integrated_adjust='".$_POST['integrated_adjust']."',
			integrated_collection='".$_POST['integrated_collection']."',
			integrated_default_perent='".$_POST['integrated_default_perent']."',
			link_default='".$_POST['link_default']."',
			link_adjust='".$_POST['link_adjust']."',
			link_collection='".$_POST['link_collection']."',
			link_default_perent='".$_POST['link_default_perent']."',
			mandarin_m_defauft='".$_POST['mandarin_m_defauft']."',
			mandarin_m_adjust='".$_POST['mandarin_m_adjust']."',
			mandarin_m_collection='".$_POST['mandarin_m_collection']."',
			mandarin_m_default_perent='".$_POST['mandarin_m_default_perent']."',
			total_default_d='".$_POST['total_default_d']."',
			total_adjust_d='".$_POST['total_adjust_d']."',
			basic_default='".$_POST['basic_default']."',
			basic_default_perent='".$_POST['basic_default_perent']."',
			basic_adjust='".$_POST['basic_adjust']."',
			basic_collection='".$_POST['basic_collection']."',
			afternoon_robotic_default='".$_POST['afternoon_robotic_default']."',
			afternoon_robotic_adjust='".$_POST['afternoon_robotic_adjust']."',
			afternoon_robotic_collection='".$_POST['afternoon_robotic_collection']."',
			mobile_default='".$_POST['mobile_default']."',
			mobile_adjust='".$_POST['mobile_adjust']."',
			mobile_collection='".$_POST['mobile_collection']."',
			mobile_perent='".$_POST['mobile_perent']."',
			afternoon_robotic_default_perent='".$_POST['afternoon_robotic_default_perent']."',
			robotic_plus_default_perent='".$_POST['robotic_plus_default_perent']."',
			registration_default='".$_POST['registration_default']."',
			registration_adjust='".$_POST['registration_adjust']."',
			insurance_default='".$_POST['insurance_default']."',
			insurance_adjust='".$_POST['insurance_adjust']."',
			uniform_default='".$_POST['uniform_default']."',
			uniform_adjust='".$_POST['uniform_adjust']."',
			gymwear_default='".$_POST['gymwear_default']."',
			gymwear_adjust='".$_POST['gymwear_adjust']."',
			q_dees_default='".$_POST['q_dees_default']."',
			q_dees_adjust='".$_POST['q_dees_adjust']."',
			q_bag_default='".$_POST['q_bag_default']."',
			q_bag_adjust='".$_POST['q_bag_adjust']."',
			total_default_f='".$_POST['total_default_f']."',
			total_adjust_f='".$_POST['total_adjust_f']."',
			islam_default='".$_POST['islam_default']."',
			islam_adjust='".$_POST['islam_adjust']."',
			islam_collection='".$_POST['islam_collection']."',
			robotic_plus_default='".$_POST['robotic_plus_default']."',
			robotic_plus_adjust='".$_POST['robotic_plus_adjust']."',
			robotic_plus_collection='".$_POST['robotic_plus_collection']."',

			stem_programme_default='".$_POST['stem_programme_default']."',
			stem_programme_default_parent='".$_POST['stem_programme_default_parent']."',
			stem_programme_adjust='".$_POST['stem_programme_adjust']."',
			stem_programme_collection='".$_POST['stem_programme_collection']."',

			stem_studentKit_default='".$_POST['stem_studentKit_default']."',
			stem_studentKit_default_parent='".$_POST['stem_studentKit_default_parent']."',
			stem_studentKit_adjust='".$_POST['stem_studentKit_adjust']."',
			stem_studentKit_collection='".$_POST['stem_studentKit_collection']."',

			remarks='".$_POST['remarks']."',
			adjusted='$adjusted', doc_remarks='$files_filename', remarks_master = '$remarks_master' ";
			$save_sql .= "where sha1(id)='$get_sha1_fee_id'";
		}
		else
		{
			$time_date= date("Y-m-d H:i:s");

			$save_sql="update  fee_structure set status='$actionButton', adjusted='$adjusted', doc_remarks='$files_filename', remarks_master = '$remarks_master', approval_date = '$time_date'";
			$save_sql .= "where sha1(id)='$get_sha1_fee_id'";
		}
            
            //mysqli_query($connection, $sql);
		//echo $product_photo_filename; die;
		 //echo $_POST['actionButton']; die;
		//echo $save_sql; die;
		$result=mysqli_query($connection, $save_sql);
		if($result){
			echo "<script type='text/javascript'>window.top.location='index.php?p=fee_approve&mode=EDIT&msg=Record Updated';</script>";
			$msg="Record Updated";
		}else{
			$msg="Failed to save data";
		}
      }else{
		  
	  }
	  
	  }
	
?>