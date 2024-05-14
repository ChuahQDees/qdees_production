<div class="student-page">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-4 col-sm-12 d-flex justify-content-center">
            <div>
                <div class="text-center student-block d-flex justify-content-center align-items-center"  style="cursor:pointer;">
                        <div class="genQR" data-toggle="modal" data-target=".modal">
                            <img src="images/Category/qr.png" style="width: 100px; height: 100px;">
                            <p>Visitor Registration QR Code</p>
                        </div>
                    <div class="modal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header myheader">
                                    <h5 class="uk-text-center myheader-text-color myheader-text-style">QR Code</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
									<a href="admin/visitor_qrcode.php">
										<img src="admin/visitor_qrcode.php" alt=""><br/><br>
										<button class="uk-button uk-button-primary form_btn">Download</button>
									</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="toCopy" style="margin-top: 25px;" class="text-center"><span style="background-color: #3399ff;">(Copy link)</span></div>
                <a target="_blank" target="_blank" href="visitor_qr.php?centre_code=<?php echo $_SESSION['CentreCode']?>"><div style="margin-top: 25px;" class="text-center"><span style="font-size: 1rem; cursor: pointer; border-radius: 8px; padding: .5em 1.5em; background:transparent !important; border: 1px solid #6F757D; color: #6F757D ">Open in new tab</span></div></a>
            </div>
        </div>
		<div class="col-md-4 col-sm-12 d-flex justify-content-center">
            <a target="_self" href="index.php?p=visitor_qr_list">
                <div class="text-center student-block d-flex justify-content-center align-items-center">
                    <div>
                        <img src="images/Category/VisitorReg.png" style="width: 120px; height: 100px;">
                        <p>Visitor Registration Listing</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>

<style>
    a {
        color: initial
    }

    a:hover{color: initial; text-decoration: none}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.10/clipboard.min.js"></script>
<script>

    $('.modal').on('shown.bs.modal', function () {
        $('.genQR').trigger('focus')
    });

    let copyurl = [location.protocol, '//', location.host, location.pathname.replace(/[^/]*$/, '')].join('')+"visitor_qr.php?centre_code=<?php echo $_SESSION['CentreCode']?>";

    // Tooltip

    $('#toCopy').tooltip({
        trigger: 'hover',
        placement: 'bottom'
    });

    function setTooltip(message) {
        $('#toCopy').tooltip('hide')
            .attr('data-original-title', message)
            .tooltip('show');
    }

    function hideTooltip() {
        setTimeout(function() {
            $('#toCopy').tooltip('hide');
        }, 1000);
    }

    new Clipboard('#toCopy', {
        text: function() {
            return copyurl;
        }
    });

    $('#toCopy').hover(function() {
        setTooltip('Copy');
        hideTooltip();
    });


</script>