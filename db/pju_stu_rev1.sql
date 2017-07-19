-- untuk menyimpan data level pengguna
CREATE TABLE roles (
  id          SMALLINT    NOT NULL,
  name        VARCHAR(20) NOT NULL,
  PRIMARY KEY(id),
  UNIQUE(name)
);

INSERT INTO roles(id, name) VALUES(1, 'ADMIN');
INSERT INTO roles(id, name) VALUES(2, 'USER');

-- untuk menyimpan data pelanggan
CREATE TABLE customers (
  id          SERIAL   	  NOT NULL,
  name        VARCHAR(100) NOT NULL,
  email       VARCHAR(50),
  phone       VARCHAR(20),
  PRIMARY KEY(id),
  UNIQUE(name)
);

INSERT INTO customers(name) VALUES('PT. ABC');
INSERT INTO customers(name) VALUES('PT. XYZ');

-- untuk menyimpan data pengguna
CREATE TABLE users (
  id          SERIAL       NOT NULL,
  username    VARCHAR(255) NOT NULL,
  name        VARCHAR(255) NOT NULL,
  email       VARCHAR(255),
  password    VARCHAR(255) NOT NULL,
  roles_id    SMALLINT,
  customers_id INT,
  created_at  TIMESTAMP,
  upadated_at TIMESTAMP,
  PRIMARY KEY(id),
  UNIQUE(username),
  FOREIGN KEY(roles_id) REFERENCES roles(id) ON DELETE SET NULL,
  FOREIGN KEY(customers_id) REFERENCES roles(id) ON DELETE CASCADE
);

INSERT INTO users (username, name, password, roles_id) VALUES ('admin', 'sinergi', md5('admin'), 1);

-- untuk menyimpan pengelompokan lampu / grup lampu
CREATE TABLE subnet (
  id          SERIAL   	  NOT NULL,
  name        VARCHAR(100) NOT NULL,
  customers_id INT,
  parent_id   INT,
  PRIMARY KEY(id),
  FOREIGN KEY(customers_id) REFERENCES customers(id) ON DELETE CASCADE,
  FOREIGN KEY(parent_id) REFERENCES subnet(id) ON DELETE CASCADE
);

-- untuk menyimpan data titik lampu yang akan ditampilkan di peta
CREATE TABLE site (
  id               SERIAL           NOT NULL,
  name             VARCHAR(100)     NOT NULL,
  address		   VARCHAR(255),
  km			   VARCHAR(5),
  pole		   	   SMALLINT NOT NULL DEFAULT 1,
  latitude         DOUBLE PRECISION NOT NULL,
  longitude        DOUBLE PRECISION NOT NULL,
  imei             VARCHAR(30),
  chanel           SMALLINT,
  subnet_id        INT,
  consent_id       INT,
  pvoltage         REAL,
  vbatt            REAL,
  ibatt            REAL,
  iload            REAL,
  temperature_ctrl REAL,
  temperature_batt REAL,
  status           SMALLINT NOT NULL DEFAULT 0,  
  protocol         VARCHAR(3),
  rfid_mstr        SMALLINT,
  pack_volt        REAL DEFAULT 0,
  cell_1_volt      REAL DEFAULT 0,
  cell_2_volt      REAL DEFAULT 0,
  cell_3_volt      REAL DEFAULT 0,
  cell_4_volt      REAL DEFAULT 0,
  cell_5_volt      REAL DEFAULT 0,
  cell_6_volt      REAL DEFAULT 0,
  cell_7_volt      REAL DEFAULT 0,
  cell_8_volt      REAL DEFAULT 0,
  bms_curr         REAL DEFAULT 0,
  soc              REAL DEFAULT 0,
  bms_status       SMALLINT DEFAULT 0,  
  created_at       TIMESTAMP,
  updated_at       TIMESTAMP,
  PRIMARY KEY(id),
  UNIQUE(latitude, longitude),
  FOREIGN KEY(subnet_id) REFERENCES subnet(id) ON DELETE CASCADE,
  FOREIGN KEY(consent_id) REFERENCES site(id) ON DELETE SET NULL
);

CREATE INDEX site_name_idx ON site(name);
CREATE INDEX imei_idx ON site(imei);

-- untuk menyimpan log data setiap lampu
CREATE TABLE datalog
(
  id               BIGSERIAL           NOT NULL,
  site_id          INT,
  dtime            TIMESTAMP,
  pvoltage         DOUBLE PRECISION,
  vbatt            DOUBLE PRECISION,
  ibatt            DOUBLE PRECISION,
  iload            DOUBLE PRECISION,
  temperature_ctrl DOUBLE PRECISION,
  temperature_batt DOUBLE PRECISION,
  status           SMALLINT NOT NULL DEFAULT 0,  
  pack_volt        REAL DEFAULT 0,
  cell_1_volt      REAL DEFAULT 0,
  cell_2_volt      REAL DEFAULT 0,
  cell_3_volt      REAL DEFAULT 0,
  cell_4_volt      REAL DEFAULT 0,
  cell_5_volt      REAL DEFAULT 0,
  cell_6_volt      REAL DEFAULT 0,
  cell_7_volt      REAL DEFAULT 0,
  cell_8_volt      REAL DEFAULT 0,
  bms_curr         REAL DEFAULT 0,
  soc              REAL DEFAULT 0,
  bms_status       SMALLINT DEFAULT 0,  
  PRIMARY KEY(id),
  FOREIGN KEY(site_id) REFERENCES site(id) ON DELETE CASCADE
);

CREATE TABLE lamp_controll (
  id SERIAL NOT NULL,
  imei VARCHAR(255) NOT NULL,
  site_id INT NOT NULL,
  set_status  VARCHAR(5),
  PRIMARY KEY(id),
  FOREIGN KEY(site_id) REFERENCES site(id) ON DELETE CASCADE
);

-- untuk menyimpan data-data konfigurasi
CREATE TABLE setting (
  id SERIAL NOT NULL,
  xpos NUMERIC,
  ypos NUMERIC,
  zoom SMALLINT DEFAULT 12,
  nms_version VARCHAR(5),
  PRIMARY KEY(id)
);

INSERT INTO setting(xpos, ypos, zoom, nms_version) VALUES(-6.2115, 106.8452, 12, '1.0');

-- untuk menyimpan data-data mentah hasil pengiriman / penerimaan melalui http dari program pak heryadi
CREATE TABLE debug (
  id SERIAL NOT NULL,
  dtime TIMESTAMP,
  url VARCHAR(100),
  msg TEXT,
  PRIMARY KEY(id)
);

CREATE TABLE severity
(
    id SERIAL NOT NULL,
    name VARCHAR(10),
    color VARCHAR(7),
    PRIMARY KEY(id),
    UNIQUE(name)
);

INSERT INTO severity (name, color) VALUES 
('CRITICAL', '#FF0000'),
('MAJOR', '#00FF00'),
('MINOR', '#FF0000'),
('WARNING', '#FF0000');

CREATE TABLE alarm_list
(
    id SERIAL NOT NULL,
    name VARCHAR(50) NOT NULL,
    severity_id INT,
    PRIMARY KEY(id),
    UNIQUE(name),
    FOREIGN KEY(severity_id) REFERENCES severity(id) ON DELETE SET NULL    
);

INSERT INTO alarm_list (name, severity_id) VALUES 
('Fail ON', 2),
('Fail OFF', 2),
('Low Batt', 1),
('Low Load', 3);

CREATE TABLE alarm_temp
(
    id BIGSERIAL NOT NULL,    
    site_id INT NOT NULL,
    dtime TIMESTAMP NOT NULL,
    alarm_list_id INT NOT NULL,
    alarm_label VARCHAR(50),
    severity_id INT,
    PRIMARY KEY(id),
    FOREIGN KEY(site_id) REFERENCES site(id) ON DELETE CASCADE,
    FOREIGN KEY(alarm_list_id) REFERENCES alarm_list(id) ON DELETE SET NULL,
    FOREIGN KEY(severity_id) REFERENCES severity(id) ON DELETE SET NULL
);

CREATE TABLE alarm_log
(
    id BIGINT NOT NULL,    
    site_id INT NOT NULL,
    dtime TIMESTAMP NOT NULL,
    dtime_end TIMESTAMP,
    alarm_list_id INT NOT NULL,
    alarm_label VARCHAR(50),
    severity_id INT,
    PRIMARY KEY(id),
    FOREIGN KEY(site_id) REFERENCES site(id) ON DELETE CASCADE,
    FOREIGN KEY(alarm_list_id) REFERENCES alarm_list(id) ON DELETE SET NULL,
    FOREIGN KEY(severity_id) REFERENCES severity(id) ON DELETE SET NULL
);

DROP VIEW IF EXISTS alarm_temp_view;
CREATE VIEW alarm_temp_view AS 
    SELECT alt.*,
        als.name as severity,        
        site.name as site,
        site.subnet_id,
        subnet.name as area,
		(SELECT id FROM subnet WHERE id = 
            (SELECT parent_id FROM subnet WHERE id = 
                (SELECT subnet_id FROM site WHERE id = alt.site_id)))
        as region_id,
        (SELECT name FROM subnet WHERE id = 
            (SELECT parent_id FROM subnet WHERE id = 
                (SELECT subnet_id FROM site WHERE id = alt.site_id)))
        as region,
        to_char(alt.dtime, 'DD-MON-YY HH24:MI') as ddtime         
    FROM alarm_temp alt 
    LEFT JOIN site ON alt.site_id = site.id 
    LEFT JOIN subnet ON site.subnet_id = subnet.id 
    LEFT JOIN alarm_list al ON alt.alarm_list_id = al.id 
    LEFT JOIN severity als ON alt.severity_id = als.id;


DROP VIEW IF EXISTS alarm_log_view;
CREATE VIEW alarm_log_view AS 
    SELECT alt.*,
        als.name as severity,        
        site.name as site,
        subnet.name as area,
		(SELECT id FROM subnet WHERE id = 
            (SELECT parent_id FROM subnet WHERE id = 
                (SELECT subnet_id FROM site WHERE id = alt.site_id)))
        as region_id,
        (SELECT name FROM subnet WHERE id = 
            (SELECT parent_id FROM subnet WHERE id = 
                (SELECT subnet_id FROM site WHERE id = alt.site_id)))
        as region,
        to_char(alt.dtime, 'DD-MON-YY HH24:MI') as ddtime,
        to_char(alt.dtime_end, 'DD-MON-YY HH24:MI') as ddtime_end 
    FROM alarm_log alt 
    LEFT JOIN site ON alt.site_id = site.id 
    LEFT JOIN subnet ON site.subnet_id = subnet.id 
    LEFT JOIN alarm_list al ON alt.alarm_list_id = al.id 
    LEFT JOIN severity als ON alt.severity_id = als.id;
    
DROP VIEW IF EXISTS region_view;
CREATE VIEW region_view AS 
    SELECT * FROM subnet WHERE parent_id IS NULL ;
   
DROP VIEW IF EXISTS area_view;
CREATE VIEW area_view AS 
    SELECT area.*, region.name AS region 
    FROM subnet area 
    LEFT JOIN subnet region ON area.parent_id = region.id 
    WHERE area.parent_id IN (SELECT id FROM subnet WHERE parent_id IS NULL) ; 

DROP VIEW IF EXISTS site_view;
CREATE VIEW site_view AS 
    SELECT site.*, 
        area.id AS area_id, 
        area.name AS area, 
        region.id AS region_id, 
        region.name AS region
    FROM site  
    LEFT JOIN subnet area ON site.subnet_id = area.id
    LEFT JOIN subnet region ON area.parent_id = region.id 
    WHERE site.subnet_id IN (SELECT id FROM subnet WHERE parent_id IN (SELECT id FROM subnet WHERE parent_id IS NULL)) ;
    
DROP VIEW IF EXISTS datalog_view;
CREATE VIEW datalog_view AS 
    SELECT p.*, to_char(p.dtime, 'DD-MON-YY HH24:MI') as ddtime, to_char(p.dtime, 'Month DD, YYYY HH24:MI:SS') as jsdate
    FROM datalog p;