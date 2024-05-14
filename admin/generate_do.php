<?php
include_once("../mysql.php");
include_once("../uikit1.php");

foreach ($_REQUEST as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value); //$order_no
}

function getFranchiseeInfo($order_no, &$actual_order_no, &$ordered_on, &$centre_name, &$address1, &$address2, &$address3, &$address4, &$address5, &$principle_name, &$principle_contact_no, &$operator_name, &$operator_contact_no, &$name, &$ic_no, &$courier, &$tracking_no, &$remarks)
{
   global $connection;

   $sql = "SELECT o.order_no, o.ordered_on, c.company_name, c.address1, c.address2, c.address3, c.address4, c.address5, c.principle_name,
   c.principle_contact_no, c.operator_name, c.operator_contact_no, o.name, o.ic_no, o.courier, o.tracking_no, o.remarks_delivery from centre c, `order` o where o.centre_code=c.centre_code and sha1(o.order_no)='$order_no'";
   //echo $sql;
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);
   $actual_order_no = $row["order_no"];
   $centre_name = $row["company_name"];
   $ordered_on = $row["ordered_on"];
   $ordered_on = date("d/m/Y", strtotime($ordered_on));
   $name = $row["name"];
   $ic_no = $row["ic_no"];
   $courier = $row["courier"];
   $tracking_no = $row["tracking_no"];
   $remarks = $row["remarks_delivery"];
   $address1 = $row["address1"];
   $address2 = $row["address2"];
   $address3 = $row["address3"];
   $address4 = $row["address4"];
   $address5 = $row["address5"];
   $principle_name = $row["principle_name"];
   $principle_contact_no = $row["principle_contact_no"];
   $operator_name = $row["operator_name"];
   $operator_contact_no = $row["operator_contact_no"];
}
$company_sql = "SELECT c.code, c.description from `order` o, product p, codes c 
where sha1(order_no)='$order_no' and p.product_code=o.product_code and p.category=c.code and c.module='CATEGORY' and c.parent='' group by p.category";
//echo $sql;
$company_result = mysqli_query($connection, $company_sql);
$company_num_row = mysqli_num_rows($company_result);

if ($company_num_row > 0) {
   getFranchiseeInfo($order_no, $actual_order_no, $ordered_on, $centre_name, $address1, $address2, $address3, $address4, $address5, $principle_name, $principle_contact_no, $operator_name, $operator_contact_no, $name, $ic_no, $courier, $tracking_no, $remarks);
   $active_company = [];
?>
   <script src="../lib/sign/js/jquery.signature.js"></script>
   <script type="text/javascript" src="../lib/sign/js/jquery.ui.touch-punch.min.js"></script>

   <style>
      html {
         height: 100%;
         box-sizing: border-box;
      }

      body {
         position: relative;
         margin: 0;
         padding-bottom: 6rem;
         min-height: 100%;
      }

      table {
         width: 100%;
         border-collapse: collapse;
      }

      .td-border {
         border: solid 1px #000000;
      }

      .td-no-border {
         border: 0px;
      }

      .hr-border {
         border-width: 1px;
         border-style: inset;
      }

      .font-30 {
         font-size: 30px;
      }

      .td-no-right-border {
         border-right: none;
      }

      .td-no-bottom-border {
         border-bottom: none;
      }

      .td-no-top-border {
         border-top: none;
      }

      .header-space,
      .footer-space {
         /* height: 60px; */
      }

      .kbw-signature {
         width: 370px;
         height: 300px;
         border: 1px;
         /* position: relative;
         float: right; */
      }

      .autho_text {
         /*margin-top: 300px;*/
         text-align: center;
         float: left;
		 position: relative;
		top: -70px;
      }

      .signature_text {
		margin-top: 160px;
         text-align: center;
         float: right;
      }

      @media print {
         .hidePrint {
            display: none;
         }

         .footer {
            position: fixed;
            bottom: 20px;
         }

         html,
         body {
            height: 92%;
         }

         .neverBreak {
            page-break-inside: avoid;
         }
      }
   </style>
   <div style="padding: 10px" class="hidePrint">
      <?php
      while ($company_row = mysqli_fetch_assoc($company_result)) {
         if ($active_company == []) {
            $active_company[] = $company_row['code'];
            $active_company[] = $company_row['description'];
         }
      ?>
         <button onclick="switchCompany('<?php echo $company_row['code'] ?>', this)" class="uk-button <?php echo $active_company[0] == $company_row['code'] ? 'uk-active' : '' ?>">Delivery Order for <?php echo $company_row['code'] ?></button>
      <?php } ?>
   </div>
   <br>
   <?php
   $company_result = mysqli_query($connection, $company_sql);
   while ($company_row = mysqli_fetch_assoc($company_result)) {
      $company_name = $company_row['code'];
   ?>
      <div class="uk-margin-left uk-margin-right so-content" id="company-<?php echo $company_row['code'] ?>" <?php echo $active_company[0] == $company_row['code'] ? '' : 'style="display:none"' ?>>
         <table>

            <tr>

               <td class="td-no-border" style="text-align:left;">

                  <?php $company_name = strtoupper($company_name);
                  //echo $company_name;
                  if ($company_name == "MINDSPECTRUM SDN.BHD") {
                  ?>
                     <img src="../images/MindSpectrum.png" alt="logo" style="width:150px;"><br>
                  <?php } elseif ($company_name == "Q-DEES HOLDINGS SDN. BHD.") { ?>
                     <img src="../images/Qdees-logo-n.png" alt="logo" style="width:150px;"><br>
                  <?php } elseif ($company_name == "Q-DEES GLOBAL PTE. LTD.") { ?>
                     <img src="../images/Qdees-logo-n.png" alt="logo" style="width:150px;"><br>
                  <?php } else { ?>
                     <img src="../images/Tenations.png" alt="logo" style="width:150px;"><br>
                  <?php } ?>
                  <?php echo strtoupper($company_name) ?><br>

                  <?php $company_name = strtoupper($company_name);
                  //echo $company_name;
                  if ($company_name == "MINDSPECTRUM SDN.BHD") {
                  ?>
                     Company No. :201401034359 (1110457-P)<br>

                  <?php } elseif ($company_name == "Q-DEES HOLDINGS SDN. BHD.") { ?>
                     Company No. :199101011232 (221544-V)<br>
                  <?php } elseif ($company_name == "Q-DEES GLOBAL PTE. LTD.") { ?>
                     Company No. :199101011232 (221544-V)<br>
                  <?php } else { ?>
                     Company No. :(480480-T)<br>
                  <?php } ?>
                  <!-- Company No. : <?php //echo $company_row['description'] 
                                       ?><br> -->
                  <!--1110457-P-->

               </td>
               <td class="td-no-border">

               </td>
            </tr>
         </table>
         <hr class="hr-border">
         <table>
            <tr>
               <td class="font-30">
                  <center>DELIVERY ORDER</center>
               </td>
            </tr>
         </table>
         <table>
            <col style="width: 60%">
            <col style="width: 40%">
            <tr>
               <td>
                  <?php echo $centre_name ?><br>
                  <?php echo $address1 ?><br>
                  <?php echo $address2 ?><br>
                  <?php echo $address3 ?><br>
                  <?php echo $address4 ?><br>
                  <?php echo $address5 ?><br>
                  ATTN : <?php echo $operator_name ?><br>
                  TEL. : <?php echo $operator_contact_no ?><br>
                  FAX : <?php echo $fax ?><br>
               </td>
               <td style="float: right;margin-right: 20px;">
                  <?php echo "Delivery Order No. : $actual_order_no" ?><br>
                  <?php echo "DATE : $ordered_on" ?><br>
                  <?php echo "NAME AS PER IC : $name" ?><br>
                  <?php echo "ID/IC/PASSPORT NUMBER : $ic_no" ?><br>
                  <?php echo "COLLECTION TYPE : $courier" ?><br>
                  <?php echo "TRACKING NUMBER : $tracking_no" ?><br>
                  <?php echo "REMARKS : $remarks" ?>
                  <div id="content">
                     <div id="pageFooter">PAGE : </div>
                  </div>
                  <?php //echo "PAGE : " 
                  ?><br><br>
               </td>
            </tr>
         </table>

         <table>
            <thead>
               <tr>
                  <td colspan="5">
                     <hr>
                  </td>
               </tr>
               <tr class="td-no-border">
                  <td>ITEM NO.</td>
                  <td>DESCRIPTION</td>
                  <td style="text-align:right">QTY</td>
               </tr>
               <tr>
                  <td colspan="3">
                     <hr>
                  </td>
               </tr>
            </thead>
            <tbody>
               <?php

               $sql = "SELECT o.*, p.product_name from `order` o, product p where sha1(order_no)='$order_no' and p.product_code=o.product_code and p.category='$company_name' and (o.cancelled_by = '' OR o.cancelled_by IS NULL) order by id";
               //echo $sql;
               $result = mysqli_query($connection, $sql);
               $num_row = mysqli_num_rows($result);

               if ($num_row > 0) {
                  $count = 0;
                  $grand_total = 0;
                  $total_qty = 0;
                  while ($row = mysqli_fetch_assoc($result)) {
                     $count++;
                     $remarks = $row["remarks"];
                     $grand_total = $grand_total + ($row["qty"] * $row["unit_price"]);
                     $total_qty = $total_qty + $row["qty"];
                     $name = $row["name"];
                     $ic_no = $row["ic_no"];
                     $tracking_no = $row["tracking_no"];
                     $signature = $row["signature"];
               ?>
                     <tr class="td-no-border">
                        <td><?php echo explode("((--",$row["product_code"])[0] ?></td>
                        <td><?php echo $row["product_name"] ?></td>
                        <td style="text-align:right"><?php echo number_format($row["qty"], 0) ?></td>
                     </tr>
               <?php
                  }
               }
               ?>
            </tbody>
            <tfoot>
               <tr>
                  <td colspan="3">
                     <hr>
                  </td>
               </tr>
               <tr>
                  <td colspan="2"><span style="float: right">Total : </span></td>
                  <td style="text-align:right"><?php echo number_format($total_qty, 0) ?></td>
               </tr>
               <tr>
                  <td>
                     <div class="footer-space">&nbsp;</div>
                  </td>
               </tr>
            </tfoot>
         </table>

         <div>
            Kindly examine your order upon delivery to ensure that there are no visible signs of damaged, missing or incorrect quantity. In the event that there are missing, damaged or incorrect packages or quantity, please retain the item(s), indicate the problem on the Delivery Order and contact us immediately of your delivery. A signed delivery receipt herein, without notations of missing, damaged, incorrect item(s) or quantity, represents your acceptance of the complete order in perfect condition and thus, no further claims either damages or replacement shall be attend by Q-dees.
            <!-- <?php //echo htmlspecialchars($remarks); 
                  ?> -->

         </div><br>
         <div>Remarks:<br>
This receipt is auto generated therefore no signature is required.
         </div>
         <!--p style="text-align:left;" class="footer">This is computer generated Delivery Order. No signature required.</p-->
      </div>
   <?php
   }
   ?>
   <div class="uk-margin-left uk-margin-right">
      <table class="neverBreak" style="align:right">
         <tr>
            <td style="width:50%;float: left;">
			    <input type="hidden" id="signaturejson" value='<?php echo $signature ?>'>
               <div id="signature"></div>
               <div class="autho_text">
                  __________________________<br>
                  AUTHORISED SIGNATURE & I.C.<br>
                  (COMPANY STAMP)</div>

               <!-- <table style="">
                  <tr>
                     <td>Name : <?php //echo $name 
                                 ?></td>
                  </tr>
                  <tr>
                     <td>IC No : <?php //echo $ic_no 
                                 ?></td>
                  </tr>
                  <tr>
                     <td>Tracking No. : <?php //echo $tracking_no 
                                          ?></td>
                  </tr>
               </table> -->
            </td>
            <td style="width:50%;">
           
               <div class="signature_text"> __________________________<br>
                  <?php echo strtoupper($company_name) ?> </div>
            </td>

         </tr>
         <tr>

         </tr>
      </table>
   </div>
   <div style="padding: 10px" class="hidePrint">
      <br>
      <button onclick="printDialog();" id="btnPrint" class="uk-button">Print</button>
   </div>
   <style>
      #content {
         display: table;
      }

      #pageFooter {
         display: table-footer-group;
      }

      #pageFooter:after {
         counter-increment: page;
         content: counter(page);
      }
   </style>
   <script>
      function printDialog() {
         //$("#btnPrint").hide();
         window.print();
      }

      function switchCompany(company, curEl) {
         [].forEach.call(document.querySelectorAll('.uk-active'), function(el) {
            el.classList.remove("uk-active");
         });
         [].forEach.call(document.querySelectorAll('.so-content'), function(el) {
            el.style.display = 'none';
         });
         curEl.classList.add("uk-active");
         document.getElementById('company-' + company).style.display = "block";
      }
      $(document).ready(function() {
         $("#signature").signature({
            background: '#FFFFFF'
         });
         $("#signature").signature('draw', $("#signaturejson").val());
         $("#signature").signature("disable", true);
      });
   </script>
<?php
}
?>