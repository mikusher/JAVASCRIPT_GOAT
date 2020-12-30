﻿<?php  class ConversionItem{private$sPath;private$sUser;public function __construct($user,$path){dmp(" >>>> oConversionItem->__construct($user,$path);");$this->sPath=$path;$this->sUser=$user;dmp(" <<<< true");}public function __destruct(){dmp(" >>>> oConversionItem->__destruct($user,$path);");dmp(" <<<< 1");}public function convert($sFID,$aContacts,$aGroups,$share='P'){dmp("<b>Converting $this->sUser</b><br/>");$this->importToDatabase($aContacts,$sFID,$share);$this->importGroupsToDatabase($aGroups,$sFID,$share);}public function loadGroupsFromFile(&$iSkipped){$iSkipped=0;$sProcessed=@file_get_contents($this->sPath.DONE_FILE3);if($sProcessed)$aProcessed=explode(DELIMITER_CHAR,$sProcessed);$path=$this->sPath.GROUPS_FILE;if(!(@$file=fopen($path,"r")))return$groups;while(!(feof($file))){$line=fgets($file,65536);if(!(trim($line)=="")){$xitem=explode(DELIMITER_CHAR,$line);$item["NAME"]=rawurldecode($xitem[0]);$item["LIST"]=rawurldecode($xitem[1]);$item["ISGROUP"]=1;$item["DELETED"]=false;if($aProcessed&&$item["NAME"]&&in_array($item["NAME"],$aProcessed)){$iSkipped++;continue;}$groups[]=$item;}}fclose($file);return$groups;}public function loadContactsFromFile(&$iSkipped){$iSkipped=0;$sProcessed=@file_get_contents($this->sPath.DONE_FILE);if($sProcessed)$aProcessed=explode(DELIMITER_CHAR,$sProcessed);$path=$this->sPath.ADDRESS_FILE;if(!(@$file=fopen($path,"r")))return$addresses;while(!(feof($file))){$line=fgets($file,16384);unset($address);if(!(trim($line)=="")){$xaddress=explode(DELIMITER_CHAR,trim($line));$address["ID"]=rawurldecode($xaddress[0]);$address["NAME"]=rawurldecode($xaddress[1]);if($aProcessed&&in_array($address["ID"],$aProcessed)){$iSkipped++;continue;}$address["CATEGORY"]=rawurldecode($xaddress[16]);$address["ORGANIZATION"]=rawurldecode($xaddress[2]);$address["CERTIFICATE"]=rawurldecode($xaddress[48]);$address["NICK"]=rawurldecode($xaddress[3]);$address["EMAIL"]=rawurldecode($xaddress[4]);$address["STREETB"]=rawurldecode($xaddress[5]);$address["CITYB"]=rawurldecode($xaddress[6]);$address["ZIPB"]=rawurldecode($xaddress[7]);$address["COUNTRYB"]=rawurldecode($xaddress[8]);$address["PHONE1"]=rawurldecode($xaddress[9]);$address["PHONE4"]=rawurldecode($xaddress[10]);$address["BIRTH"]=rawurldecode($xaddress[11]);$address["SEX"]=rawurldecode($xaddress[12]);$address["NOTE"]=rawurldecode($xaddress[13]);$address["DELETE"]=false;$address["NAME1"]=rawurldecode($xaddress[17]);$address["NAME2"]=rawurldecode($xaddress[18]);$address["NAME3"]=rawurldecode($xaddress[19]);$address["EMAIL2"]=rawurldecode($xaddress[20]);$address["EMAIL3"]=rawurldecode($xaddress[21]);$address["JOB"]=rawurldecode($xaddress[22]);$address["PROFESSION"]=rawurldecode($xaddress[23]);$address["DEPARTMENT"]=rawurldecode($xaddress[24]);$address["OFFICE"]=rawurldecode($xaddress[25]);$address["ASISTENT"]=rawurldecode($xaddress[26]);$address["PHONE2"]=rawurldecode($xaddress[14]);$address["PHONE3"]=rawurldecode($xaddress[15]);$address["PHONE1T"]=rawurldecode($xaddress[27]);$address["PHONE2T"]=rawurldecode($xaddress[28]);$address["PHONE3T"]=rawurldecode($xaddress[29]);$address["PHONE4T"]=rawurldecode($xaddress[30]);$address["URL"]=rawurldecode($xaddress[31]);$address["CALENDARURL"]=rawurldecode($xaddress[32]);$address["SPOUS"]=rawurldecode($xaddress[33]);$address["ANNIVERSARY"]=rawurldecode($xaddress[34]);$address["TITLE"]=rawurldecode($xaddress[35]);$address["MANAGER"]=rawurldecode($xaddress[36]);$address["STATEB"]=rawurldecode($xaddress[37]);$address["STREETH"]=rawurldecode($xaddress[38]);$address["CITYH"]=rawurldecode($xaddress[39]);$address["ZIPH"]=rawurldecode($xaddress[40]);$address["COUNTRYH"]=rawurldecode($xaddress[41]);$address["STATEH"]=rawurldecode($xaddress[42]);$address["STREETO"]=rawurldecode($xaddress[43]);$address["CITYO"]=rawurldecode($xaddress[44]);$address["ZIPO"]=rawurldecode($xaddress[45]);$address["COUNTRYO"]=rawurldecode($xaddress[46]);$address["STATEO"]=rawurldecode($xaddress[47]);$address["PHONE1TYPE"]=rawurldecode($xaddress[49]);$address["PHONE2TYPE"]=rawurldecode($xaddress[50]);$address["PHONE3TYPE"]=rawurldecode($xaddress[51]);$address["PHONE4TYPE"]=rawurldecode($xaddress[52]);$addresses[$address["ID"]]=$address;dmp("Contact ".$address['NAME']." loaded");}}fclose($file);return$addresses;}private function importGroupsToDatabase($groups,$groupsessid=false){global$oGWAPI;$file=fopen($this->sPath.DONE_FILE3,"a+");if($groups)foreach($groups as$key=>$val){unset($datain);$datain[ITMCLASS]="L";$datain[ITMTITLE]=$val['NAME'];$cid=$oGWAPI->FunctionCall("AddContactInfo",$groupsessid,$oGWAPI->CreateParamLine($datain));if($cid){$oGWAPI->FunctionCall("DeleteContactLocations",$groupsessid,$cid);$Garr=explode(";",$val['LIST']);$counter=0;if(is_array($Garr)&&count($Garr)){unset($datain,$arr);foreach($Garr as$gv){$gv=ereg_replace("[\"\']",'',trim($gv));if(!$gv)continue;if(eregi("<([a-z0-9@\-\.\_\~-]+)",$gv,$arr)){$datain['LCTEMAIL1']=$arr[1];$datain['LCTDESCRIPTION']=trim(substr($gv,0,strpos($gv,"<")));}elseif(eregi("([a-z0-9\-\.\_\~\-]+@[a-z0-9\-\.\_\~\-]+)",$gv,$arr)){$datain['LCTEMAIL1']=$arr[1];$gv=ereg_replace("[<>]",'',$gv);$datain['LCTDESCRIPTION']=trim(substr($gv,0,strpos($gv,$arr[1])));}else{$gv=trim(ereg_replace("[<>]",' ',$gv));if(!$gv)continue;$sppos=strpos($gv," ");if($sppos)$gv=substr($gv,0,$sppos);$datain['LCTEMAIL1']=$gv;$datain['LCTDESCRIPTION']='';}if(!$datain['LCTEMAIL1']&&!$datain['LCTDESCRIPTION'])continue;if(!$counter)$datain['LCTTYPE']='O';else$datain['LCTTYPE']='L';$counter++;$oGWAPI->FunctionCall("AddContactLocation",$groupsessid,$cid,$oGWAPI->CreateParamLine($datain));}}$oGWAPI->FunctionCall("AddContactInfo",$groupsessid,'',$cid);fwrite($file,$val["NAME"].DELIMITER_CHAR);dmp("Distribution list ".$val["NAME"]." added to database");}}@fclose($file);return true;}private function importToDatabase($address,$groupsessid=false,$share="P"){global$oGWAPI;if(!$groupsessid){$this->errors['aImportFail']=$this->sUser;return;}$file=fopen($this->sPath.DONE_FILE,"a+");if(!is_array($address))return;reset($address);while(list(,$val)=each($address)){if(!$val['NAME']){if($val['NAME1']||$val['NAME3'])$val['NAME']=trim($val['NAME1']." ".$val['NAME3']);elseif($val['NICK'])$val['NAME']=$val['NICK'];else$val['NAME']=MSG_EMPTY_NAME;}unset($datain,$addressbirth,$addressanniversary);if($val['BIRTH']){list($addressbirth1,$addressbirth2,$addressbirth3)=explode(".",$addressbirth);if($addressbirth1&&$addressbirth2&&$addressbirth3)$addressbirth=calendardatetostr($addressbirth3,$addressbirth2,$addressbirth1);else$addressbirth="";}if($val['ANNIVERSARY']){list($addressanniversary1,$addressanniversary2,$addressanniversary3)=explode(".",$addressanniversary);if($addressanniversary1&&$addressanniversary2&&$addressanniversary3)$addressanniversary=calendardatetostr($addressanniversary3,$addressanniversary2,$addressanniversary1);else$addressanniversary="";}$datain[ITMCLASS]='C';$datain[ITMFIRSTNAME]=$val['NAME1'];$datain[ITMMIDDLENAME]=$val['NAME2'];$datain[ITMSHARETYPE]=$share;$datain[ITMTITLE]=$val['TITLE'];$datain[ITMSURNAME]=$val['NAME3'];$datain[ITMJOBTITLE]=$val['JOB'];$datain[ITMCOMPANY]=$val['ORGANIZATION'];$datain[ITMCLASSIFYAS]=$val['NAME'];$datain[ITMDESCRIPTION]=$val['NOTE'];$datain[ITMOFFICELOCATION]=$val['OFFICE'];$datain[ITMGENDER]=$val['SEX'];$datain[ITMPROFESSION]=$val['PROFESSION'];$datain[ITMDEPARTMENT]=$val['SEX'];$datain[ITMNICKNAME]=$val['NICK'];$datain[ITMBDATE]=$addressbirth;$datain[ITMSPOUSE]=$val['SPOUSE'];$datain[ITMANNIVERSARY]=$addressanniversary;$datain[ITMINTERNETFREEBUSY]=$val['CALENDARURL'];$datain[ITMASSISTANTNAME]=$val['ASSISTANT'];$datain[ITMMANAGERNAME]=$val['MANAGER'];$datain[ITMCATEGORY]=$val['CATEGORY'];$cid=$oGWAPI->FunctionCall("AddContactInfo",$groupsessid,$oGWAPI->CreateParamLine($datain));if($cid){$oGWAPI->FunctionCall("DeleteContactLocations",$groupsessid,$cid);unset($datain);$datain[LCTTYPE]='B';$datain[LCTWEBPAGE]=$val['URL'];$datain[LCTEMAIL1]=$val['EMAIL'];$datain[LCTEMAIL2]=$val['EMAIL2'];$datain[LCTEMAIL3]=$val['EMAIL3'];$datain[LCTSTREET]=$val['STREETB'];$datain[LCTCITY]=$val['CITYB'];$datain[LCTSTATE]=$val['STATEB'];$datain[LCTCOUNTRY]=$val['COUNTRYB'];$datain[LCTZIP]=$val['ZIPB'];$LcB=$oGWAPI->FunctionCall("AddContactLocation",$groupsessid,$cid,$oGWAPI->CreateParamLine($datain));if($val['PHONE1']){unset($datain1);$datain1[PHNTYPE]=$val['PHONE1TYPE'];$datain1[PHNNUMBER]=$val['PHONE1'];$datain1[PHNDESCRIPTION]=$val['PHONE1T'];$oGWAPI->FunctionCall("AddContactLocationPhone",$groupsessid,$LcB,$oGWAPI->CreateParamLine($datain1));}if($val['PHONE2']){unset($datain1);$datain1[PHNTYPE]=$val['PHONE2TYPE'];$datain1[PHNNUMBER]=$val['PHONE2'];$datain1[PHNDESCRIPTION]=$val['PHONE2T'];$oGWAPI->FunctionCall("AddContactLocationPhone",$groupsessid,$LcB,$oGWAPI->CreateParamLine($datain1));}if($val['PHONE3']){unset($datain1);$datain1[PHNTYPE]=$val['PHONE3TYPE'];$datain1[PHNNUMBER]=$val['PHONE3'];$datain1[PHNDESCRIPTION]=$val['PHONE3T'];$oGWAPI->FunctionCall("AddContactLocationPhone",$groupsessid,$LcB,$oGWAPI->CreateParamLine($datain1));}if($val['PHONE4']){unset($datain4);$datain1[PHNTYPE]=$val['PHONE4TYPE'];$datain1[PHNNUMBER]=$val['PHONE4'];$datain1[PHNDESCRIPTION]=$val['PHONE4T'];$oGWAPI->FunctionCall("AddContactLocationPhone",$groupsessid,$LcB,$oGWAPI->CreateParamLine($datain1));}$datain[LCTTYPE]='H';$datain[LCTSTREET]=$val['STREETH'];$datain[LCTCITY]=$val['CITYH'];$datain[LCTSTATE]=$val['STATEH'];$datain[LCTCOUNTRY]=$val['COUNTRYH'];$datain[LCTZIP]=$val['ZIPH'];$LcH=$oGWAPI->FunctionCall("AddContactLocation",$groupsessid,$cid,$oGWAPI->CreateParamLine($datain));if($val['PHONE1'])$oGWAPI->FunctionCall("AddContactLocationPhone",$groupsessid,$LcH,$oGWAPI->CreateParamLine($datain1));if($val['PHONE2'])$oGWAPI->FunctionCall("AddContactLocationPhone",$groupsessid,$LcH,$oGWAPI->CreateParamLine($datain2));if($val['PHONE3'])$oGWAPI->FunctionCall("AddContactLocationPhone",$groupsessid,$LcH,$oGWAPI->CreateParamLine($datain3));if($val['PHONE4'])$oGWAPI->FunctionCall("AddContactLocationPhone",$groupsessid,$LcH,$oGWAPI->CreateParamLine($datain4));$datain[LCTTYPE]='O';$datain[LCTSTREET]=$val['STREET'];$datain[LCTCITY]=$val['CITY'];$datain[LCTSTATE]=$val['STATE'];$datain[LCTCOUNTRY]=$val['COUNTRY'];$datain[LCTZIP]=$val['ZIP'];$LcO=$oGWAPI->FunctionCall("AddContactLocation",$groupsessid,$cid,$oGWAPI->CreateParamLine($datain));if($val['PHONE1'])$oGWAPI->FunctionCall("AddContactLocationPhone",$groupsessid,$LcO,$oGWAPI->CreateParamLine($datain1));if($val['PHONE2'])$oGWAPI->FunctionCall("AddContactLocationPhone",$groupsessid,$LcO,$oGWAPI->CreateParamLine($datain2));if($val['PHONE3'])$oGWAPI->FunctionCall("AddContactLocationPhone",$groupsessid,$LcO,$oGWAPI->CreateParamLine($datain3));if($val['PHONE4'])$oGWAPI->FunctionCall("AddContactLocationPhone",$groupsessid,$LcO,$oGWAPI->CreateParamLine($datain4));$oGWAPI->FunctionCall("AddContactInfo",$groupsessid,'',$cid);fwrite($file,$val["ID"].DELIMITER_CHAR);dmp("Contact ".$val["NAME"]." added to database");}}fclose($file);}}?>