﻿<?php
function dmp($value,$detailded=false,$var_dump=false){if(!MSG_SHOW)return;if($detailed){echo"<pre>";if($var_dump)var_dump($value);else print_r($value);echo"</pre>";}else echo($value);echo"\r\n<br/>";}class Tools{public static function explode_j($sep,$string){$return=explode($sep,$string);unset($return[count($return)-1]);return$return;}public static function dircopy($srcdir,$dstdir,$verbose=false){$num=0;if(!is_dir($dstdir))mkdir($dstdir);if($curdir=opendir($srcdir)){while($file=readdir($curdir)){if($file!='.'&&$file!='..'){$srcfile=$srcdir.FDR_DELIMITTER.$file;$dstfile=$dstdir.FDR_DELIMITTER.$file;if(is_file($srcfile)){if(is_file($dstfile))$ow=filemtime($srcfile)-filemtime($dstfile);else$ow=1;if($ow>0){if($verbose)echo"Copying '$srcfile' to '$dstfile'...";if(copy($srcfile,$dstfile)){touch($dstfile,filemtime($srcfile));$num++;if($verbose)echo"OK\n";}else echo"Error: File '$srcfile' could not be copied!\n";}}else if(is_dir($srcfile)){$num+=self::dircopy($srcfile,$dstfile,$verbose);}}}closedir($curdir);}return$num;}public static function mkdir($dir){if(!is_dir($dir)){if(!mkdir($dir,0755,true))return false;}}public static function mkdir_r($dirName,$rights=0777){$dirs=explode(FDR_DELIMITTER,$dirName);$dir='';foreach($dirs as$part){$dir.=$part.FDR_DELIMITTER;if(!is_dir($dir)&&strlen($dir)>0)mkdir($dir,$rights);}return true;}}?>
