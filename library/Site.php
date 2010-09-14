<?php
class Site{
  public $id;
  public $trace;
  public $cache;
  public $infos;
  public $DbLink;
  public $DbRs;
  
	function __tostring() {
    	return "Cette classe permet de dÔøΩfinir et manipuler un site.<br/>";
    }

  	function __construct($infos) {
    	$this->infos = $infos;
    }

	function getmicrotime($t) { 
		list($usec, $sec) = explode(" ",$t); 
		return ((float)$usec + (float)$sec); 
	} 

	public function DbGet($sql){
		// Connexion et sÈlection de la base
    	$this->DbLink = mysql_connect($this->infos["SQL_HOST"], $this->infos["SQL_LOGIN"], $this->infos["SQL_PWD"])
		    or die('Impossible de se connecter : ' . mysql_error());
		mysql_select_db($this->infos["SQL_DB"]) or die('Impossible de sÈlectionner la base de donnÈes');
		
		// ExÈcution des requÍtes SQL
		$this->DbRs = mysql_query($sql) or die('…chec de la requÍte : ' . mysql_error());
			    	
		return $this->DbRs;
		
    }

    public function DbFree(){
		// Fermeture de la connexion
		mysql_close($this->DbLink);
    }
    
    
	public function GetFile($path){

	    if(!$_SESSION['ForceCalcul'] && file_exists($path)){
			$contents = file_get_contents($path);
			return $contents;
		}else{
			return false;	
		}
    }
    
    public function SaveFile($path,$texte){

   		$fic = fopen($path, "w");
		if($fic){
			fwrite($fic, $texte);		
	    	fclose($fic);
		}

    }
    
    
	
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
	  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
	  $theValue = str_replace("'","''",$theValue);
	  $theValue = str_replace("\"","''",$theValue);
	  $theValue = htmlentities($theValue);
	  //echo $theValue."<br/>";

	  switch ($theType) {
	    case "text":
	      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	      break;    
	    case "long":
	    case "int":
	      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
	      break;
	    case "double":
	      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
	      break;
	    case "date":
	      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	      break;
	    case "defined":
	      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
	      break;
	  }
	  return $theValue;
	}

	public function XML_entities($str)
	{
		//$str = str_replace("'","''",$str);
	    return preg_replace(array("'&'", "'\"'", "'<'", "'>'"), array('&#38;', '&#34;','&lt;','&gt;'), $str);
	}
	
	public function GetCurl($url){
		
		if($this->trace)
			echo "Site:GetCurl:url=".$url."<br/>";
		$oCurl = curl_init($url);
		// set options
	   // curl_setopt($oCurl, CURLOPT_HEADER, true);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		$arrInfos = curl_getinfo($oCurl);
		if($this->trace)
			echo "Site:GetCurl:arrInfos=".print_r($arrInfos)."<br/>";

		// request URL
		$sResult = curl_exec($oCurl);
		if($this->trace)
			echo "Site:GetCurl:sResult=".$sResult."<br/>";
		
		// close session
		curl_close($oCurl);

		return $sResult;
		
	}
	

	
	function stripAccents($string){
 		return strtr($string,'‡·‚„‰ÁËÈÍÎÏÌÓÔÒÚÛÙıˆ˘˙˚¸˝ˇ¿¡¬√ƒ«»… ÀÃÕŒœ—“”‘’÷Ÿ⁄€‹›',
		 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
  	}

	function strtokey($str){
	    for ($iii = 0; $iii < strlen($str); $iii++)
	      if (ord($str[$iii]) == 146 || ord($str[$iii]) == 156)
		$str[$iii] = '-';
	    $key = str_replace("_", "-", $str);
	    $key = str_replace("'", "-", $key);
	
	    $key = str_replace("`", "-", $key);
	    $key = str_replace(".", "-", $key);
	    $key = str_replace(" ", "-", $key);
	    $key = str_replace(",", "-", $key);
	    $key = str_replace("{}", "_", $key);
	    $key = str_replace("(", "_", $key);
	    $key = str_replace(")", "_", $key);
	    $key = str_replace("--", "-", $key);
	    $key = str_replace("- -", "-", $key);
	    $key = str_replace("<i>", "", $key);
	    $key = str_replace("</i>", "", $key);
	    $key = str_replace(":", "", $key);
	    $key = str_replace("´", "", $key);
	    $key = str_replace("ª", "", $key);
	    $key = str_replace("/", "", $key);
	    $key = str_replace("ì", "", $key);
	    $key = str_replace("î", "", $key);
	    $key = str_replace("\\", "", $key);
	    
	    $key = strtolower($key);
	    return $this->stripAccents($key);
	}

	function StringToHTML($string) {
		$HTMLstring = '';

		$strlen = strlen($string);
		for ($i = 0; $i < $strlen; $i++) {
			$char_ord_val = ord($string{$i});
			$charval = 0;
			if ($char_ord_val < 0x80) {
				$charval = $char_ord_val;
			} elseif ((($char_ord_val & 0xF0) >> 4) == 0x0F  &&  $i+3 < $strlen) {
				$charval  = (($char_ord_val & 0x07) << 18);
				$charval += ((ord($string{++$i}) & 0x3F) << 12);
				$charval += ((ord($string{++$i}) & 0x3F) << 6);
				$charval +=  (ord($string{++$i}) & 0x3F);
			} elseif ((($char_ord_val & 0xE0) >> 5) == 0x07  &&  $i+2 < $strlen) {
				$charval  = (($char_ord_val & 0x0F) << 12);
				$charval += ((ord($string{++$i}) & 0x3F) << 6);
				$charval +=  (ord($string{++$i}) & 0x3F);
			} elseif ((($char_ord_val & 0xC0) >> 6) == 0x03  &&  $i+1 < $strlen) {
				$charval  = (($char_ord_val & 0x1F) << 6);
				$charval += (ord($string{++$i}) & 0x3F);
			}
			if (($charval >= 32) && ($charval <= 127)) {
				$HTMLstring .= htmlentities(chr($charval));
			} else {
				$HTMLstring .= '&#'.$charval.';';
			}
		}

		return $HTMLstring;
	}	
	
}
?>