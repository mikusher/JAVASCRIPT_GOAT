﻿<?php
require_once('inc/syntax/clsstdgrid.php');class cWordsGrid extends cStdGrid{function cWordsGrid($option,$name){$this->Name=$name;if(strtolower($name)=='whitelist_words')$this->variableName='BypassKeywords';else$this->variableName='BlockKeywords';}function Load($gObj,$array){$datfile=parsedatfile("spam.dat","spam2");$words=$datfile[$this->variableName];$words=explode_j(";",$words);if($words)foreach($words as$key=>$word)$this->Data[$key]["word"]=$word;if(!$array)$this->SaveToSession($gObj);}function Save(){$return="";if($this->Data)foreach($this->Data as$data)$return.=$data["word"].";";$_SESSION['datdata']["spam.dat"]["data"][$this->variableName]=$return;}}?>