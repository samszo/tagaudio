<?php
  //
  // Fichier contenant les definitions de constantes

  //define ("PathRoot",$_SERVER["DOCUMENT_ROOT"]."/");
  //define ("PathWeb","http://claudeyacoub.org/");
  //pour le débubbage
  define ("PathRoot","C:/wamp/www/cy-manifestes/");
  define ("PathWeb","http://localhost/cy-manifestes/");
  
  //inclusion des class.
  require_once(PathRoot."library/Flux.php");
  require_once(PathRoot."library/Site.php");
  require_once(PathRoot."library/getid3/getid3.php");
  require_once(PathRoot."library/Flex.php");
  require_once(PathRoot."library/getid3/getid3.php");
  require_once(PathRoot."library/cache.inc.php");
    
  define ("TRACE", false);
  define ("DEFSITE", "local");

  define ("CACHETIME", 0); //86400 une journée
  define ("FORCE_CALCUL", true); //pour forcer les calculs et la mise à jour
  define('CACHE_PATH', PathRoot.'tmp/');    
  define('EOL', "\r\n");
  define ("PathAjax",PathWeb."library/ExeAjax.php");
  define ("PathAudio",PathWeb."cy-audios/");

  $infos = array(
	"SQL_LOGIN" => "root", 
	"SQL_PWD" => "", 
	"SQL_HOST" => "localhost",
	"SQL_DB" => "cy_tagaudio",
	"NOM" => "tagaudio",//je sais pas
  	); 

  	
?>