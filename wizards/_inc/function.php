<?php
function parse_info($str,$rpath){$str=str_replace('src=\'','src=\''.$rpath,$str);$str=str_replace('{{','<',$str);$str=str_replace('}}','>',$str);return$str;}function checkItemEnable($account,$disable){if(substr_count($account,strtoupper($disable))>0&&substr_count(strtoupper($disable),$account)>0)$return=0;else$return=1;return$return;}?>
