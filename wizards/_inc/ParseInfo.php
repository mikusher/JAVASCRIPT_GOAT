<?php
function parse_info($str,$rpath){$str=str_replace('src=\'','src=\''.$rpath,$str);$str=str_replace('{{','<',$str);$str=str_replace('}}','>',$str);return$str;}?>