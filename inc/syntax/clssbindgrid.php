﻿<?php
require_once"inc/syntax/clsstdgrid.php";class cServiceBindGrid extends cStdGrid{function cServiceBindGrid($name,$service,$filename){$this->Name=$name;$this->Service=$service;$this->Item=new cSBItem();$this->Filename=$_SESSION["CONFIGPATH"]."servicebind.dat";}function loadFromBuffer(){if($this->Buffer){$ic=$oc=0;$lines=explode(CRLF,$this->Buffer);for($i=0;$i<count($lines);$i++){$item=array();if($lines[$i]=="")continue;if($this->Item->Length==1)$item[0]=$lines[$i];else{$pom=$lines[$i];for($j=0;$j<$this->Item->Length-1;$j++){$pom=explode($this->Item->Seps[$j],$pom,2);$item[$j]=$pom[0];$pom=$pom[1];}$item[$j]=$pom;}$item[6]=str_replace($this->Service,"",$item[0])=="SSL"?true:false;if(str_replace("SSL","",$item[0])!=$this->Service){$this->otherData[$oc++]=$item;continue;}$items[$ic++]=$item;}$this->Data=$items;}}function saveToBuffer(){$buffer="";if($this->Data)foreach($this->Data as$i=>$val){$this->Data[$i][0]=$this->Service.($this->Data[$i][4]?'SSL':'');if($this->Item->Length==1)$buffer.=$this->Data[$i][0].CRLF;else{for($j=0;$j<$this->Item->Length-1;$j++){$buffer.=$this->Data[$i][$j].$this->Item->Seps[$j];}$buffer.=$this->Data[$i][$j].CRLF;}}if($this->otherData)foreach($this->otherData as$i=>$val){if($this->Item->Length==1)$buffer.=$this->otherData[$i][0].CRLF;else{for($j=0;$j<$this->Item->Length-1;$j++){$buffer.=$this->otherData[$i][$j].$this->Item->Seps[$j];}$buffer.=$this->otherData[$i][$j].CRLF;}}$this->Buffer=$buffer;return$buffer;}function loadFromSession(){$this->Data=$_SESSION["griddata"]["grids"][$this->uid][$this->Name];$this->otherData=$_SESSION["griddata"]["other"][$this->uid][$this->Name];}function saveToSession(){$_SESSION["griddata"]["grids"][$this->uid][$this->Name]=$this->Data;$_SESSION["griddata"]["other"][$this->uid][$this->Name]=$this->otherData;}}?>