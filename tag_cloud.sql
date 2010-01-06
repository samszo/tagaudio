CREATE TABLE IF NOT EXISTS medias (
  id_media int(11) NOT NULL AUTO_INCREMENT,
  path varchar(255) NOT NULL,
  file_name varchar(200) NOT NULL,
  PRIMARY KEY (id_media)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS tags (
  id_tag int(11) NOT NULL AUTO_INCREMENT,
  tag varchar(100) NOT NULL,
  PRIMARY KEY (id_tag)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS media_tags (
  id_media int(11) NOT NULL,
  id_tag int(11) NOT NULL,
  debut time NOT NULL,
  fin time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




