<?php

require_once ("../param/Constantes.php");
	
$oSite = new Site($infos);
$oFlux = new Flux($oSite);
$oFlux->SetInstant(1,false,"importation originale");

// Initialize getID3 engine
// Needed for windows only
define('GETID3_HELPERAPPSDIR', 'C:/helperapps/');
$getID3 = new getID3;
$getID3->setOption(array('encoding' => 'UTF-8'));

ExploreDir("../cy-audios/hachour/");

ob_end_flush();

function ExploreDir($listdirectory){

	global $getID3;
	
	if ($handle = @opendir($listdirectory)) {
	
		while ($file = readdir($handle)) {
			if($file!="." && $file!=".." && $file!="codeqr" ){
				$currentfilename = $listdirectory.'/'.$file;
				if(is_dir($currentfilename)){
					ExploreDir($currentfilename);
				}
				if(is_file($currentfilename)){
					// symbolic-link-resolution enhancements by davidbullock״ech-center*com
					$TargetObject     = realpath($currentfilename);  // Find actual file path, resolve if it's a symbolic link
					$fileinformation = $getID3->analyze($currentfilename);
					if(!$fileinformation["error"]){
						SetFlux($fileinformation);
					}
				}
			}
		}
		closedir($handle);
		//echo 'FIN<br>';
	}
}


function SetFlux($fi){
			
	global $oSite;//$oFlux;
	$oFlux = new Flux($oSite);
	
	//enregistre l'existence
	$idExi= $oFlux->GetExi($fi["tags_html"]["id3v2"]["artist"][0]);
	echo $fi["tags_html"]["id3v2"]["artist"][0]." (".$idExi.")<br/>";
	
	//enregistre les tags de l'existence
	$arrTags = split("_",$fi["tags_html"]["id3v2"]["comments"][0]);
	$arrGenre = split("-",$fi["tags_html"]["id3v2"]["genre"][0]);
	$t="";
	foreach($arrGenre as $genre){
		$arr = $oFlux->SetExiTag("",$genre,$idExi);	
		$t.=$genre." (".$arr["idTag"].") , ";
	}
	echo "  - ".$t."<br/>";
	
	//enregistre le document
	$idDoc = $oFlux->GetDoc($fi["filenamepath"]
		, $fi["tags_html"]["id3v2"]["title"][0]
		, $fi["tags_html"]["id3v2"]["track_number"][0],"","audio/mpeg");
	echo $fi["filename"]." (".$idDoc.")<br/>";
		
	//enregistre les tags du doc
	$t="";
	foreach($arrTags as $tag){
		$arr = $oFlux->SetDocTag($idDoc,$tag);
		$t.=$tag." (".$arr["idTag"].") , ";
	}

	//enregistre le doc pour l'utilisateur
	$oFlux->SetExiDoc($idExi,$idDoc);
	
	echo "  - ".$t."<br/>";
	echo "<br/>";
	
}

?>