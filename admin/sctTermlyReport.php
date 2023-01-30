<div style="width:100%;overflow-x:auto">
   <table class="uk-table t-border">
      <tr>
         <th colspan="4">CENTRE TERMLY STOCK REPORT</th>
<?php
for ($i=1; $i<=5; $i++) {
?>
         <th colspan="10">LEVEL <?php echo $i?> MODULE</th>
<?php
}
?>
      </tr>
      <tr>
         <th></th>
         <th></th>
         <th>TOTAL</th>
         <th>BRIDGE</th>
<?php
for ($j=1; $j<=50; $j++) {
?>
         <th><?php echo $j?></th>
<?php
}
?>
      </tr>
<?php
for ($k=1; $k<=5; $k++) { //looping each term
   for ($i=1; $i<=5; $i++) {
?>
      <tr>
         <th rowspan="5">TERM <?php echo $k?></th>
<?php
if ($k==1) {
?>
         <th>Opening</th>
<?php
} else {
?>
         <th>Balance b/f</th>
<?php
}
?>
<?php
if ($k==1) {
?>
         <th><?php $opening=calcTotalPurchase($centre_code, 1, $k, "", ""); echo $opening?></th>
<?php
} else {
?>
         <th><?php echo calcBF($centre_code, $k, "", "")?></th>
<?php
}
?>
         <th>-<!-- Bridge --></th>
<?php
for ($j=1; $j<=5; $j++) {
   for ($i=1; $i<=10; $i++) {
      $m=($j-1)*10+$i;
      if ($k==1) {
?>
         <td><?php echo calcTotalPurchase($centre_code, 1, $k, $j, $m)?></td>
<?php
      } else {
?>
         <td><?php echo calcBF($centre_code, $k, $j, $i)?></td>
<?php
      }
   }
}
?>
      </tr>
<?php
   }
?>
      <tr>
         <th>Purchase 1</th>
<?php
if ($k==1) {
?>
         <th><?php echo calcTotalPurchase($centre_code, 2, $k, "", "")?></th>
<?php
} else {
?>
         <th><?php echo calcTotalPurchase($centre_code, 1, $k, "", "")?></th>
<?php
}
?>
         <th>-<!-- Bridge --></th>
<?php
for ($j=1; $j<=5; $j++) {
   for ($i=1; $i<=10; $i++) {
      $m=($j-1)*10+$i;
      if ($k==1) {
?>
         <td><?php echo calcTotalPurchase($centre_code, 2, $k, $j, $m)?></td>
<?php
      } else {
?>
         <td><?php echo calcTotalPurchase($centre_code, 1, $k, $j, $m)?></td>
<?php
      }
   }
}
?>
      </tr>
      <tr>
         <th>Purchase 2</th>
<?php
if ($k==1) {
?>
         <th><?php echo calcTotalPurchase($centre_code, 3, $k, "", "")?></th>
<?php
} else {
?>
         <th><?php echo calcTotalPurchase($centre_code, 2, $k, "", "")?></th>
<?php
}
?>
         <th>-</th>
<?php
for ($j=1; $j<=5; $j++) {
   for ($i=1; $i<=10; $i++) {
      $m=($j-1)*10+$i;
      if ($k==1) {
?>
         <td><?php echo calcTotalPurchase($centre_code, 3, $k, $j, $m)?></td>
<?php
      } else {
?>
         <td><?php echo calcTotalPurchase($centre_code, 2, $k, $j, $m)?></td>
<?php
      }
   }
}
?>
      </tr>
      <tr>
         <th>Consume</th>
         <th><?php echo calcTotalConsume($centre_code, $k, "", "")?></th>
         <th>-</th>
<?php
for ($j=1; $j<=5; $j++) {
   for ($i=1; $i<=10; $i++) {
      $m=($j-1)*10+$i;
?>
         <td><?php echo calcTotalConsume($centre_code, $k, $j, $m)?></td>
<?php
   }
}
?>
      </tr>
      <tr>
         <th>Balance c/f</th>
         <th><?php echo calcCF($centre_code, $k, "", "")?></th>
         <th>-</th>
<?php
for ($j=1; $j<=5; $j++) {
   for ($i=1; $i<=10; $i++) {
      $m=($j-1)*10+$i;
?>
         <td><?php echo calcCF($centre_code, $k, $j, $m)?></td>
<?php
   }
}
?>
      </tr>
<?php
}
?>
   </table>
</div>
