--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'SQL_ASCII';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

-- CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

-- COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: customers; Type: TABLE; Schema: public; Owner: sinergi; Tablespace: 
--

CREATE TABLE customers (
    id integer NOT NULL,
    name character varying(100) NOT NULL
);


ALTER TABLE customers OWNER TO sinergi;

--
-- Name: customers_id_seq; Type: SEQUENCE; Schema: public; Owner: sinergi
--

CREATE SEQUENCE customers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE customers_id_seq OWNER TO sinergi;

--
-- Name: customers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sinergi
--

ALTER SEQUENCE customers_id_seq OWNED BY customers.id;


--
-- Name: datalog; Type: TABLE; Schema: public; Owner: sinergi; Tablespace: 
--

CREATE TABLE datalog (
    id bigint NOT NULL,
    site_id integer,
    dtime timestamp without time zone,
    pvoltage double precision,
    vbatt double precision,
    ibatt double precision,
    iload double precision,
    temperature_ctrl double precision,
    temperature_batt double precision,
    status smallint DEFAULT 0 NOT NULL
);


ALTER TABLE datalog OWNER TO sinergi;

--
-- Name: datalog_id_seq; Type: SEQUENCE; Schema: public; Owner: sinergi
--

CREATE SEQUENCE datalog_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE datalog_id_seq OWNER TO sinergi;

--
-- Name: datalog_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sinergi
--

ALTER SEQUENCE datalog_id_seq OWNED BY datalog.id;


--
-- Name: debug; Type: TABLE; Schema: public; Owner: sinergi; Tablespace: 
--

CREATE TABLE debug (
    id integer NOT NULL,
    dtime timestamp without time zone,
    url character varying(100),
    msg text
);


ALTER TABLE debug OWNER TO sinergi;

--
-- Name: debug_id_seq; Type: SEQUENCE; Schema: public; Owner: sinergi
--

CREATE SEQUENCE debug_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE debug_id_seq OWNER TO sinergi;

--
-- Name: debug_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sinergi
--

ALTER SEQUENCE debug_id_seq OWNED BY debug.id;


--
-- Name: lamp_controll; Type: TABLE; Schema: public; Owner: sinergi; Tablespace: 
--

CREATE TABLE lamp_controll (
    id integer NOT NULL,
    imei character varying(255) NOT NULL,
    site_id integer NOT NULL,
    set_status character varying(5)
);


ALTER TABLE lamp_controll OWNER TO sinergi;

--
-- Name: lamp_controll_id_seq; Type: SEQUENCE; Schema: public; Owner: sinergi
--

CREATE SEQUENCE lamp_controll_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE lamp_controll_id_seq OWNER TO sinergi;

--
-- Name: lamp_controll_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sinergi
--

ALTER SEQUENCE lamp_controll_id_seq OWNED BY lamp_controll.id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: sinergi; Tablespace: 
--

CREATE TABLE roles (
    id smallint NOT NULL,
    name character varying(20) NOT NULL
);


ALTER TABLE roles OWNER TO sinergi;

--
-- Name: setting; Type: TABLE; Schema: public; Owner: sinergi; Tablespace: 
--

CREATE TABLE setting (
    id integer NOT NULL,
    xpos numeric,
    ypos numeric,
    zoom smallint DEFAULT 12,
    nms_version character varying(5)
);


ALTER TABLE setting OWNER TO sinergi;

--
-- Name: setting_id_seq; Type: SEQUENCE; Schema: public; Owner: sinergi
--

CREATE SEQUENCE setting_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE setting_id_seq OWNER TO sinergi;

--
-- Name: setting_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sinergi
--

ALTER SEQUENCE setting_id_seq OWNED BY setting.id;


--
-- Name: site; Type: TABLE; Schema: public; Owner: sinergi; Tablespace: 
--

CREATE TABLE site (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    address character varying(255),
    km character varying(5),
    pole smallint DEFAULT 1 NOT NULL,
    latitude double precision NOT NULL,
    longitude double precision NOT NULL,
    imei character varying(30),
    chanel smallint,
    subnet_id integer,
    consent_id integer,
    pvoltage double precision,
    vbatt double precision,
    ibatt double precision,
    iload double precision,
    temperature_ctrl double precision,
    temperature_batt double precision,
    status smallint DEFAULT 0 NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


ALTER TABLE site OWNER TO sinergi;

--
-- Name: site_id_seq; Type: SEQUENCE; Schema: public; Owner: sinergi
--

CREATE SEQUENCE site_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE site_id_seq OWNER TO sinergi;

--
-- Name: site_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sinergi
--

ALTER SEQUENCE site_id_seq OWNED BY site.id;


--
-- Name: subnet; Type: TABLE; Schema: public; Owner: sinergi; Tablespace: 
--

CREATE TABLE subnet (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    customers_id integer,
    parent_id integer
);


ALTER TABLE subnet OWNER TO sinergi;

--
-- Name: subnet_id_seq; Type: SEQUENCE; Schema: public; Owner: sinergi
--

CREATE SEQUENCE subnet_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE subnet_id_seq OWNER TO sinergi;

--
-- Name: subnet_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sinergi
--

ALTER SEQUENCE subnet_id_seq OWNED BY subnet.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: sinergi; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255),
    password character varying(255) NOT NULL,
    roles_id smallint,
    customers_id integer,
    created_at timestamp without time zone,
    upadated_at timestamp without time zone
);


ALTER TABLE users OWNER TO sinergi;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: sinergi
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE users_id_seq OWNER TO sinergi;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sinergi
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY customers ALTER COLUMN id SET DEFAULT nextval('customers_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY datalog ALTER COLUMN id SET DEFAULT nextval('datalog_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY debug ALTER COLUMN id SET DEFAULT nextval('debug_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY lamp_controll ALTER COLUMN id SET DEFAULT nextval('lamp_controll_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY setting ALTER COLUMN id SET DEFAULT nextval('setting_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY site ALTER COLUMN id SET DEFAULT nextval('site_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY subnet ALTER COLUMN id SET DEFAULT nextval('subnet_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Data for Name: customers; Type: TABLE DATA; Schema: public; Owner: sinergi
--

INSERT INTO customers VALUES (1, 'PT. ABC');
INSERT INTO customers VALUES (2, 'PT. XYZ');


--
-- Name: customers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sinergi
--

SELECT pg_catalog.setval('customers_id_seq', 2, true);


--
-- Data for Name: datalog; Type: TABLE DATA; Schema: public; Owner: sinergi
--



--
-- Name: datalog_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sinergi
--

SELECT pg_catalog.setval('datalog_id_seq', 1, false);


--
-- Data for Name: debug; Type: TABLE DATA; Schema: public; Owner: sinergi
--



--
-- Name: debug_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sinergi
--

SELECT pg_catalog.setval('debug_id_seq', 1, false);


--
-- Data for Name: lamp_controll; Type: TABLE DATA; Schema: public; Owner: sinergi
--



--
-- Name: lamp_controll_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sinergi
--

SELECT pg_catalog.setval('lamp_controll_id_seq', 1, false);


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: sinergi
--

INSERT INTO roles VALUES (1, 'ADMIN');
INSERT INTO roles VALUES (2, 'USER');


--
-- Data for Name: setting; Type: TABLE DATA; Schema: public; Owner: sinergi
--

INSERT INTO setting VALUES (1, -6.2115, 106.8452, 12, '1.0');


--
-- Name: setting_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sinergi
--

SELECT pg_catalog.setval('setting_id_seq', 1, true);


--
-- Data for Name: site; Type: TABLE DATA; Schema: public; Owner: sinergi
--

INSERT INTO site VALUES (1, 'MSTR1', 'Jl. xxx', NULL, 1, -6.92177600000000037, 107.61121, '1020304050', 10, 2, NULL, 5, 4, 2, 2, 24.2600000000000016, 25.2600000000000016, 0, '2016-08-07 11:37:38.662816', NULL);
INSERT INTO site VALUES (2, 'MSTR2', 'Jl. xx', '1', 2, -6.92177600000000037, 107.611249999999998, NULL, 1, 2, 1, 6, 5, 4, 2, 30.25, 30.2600000000000016, 1, '2016-08-07 11:39:44.60602', NULL);
INSERT INTO site VALUES (3, 'MSTR3', 'Jl. xx', '2', 3, -6.92177600000000037, 107.611289999999997, NULL, 1, 2, 1, 6, 5, 5, 4, 25.5399999999999991, 26.5799999999999983, 1, '2016-08-07 11:41:43.767836', NULL);


--
-- Name: site_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sinergi
--

SELECT pg_catalog.setval('site_id_seq', 3, true);


--
-- Data for Name: subnet; Type: TABLE DATA; Schema: public; Owner: sinergi
--

INSERT INTO subnet VALUES (1, 'JAWA BARAT', 1, NULL);
INSERT INTO subnet VALUES (2, 'BANDUNG', 1, 1);


--
-- Name: subnet_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sinergi
--

SELECT pg_catalog.setval('subnet_id_seq', 2, true);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: sinergi
--

INSERT INTO users VALUES (1, 'admin', 'sinergi', NULL, '21232f297a57a5a743894a0e4a801fc3', 1, NULL, NULL, NULL);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sinergi
--

SELECT pg_catalog.setval('users_id_seq', 1, true);


--
-- Name: customers_name_key; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY customers
    ADD CONSTRAINT customers_name_key UNIQUE (name);


--
-- Name: customers_pkey; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY customers
    ADD CONSTRAINT customers_pkey PRIMARY KEY (id);


--
-- Name: datalog_pkey; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY datalog
    ADD CONSTRAINT datalog_pkey PRIMARY KEY (id);


--
-- Name: debug_pkey; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY debug
    ADD CONSTRAINT debug_pkey PRIMARY KEY (id);


--
-- Name: lamp_controll_pkey; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY lamp_controll
    ADD CONSTRAINT lamp_controll_pkey PRIMARY KEY (id);


--
-- Name: roles_name_key; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_name_key UNIQUE (name);


--
-- Name: roles_pkey; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: setting_pkey; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY setting
    ADD CONSTRAINT setting_pkey PRIMARY KEY (id);


--
-- Name: site_latitude_longitude_key; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY site
    ADD CONSTRAINT site_latitude_longitude_key UNIQUE (latitude, longitude);


--
-- Name: site_pkey; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY site
    ADD CONSTRAINT site_pkey PRIMARY KEY (id);


--
-- Name: subnet_pkey; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY subnet
    ADD CONSTRAINT subnet_pkey PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users_username_key; Type: CONSTRAINT; Schema: public; Owner: sinergi; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- Name: imei_idx; Type: INDEX; Schema: public; Owner: sinergi; Tablespace: 
--

CREATE INDEX imei_idx ON site USING btree (imei);


--
-- Name: site_name_idx; Type: INDEX; Schema: public; Owner: sinergi; Tablespace: 
--

CREATE INDEX site_name_idx ON site USING btree (name);


--
-- Name: datalog_site_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY datalog
    ADD CONSTRAINT datalog_site_id_fkey FOREIGN KEY (site_id) REFERENCES site(id) ON DELETE CASCADE;


--
-- Name: lamp_controll_site_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY lamp_controll
    ADD CONSTRAINT lamp_controll_site_id_fkey FOREIGN KEY (site_id) REFERENCES site(id) ON DELETE CASCADE;


--
-- Name: site_consent_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY site
    ADD CONSTRAINT site_consent_id_fkey FOREIGN KEY (consent_id) REFERENCES site(id) ON DELETE SET NULL;


--
-- Name: site_subnet_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY site
    ADD CONSTRAINT site_subnet_id_fkey FOREIGN KEY (subnet_id) REFERENCES subnet(id) ON DELETE CASCADE;


--
-- Name: subnet_customers_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY subnet
    ADD CONSTRAINT subnet_customers_id_fkey FOREIGN KEY (customers_id) REFERENCES customers(id) ON DELETE CASCADE;


--
-- Name: subnet_parent_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY subnet
    ADD CONSTRAINT subnet_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES subnet(id) ON DELETE CASCADE;


--
-- Name: users_customers_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_customers_id_fkey FOREIGN KEY (customers_id) REFERENCES roles(id) ON DELETE CASCADE;


--
-- Name: users_roles_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: sinergi
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_roles_id_fkey FOREIGN KEY (roles_id) REFERENCES roles(id) ON DELETE SET NULL;
