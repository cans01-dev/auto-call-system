USE auto_call_system;

DROP TABLE send_emails;
DROP TABLE options;
DROP TABLE stations;
DROP TABLE reserves_areas;
DROP TABLE areas;
DROP TABLE favorites;
DROP TABLE reserves;
DROP TABLE faqs;
DROP TABLE surveys;
DROP TABLE users;


CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT,
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE send_emails (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  email varchar(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE surveys (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  note text(65535),
  greeting_text text(65535),
  ending_text text(65535),
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE faqs (
  id int(11) NOT NULL AUTO_INCREMENT,
  survey_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  text text(65535),
  PRIMARY KEY (id),
  FOREIGN KEY (survey_id) REFERENCES surveys (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE options (
  id int(11) NOT NULL AUTO_INCREMENT,
  faq_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  text text(65535),
  is_last boolean NOT NULL,
  next_faq_id int(11),
  PRIMARY KEY (id),
  FOREIGN KEY (faq_id) REFERENCES faqs (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE reserves (
  id int(11) NOT NULL AUTO_INCREMENT,
  survey_id int(11) NOT NULL,
  date date NOT NULL,
  start time NOT NULL,
  end time NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (survey_id) REFERENCES surveys (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE favorites (
  id int(11) NOT NULL AUTO_INCREMENT,
  survey_id int(11) NOT NULL,
  reserve_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (survey_id) REFERENCES surveys (id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (reserve_id) REFERENCES reserves (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE areas (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  PRIMARY KEY (id)
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
  PRIMARY KEY (id),
  FOREIGN KEY (area_id) REFERENCES areas (id) ON DELETE CASCADE ON UPDATE CASCADE
);


INSERT INTO users (email, password) VALUES
('test@example.com', '$2y$10$smM.1r.LkbkvktimMdr14ufFph9Wb97w2t5/wZVuXCeW0z3MLi8iW');