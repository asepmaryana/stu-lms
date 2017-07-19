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
  pvoltage         DOUBLE PRECISION,
  vbatt            DOUBLE PRECISION,
  ibatt            DOUBLE PRECISION,
  iload            DOUBLE PRECISION,
  temperature_ctrl DOUBLE PRECISION,
  temperature_batt DOUBLE PRECISION,
  status           SMALLINT NOT NULL DEFAULT 0,
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
CREATE TABLE datalog (
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
