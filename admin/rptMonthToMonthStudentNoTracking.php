<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

if ($_SESSION["isLogin"]==1) {
    if ($_SESSION["UserName"]=="management") {
?>
<style>
     .overlay{
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 999;
        background: rgba(255,255,255,0.8) url("https://www.tutorialrepublic.com/examples/images/loader.gif") center no-repeat;
    }
    
    /* Turn off scrollbar when body element has the loading class */
    body.loading{
        overflow: hidden;   
    }
    /* Make spinner image visible when body element has the loading class */
    body.loading .overlay{
        display: block;
    }
    
    .uk-tab > li.uk-active > a {
        border : 1px solid #dddddd;
    }

    .uk-tab > li > a {
        padding: 3px 12px 3px 12px;
    }
</style>
<div class="overlay"></div>

  <span class="page_title"><img src="/images/title_Icons/Student Population-1.png">Month to Month Student No Tracking</span>

    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color myheader-text-style">SEARCHING</h2>
    </div>
    <div class="uk-overflow-container uk-form nice-form">
    <div class="d-flex uk-grid uk-grid-small">
        <div class="uk-width-medium-2-10" style="display:none;">
        Centre<br>
        <?php
            $sql = "SELECT * from centre order by centre_code";
            $result = mysqli_query($connection, $sql);
        ?>
            <input list="centre_name" id="screens.screenid" name="centre_name" value="">

            <datalist class="form-control" id="centre_name" style="display: none;">

            <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?> >All Centres</option>

            <?php
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <option value="<?php echo $row['centre_code'] ?>" <?php echo $row['centre_code'] == $centreCode ? 'selected' : '' ?>><?php echo $row["company_name"] ?></option>

            <?php
                }
            ?>

            </datalist>
        </div>

        <div class="uk-width-2-10" style="width: auto;">
          <br>
            <ul class="uk-tab" style="border-bottom:none;" data-uk-tab="{connect:'#my-id'}">
                <li onclick="generateBalReport()" ><a href="" >Student No</a></li>
                <li onclick="generateBalReport('per')" ><a href="" >Percentage</a></li>
            </ul>
        </div>
        
        <div class="uk-width-medium-2-10"><br>
            <button class="uk-button" onclick="generateBalReport()">Show on screen</button>
        </div>
        
    </div>
    </div>

    <script>
        function generateBalReport(graph_type = '') {
            $("body").addClass("loading");
            var centre_code=$("[name='centre_name']").val();
            $.ajax({
                url : "admin/a_rptMonthToMonthStudentNoTracking.php",
                type : "POST",
                data : "graph_type="+graph_type,
                dataType : "text",
                success : function(response, status, http) {						
                    $("#sctResult").html(response);
                    $("body").removeClass("loading");
                },
                error : function(http, status, error) {
                    $("body").removeClass("loading");
                    UIkit.notify("Error:"+error);
                }
                
            });
        }

        $(document).ready(function () {
            generateBalReport();
        });

    </script>
    <div id="sctResult" style="border-top-right-radius: 15px;border-top-left-radius: 15px;">

    </div>
<?php
  }
}
?>
