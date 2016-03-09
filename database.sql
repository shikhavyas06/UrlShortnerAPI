

CREATE TABLE short_urls (
  id varchar(255) NOT NULL default '',
  url text,
  date timestamp NOT NULL,
  PRIMARY KEY (id)
)ENGINE=InnoDB