<div class="uk-width-1-1 myheader">

      <div class="uk-grid">
         <div class="uk-width-1-1">
            <h2 class="uk-text-center myheader-text-color myheader-text-style">Terms & Condition</h2>
         </div>
      </div>
</div>
<div style=" padding: 15px; border-radius: 0 0 20px 20px; background: white; box-shadow: 0px 13px 21px -10px rgba(0, 0, 0, 0.15)!important;color: gray;" class="termsCon">
<!----<h2 class="text-center" style="color: #f26d6f">YOUR ORDER #<?php echo (! empty($_GET['order_no']) ? htmlspecialchars($_GET['order_no']) : 'ERROR') ?> HAS BEEN PLACED SUCCESSFULLY!</h2>------>

    <h4><b>1)	Orders:</b></h4>
    i.	Kindly be informed that all Term Orders will take 3 - 4 weeks of processing. Please make sure to place your orders earlier to ensure a timely delivery.
<br>ii.	Any additional orders will take 3 - 5 working days to process.

    <h4><b>2)	Self-collection/Collection with representative:</b></h4>

    i.	Please inform HQ at least 3 days prior to your pick up to ensure a smooth collection during your visit to HQ.
<br>
    ii.	Do note that the franchisee/representative is required to calculate the stocks upon collection. <br>
    •	This is to ensure you receive the accurate quantity as per ordered. <br>
    •	Any shortages and/or defects upon collection can be rectified immediately (except for uniform and gym wear stocks)
<br>
    iii.	HQ is not responsible for any shortages or miscalculation if franchisee/representative fails to count the stocks upon collection.
<br>
    iv.	Franchisee/representative is required to fill up the Confirmation of Receipt Goods upon collection of stocks.
<br>
    v.	Please inform HQ in advance via email/WhatsApp if you are unable to collect your order or stocks. HQ will reschedule your collection to a later date.


    <h4><b>3)	Self-arranged Delivery/Self-arranged Pick Up</b></h4>
    i.	Please inform HQ at least 3 days prior to requesting any Transporter/Courier Service to collect the stocks at HQ to ensure that the stocks will be ready for collection and to avoid any inconvenience.
<br>
    ii.	Franchisee is given 3 working days to inform HQ upon receiving the stocks should there be any shortages or defects.
<br>
    •	In any case of shortages or defects, Franchisee is required to fill up the defective form and submit it to HQ within 3 working days.
<br>
    iii.	Please inform HQ in advance via email/WhatsApp if your Transporter/Courier is unable to collect your order or stocks. HQ will reschedule your collection to a later date.

    <h4><b>4)	Payment</b></h4>
    i.	Payment can be made through Online Payment/Cash/Cheque/TT Transfer. <br>
    •	Goods will be withheld should the franchisee be unable to produce the relevant bank-in slips for collection even though the franchisee may insist that payment has been made.
<br>ii.	Payment is payable to: <br><br>

    STOCKS FEE	Q-DEES HOLDINGS SDN BHD	MBB 0141-9620-9204 <br>
    TENATIONS GLOBAL SDN BHD	MBB 5141-9633-5218 <br>
    MINDSPECTRUM SDN BHD	MBB 5141-9666-1506 <br>
    ROYALTY FEE	Q-DEES WORLDWIDE EDUSYSTEMS (M) SDN BHD	MBB 5141-9631-4454 <br><br>


    iii.	The Franchisee shall pay to Franchisor Royalty Fee, Advertising & Promotion Fee and Software License Fee, according to the terms and conditions stated in this declaration, in Ringgit Malaysia on a monthly basis, on the 1st day but not later than the 5th day of each month. Late payments of the Royalty Fee shall be levied with an additional 1.5% charge per month.
    iv.	All outstanding payments MUST be settled before stocks can be released for collection. <br><br>

    <h4><b>5)	Payments & Report</b></h4>
    i.	For monthly Royalty, A&P and Software License Payments & Report, please email to: <br>
    •	Centre Growth & Operations department: operations@q-dees.com <br><br>

    •	Finance department:
    Royalty: jaya@q-dees.com (Jayashree) <br>
    Stock Payment: financeholdings@q-dees.com (Kevin) <br>
    Note: Please cc a copy to your respective CGO Consultant <br><br>

    ii.	For orders and stock collections, please contact: <br>
    Name: Diana <br>
    Tel: 017-6202 133 <br>
    Email: operations@q-dees.com <br>
    Note: Please cc a copy to your respective CGO Consultant <br><br>

    Name: Eera <br>
    Tel: 012-393 1800 <br>
    Email: enquiry@q-dees.com <br>
    Note: Please cc a copy to your respective CGO Consultant <br><br>

    <h4><b>6)	Operating Hours</b></h4>
    Monday to Friday: 10.00am – 4.00pm <br>
    Lunch hours:  <br>
    Monday to Thursday: 12.30pm – 1.30pm <br>
    Friday: 1.00pm-2.45pm <br><br>

    Should you require assistance for stock collection, please do not hesitate to contact OPS (Diana/Eira) at 1700-81-5077 or operations@q-dees.com. You may also Whatsapp 017-6202133 or your respective Operations Consultant.

	<div class="text-center" style="margin-top: 25px;"><a href="/" class="modal_btn" style="padding: .6em 1.5em;" >Close</a></div>
</div>

<!-- <script src="lib/sweetalert/sweetalert.min.js"></script> -->
<link rel="stylesheet" type="text/css" href="lib/jquery_modal/jquery.modal.css">
<script src="lib/jquery_modal/jquery.modal.js"></script>
<?php
$msg="Order No: ".$_GET["order_no"];
?>

<script>
$(document).ready(function () {
   $("#dlgOrder").modal({
      clickClose: false
   });
});
</script>

<div id="dlgOrder" class="modal">
   <div class="text-center"><img src="images/ThankyouOrder.png"  style=""></div>
   <h2 class="text-center" style="color: #7fdcda;margin-top:0px;">Thank you for your order!</h1>
   <h3 class="text-center"><strong>Order No:</strong> #<?php echo (! empty($_GET['order_no']) ? htmlspecialchars($_GET['order_no']) : 'ERROR') ?></h2>
   <p class="text-center">You may generate the Sales Order here for payment reference.</p>
   <div class="text-center" style="margin-top: 25px;"><a href="admin/generate_so.php?order_no=<?php echo sha1($_GET['order_no']); ?>" target="_blank" class="modal_btn" style="background:transparent !important; border:1px solid darkgrey; color:darkgrey;text-shadow: none;padding: .6em 1.5em;" >Generate SO</a></div>
   <div class="text-center" style="margin-top: 25px;"><a href="#" class="modal_btn" rel="modal:close" style="padding: .6em 3.3em;">Close</a></div>
</div>
