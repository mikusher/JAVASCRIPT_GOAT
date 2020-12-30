﻿<?php  require_once"inc/syntax/clsstdgrid.php";require_once"inc/editfile.php";class cChallengeGrid extends cStdGrid{function cChallengeGrid($option,$name,$count=false,$sender=false,$owner=false,$domain=false){$this->Name=$name;$this->Item=new cStdItem(8,";SEP;SEP;SEP;SEP;SEP;SEP;");switch(strtolower($option['ATTRIBUTES']['PARAM'])){case'quarantine':$this->type=2;break;case'blacklist':$this->type=0;break;case'whitelist':default:$this->type=1;break;}if($count)$this->counter=$count;$this->sender=$sender;$this->owner=$owner;$this->domain=$domain;}function getBuffer(){global$formapi;$query=" WHERE SndAuthorized = ".$this->type;if($this->sender){$query.=" AND SndEmail LIKE '%".$this->sender."%'";}if($this->owner&&$_SESSION['ACCOUNT']!='USER'){$query.=" AND SndOwner LIKE '%".$this->owner."%'";}switch($_SESSION['ACCOUNT']){case'USER':$query.=" AND SndOwner = '".$_SESSION['EMAIL']."'";break;case'DOMAINADMIN':$query.=" AND (";$list=getdomainlist();foreach($list as$dom){$query.="SndDomain LIKE '".$dom['DOMAIN']."%'";if($count++<count($list)-1)$query.=" OR ";}$query.=")";break;case'ADMIN':default:if($this->domain){$query.=" AND SndDomain LIKE '%".$this->domain."%'";}break;}$this->Buffer=$formapi->QuarantineList('',$query,$this->counter);}function loadFromBuffer(){if($this->Buffer){$ic=0;$lines=explode(CRLF,$this->Buffer);for($i=0;$i<count($lines);$i++){$item=array();if($lines[$i]=="")continue;if($this->Item->Length==1)$item[0]=$lines[$i];else{$pom=$lines[$i];for($j=0;$j<$this->Item->Length-1;$j++){$pom=explode($this->Item->Seps[$j],$pom,2);if($j==5){$date=getDate(jdtounix($item[1]));$hours=intval($pom[0]/3600).":";$time=$hours.intval(($pom[0]-$hours*3600)/60);$pom[0]=$date['year'].'-'.$date['mon'].'-'.$date['mday'].' '.$time;}$item[$j]=$pom[0];$pom=$pom[1];}$item[$j]=str_replace(";","",$pom);}$items[$ic++]=$item;}$this->Data=$items;}}function Save(){$aDomains=getdomainlist();foreach($aDomains as$dom)$aAllowedDomains[$dom['DOMAIN']]=1;$noSaveActions=array(0=>'ch_whitelist',1=>'ch_blacklist',2=>'ch_delete',3=>'ch_deliver',4=>'ch_refresh',4=>'g_refresh',4=>'g_delete',5=>'g_authorize',6=>'bw_blacklist',7=>'bw_whitelist');if(!(in_array($_REQUEST['gridaction'],$noSaveActions)||$this->type==2)){$api=createobject("api");if($this->Data)foreach($this->Data as$ajtem){$list=$api->QuarantineList(''," WHERE sndAuthorized='".$this->type."' AND sndEmail = '".$ajtem[0]."' AND sndOwner ='".$ajtem[4]."' ",0);if($list==''){if($_SESSION['ACCOUNT']=='DOMAINADMIN'){if(!$ajtem[4])continue;else{if(strpos($ajtem[4],'@')===false){$domain=$ajtem[4];}else$domain=substr($ajtem[4],strpos($ajtem[4],'@')+1);if(!$aAllowedDomains[$domain])continue;if(strpos($domain,'[')===false&&strpos($domain,']')===false)$domain='['.$domain.']';}}if($_SESSION['ACCOUNT']=='USER'){$ajtem[4]=$_SESSION['EMAIL'];}$api->QuarantineAdd($ajtem[4]?$ajtem[4]:'*',$ajtem[0],$this->type);}}}}function getGridButtons($option,$type){global$alang,$_REQUEST,$formapi,$value;@$files=$option["SOURCE"][0]["FILE"][0]["ATTRIBUTES"];@$dialog=$files["DIALOG"];@$file=$files["NAME"];@$comment=$files["COMMENT"];if($option["BUTTONS"][0]["BUTTON"])foreach($option["BUTTONS"][0]["BUTTON"]as$bk=>$bv){unset($item);@$item["type"]=$bv["ATTRIBUTES"]["TYPE"];@$item["label"]=$alang[$bv["ATTRIBUTES"]["LABEL"]];@$width=$option["ATTRIBUTES"]["WIDTH"];if(@!$width)$width=640;@$height=$option["ATTRIBUTES"]["HEIGHT"];if(@!$height)$height=480;$item["onclick"]='processdatagrid("'.$item["type"].'","'.$dialog.'","'.$this->Name.'","",'.$width.','.$height.',"",'.$this->count.',0,"","'.$this->uid.'");';switch($item["type"]){case'removeall':case'delete':case'ch_delete':case'up':case'down':case'ch_deliver':case'ch_blacklist':case'ch_refresh':case'ch_whitelist':case'bw_whitelist':case'bw_blacklist':if($type=="datagrid")$nr='document.getElementById("noreload").value=1;';else$nr='';$item["onclick"]=$nr.'document.getElementById("gridval").value="'.$this->Name.'";document.getElementById("gridaction").value="'.$item["type"].'";document.forms[0].submit();';break;}$items[]=$item;}return$items;}function getObjectJS($selector,$vindex,$function=false,$option){global$skin_dir,$sGridItem;$arr["dname"]=$this->Name;@$files=$option["SOURCE"][0]["FILE"][0]["ATTRIBUTES"];@$dialog=$files["DIALOG"];@$file=$files["NAME"];@$comment=$files["COMMENT"];@$width=$option["ATTRIBUTES"]["WIDTH"];if(@!$width)$width=640;@$height=$option["ATTRIBUTES"]["HEIGHT"];if(@!$height)$height=480;if($this->Data)foreach($this->Data as$key2=>$val2){foreach($vindex as$key3=>$val3){$item=eregi_replace("([\\\'\"])","\\\\1",$val2[strtolower($val3)]);if($this->showFunction){$func=$this->showFunction;$arr["items"]["num"][$key2]['item']["num"][$key3]['value']=$func($item);}else$arr["items"]["num"][$key2]['item']["num"][$key3]['value']=str_replace(CRLF,";",$item);if($function[$val3]){$function[$val3]['option']['ATTRIBUTES']['COUNT']=$key3;formfunctionvalues($function[$val3]['function'],$arr["items"]["num"][$key2]["num"][$key3],true,$function[$val3]['option']['ATTRIBUTES']['VINDEX'],$error,$function[$val3]['option']);}if($this->type==2){$path=$_SESSION['MAILPATH'].$this->Data[$key2][6].'/';$userdir=str_replace('@'.$this->Data[$key2][6],'',$this->Data[$key2][4]).'/';if(is_dir($path.$userdir))$path.=$userdir;@$dir=opendir($path.'~spam/~quarantine/'.$this->Data[$key2][2].'/');while((@$file=readdir($dir))!==false){@$info=pathinfo($file);if($info['extension']=='tmp'||$info['extension']=='imap')break;}@closedir($dir);$arr["items"]["num"][$key2]["item"]["num"][$key3]['label']='<a href="javascript:newwindow(\\\'messageview.html?object='.addslashes(addslashes($this->Data[$key2][4])).'&fileid=quarantine_view&qfolder='.$this->Data[$key2][2].'&id='.$file.'\\\');">'.$arr["items"]["num"][$key2]['item']["num"][$key3]['value'].'</a>';$arr["items"]["num"][$key2]["item"]["num"][$key3]['sort']=$arr["items"]["num"][$key2]["item"]["num"][$key3]['value'];}else{$arr["items"]["num"][$key2]["item"]["num"][$key3]['label']='<a href="javascript:processdatagrid(\\\'edit\\\',\\\''.$dialog.'\\\',\\\''.$this->Name.'\\\',\\\'\\\','.$width.','.$height.',\\\'\\\',\\\''.$this->count.'\\\',0,\\\''.$key2.'\\\',\\\''.$this->uid.'\\\');">'.$arr["items"]["num"][$key2]['item']["num"][$key3]['value'].'</a>';$arr["items"]["num"][$key2]["item"]["num"][$key3]['sort']=$arr["items"]["num"][$key2]["item"]["num"][$key3]['value'];}}$arr["items"]["num"][$key2]["cislo"]=$key2;if($this->Name.$key2==$sGridItem)$arr["items"]["num"][$key2]["selected"]=1;}$arr["selector"]=$selector;return template($skin_dir."dgridjs2.tpl",$arr);}}?>