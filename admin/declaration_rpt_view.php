<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
?>

<a onclick="history.back();">                
    <span class="d_n btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Declaration Report View</span>
</span>
<div class="uk-width-1-1 myheader text-center">
    <h2 class="uk-text-center myheader-text-color myheader-text-style">DECLARATION REPORT</h2>
</div>

<div class="nice-form">
    <div class="uk-grid">
        <div class="uk-width-medium-5-10">
            <table class="uk-table" style="width: 100%;">
               <tr>

                  <td class="uk-text-bold">Centre Name</td>
                  <td>(Center Name Here)</td>
               </tr>
               <tr>
                  <td class="uk-text-bold">Prepared By</td>
                  <td>(Testing Z)</td>
               </tr>
               <tr>
                  <td class="uk-text-bold">Date of submission</td>
                  <td><?php echo date("Y-m-d H:i:s") ?></td>
               </tr>
            </table>
         </div>
         <div class="uk-width-medium-5-10">
            <table class="uk-table">
               <tr>
                  <td class="uk-text-bold">Academic Year</td>
                  <td>2023-2024</td>
               </tr>
               <tr>
                  <td class="uk-text-bold">Month/Year</td>
                  <td>(March/2023-2024)</td>
               </tr>
            </table>
        </div>
    </div>
    <!-- 
    //////////////////////////////
    A) SCHOOL FEES
    //////////////////////////////
    -->
    <div name="school_fees">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold " style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">A) School Fees</td>
                    <td />
                </tr>
            </tbody>
        </table>
        <!-- SCHOOL FEES EDP START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">EDP (9)</td> <!-- EDP + Total Number -->
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">EDP MAR23 to FEB24 (9)</td> <!-- Fee Structure Name + Student Total -->
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">1. EIDEN JEE KAH YUAN</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">2. IFTI ZARA AMANDA BINTI MAS ARUWARDY</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">3. MUHAMMAD HARRAZ ANAQI BIN MOHD HAFIZ</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">4. JAYDEN KHO YII JIA</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">5. ANDREA LIEW XUEN</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">6. DEBORAH WONG EN YU</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">7. LAI YU HAN (CHARLOTTE)</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">8. CHONG HAI FEEN</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">9. Alpheus Bong Kai Yan</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;"></td>
                </tr>
            </tbody>
        </table>
        <!-- SCHOOL FEES EDP END -->

        <!-- SCHOOL FEES QF1 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF1 (15)</td> <!-- QF1 + Total Number -->
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF1 MAR23 to FEB24 (13)</td>  <!-- Fee Structure Name + Student Total -->
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">1. Bernard Chua Wei Xian</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">2. LIYA HANA ZAFIRAH BINTI UMAR RASYIDDIN</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">3. ETHAN CHAI YU TENG</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">4. LEE HUI ROU (GAIL)</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">5. Ayyden Damian anak Andreson Sibat</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">6. Yvonne Kong yan yan</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">7. Leah Teng Le Xuen</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">8. Leonard Lau jinshi</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">10. ELROY LUK BANG WEI</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">11. JENNA KIEW YUE NA</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">12. EL HAFIY BIN ISMAIL</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">13. CHARLOTTE SKYE</td>
                </tr>
            </tbody>
        </table>
        <!-- SCHOOL FEES QF1 END -->

        <!-- SCHOOL FEES QF2 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF1 MAR23 to FEB24 Sibling Discount: (1)</td>  <!-- Fee Structure Name + Student Total -->
                </tr>
                <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. QUINCEE GOH ZI XUAN</td></tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF1 MAR23 to FEB24 Operator Teacher: (1)</td>  <!-- Fee Structure Name + Student Total -->
                </tr>
                <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. PHIDRON SIM POH SHIUN</td></tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF2 (1)</td> <!-- QF2 + Total Number -->
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF2 MAR23 to FEB24 (1)</td>  <!-- Fee Structure Name + Student Total -->
                </tr>
                <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. FATIH EL RAYYAN BIN MAS ARUWARDY</td></tr>
            </tbody>
        </table>
        <!-- SCHOOL FEES QF2 END -->

        <!-- SCHOOL FEES QF3 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF3 (0)</td> <!-- QF3 + Total Number -->
                    <td />
                </tr>
                <tr>
                    <td style="text-indent: 50px;border:none;">No Record Found</td>
                </tr>
            </tbody>
        </table>
        <!-- SCHOOL FEES QF3 END -->
    </div>

    <!-- 
    //////////////////////////////
    B) AFTERNOON PROGRAMME FEES
    //////////////////////////////
    -->
    <div name="afternoon_programme_fees">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold " style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">B) Afternoon Programme Fees</td>
                    <td />
                </tr>
            </tbody>
        </table>
        <!-- AFTERNOON PROGRAMME FEES EDP START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">EDP (5)</td>
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">EDP MAR23 to FEB24 (5)</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">1. MUHAMMAD HARRAZ ANAQI BIN MOHD HAFIZ</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">2. JAYDEN KHO YII JIA</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">3. LAI YU HAN (CHARLOTTE)</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">4. CHONG HAI FEEN</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">5. Alpheus Bong Kai Yan</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;"></td>
                </tr>
            </tbody>
        </table>
        <!-- AFTERNOON PROGRAMME FEES EDP END -->

        <!-- AFTERNOON PROGRAMME FEES QF1 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">QF1 (2)</td>
                <td />
            </tr>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF1 MAR23 to FEB24 (1)</td>
            </tr>
            <tr>
                <td class="uk-text-bold" style="text-indent: 50px;border:none;">1. Ayyden Damian anak Andreson Sibat</td>
                <td />
            </tr>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF1 MAR23 to FEB24 Operator Teacher: (1)</td>
            </tr>
            <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. PHIDRON SIM POH SHIUN</td></tr>
            </tbody>
        </table>
        <!-- AFTERNOON PROGRAMME FEES QF1 END -->

        <!-- AFTERNOON PROGRAMME FEES QF2 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF2 (1)</td>
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF2 MAR23 to FEB24 (1)</td>
                </tr>
                <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. FATIH EL RAYYAN BIN MAS ARUWARDY</td></tr>
            </tbody>
        </table>
        <!-- AFTERNOON PROGRAMME FEES QF2 END -->

        <!-- AFTERNOON PROGRAMME FEES QF3 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">QF3 (0)</td>
                <td />
            </tr>
            <tr>
                <td style="text-indent: 50px;border:none;">No Record Found</td>
            </tr>
            </tbody>
        </table>
        <!-- AFTERNOON PROGRAMME FEES QF3 END -->
    </div>

    <!-- 
    //////////////////////////////
    C) MATERIAL FEES
    //////////////////////////////
    -->
    <div name="material_fees">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">C) Material Fees</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <!-- MATERIAL FEE EDP START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">EDP (2)</td>
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">EDP MAR23 to FEB24 (2)</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">1. MUHAMMAD HARRAZ ANAQI BIN MOHD HAFIZ</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">2. JAYDEN KHO YII JIA</td>
                </tr>
            </tbody>
        </table>
        <!-- MATERIAL FEE EDP END -->

        <!-- MATERIAL FEE QF1 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">QF1 (1)</td>
                <td />
            </tr>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF1 MAR23 to FEB24 Operator Teacher: (1)</td>
            </tr>
            <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. PHIDRON SIM POH SHIUN</td></tr>
            </tbody>
        </table>
        <!-- MATERIAL FEE QF1 END -->

        <!-- MATERIAL FEE QF2 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF2 (1)</td>
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF2 MAR23 to FEB24 (1)</td>
                </tr>
                <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. FATIH EL RAYYAN BIN MAS ARUWARDY</td></tr>
            </tbody>
        </table>
        <!-- MATERIAL FEE QF2 END -->

        <!-- MATERIAL FEE QF3 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF3 (0)</td>
                    <td />
                </tr>
                <tr>
                    <td style="text-indent: 50px;border:none;">No Record Found</td>
                </tr>
            </tbody>
        </table>
        <!-- MATERIAL FEE QF3 END -->
    </div>

    <!-- 
    //////////////////////////////
    D) Q-DEES FOUDNATION MANDARIN MODULES MATERIALS
    //////////////////////////////
    -->
    <div name="foundation_mandarin">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">D) Q-Dees Foundation Mandarin Modules Materials</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <!-- MANDARIN EDP START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">EDP (2)</td>
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">EDP MAR23 to FEB24 (2)</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">1. MUHAMMAD HARRAZ ANAQI BIN MOHD HAFIZ</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">2. JAYDEN KHO YII JIA</td>
                </tr>
            </tbody>
        </table>
        <!-- MANDARIN EDP END -->

        <!-- MANDARIN QF1 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">QF1 (1)</td>
                <td />
            </tr>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF1 MAR23 to FEB24 Operator Teacher: (1)</td>
            </tr>
            <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. PHIDRON SIM POH SHIUN</td></tr>
            </tbody>
        </table>
        <!-- MANDARIN QF1 END -->

        <!-- MANDARIN QF2 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF2 (1)</td>
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF2 MAR23 to FEB24 (1)</td>
                </tr>
                <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. FATIH EL RAYYAN BIN MAS ARUWARDY</td></tr>
            </tbody>
        </table>
        <!-- MANDARIN QF2 END -->

        <!-- MANDARIN QF3 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF3 (0)</td>
                    <td />
                </tr>
                <tr>
                    <td style="text-indent: 50px;border:none;">No Record Found</td>
                </tr>
            </tbody>
        </table>
        <!-- MANDARIN QF3 END -->
    </div>

    <!-- 
    //////////////////////////////
    E) REGISTRATION PACK
    //////////////////////////////
    -->
    <div name="registration_pack">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">E) REGISTRATION PACK</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <!-- REGISTRATION EDP START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">EDP (2)</td>
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">EDP MAR23 to FEB24 (2)</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">1. MUHAMMAD HARRAZ ANAQI BIN MOHD HAFIZ</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">2. JAYDEN KHO YII JIA</td>
                </tr>
            </tbody>
        </table>
        <!-- REGISTRATION EDP END -->

        <!-- REGISTRATION QF1 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF1 (1)</td>
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF1 MAR23 to FEB24 Operator Teacher: (1)</td>
                </tr>
                <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. PHIDRON SIM POH SHIUN</td></tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF2 (1)</td>
                    <td />
                </tr>
            </tbody>
        </table>
        <!-- REGISTRATION QF1 END -->

        <!-- REGISTRATION QF2 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF2 MAR23 to FEB24 (1)</td>
                </tr>
                <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. FATIH EL RAYYAN BIN MAS ARUWARDY</td></tr>
            </tbody>
        </table>
        <!-- REGISTRATION QF2 END -->

        <!-- REGISTRATION QF3 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF3 (0)</td>
                    <td />
                </tr>
                <tr>
                    <td style="text-indent: 50px;border:none;">No Record Found</td>
                </tr>
            </tbody>
        </table>
        <!-- REGISTRATION QF3 END -->
    </div>

    <!-- 
    //////////////////////////////
    F) PRODUCTS
    //////////////////////////////
    -->
    <div name="products">
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="background-color: #F5F5F5;color:#2F2F2F;border:none;">
                    <td style="vertical-align: middle;font-size:18px;">F) PRODUCTS</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <!-- 
        //////////////////////////////
        i) Pre-School Kits
        //////////////////////////////
        -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="border:none;">
                    <td style="vertical-align: middle;font-size:18px;">i) Pre-School Kits</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <!-- PRE-SCHOOL KITS EDP START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">EDP (2)</td>
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">EDP MAR23 to FEB24 (2)</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">1. MUHAMMAD HARRAZ ANAQI BIN MOHD HAFIZ</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">2. JAYDEN KHO YII JIA</td>
                </tr>
            </tbody>
        </table>
        <!-- PRE-SCHOOL KITS EDP END -->

        <!-- PRE-SCHOOL KITS QF1 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">QF1 (1)</td>
                <td />
            </tr>
            <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF1 MAR23 to FEB24 Operator Teacher: (1)</td>
                </tr>
                <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. PHIDRON SIM POH SHIUN</td></tr>
            </tbody>
        </table>

        <!-- PRE-SCHOOL KITS QF1 END -->

        <!-- PRE-SCHOOL KITS QF2 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF2 (1)</td>
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF2 MAR23 to FEB24 (1)</td>
                </tr>
                <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. FATIH EL RAYYAN BIN MAS ARUWARDY</td></tr>
            </tbody>
        </table>
        <!-- PRE-SCHOOL KITS QF2 END -->

        <!-- PRE-SCHOOL KITS QF3 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF3 (0)</td>
                    <td />
                </tr>
                <tr>
                    <td style="text-indent: 50px;border:none;">No Record Found</td>
                </tr>
            </tbody>
        </table>
        <!-- PRE-SCHOOL KITS QF3 END -->

        <!-- 
        //////////////////////////////
        ii) Memories to Cherish
        //////////////////////////////
        -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="border:none;">
                    <td style="vertical-align: middle;font-size:18px;">ii) Memories to Cherish</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <!-- MEMORIES TO CHERISH EDP START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">EDP (0)</td>
                    <td />
                </tr>
                <tr>
                    <td style="text-indent: 50px;border:none;">No Record Found</td>
                </tr>
            </tbody>
        </table>
        <!-- MEMORIES TO CHERISH EDP END -->

        <!-- MEMORIES TO CHERISH QF1 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">QF1 (0)</td>
                <td />
            </tr>
            <tr>
                <td style="text-indent: 50px;border:none;">No Record Found</td>
            </tr>
            </tbody>
        </table>

        <!-- MEMORIES TO CHERISH QF1 END -->

        <!-- MEMORIES TO CHERISH QF2 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF2 (0)</td>
                    <td />
                </tr>
                <tr>
                    <td style="text-indent: 50px;border:none;">No Record Found</td>
                </tr>
            </tbody>
        </table>
        <!-- MEMORIES TO CHERISH QF2 END -->

        <!-- MEMORIES TO CHERISH QF3 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF3 (0)</td>
                    <td />
                </tr>
                <tr>
                    <td style="text-indent: 50px;border:none;">No Record Found</td>
                </tr>
            </tbody>
        </table>
        <!-- MEMORIES TO CHERISH QF3 END -->

        <!-- 
        //////////////////////////////
        iii) Q-DEES BAG
        //////////////////////////////
        -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold" style="border:none;">
                    <td style="vertical-align: middle;font-size:18px;">iii) Q-Dees Bag</td>
                    <td />
                </tr>
            </tbody>
        </table>

        <!-- Q-DEES BAG START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">EDP (2)</td>
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">EDP MAR23 to FEB24 (2)</td>
                </tr>
                <tr>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">1. MUHAMMAD HARRAZ ANAQI BIN MOHD HAFIZ</td>
                    <td class="uk-text-bold" style="text-indent: 50px;border:none;">2. JAYDEN KHO YII JIA</td>
                </tr>
            </tbody>
        </table>
        <!-- Q-DEES BAG EDP END -->

        <!-- Q-DEES BAG QF1 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">QF1 (1)</td>
                <td />
            </tr>
            <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF1 MAR23 to FEB24 Operator Teacher: (1)</td>
                </tr>
                <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. PHIDRON SIM POH SHIUN</td></tr>
            </tbody>
        </table>

        <!-- Q-DEES BAG QF1 END -->

        <!-- Q-DEES BAG QF2 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF2 (1)</td>
                    <td />
                </tr>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;border:none;text-decoration: underline;">QF2 MAR23 to FEB24 (1)</td>
                </tr>
                <tr><td class="uk-text-bold" style="text-indent: 50px;border:none;">1. FATIH EL RAYYAN BIN MAS ARUWARDY</td></tr>
            </tbody>
        </table>
        <!-- Q-DEES BAG QF2 END -->

        <!-- Q-DEES BAG QF3 START -->
        <table class="uk-table" style="width: 100%;">
            <tbody>
                <tr class="uk-text-bold">
                    <td style="vertical-align: middle;font-size:16px;color:#086788;">QF3 (0)</td>
                    <td />
                </tr>
                <tr>
                    <td style="text-indent: 50px;border:none;">No Record Found</td>
                </tr>
            </tbody>
        </table>
        <!-- Q-DEES BAG QF3 END -->

    </div>

    <!-- 
    //////////////////////////////
    iv) UNIFORM
    //////////////////////////////
    -->
    <table class="uk-table" style="width: 100%;">
        <tbody>
            <tr class="uk-text-bold" style="border:none;">
                <td style="vertical-align: middle;font-size:18px;">iv) Uniform</td>
                <td />
            </tr>
        </tbody>
    </table>

    <!-- MEMORIES TO CHERISH EDP START -->
    <table class="uk-table" style="width: 100%;">
        <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">EDP (0)</td>
                <td />
            </tr>
            <tr>
                <td style="text-indent: 50px;border:none;">No Record Found</td>
            </tr>
        </tbody>
    </table>
    <!-- MEMORIES TO CHERISH EDP END -->

    <!-- MEMORIES TO CHERISH QF1 START -->
    <table class="uk-table" style="width: 100%;">
        <tbody>
        <tr class="uk-text-bold">
            <td style="vertical-align: middle;font-size:16px;color:#086788;">QF1 (0)</td>
            <td />
        </tr>
        <tr>
            <td style="text-indent: 50px;border:none;">No Record Found</td>
        </tr>
        </tbody>
    </table>

    <!-- MEMORIES TO CHERISH QF1 END -->

    <!-- MEMORIES TO CHERISH QF2 START -->
    <table class="uk-table" style="width: 100%;">
        <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">QF2 (0)</td>
                <td />
            </tr>
            <tr>
                <td style="text-indent: 50px;border:none;">No Record Found</td>
            </tr>
        </tbody>
    </table>
    <!-- MEMORIES TO CHERISH QF2 END -->

    <!-- MEMORIES TO CHERISH QF3 START -->
    <table class="uk-table" style="width: 100%;">
        <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">QF3 (0)</td>
                <td />
            </tr>
            <tr>
                <td style="text-indent: 50px;border:none;">No Record Found</td>
            </tr>
        </tbody>
    </table>
    <!-- MEMORIES TO CHERISH QF3 END -->

     <!-- 
    //////////////////////////////
    v) GYMWEAR
    //////////////////////////////
    -->
    <table class="uk-table" style="width: 100%;">
        <tbody>
            <tr class="uk-text-bold" style="border:none;">
                <td style="vertical-align: middle;font-size:18px;">v) Gymwear</td>
                <td />
            </tr>
        </tbody>
    </table>

    <!-- GYMWEAR EDP START -->
    <table class="uk-table" style="width: 100%;">
        <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">EDP (0)</td>
                <td />
            </tr>
            <tr>
                <td style="text-indent: 50px;border:none;">No Record Found</td>
            </tr>
        </tbody>
    </table>
    <!-- GYMWEAR EDP END -->

    <!-- GYMWEAR QF1 START -->
    <table class="uk-table" style="width: 100%;">
        <tbody>
        <tr class="uk-text-bold">
            <td style="vertical-align: middle;font-size:16px;color:#086788;">QF1 (0)</td>
            <td />
        </tr>
        <tr>
            <td style="text-indent: 50px;border:none;">No Record Found</td>
        </tr>
        </tbody>
    </table>

    <!-- GYMWEAR QF1 END -->

    <!-- GYMWEAR QF2 START -->
    <table class="uk-table" style="width: 100%;">
        <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">QF2 (0)</td>
                <td />
            </tr>
            <tr>
                <td style="text-indent: 50px;border:none;">No Record Found</td>
            </tr>
        </tbody>
    </table>
    <!-- GYMWEAR QF2 END -->

    <!-- GYMWEAR QF3 START -->
    <table class="uk-table" style="width: 100%;">
        <tbody>
            <tr class="uk-text-bold">
                <td style="vertical-align: middle;font-size:16px;color:#086788;">QF3 (0)</td>
                <td />
            </tr>
            <tr>
                <td style="text-indent: 50px;border:none;">No Record Found</td>
            </tr>
        </tbody>
    </table>
    <!-- GYMWEAR QF3 END -->

    <!-- Print to PDF -->
    <!-- Chuah Note: Put this as the last part to prevent double work, once everything above done, just copy the stuff above and paste, while removing the-->
    <center>
        <a type="button" id="print_to_pdf" target="_blank" href="" name="print_to_pdf" class="uk-button uk-button-primary form_btn uk-text-center">Print to PDF</a>
    </center>
</div>