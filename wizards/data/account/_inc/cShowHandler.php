﻿<?php  require_once'inc/domains.php';require_once'inc/accounts.php';class cShowHandler extends cShowHandlerMain{var$COMObject;function show_domain_list($_language,$option,$data){$this->COMObject=createobject("api");$Option_tpl['heading']=$_language['account_select_domain'];$Option_tpl['option']='<select name="domain_name" class="input" style="width:80%;" id="domain_name_id" >';$i=0;$list=getdomainlist();foreach($list as$key=>$value){if($_GET['domain_name']==$value['DOMAIN'])$selected=' selected';$Option_tpl['option'].='<option value="'.$value['DOMAIN'].'" '.$selected.' >'.$value['DOMAIN'].'</option>';$selected='';};$Option_tpl['option'].='</select>';$Option_tpl['info']=$_language[$option['ATTRIBUTES']['LABEL'].'INFO'];$Options_tpl['option_list']=template($this->Root.'templates/w_optionspec2.tpl',$Option_tpl);$this->COMObject=NULL;return$Options_tpl['option_list'];}function account_summarize($_language,$option,$data){$Option_tpl['head']=$_language['FORM_NAME'];$Option_tpl['val']=$data['domain_name'];$Options_tpl.=template($this->Root.'templates/w_sum.tpl',$Option_tpl);foreach($this->DataXML['DATA'][0]['GROUPS'][0]['GROUP']as$group_val){if($group_val['OPTION'][0]!=''){foreach($group_val['OPTION']as$option_key=>$option_val){if(!$this->Wizard->checkformlimit($option_val['ATTRIBUTES']['NAME'])||strtoupper($option_val["ATTRIBUTES"]["DISABLE"])==$_SESSION['ACCOUNT'])continue;if($option_val['ATTRIBUTES']['VARIABLE']!=''){if($option_val['ATTRIBUTES']['LABELS']!=''){$a_list=explode("|",$_language[$option_val['ATTRIBUTES']['LABELS']]);if($option_val['ATTRIBUTES']['VALUE']!=''){$a_vals=explode("|",$option_val['ATTRIBUTES']['VALUE']);$Option_tpl['head']=$_language[$option_val['ATTRIBUTES']['LABEL']];$Option_tpl['val']=$a_list[$a_vals[$data[$option_val['ATTRIBUTES']['NAME']]]];}else{$Option_tpl['head']=$_language[$option_val['ATTRIBUTES']['LABEL']];$Option_tpl['val']=$a_list[$data[$option_val['ATTRIBUTES']['NAME']]];}}else{$Option_tpl['head']=$_language[$option_val['ATTRIBUTES']['LABEL']];$Option_tpl['val']=$data[$option_val['ATTRIBUTES']['NAME']];}if($option_val['ATTRIBUTES']['TYPE']=='password')$Options_tpl.=template($this->Root.'templates/w_sump.tpl',$Option_tpl);else$Options_tpl.=template($this->Root.'templates/w_sum.tpl',$Option_tpl);}}}}return$Options_tpl;}}?>
