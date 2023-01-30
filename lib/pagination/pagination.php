<?php
function getPagination($pg, $numperpage, $query, $sql, &$start_record, &$num_row) {
   global $connection;

   if ($sql!="") {
      if ($pg=="") {
         $pg=1;
      }

      $start_record=(($pg-1)*$numperpage);

      $result=mysqli_query($connection, $sql);
      $num_row=mysqli_num_rows($result);

      if ((int)($num_row/$numperpage)==($num_row/$numperpage)) {
         $num_page=$num_row/$numperpage;
      } else {
         $num_page=(int)($num_row/$numperpage)+1;
      }

      $prev_pg=$pg-1;
      $next_pg=$pg+1;

      $prev_query=$query."&pg=".$prev_pg;
      $next_query=$query."&pg=".$next_pg;

      if ($num_page>1) {
         $s.="<ul class=\"uk-pagination uk-margin-top uk-flex-center\">";
         if ($pg>1) {
            $s.="<li><a class='uk-text-small' data-uk-tooltip title='Previous Page' href=\"index.php?".$prev_query."\"><<</a></li>";
//            $s.="<li><a href=\"index.php?".$prev_query."\"><span uk-pagination-previous></span></a></li>";
            if ($pg>6) {
               $s.="<li>...</li>";
            }
         }

         for ($i=1; $i<=$num_page; $i++) {
            if (($i>=$pg-5) & ($i<=$pg+5)) {
               if ($i!=$pg) {
                  $query.="&pg=".$i;
                  $s.="<li><a href=\"index.php?".$query."\">$i</a></li>";
               } else {
                  $s.="<li class=\"uk-active\"><span>$i</span></li>";
               }
            }
         }

         if ($pg<$num_page) {
            if ($pg < ($num_page-5)) {
               $s.="<li>...</li>";
            }

            $s.="<li><a class='uk-text-small' data-uk-tooltip title='Next Page' href=\"index.php?".$next_query."\">>></a></li>";
//            $s.="<li><a href=\"index.php?".$next_query."\"><span uk-pagination-next></span></a></li>";
         }
         $s.="</ul>";

         return $s;
      } else {
         return "<br>";
      }
   }
}

?>