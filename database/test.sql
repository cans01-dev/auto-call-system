DROP DATABASE auto_call_system;

CREATE DATABASE auto_call_system;
USE auto_call_system;

CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT,
  email varchar(255) NOT NULL UNIQUE,
  password varchar(255) NOT NULL,
  status int(11) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE send_emails (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  email varchar(255) NOT NULL,
  enabled boolean NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE surveys (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  note text(65535),
  greeting text(65535),
  greeting_voice_file varchar(255),
  voice_name varchar(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE endings (
  id int(11) NOT NULL AUTO_INCREMENT,
  survey_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  text text(65535),
  voice_file varchar(255),
  PRIMARY KEY (id),
  FOREIGN KEY (survey_id) REFERENCES surveys (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE faqs (
  id int(11) NOT NULL AUTO_INCREMENT,
  survey_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  text text(65535),
  order_num int(11) NOT NULL,
  voice_file varchar(255),
  PRIMARY KEY (id),
  FOREIGN KEY (survey_id) REFERENCES surveys (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE options (
  id int(11) NOT NULL AUTO_INCREMENT,
  faq_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  dial int(11) NOT NULL,
  next_ending_id int(11),
  next_faq_id int(11),
  PRIMARY KEY (id),
  FOREIGN KEY (faq_id) REFERENCES faqs (id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (next_faq_id) REFERENCES faqs (id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (next_ending_id) REFERENCES endings (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE reserves (
  id int(11) NOT NULL AUTO_INCREMENT,
  survey_id int(11) NOT NULL,
  date date NOT NULL,
  start time NOT NULL,
  end time NOT NULL,
  status int(11) NOT NULL,
  reserve_file varchar(255),
  result_file varchar(255),
  PRIMARY KEY (id),
  FOREIGN KEY (survey_id) REFERENCES surveys (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE favorites (
  id int(11) NOT NULL AUTO_INCREMENT,
  survey_id int(11) NOT NULL,
  start time NOT NULL,
  end time NOT NULL,
  title varchar(255) NOT NULL,
  color char(7) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (survey_id) REFERENCES surveys (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE areas (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE favorites_areas (
  id int(11) NOT NULL AUTO_INCREMENT,
  favorite_id int(11) NOT NULL,
  area_id int(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (favorite_id) REFERENCES favorites (id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (area_id) REFERENCES areas (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE reserves_areas (
  id int(11) NOT NULL AUTO_INCREMENT,
  reserve_id int(11) NOT NULL,
  area_id int(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (reserve_id) REFERENCES reserves (id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (area_id) REFERENCES areas (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE stations (
  id int(11) NOT NULL AUTO_INCREMENT,
  area_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  prefix varchar(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (area_id) REFERENCES areas (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE calls (
  id int(11) NOT NULL AUTO_INCREMENT,
  reserve_id int(11) NOT NULL,
  number char(13) NOT NULL,
  status int(11) NOT NULL,
  duration int(11),
  time time,
  PRIMARY KEY (id),
  FOREIGN KEY (reserve_id) REFERENCES reserves (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE answers (
  id int(11) NOT NULL AUTO_INCREMENT,
  call_id int(11) NOT NULL,
  faq_id int(11) NOT NULL,
  option_id int(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (call_id) REFERENCES calls (id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (faq_id) REFERENCES faqs (id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (option_id) REFERENCES options (id) ON DELETE CASCADE ON UPDATE CASCADE
);