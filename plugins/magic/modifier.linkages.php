<?php

function magic_modifier_linkages($string,$parse_var = '',$magic_vars = ''){
global $Linkages;
if ($string=="") return "";
$linkage_result = $magic_vars["_G"]['linkages'];
$_parse_var = explode("/",$parse_var);
$parse_var = $_parse_var[0];
$var = explode(",",$string);
$result = array();
foreach ($var as $key =>$val){
if (isset($linkage_result[$parse_var]) &&$linkage_result[$parse_var]!=""){
$result[] = $linkage_result[$parse_var][$val];
}else if (isset($_parse_var[1]) &&$_parse_var[1] =="value"){
foreach ($linkage_result[$parse_var] as $key =>$value){
if ($linkage_result[$val]==$value){
$result[] = $key;
}
}
}elseif ($parse_var != ""){
$result[] = $linkage_result[$parse_var][$val];
}elseif (isset($linkage_result[$val])){
$result[] = $linkage_result[$val];
}
}
return join(",",$result);
}

?>