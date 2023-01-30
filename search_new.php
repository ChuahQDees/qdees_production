<?php
function ConcatWhere($final_sql, $final_token) {
   if ($final_token != "") {
      $fsql=strtoupper($final_sql);
      if (strpos($fsql, "WHERE")==false) {
         $concat_word=" WHERE ";
      } else {
         $concat_word=" and ";
      }

      if ($final_sql != "") {
         return $final_sql.$concat_word.$final_token;
      } else {
         return $final_sql;
      }
   } else {
      return $final_sql;
   }
}

function ConcatToken($Token1, $Token2, $Operator) {
   if ($Token1 == '') {
      return $Token2;
   } else {
      if ($Token2 == '') {
         return $Token1;
      } else {
         return "$Token1 $Operator $Token2";
      }
   }
}

function ConcatTokenGroup($Token1, $Token2, $Operator) {
   if ($Token1 == '') {
      return $Token2;
   } else {
      if ($Token2 == '') {
         return $Token1;
      } else {
         return "($Token1 $Operator $Token2)";
      }
   }
}

function ConstructTokenGroup($FieldName1, $FieldData1, $Operator1, $FieldName2, $FieldData2, $Operator2, $Operator) {
   $token1=ConstructToken($FieldName1, $FieldData1, $Operator1);
   $token2=ConstructToken($FieldName2, $FieldData2, $Operator2);

   $final=ConcatTokenGroup($token1, $token2, $Operator);
   return $final;
}

function ConstructToken($FieldName, $FieldData, $Operator) {
   $mysql="";
   if ((strlen($FieldData)>=1) & ($FieldData!="%") & ($FieldData!="%%")) {
      if ($FieldData=="{}") {
         $FieldData="";
      }

      if (($Operator[1]=="%") || ($Operator[strlen($Operator)]=="%")) {
         $LikeChar="%";
      } else {
         $LikeChar="*";
      }

      if (strtoupper(str_replace($LikeChar, $Operator, ''))=="LIKE") {
         if ($Operator[1]==$LikeChar) {
            if ($Operator[strlen($Operator)]==$LikeChar) {
               $mysql="(".$FieldName." LIKE '".$LikeChar.$FieldData.$LikeChar."')";
            } else {
               $mysql="(".$FieldName." LIKE '".$LikeChar.$FieldData."')";
            }
         } else {
            if ($Operator[strlen($Operator)]==$LikeChar) {
               $mysql="(".$FieldName." LIKE '".$FieldData.$LikeChar."')";
            } else {
               $mysql="(".$FieldName." LIKE '".$LikeChar.$FieldData.$LikeChar."')";
            }
         }
      } else {
         if (strtoupper(str_replace($LikeChar, $Operator, ''))=="NOT LIKE") {
            if ($Operator[1]==$LikeChar) {
               if ($Operator[strlen($Operator)]==$LikeChar) {
                  $mysql="(".$FieldName." NOT LIKE '".$LikeChar.$FieldData.$LikeChar."')";
               } else {
                  $mysql="(".$FieldName." NOT LIKE '".$LikeChar.$FieldData."')";
               }
            } else {
               if ($Operator[strlen($Operator)]==$LikeChar) {
                  $mysql="(".$FieldName." NOT LIKE '".$FieldData.$LikeChar."')";
               } else {
                  $mysql="(".$FieldName." NOT LIKE '".$LikeChar.$FieldData.$LikeChar."')";
               }
            }
         } else {
            $mysql="($FieldName $Operator '$FieldData')";
         }
      }
   }
   return $mysql;
}

function ConstructOrderToken($FieldName, $Order) {
   if ($FieldName != "") {
      return trim("$FieldName $Order");
   } else {
      return "";
   }
}

function ConcatOrder($sql, $Order) {
   if ($Order != "") {
      return "$sql ORDER BY $Order";
   } else {
      return $sql;
   }
}

function ConcatGroupBy($sql, $group_by) {
   if ($group_by != "") {
      return "$sql GROUP BY $group_by";
   } else {
      return $sql;
   }
}
?>