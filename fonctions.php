<?php
/* ----------------------------------------------------------------------------------------------------- */
// ajouter des tags pour sur période donnee du fichier
function Add_tags($id_media, $debut, $fin, $list_tag)
{
 $id_tag= -1 ;
 
	//parcourir la liste des tags
	foreach($list_tag as $tag)
	{$sql="select id_tag from tags where tag='".$tag."'";
	 $result = mysql_query ($sql);
	 $num_rows = mysql_num_rows($result);
	 
        if ($num_rows==0)
					{ //le tag n existait pas
					mysql_query("insert into tags values('','".$tag."')");
					$id_tag = mysql_insert_id();
					}
		else 
					{ //le tag existe deja
					$rows=mysql_fetch_assoc($result);
					$id_tag = $rows['id'];		
					}
	 $sql="insert into media_tags values ('".$id_media."','".$id_tag."','".$debut."','".$fin."')";
	}//for each
	
}//function


/* ----------------------------------------------------------------------------------------------------- */
function Get_tags($id_media, $scnd1, $scnd2)
{
 $list_tags = array();
 
	 $sql="select tag from tags where id_tag in (select id_tag from media_tags where debut>='".$debut."' and fin <= '".$fin."' and id_media = '".$id_media."')";
	 $result = mysql_query ($sql);
	 $num_rows = mysql_num_rows($result);
	 
     if ($num_rows!=0) 
			{
			  while ($rows=mysql_fetch_assoc($result))
			  
			  $list_tags[] = $rows['tag'];
			 }
 return ($list_tags);
}//function


/* ----------------------------------------------------------------------------------------------------- */
 function Delete_tags ($id_media, $list_tags)
{
 $sql="delete from media_tags where id_media = '".$id_media."' and id_tag in (".implode(",",$list_tags)."))";
 $result = mysql_query ($sql);

 }//function


/* ----------------------------------------------------------------------------------------------------- */
// a l ouverture d un fichier audio
function Load_tags($id_media)
{
 $sql="select tag from tags where id_tag in( select id_tag from media_tags where id_media='".$id_media."')";
 $list_tags = array();
 if ($num_rows!=0) 
			{
			  while ($rows=mysql_fetch_assoc($result)
					$list_tags[] = $rows['tag'];
			 }

 return ($list_tags);
}


/* ----------------------------------------------------------------------------------------------------- */
function Search_tag( $id_media, $tag )
{
 $sql=" select * from media_tags where id_media='".$id_media."' and id_tag in ( select id_tag from tags where tag='".$tag."')";
 $list_occurences_tag = array();
 if ($num_rows!=0) 
			{
			  while ($rows=mysql_fetch_assoc($result))
			  
					$list_occurences_tag[] = $rows['debut']."-".$rows['fin'];
			 }

}//function


/* ----------------------------------------------------------------------------------------------------- */
function get_id_media ($path, $file_name)
{
 $sql="select id_media from medias where path='".$path."' and file_name='".$file_name."')";

 if ($num_rows!=0) 
			{
			  $rows=mysql_fetch_assoc($result);
					$id_media = $rows['id_media'];
			 }
 return ($id_media);
 
}//function

?>