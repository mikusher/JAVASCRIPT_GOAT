﻿<?php
require_once"inc/syntax/clsitem.php";require_once"inc/syntax/clsstdgrid.php";require_once"inc/include.php";class cListMemberGrid extends cStdGrid{function cListMemberGrid($name,$option){global$formapi;$domain=$formapi->Domain;$account=substr($formapi->EmailAddress,0,strpos($formapi->EmailAddress,"@"));$item=$option["SYNTAX"][0]["OBJECT"][0];$length=$item["ATTRIBUTES"]["LENGTH"];$seps=$item["SEPS"][0]["VALUE"];$this->showFunction=false;$this->Item=new cStdItem($length,$seps);if($mlist=$formapi->GetProperty('M_ListFile')){$path=$mlist;}else{$path=$domain."/".$account.".txt";}$this->Filename=$_SESSION["CONFIGPATH"].$path;$this->Name=$option["ATTRIBUTES"]["NAME"];}}?>