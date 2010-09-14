<?php
require_once ("../param/Constantes.php");

if(isset($_GET['f']))
	$fonction = $_GET['f'];
else{
	if(isset($_POST['f']))
		$fonction = $_POST['f'];
	else
    	$fonction ='';
}

if(isset($_GET['src']))
	$src = $_GET['src'];
else 
    $src ='all';

if(isset($_GET['dst']))
	$dst = $_GET['dst'];
else 
    $dst ='table';

if(isset($_GET['params']))
	$params = $_GET['params'];
else 
    $params = '';

if(isset($_GET['idGrille']))
	$idGrille = $_GET['idGrille'];
else 
	if(isset($_POST['idGrille']))
		$idGrille = $_POST['idGrille'];
	else
    	$idGrille = '';

if(isset($_GET['idDoc']))
	$idDoc = $_GET['idDoc'];
else 
	if(isset($_POST['idDoc']))
		$idDoc = $_POST['idDoc'];
	else
    	$idDoc = -1;
    
if(isset($_GET['idExi']))
	$idExi = $_GET['idExi'];
else 
	if(isset($_POST['idExi']))
		$idExi = $_POST['idExi'];
	else
    	$idExi = -1;
    	
if(isset($_GET['tag']))
	$tag = $_GET['tag'];
else 
	if(isset($_POST['tag']))
		$tag = $_POST['tag'];
	else
    	$tag = "";

if(isset($_GET['idTag']))
	$idTag = $_GET['idTag'];
else 
	if(isset($_POST['idTag']))
		$idTag = $_POST['idTag'];
	else
    	$idTag = -1;
    	
    	
$oSite = new Site($infos);
$oFlux = new Flux($oSite);
$oFlex = new Flex($oSite);

$resultat = "";     
switch ($fonction) {
	case 'GetTags':		
		$oFlux->GetTags($src, $dst, $params);
		break;
	case 'GetListeMp3':
		$oFlux->GetListeMp3($src, $params);
		break;
	case 'GetCols':
		$oFlex->GetColos($idGrille);
		break;
	case "FindAll":
		$oFlex->FindAll($idGrille,$idExi,$idDoc);
		break;
	case "Delete":
		$oFlex->Delete($idGrille,$idExi,$idDoc,$idTag);
		break;
	case "InsertTag":
		$oFlex->InsertTag($idGrille,$idExi,$idDoc,$tag);
		break;
}
        
echo $resultat;  
        
  
?>
