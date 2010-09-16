<?php
class Flux{
	public $idInstant=-1;
	public $trace;
	private $site;
	
	
	function __construct($objSite){
		
		$this->trace = TRACE;		
		$this->site=$objSite;
		$this->GetLastInstant();
	}
    
	function GetLastInstant(){
		
		//ajoute un nouvel instant
		$sql = "SELECT MAX(id_instant) id FROM flux_instants";
		$rs = $this->site->DbGet($sql);
		$r = mysql_fetch_assoc($rs);
		$this->idInstant = $r["id"];
		$this->site->DbFree();

		return $this->idInstant;
	}
	
	function SetInstant($idExi,$ici=false,$desc=""){
		
		if(!$ici)$ici=$_SERVER["REMOTE_ADDR"];
		
		if($idExi=="null")$idExi=-1;
		
		//ajoute un nouvel instant
		$sql = "INSERT INTO flux_instants (id_exi, maintenant, ici, nom) 
			VALUES (".$idExi.", now(),\"".$ici."\",\"".$desc."\")";
		$this->site->DbGet($sql);
		$this->idInstant = mysql_insert_id();
		$this->site->DbFree();

		return $this->idInstant;
	}
	
	function GetExi($nom){
		
		//récupère l'identifiant de l'existence
		$sql = "SELECT id_exi FROM flux_exis WHERE nom=\"".$nom."\"";
		$rs = $this->site->DbGet($sql);
		
		if($r = mysql_fetch_assoc($rs)){
			$id = $r["id_exi"];
		}else{
			//ajoute un nouvel utilisateur
			$sql = "INSERT INTO flux_exis (nom) VALUES (\"".$nom."\")";
			$this->site->DbGet($sql);
			$id = mysql_insert_id();
		}
		$this->site->DbFree();
		//renvoie l'identifiant
		return $id;
		
	}

	function GetTag($tag){
		
		//récupère l'identifiant de l'utilisateur
		$sql = "SELECT id_tag FROM flux_tags WHERE tag=\"".$tag."\"";
		$rs = $this->site->DbGet($sql);
		
		if($r = mysql_fetch_assoc($rs)){
			$id = $r["id_tag"];
		}else{
			//ajoute un nouveau tag
			$sql = "INSERT INTO flux_tags (tag) VALUES (\"".$tag."\")";
			$this->site->DbGet($sql);
			$id = mysql_insert_id();
		}
		$this->site->DbFree();
		//renvoie l'identifiant
		return $id;
		
	}

	function GetDocById($idDoc){
		
		//récupère l'identifiant de l'utilisateur
		$sql = "SELECT d.id_doc, d.titre, d.branche, d.url
			FROM flux_docs d
			WHERE d.id_doc =".$idDoc;
		$rs = $this->site->DbGet($sql);
		$this->site->DbFree();
		
		return $rs;
	
	}
	
	function GetDoc($url, $titre="", $branche="", $tronc="", $content_type=""){
		
		//récupère l'identifiant de l'utilisateur
		$sql = "SELECT * FROM flux_docs WHERE url=\"".$url."\"";
		$rs = $this->site->DbGet($sql);
		
		if($r = mysql_fetch_assoc($rs)){
			$id = $r["id_doc"];
		}else{
			//ajoute un nouvel utilisateur
			$sql = "INSERT INTO flux_docs (url, titre, branche, tronc, content_type) 
				VALUES (\"".$url."\",\"".$titre."\",\"".$branche."\",\"".$tronc."\",\"".$content_type."\")";
			$this->site->DbGet($sql);
			$id = mysql_insert_id();
		}
		$this->site->DbFree();
		if($GetRow)
			return $r;
		else
			return $id;
		
	}

	function SetTagTag($TagSrc, $TagDst){

	    if(!$idTagSrc) $idTagSrc = $this->GetTag($TagSrc);			   
	    if(!$idTagDst) $idTagDst = $this->GetTag($TagDst);			   
	    
	    //vérifie si l'utilisateur possède le tag
		$sql = "SELECT id_instant FROM flux_tags_tags 
			WHERE id_tag_src=".$idTagSrc." 
				AND id_tag_dst=".$idTagDst."
				AND id_instant > 0";
		$rs = $this->site->DbGet($sql);
		
		if(!$r = mysql_fetch_assoc($rs)){
			//ajoute un nouvel tags de tags
			$sql = "INSERT INTO flux_tags_tags (id_tag_src, id_tag_dst, id_instant) 
				VALUES (".$idTagSrc.",".$idTagDst.",".$this->idInstant.")";
			$this->site->DbGet($sql);
		}
		$this->site->DbFree();
		return array("idTagSrc"=>$idTagSrc,"idTagDst"=>$idTagDst);
	}
		
	function SetExiTag($nom, $tag, $idExi=false, $idTag=false){

	    if(!$idTag) $idTag = $this->GetTag($tag);			   
	    if(!$idExi) $idExi = $this->GetUti($nom);			   
	    
	    //vérifie si l'utilisateur possède le tag
		$sql = "SELECT id_tag FROM flux_tags_exis 
			WHERE id_tag=".$idTag." AND id_exi=".$idExi."
				AND id_instant > 0";
		$rs = $this->site->DbGet($sql);
		
		if(!$r = mysql_fetch_assoc($rs)){
			//ajoute un nouvel utilisateur
			$sql = "INSERT INTO flux_tags_exis (id_tag, id_exi, id_instant) 
				VALUES (".$idTag.",".$idExi.",".$this->idInstant.")";
			$this->site->DbGet($sql);
		}
		$this->site->DbFree();
		return array("idExi"=>$idExi,"idTag"=>$idTag);
	}

	function SetExiDoc($idExi, $idDoc){

	    
	    //vérifie si l'utilisateur possède le doc
		$sql = "SELECT id_doc FROM flux_exis_docs 
			WHERE id_doc=".$idDoc." AND id_exi=".$idExi."
				AND id_instant > 0";
		$rs = $this->site->DbGet($sql);
		
		if(!$r = mysql_fetch_assoc($rs)){
			//ajoute un nouvel utilisateur
			$sql = "INSERT INTO flux_exis_docs (id_doc, id_exi, id_instant) 
				VALUES (".$idDoc.",".$idExi.",".$this->idInstant.")";
			$this->site->DbGet($sql);
		}
		$this->site->DbFree();
		return array("idExi"=>$idExi,"idDoc"=>$idDoc);
	}
	
	function SetDocTag($idDoc, $tag, $idTag=false){

	    if(!$idTag) $idTag = $this->GetTag($tag);			   
	    
	    //vérifie si l'utilisateur possède le tag
		$sql = "SELECT id_tag FROM flux_tags_docs 
			WHERE id_tag=".$idTag." AND id_doc=".$idDoc."
				AND id_instant > 0";
		$rs = $this->site->DbGet($sql);
		
		if(!$r = mysql_fetch_assoc($rs)){
			//ajoute un nouvel utilisateur
			$sql = "INSERT INTO flux_tags_docs (id_tag, id_doc, id_instant) 
				VALUES (".$idTag.",".$idDoc.",".$this->idInstant.")";
			$this->site->DbGet($sql);
		}
		$this->site->DbFree();
		return array("idDoc"=>$idDoc,"idTag"=>$idTag);
	}

	function GetTags($src, $dst, $params=""){
		
		$occu=10;$repetead=10;$taille=64;
		
		switch ($src) {
			case 'all':		
				$sql = "SELECT t.tag, t.id_tag id, COUNT(td.id_tag) poids, '$occu' occu, '$repetead' repetead, '$taille' taille
					FROM flux_tags t
						INNER JOIN flux_tags_docs td ON td.id_tag = t.id_tag AND td.id_instant >= 0  
					GROUP BY t.id_tag
					ORDER BY t.tag";
				break;
			case 'tags_exis':		
				$sql = "SELECT t.tag, t.id_tag id, COUNT(te.id_tag) poids, '$occu' occu, '$repetead' repetead, '$taille' taille
					FROM flux_tags t
						INNER JOIN flux_tags_exis te ON te.id_tag = t.id_tag  AND te.id_instant >= 0
					GROUP BY t.id_tag
					ORDER BY t.tag";
				break;
			case 'tags_docs':
				$params = split("_",$params);
				$titre = $this->site->StringToHTML(utf8_encode($params[0]));
				$branche = $params[1];		
				$sql = "SELECT t.tag, t.id_tag id, COUNT(td.id_tag) poids, '$occu' occu, '$repetead' repetead, '$taille' taille
					FROM flux_tags t
						INNER JOIN flux_tags_docs td ON td.id_tag = t.id_tag  AND td.id_instant >= 0
						INNER JOIN flux_docs d ON d.id_doc = td.id_doc AND d.titre = \"".$titre."\" AND d.branche = $branche
					GROUP BY t.id_tag
					ORDER BY t.tag";
				break;
			case 'tags_doc':
				$sql = "SELECT t.tag, t.id_tag id, COUNT(td.id_tag) poids, '$occu' occu, '$repetead' repetead, '".($taille*2)."' taille
					FROM flux_tags t
						INNER JOIN flux_tags_docs td ON td.id_tag = t.id_tag  AND td.id_instant >= 0 AND td.id_doc = ".$params."
					GROUP BY t.id_tag
					ORDER BY t.tag";
				break;
			case 'DocTags':
				$sql = "SELECT t.tag, t.id_tag, td.id_doc
					FROM flux_tags t
						INNER JOIN flux_tags_docs td ON td.id_tag = t.id_tag  AND td.id_instant >= 0 AND td.id_doc = $params
					ORDER BY t.tag";
				break;
			case 'exis':		
				$sql = "SELECT e.nom tag, e.id_exi id, COUNT(te.id_tag) poids, '$occu' occu, '$repetead' repetead, '$taille' taille
					FROM flux_exis e
						INNER JOIN flux_tags_exis te ON te.id_exi = e.id_exi  AND te.id_instant >= 0
					GROUP BY e.id_exi
					ORDER BY e.nom";
				break;
			case 'exis_tag':		
				$sql = "SELECT e.nom tag, e.id_exi id, COUNT(te.id_tag) poids, '$occu' occu, '$repetead' repetead, '100' taille
					FROM flux_exis e
						INNER JOIN flux_tags_exis te ON te.id_exi = e.id_exi AND te.id_tag = $params AND te.id_instant >= 0
					GROUP BY e.id_exi
					ORDER BY e.nom";
				break;
			case 'ExiTags':		
				$sql = "SELECT t.tag, t.id_tag, e.id_exi
					FROM flux_exis e
						INNER JOIN flux_tags_exis te ON te.id_exi = e.id_exi AND te.id_instant >= 0
						INNER JOIN flux_tags t ON t.id_tag = te.id_tag
					WHERE e.id_exi = $params
					ORDER BY t.tag";
				break;
			case 'doc_titre':		
				$sql = "SELECT d.titre tag, d.titre id, COUNT(d.id_doc) poids, '$occu' occu, '$repetead' repetead,  '".($taille*2)."' taille
					FROM flux_docs d
					WHERE d.titre != 'Questionnaire de Proust' AND d.content_type='audio/mpeg'
					GROUP BY d.titre
					ORDER BY d.titre";
				break;
			case 'doc_questions':		
				$titre = $this->site->StringToHTML(utf8_encode($params));		
				$sql = "SELECT tQ.tag tag, CONCAT(d.titre, '_', d.branche) id, COUNT(d.id_doc) poids, '$occu' occu, '$repetead' repetead, '160' taille
					FROM flux_docs d
						INNER JOIN flux_tags t ON t.tag = CONCAT('Question ',d.branche) 
						INNER JOIN flux_tags_tags tt ON tt.id_tag_src = t.id_tag AND tt.id_instant >= 0
						INNER JOIN flux_tags tQ ON tQ.id_tag = tt.id_tag_dst  
					WHERE d.titre = \"".$titre."\"
					GROUP BY d.branche
					ORDER BY d.id_doc";
				break;
		}		
		
		
		$rs = $this->site->DbGet($sql);
		$this->site->DbFree();
		
		switch ($dst) {
			case 'XML3D':
				return $this->GetTagsXml3D($rs);
				break;
			case 'table':		
				return $this->GetTagsTable($rs);
				break;
			case 'rs':
				return $rs;
				break;
		}		

		
	}

	function GetListeMp3($src, $params=""){
		
		$where = " WHERE d.content_type = 'audio/mpeg' ";
		switch ($src) {
			case 'tags_exis':
				$tags_exis = " INNER JOIN flux_tags_exis te ON te.id_exi = e.id_exi AND te.id_tag =".$params." ";
				break;
			case 'exis_tag':
				$exis_tag = " AND e.id_exi =".$params;
				break;
			case 'tags_docs':		
				$tags_docs = " INNER JOIN flux_tags_docs td ON td.id_doc = d.id_doc AND td.id_tag =".$params." ";
				break;
			case 'doc_titre':		
				$titre = $this->site->StringToHTML(utf8_encode($params));
				$where .= " AND d.titre =\"".$titre."\" ";
				break;
			case 'doc_questions':		
				$arrP = split("_",$params);
				$titre = $this->site->StringToHTML(utf8_encode($arrP[0]));
				$where = " AND d.titre =\"".$titre."\" AND d.branche=".$arrP[1]." ";
				break;
		}		

		
		//construction de la requête suivant les paramètres		
		$sql = "SELECT d.id_doc, d.titre, d.url, d.branche
				, e.nom, e.id_exi
				, tQ.tag question
				, dIma.url image
				, dLien.url lien 
			FROM flux_docs d
				INNER JOIN flux_exis_docs ed ON ed.id_doc = d.id_doc
				INNER JOIN flux_exis e ON e.id_exi = ed.id_exi ".$exis_tag." 
				INNER JOIN flux_tags t ON t.tag = CONCAT('Question ',d.branche) 
				INNER JOIN flux_tags_tags tt ON tt.id_tag_src = t.id_tag
				INNER JOIN flux_tags tQ ON tQ.id_tag = tt.id_tag_dst 
				INNER JOIN flux_exis_docs edIma ON edIma.id_exi = ed.id_exi
				INNER JOIN flux_docs dIma ON dIma.id_doc = edIma.id_doc AND dIma.content_type='image/png'
				INNER JOIN flux_exis_docs edLien ON edLien.id_exi = ed.id_exi
				INNER JOIN flux_docs dLien ON dLien.id_doc = edLien.id_doc AND dLien.content_type='text/html'
				".$tags_exis."
				".$tags_docs."
				".$where."
			GROUP BY d.id_doc
			ORDER BY RAND();
				";
		$rs = $this->site->DbGet($sql);		
		$this->site->DbFree();
		
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<playlist version="0">
			<trackList>'.EOL;
		while($r=mysql_fetch_assoc($rs)){
			$path = str_replace("/homez.311/claudeya/www/manifestes/",PathWeb,$r['url']);
			$xml .= '<track>
			<album>'.$r['titre'].'</album>
			<annotation>'.$r['id_doc'].'_'.$r['id_exi'].'</annotation>
			<artist>'.$r['nom'].'</artist>
			<creator>Claude Yacoub</creator>
			<image>'.$r['image'].'</image>
			<info>'.$r['lien'].'</info>
			<link>'.$r['lien'].'</link>
			<location>'.$path.'</location>
			<title>'.$r['titre'].' * '.$r['nom'].' * '.$r['question'].'</title>
			<trackNum>'.$r['branche'].'</trackNum>
			</track>'.EOL;
		}
		$xml .= '</trackList>
			</playlist>';
		
		return $xml;
				
	}
	
	function GetTagsXml3D($rs){

		//inspiré par http://carvalhar.com/#/en/blog/183/flex-cumulus-tag-cloud/

		$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<tags>'.EOL;
		while($r=mysql_fetch_assoc($rs)){
			$xml .= '<tag>';
    		$xml .= '<name>'.$r['tag'].'</name>';
    		$xml .= '<tid>'.$r['id'].'</tid>';
			$xml .= '<weight>'.$r['poids'].'</weight>';
			$xml .= '<counter>'.$r['occu'].'</counter>';
			$xml .= '<repetead>'.$r['repetead'].'</repetead>';
			$xml .= '<size>'.$r['taille'].'</size>';
			//$xml .= '<meuPeso>'.($r['poids']*$r['occu'])+($r['poids']*$r['occu']).'</meuPeso>';
			$xml .= '<meuPeso>2</meuPeso>';
			$xml .= '</tag>'.EOL;
		}
		$xml .= '</tags>';
		
		return $xml;
		
	}

	function GetTagsTable($rs){

		//inspiré par http://carvalhar.com/#/en/blog/183/flex-cumulus-tag-cloud/

		$xml = '<table>'.EOL;
		$xml .= '<tr><th>id</th><th>tag</th><th>poids</th></tr>'.EOL;
		while($r=mysql_fetch_assoc($rs)){
			$xml .= '<tr>';
			$xml .= '<td>'.$r['id'].'</td>';
			$xml .= '<td>'.$r['tag'].'</td>';
			$xml .= '<td>'.$r['poids'].'</td>';
			$xml .= '</tr>'.EOL;
		}
		$xml .= '</table>';
		
		return $xml;
		
	}

	function DeleteDocTag($idExi, $idDoc, $idTag){
		
		//création de l'instant
		$this->SetInstant($idExi,false,"DeleteDocTag");
		
		//on rend négatif l'instant du tag sopurce		
		$sql = "UPDATE flux_tags_docs SET id_instant = ".($this->idInstant*-1)." 
				WHERE id_tag = $idTag AND id_doc = $idDoc AND id_instant >= 0 ";
		$rs = $this->site->DbGet($sql);
		
		$this->site->DbFree();
		
	}

	function DeleteExiTag($idExi, $idTag){
		
		//création de l'instant
		$this->SetInstant($idExi,false,"DeleteExiTag");
		
		//on rend négatif l'instant du tag sopurce		
		$sql = "UPDATE flux_tags_exis SET id_instant = ".($this->idInstant*-1)." 
				WHERE id_tag = $idTag AND id_exi = $idExi AND id_instant >= 0 ";
		$rs = $this->site->DbGet($sql);
		
		$this->site->DbFree();
		
	}
	
	function ChangeTags($idTagSrc, $TagDst, $TagSrc=false, $idTagDst=false){

		//start benchmark
		$start = microtime();
		
		if(!$idTagSrc)$idTagSrc = $this->GetTag($this->site->StringToHTML(utf8_encode($TagSrc)));
		
		$arrTags = split("_",$TagDst);
		foreach($arrTags as $t){
		
			//encode le tag
			$t = $this->site->StringToHTML(utf8_encode($t));
			
			if(!$idTagDst)$idTagDst = $this->GetTag($t);
			
			switch ($t) {
				case '':
					//on ne fait rien
					/*
					$sql = "UPDATE flux_tags_docs SET id_instant = $this->idInstant 
							WHERE id_tag = $idTagSrc AND id_instant = ".($this->idInstant-1)." ";
					$rs = $this->site->DbGet($sql);
					
					//met à jour les liens avec les exis
					$sql = "UPDATE flux_tags_exis SET id_instant = $this->idInstant 
							WHERE id_tag = $idTagSrc AND id_instant = ".($this->idInstant-1)." ";
					$rs = $this->site->DbGet($sql);
					$this->site->DbFree();
					*/
					break;
				case 'x':
					//on rend négatif l'instant du tag sopurce		
					$sql = "UPDATE flux_tags_docs SET id_instant = ".($this->idInstant*-1)." 
							WHERE id_tag = $idTagSrc AND id_instant >= 0 ";
					$rs = $this->site->DbGet($sql);
					
					$sql = "UPDATE flux_tags_exis SET id_instant = ".($this->idInstant*-1)." 
							WHERE id_tag = $idTagSrc AND id_instant >= 0 ";
					$rs = $this->site->DbGet($sql);
	
					$this->site->DbFree();
					break;
				default:				
					//ajoute les liens avec les docs
					$sql = "INSERT INTO flux_tags_docs (id_tag, id_doc, id_instant) 
						SELECT $idTagDst, id_doc, $this->idInstant 
						FROM flux_tags_docs WHERE id_tag = $idTagSrc
							AND id_instant >= 0 
							AND id_doc NOT IN (
								SELECT id_doc FROM flux_tags_docs 
								WHERE id_tag = $idTagDst AND id_instant >= 0) ";
					$rs = $this->site->DbGet($sql);
					
					//met à jour les liens avec les exis
					$sql = "INSERT INTO flux_tags_exis (id_tag, id_exi, id_instant) 
						SELECT $idTagDst, id_exi, $this->idInstant 
						FROM flux_tags_exis WHERE id_tag = $idTagSrc
							AND id_instant >= 0 
							AND id_exi NOT IN (
								SELECT id_exi FROM flux_tags_exis 
								WHERE id_tag = $idTagDst AND id_instant >= 0) ";
					$rs = $this->site->DbGet($sql);

					//met à jour les liens avec les tag
					$sql = "INSERT INTO flux_tags_tags (id_tag_src, id_tag_dst, id_instant) 
						SELECT id_tag_src, $idTagDst, $this->idInstant 
						FROM flux_tags_tags WHERE id_tag_dst = $idTagSrc ";
					$rs = $this->site->DbGet($sql);
					
					//on rend négatif l'instant du tag source		
					$sql = "UPDATE flux_tags_docs SET id_instant = ".($this->idInstant*-1)." 
							WHERE id_tag = $idTagSrc AND id_instant >= 0 ";
					$rs = $this->site->DbGet($sql);
					
					$sql = "UPDATE flux_tags_exis SET id_instant = ".($this->idInstant*-1)." 
							WHERE id_tag = $idTagSrc AND id_instant >= 0 ";
					$rs = $this->site->DbGet($sql);
	
					$sql = "UPDATE flux_tags_tags SET id_instant = ".($this->idInstant*-1)." 
							WHERE id_tag_dst = $idTagSrc ";
					$rs = $this->site->DbGet($sql);
					
					$this->site->DbFree();
			}
		}
			
		$end = microtime(); 
		$t2 = ($this->site->getmicrotime($end) - $this->site->getmicrotime($start)); 
		// end benchmark timing
		echo "Total ChangeTags Time: $TagSrc -> $TagDst <b>$t2</b>";
		echo "<br/><br/>";		
	}

	function ChangeExis($idExiSrc, $ExiDst, $ExiSrc=false, $idExiDst=false){

		//start benchmark
		$start = microtime();
		
		if(!$idExiSrc)$idExiSrc = $this->GetExi($this->site->StringToHTML(utf8_encode($ExiSrc)));
		
		$arrTags = split("_",$ExiDst);
		foreach($arrTags as $t){
		
			//encode le tag
			$t = $this->site->StringToHTML(utf8_encode($t));
			
			if(!$idExiDst)$idExiDst = $this->GetExi($t);
			
			switch ($t) {
				case '':
					//on ne fait rien
					/*
					$sql = "UPDATE flux_tags_docs SET id_instant = $this->idInstant 
							WHERE id_tag = $idTagSrc AND id_instant = ".($this->idInstant-1)." ";
					$rs = $this->site->DbGet($sql);
					
					//met à jour les liens avec les exis
					$sql = "UPDATE flux_tags_exis SET id_instant = $this->idInstant 
							WHERE id_tag = $idTagSrc AND id_instant = ".($this->idInstant-1)." ";
					$rs = $this->site->DbGet($sql);
					$this->site->DbFree();
					*/
					break;
				case 'x':
					//on rend négatif l'instant du tag sopurce		
					$sql = "UPDATE flux_exis_docs SET id_instant = ".($this->idInstant*-1)." 
							WHERE id_exi = $idExiSrc AND id_instant >= 0 ";
					$rs = $this->site->DbGet($sql);
					
					$sql = "UPDATE flux_tags_exis SET id_instant = ".($this->idInstant*-1)." 
							WHERE id_exi = $idExiSrc AND id_instant >= 0 ";
					$rs = $this->site->DbGet($sql);
	
					$this->site->DbFree();
					break;
				default:				
					//ajoute les liens avec les docs
					$sql = "INSERT INTO flux_exis_docs (id_exi, id_doc, id_instant) 
						SELECT $idExiDst, id_doc, $this->idInstant 
						FROM flux_exis_docs WHERE id_exi = $idExiSrc
							AND id_instant >= 0 
							AND id_doc NOT IN (
								SELECT id_doc FROM flux_tags_docs 
								WHERE id_exi = $idExiDst AND id_instant >= 0) ";
					$rs = $this->site->DbGet($sql);
					
					//met à jour les liens avec les exis
					$sql = "INSERT INTO flux_tags_exis (id_exi, id_tag, id_instant) 
						SELECT $idExiDst, id_tag, $this->idInstant 
						FROM flux_tags_exis WHERE id_exi = $idExiSrc
							AND id_instant >= 0 
							AND id_tag NOT IN (
								SELECT id_tag FROM flux_tags_exis 
								WHERE id_exi = $idExiDst AND id_instant >= 0) ";
					$rs = $this->site->DbGet($sql);
	
					//on rend négatif l'instant du tag source		
					$sql = "UPDATE flux_exis_docs SET id_instant = ".($this->idInstant*-1)." 
							WHERE id_exi = $idExiSrc AND id_instant >= 0 ";
					$rs = $this->site->DbGet($sql);
					
					$sql = "UPDATE flux_tags_exis SET id_instant = ".($this->idInstant*-1)." 
							WHERE id_exi = $idExiSrc AND id_instant >= 0 ";
					$rs = $this->site->DbGet($sql);
	
					$this->site->DbFree();
			}
		}
			
		$end = microtime(); 
		$t2 = ($this->site->getmicrotime($end) - $this->site->getmicrotime($start)); 
		// end benchmark timing
		echo "Total ChangeExis Time: $ExiSrc -> $ExiDst <b>$t2</b>";
		echo "<br/><br/>";		
	}
	
	
}
	
	
?>
