-- untuk menyimpan data level pengguna
CREATE TABLE app_config (
  id          VARCHAR(36)   NOT NULL,
  name        VARCHAR(255) 	NOT NULL,
  label 	  VARCHAR(255)  NOT NULL,
  value 	  VARCHAR(255)  NOT NULL,
  PRIMARY KEY(id),
  UNIQUE(name)
);

CREATE TABLE app_menu (
  id          	  VARCHAR(36)   NOT NULL,
  menu_action     VARCHAR(255) 	NOT NULL,
  menu_label 	  VARCHAR(255)  NOT NULL,
  menu_order 	  INT DEFAULT 0,
  menu_level 	  INT DEFAULT 0,
  menu_options 	  VARCHAR(255),
  parent_id		  VARCHAR(36),
  PRIMARY KEY(id),
  FOREIGN KEY(parent_id) REFERENCES app_menu(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE app_permission (
  id          VARCHAR(36)   NOT NULL,
  label 	  VARCHAR(255)  NOT NULL,
  value 	  VARCHAR(255)  NOT NULL,
  PRIMARY KEY(id),
  UNIQUE(value)
);

CREATE TABLE app_role (
  id          VARCHAR(36)   NOT NULL,
  name        VARCHAR(50) 	NOT NULL,
  description VARCHAR(255),
  PRIMARY KEY(id),
  UNIQUE(name)
);

INSERT INTO app_role(id, name) VALUES('1', 'ADMIN');
INSERT INTO app_role(id, name) VALUES('2', 'USER');

CREATE TABLE app_role_permission (
  app_role_id          VARCHAR(36)   NOT NULL,
  app_permission_id    VARCHAR(36) 	NOT NULL,
  PRIMARY KEY (app_role_id, app_permission_id),
  FOREIGN KEY (app_role_id) REFERENCES app_role(id),
  FOREIGN KEY (app_permission_id) REFERENCES app_permission(id)
);

CREATE TABLE app_role_menu (
  app_role_id	VARCHAR(36)   NOT NULL,
  app_menu_id   VARCHAR(36)   NOT NULL,
  PRIMARY KEY (app_role_id, app_menu_id),
  FOREIGN KEY (app_role_id) REFERENCES app_role(id),
  FOREIGN KEY (app_menu_id) REFERENCES app_menu(id)
);

-- untuk menyimpan data pelanggan
CREATE TABLE customer (
  id          VARCHAR(36)   NOT NULL,
  name        VARCHAR(100) 	NOT NULL,
  PRIMARY KEY(id),
  UNIQUE(name)
);

-- untuk menyimpan data pengguna
CREATE TABLE app_user (
  id          VARCHAR(36)  NOT NULL,
  username    VARCHAR(255) NOT NULL,
  password    VARCHAR(255) NOT NULL,
  fullname    VARCHAR(255) NOT NULL,
  email       VARCHAR(255),
  app_role_id VARCHAR(36),
  customer_id VARCHAR(36),
  created_at  TIMESTAMP,
  upadated_at TIMESTAMP,
  PRIMARY KEY(id),
  UNIQUE(username),
  FOREIGN KEY(app_role_id) REFERENCES app_role(id) ON UPDATE CASCADE ON DELETE SET NULL,
  FOREIGN KEY(customer_id) REFERENCES customer(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE INDEX app_user_name ON app_user(username);

INSERT INTO app_user (id, username, fullname, password, app_role_id) VALUES ('1', 'admin', 'sinergi', md5('admin'), '1');
INSERT INTO app_user (id, username, fullname, password, app_role_id) VALUES ('2', 'user', 'operator', md5('user'), '2');

-- untuk menyimpan pengelompokan lampu / grup lampu
CREATE TABLE subnet (
  id          VARCHAR(36)  NOT NULL,
  name        VARCHAR(100) NOT NULL,
  customer_id VARCHAR(36),
  parent_id   VARCHAR(36),
  PRIMARY KEY(id),
  FOREIGN KEY(customer_id) REFERENCES customer(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY(parent_id) REFERENCES subnet(id) ON UPDATE CASCADE ON DELETE CASCADE
);

-- untuk menyimpan data titik lampu yang akan ditampilkan di peta
CREATE TABLE node (
  id 			   VARCHAR(36)      NOT NULL,
  name             VARCHAR(255)     NOT NULL,
  address		   VARCHAR(255),
  km			   VARCHAR(5),
  pole		   	   SMALLINT NOT NULL DEFAULT 1,
  latitude         DOUBLE PRECISION NOT NULL,
  longitude        DOUBLE PRECISION NOT NULL,
  imei             VARCHAR(30),
  chanel           SMALLINT,
  subnet_id        VARCHAR(36),
  consent_id       VARCHAR(36),
  batt_volt		   DOUBLE PRECISION,
  batt_curr		   DOUBLE PRECISION,
  batt_temp		   DOUBLE PRECISION,
  batt_life_cycle  DOUBLE PRECISION,
  cc_curr		   DOUBLE PRECISION,
  cc_power		   DOUBLE PRECISION,
  cc_temp 		   DOUBLE PRECISION,
  pv_curr		   DOUBLE PRECISION,
  pv_volt          DOUBLE PRECISION,
  ctrl_temp        DOUBLE PRECISION,
  load_curr        DOUBLE PRECISION,
  lamp_status 	   SMALLINT NOT NULL DEFAULT 0,
  fan_status	   SMALLINT NOT NULL DEFAULT 0,
  created_at       TIMESTAMP,
  updated_at       TIMESTAMP,
  PRIMARY KEY(id),
  UNIQUE(latitude, longitude),
  FOREIGN KEY(subnet_id) REFERENCES subnet(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY(consent_id) REFERENCES node(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE INDEX node_name_idx ON node(name);
CREATE INDEX imei_idx ON node(imei);

-- untuk menyimpan log data setiap lampu
CREATE TABLE datalog (
  id               VARCHAR(36) NOT NULL,
  node_id          VARCHAR(36),
  dtime            TIMESTAMP,
  batt_volt		   DOUBLE PRECISION,
  batt_curr		   DOUBLE PRECISION,
  batt_temp		   DOUBLE PRECISION,
  batt_life_cycle  DOUBLE PRECISION,
  cc_curr		   DOUBLE PRECISION,
  cc_power		   DOUBLE PRECISION,
  cc_temp 		   DOUBLE PRECISION,
  pv_curr		   DOUBLE PRECISION,
  pv_volt          DOUBLE PRECISION,
  ctrl_temp        DOUBLE PRECISION,
  load_curr        DOUBLE PRECISION,
  lamp_status 	   SMALLINT NOT NULL DEFAULT 0,
  fan_status	   SMALLINT NOT NULL DEFAULT 0,
  PRIMARY KEY(id),
  FOREIGN KEY(node_id) REFERENCES node(id) ON DELETE CASCADE
);

CREATE INDEX datalog_node_idx ON datalog(node_id);
CREATE INDEX datalog_dtime_idx ON datalog(dtime);

CREATE TABLE lamp_controll (
  id VARCHAR(36) NOT NULL,
  imei VARCHAR(255) NOT NULL,
  node_id VARCHAR(36),
  set_status  VARCHAR(5),
  PRIMARY KEY(id),
  FOREIGN KEY(node_id) REFERENCES node(id) ON DELETE CASCADE
);

DROP VIEW IF EXISTS datalog_view;
CREATE VIEW datalog_view AS 
    SELECT p.*, to_char(p.dtime, 'DD-MON-YY HH24:MI') as ddtime, to_char(p.dtime, 'Month DD, YYYY HH24:MI:SS') as jsdate
    FROM datalog p;