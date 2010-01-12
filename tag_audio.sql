CREATE TABLE medias (
  id_media int(11) NOT NULL AUTO_INCREMENT,
  url longtext NOT NULL,
  PRIMARY KEY (id_media)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE media_tags (
  id_media int(11) NOT NULL,
  id_tag int(11) NOT NULL,
  debut time NOT NULL,
  fin time NOT NULL,
  poids enum('1','2','3','4','5') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE tags (
  id_tag int(11) NOT NULL AUTO_INCREMENT,
  tag varchar(100) NOT NULL,
  PRIMARY KEY (id_tag)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;