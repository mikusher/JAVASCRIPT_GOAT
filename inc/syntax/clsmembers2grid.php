﻿<?php
require_once"inc/syntax/clsstdgrid.php";class cMembers2Grid extends cStdGrid{function cMembers2Grid($name,$group,$file,$option,$gridhandler){global$formapi;$this->Name=$name;$this->Item=new cMemberItem();$this->Gridhandler=$gridhandler;$listfile=$formapi->GetProperty('m_listfile');if(substr_count($_REQUEST['value'],'@')>0&&!$listfile){$val=explode("@",$_REQUEST['value']);$mname=$val[0];$domain=$val[1];}else{$mname=$listfile;$domain=$formapi->Domain;}if(substr_count($mname,'.txt')==0)$mname.='.txt';if(($listfile[1]!=":")&&($listfile[1]!="\\")&&($listfile[0]!="/")){$this->Filename=$_SESSION['CONFIGPATH'].$mname;}else{$this->Filename=$mname;}}function loadFromSession(){$this->Data=$_SESSION["griddata"]["grids"][$this->uid][$this->Name];}function saveToSession(){$_SESSION["griddata"]["grids"][$this->uid][$this->Name]=$this->Data;}function loadFromBuffer(){$lines=explode(CRLF,$this->Buffer);for($i=0;$i<count($lines);$i++){if($lines[$i]=="")continue;$pom=explode(";",$lines[$i]);$items[$i]["member"]=$pom[0];$items[$i]["rights"]=$pom[1];if($pom[2])$items[$i]["param"]=$pom[2];}$this->Data=$items;}function saveToBuffer(){if($this->Data)foreach($this->Data as$data)$return.=$data["member"].";".$data["rights"].";".$data["param"].CRLF;return$return;}function Save(){global$formtype;$return=$this->saveToBuffer();if($this->Filename&&!file_exists($this->Filename)||$formtype=='edit'){if($return!=""){$fp=fopen($this->Filename,"w");fwrite($fp,$return,strlen($return));fclose($fp);}else@unlink($this->Filename);}}}?>