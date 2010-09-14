<?php
class Flex{

	public $trace;
  	private $site;
  	public $idGrille;
 
    function __tostring() {
    return "Cette classe permet la création dynamique d'objet XUL.<br/>";
    }

    function __construct($site, $idGrille="") {
		//echo "new Site $sites, $id, $scope<br/>";
	  	$this->trace = TRACE;
	
	    $this->site = $site;
	    $this->idGrille = $idGrille;
		
		
		//echo "FIN new grille <br/>";		
    }

    public function GetToret($recordset, $AjoutChampEdit=false, $AjoutChampNull=false){
    	
    	$totalrows = mysql_num_rows($recordset);
		$pageNum = (int)@$_REQUEST["pageNum"];
    	
		$toret = array();
		
		if($AjoutChampEdit){
			// -ajout- pour ajouter
			array_push($toret, array("id_tag"=>-2,"titre"=>"-ajout-"));
		}
		if($AjoutChampNull){
			// --- pour null
			array_push($toret, array("id_tag"=>-1,"titre"=>"---"));			
		}
						
		
		//create the standard response structure
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<response>
		  <data>'.EOL;
		while ($row = mysql_fetch_assoc($recordset)) {
			$xml .= "<row>";
			foreach($row as $c=>$v){
				$xml .= "<$c>$v</$c>";
			}
			$xml .= "</row>".EOL;
		}
		$xml .= "</data>".EOL;
	  	$xml .= "<metadata>  
		    <totalRows>4</totalRows>
    		<pageNum>0</pageNum>
  		</metadata>".EOL;
		$xml .= "</response>";
	    
		header('Content-type: text/xml');
		echo $xml;
		
    }
        
    
    function GetColos($idGrille,$GetXml=true){

    	$xml = "<response><data>";
    	switch ($idGrille) {
			case 'ActiDoc':		
	    		$xml .= "<row>
				      <id_grille>".$idGrille."</id_grille>
	    			  <titre>Id. document</titre>
				      <champ>id_doc</champ>
				      <visible>false</visible>
			    	</row>";
	    		$xml .= "<row>
				      <id_grille>".$idGrille."</id_grille>
	    			  <titre>Titre</titre>
				      <champ>titre</champ>
				      <visible>true</visible>
			    	</row>";
	    		$xml .= "<row>
				      <id_grille>".$idGrille."</id_grille>
	    			  <titre>".utf8_encode("N° de question")."</titre>
				      <champ>branche</champ>
				      <visible>true</visible>
			    	</row>";
	    		$xml .= "<row>
				      <id_grille>".$idGrille."</id_grille>
	    			  <titre>Url</titre>
				      <champ>url</champ>
				      <visible>true</visible>
			    	</row>";
	    		break;
			case 'ExiTags':		
	    		$xml .= "<row>
				      <id_grille>".$idGrille."</id_grille>
	    			  <titre>Id. exi</titre>
				      <champ>id_exi</champ>
				      <visible>false</visible>
			    	</row>";
	    		$xml .= "<row>
				      <id_grille>".$idGrille."</id_grille>
	    			  <titre>Id. tag</titre>
				      <champ>id_tag</champ>
				      <visible>false</visible>
			    	</row>";
	    		$xml .= "<row>
				      <id_grille>".$idGrille."</id_grille>
	    			  <titre>Tag</titre>
				      <champ>tag</champ>
				      <visible>true</visible>
			    	</row>";
	    		break;	    
			case 'DocTags':		
	    		$xml .= "<row>
				      <id_grille>".$idGrille."</id_grille>
	    			  <titre>Id. doc</titre>
				      <champ>id_doc</champ>
				      <visible>false</visible>
			    	</row>";
	    		$xml .= "<row>
				      <id_grille>".$idGrille."</id_grille>
	    			  <titre>Id. tag</titre>
				      <champ>id_tag</champ>
				      <visible>false</visible>
			    	</row>";
	    		$xml .= "<row>
				      <id_grille>".$idGrille."</id_grille>
	    			  <titre>Tag</titre>
				      <champ>tag</champ>
				      <visible>true</visible>
			    	</row>";
	    		break;	    
    	}
		$xml .= "</data><metadata /></response>";			
    		
    		
		if($GetXml){
	    	header('Content-type: text/xml');
			echo $xml;
		}else{
			return simplexml_load_string(utf8_encode($xml));
		}
		
    }    

    
	function FindAll($idGrille,$idExi,$idDoc){
		
		
		//variable pour définir si on ajoute les champs d'édition
		// --- pour null
		$AjoutChampNull=false;
		// -ajout- pour ajouter
		$ajoutChampEdit=false;
		
		$f = new Flux($this->site);
		
		switch ($idGrille) {
			case 'ActiDoc':
				$rs = $f->GetDocById($idDoc);
				break;
			case 'ExiTags':
				$rs = $f->GetTags('ExiTags','rs',$idExi);
				break;
			case 'DocTags':
				$rs = $f->GetTags('DocTags','rs', $idDoc);
				break;
				
		}
		
		$this->GetToret($rs,$ajoutChampEdit,$AjoutChampNull);		

	}    

	function Delete($idGrille,$idExi,$idDoc,$idTag){
		
		$toret = false;
		$f = new Flux($this->site);
		
		switch ($idGrille) {
			case 'ExiTags':
				$rs = $f->DeleteExiTag($idExi,$idTag);
				break;
			case 'DocTags':
				$rs = $f->DeleteDocTag($idExi,$idDoc,$idTag);
				break;
				
		}

		
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<response>
		  <data>'.EOL;
		$xml .= "<row>";
		$xml .= "<champ>id_tagCol</champ>";
		$xml .= "<valeur>$idTag</valeur>";
		$xml .= "</row>".EOL;
		$xml .= "</data>".EOL;
	  	$xml .= "<metadata/>".EOL;
		$xml .= "</response>";
	
	    header('Content-type: text/xml');
		echo $xml;
	}

	function InsertTag($idGrille,$idExi,$idDoc,$tag){
		
		$toret = false;
		$f = new Flux($this->site);
		
		switch ($idGrille) {
			case 'ExiTags':
				$arr = $f->SetExiTag("",$tag,$idExi);
				break;
			case 'DocTags':
				$arr = $f->SetDocTag($idDoc,$tag);
				break;
				
		}

		
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
		<response>
		  <data>'.EOL;
		$xml .= "<row>";
		$xml .= "<idTag>".$arr["idTag"]."</idTag>";
		$xml .= "</row>".EOL;
		$xml .= "</data>".EOL;
	  	$xml .= "<metadata/>".EOL;
		$xml .= "</response>";
	
	    header('Content-type: text/xml');
		echo $xml;
	}
	
}    