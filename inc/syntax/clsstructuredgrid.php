﻿<?php
require_once"inc/syntax/clsstdgrid.php";class cStructuredGrid extends cStdGrid{function cStructuredGrid($option){$this->Item=new cMultilineItem($option);$this->Filename=$_SESSION["INSTALLPATH"].securepath($option["SOURCE"][0]["FILE"][0]["ATTRIBUTES"]["NAME"]);$this->Name=$option["SYNTAX"][0]["OBJECT"][0]["ATTRIBUTES"]["NAME"];}function loadFromBuffer(){if($this->Buffer){$lines=explode(CRLF,$this->Buffer);$iOpened=false;$iCount=0;for($i=0;$i<count($lines);$i++){if($lines[$i]==""){$iOpened=false;continue;}if(substr(trim($lines[$i]),0,1)=="["){$iOpened=true;eregi("\[([^\r\n]{0,})\]",$lines[$i],$regs);$iName=$regs[1];$items[$iCount++][strtolower($this->Item->Labelvar)]=$iName;continue;}if($iOpened&&substr($lines[$i],0,1)!=";"){$larr=explode("=",$lines[$i],2);$name=$larr[0];$value=$larr[1];if(@in_array(strtolower($name),$this->Item->VarNames))$items[$iCount-1][strtolower($name)]=$value;else if($this->Item->Textarea){while($lines[$i]!="")$items[$iCount-1][strtolower($this->Item->Textarea)].=$lines[$i++].CRLF;$i--;}}}$this->Data=$items;}}function saveToBuffer(){$buffer="";if($this->Data)foreach($this->Data as$i=>$val){$lines="";$label="";$tarea="";if($this->Item->Labelvar)$label="[".$this->Data[$i][strtolower($this->Item->Labelvar)]."]".CRLF;if($this->Item->Textarea)$tarea=$this->Data[$i][strtolower($this->Item->Textarea)];if($this->Item->VarNames)foreach($this->Item->VarNames as$var)$lines.=$var."=".$this->Data[$i][strtolower($var)].CRLF;$buffer.=$label.$lines.$tarea.CRLF;}$this->Buffer=$buffer;return$buffer;}}?>