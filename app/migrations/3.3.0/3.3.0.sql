-- alterações do censo no registro 20

CREATE TABLE edcenso_aggregated_stage (
	id INT auto_increment NOT NULL,
	name varchar(255) NULL,
	CONSTRAINT edcenso_aggregated_stage_PK PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (301,'Educação Infantil');
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (302,'Ensino Fundamental ');
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (303,'Multi e correção de fluxo');
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (304,'Ensino Médio');
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (305,'Ensino Médio - Normal/ Magistério');
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (306,'Educação de Jovens e Adultos');
INSERT INTO edcenso_aggregated_stage (id,name)
	VALUES (308,'Curso Técnico e FIC - Concomitante ou Subsequente');


ALTER TABLE edcenso_stage_vs_modality ADD aggregated_stage_id INT NULL;

UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=301
	WHERE id IN (1,2,3);

UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=302
	WHERE id IN (14,15,16,17,18,19,20,21,41);

UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=303
	WHERE id IN (22,23,56);


UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=304
	WHERE id IN (25,26,27,28,29);

UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=305
	WHERE id IN (35,36,37,38);

UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=306
	WHERE id IN (69,70,72,71,74,73,67);


UPDATE edcenso_stage_vs_modality
	SET aggregated_stage=308
	WHERE id IN (39,40,64,68);


ALTER TABLE classroom ADD is_special_education TINYINT DEFAULT 0 NOT NULL;


UPDATE edcenso_alias
	SET attr='is_special_education'
	WHERE register=20
		AND corder=24
	    AND `year`=2025;


-- alterações do censo no registro 30

ALTER TABLE student_identification
ADD COLUMN id_indigenous_people VARCHAR(8) NULL;

ALTER TABLE instructor_identification
ADD COLUMN id_indigenous_people VARCHAR(8) NULL;

UPDATE edcenso_alias
SET attr = 'id_indigenous_people'
WHERE corder = 13 and year = 2025 and cdesc = "Povo indígena";

CREATE TABLE edcenso_indigenous_people (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_indigenous_people VARCHAR(10) NOT NULL,
    name VARCHAR(50) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (254,'Tumbalalá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (255,'Tunayana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (256,'Tupaiu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (257,'Tuparí')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (258,'Tupinambá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (259,'Tupinambaraná')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (260,'Tupiniquim')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (261,'Turiwára')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (262,'Tuxá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (263,'Tuyúca')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (264,'Umutina')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (265,'Urucú')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (266,'Uru-Eu-Wau-Wau')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (267,'Wai Wai')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (268,'Waiãpy')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (269,'Waikisu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (270,'Waimiri Atroari')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (271,'Wakalitesu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (272,'Wanana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (273,'Wapixana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (274,'Warekena')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (275,'Wassú')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (276,'Wasusu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (277,'Wauja')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (278,'Wayana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (279,'Witóto')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (280,'Xacriabá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (281,'Xambioá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (282,'Xavante')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (283,'Xerente')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (284,'Xereu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (285,'Xetá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (286,'Xipáya')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (287,'Xocó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (288,'Xokléng')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (289,'Xucuru')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (290,'Xucuru - Kariri')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (291,'Yaipiyana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (292,'Yamináwa')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (293,'Yanomámi')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (294,'Yanomán')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (295,'Yawalapití')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (296,'Yawanawá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (297,'Yekuana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (298,'Yudjá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (299,'Yurutí')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (300,'Zoé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (301,'Zoró')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (302,'Zuruahã')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (998,'Outro povo/etnia indígena')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (999,'Não Declarada')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (1,'Aconã')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (2,'Aikaná')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (3,'Aimore')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (4,'Ajuru')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (5,'Akuntsú')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (6,'Alaketesu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (7,'Alantesu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (8,'Amanayé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (9,'Amondáwa')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (10,'Anacé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (11,'Anambé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (12,'Apalaí')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (13,'Apiaká')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (14,'Apinayé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (15,'Apolima  Arara')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (16,'Apurinã')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (17,'Aranã')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (18,'Arapáso')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (19,'Arapiun')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (20,'Arara de Rondônia')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (21,'Arara do Acre')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (22,'Arara do Aripuanã')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (23,'Arara do Pará')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (24,'Araweté')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (25,'Arikapú')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (26,'Arikén')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (27,'Arikosé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (28,'Aruá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (29,'Ashaninka')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (30,'Asurini do Tocantins')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (31,'Asurini do Xingu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (32,'Atikum')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (33,'Ava-Canoeiro')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (34,'Aweti')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (35,'Baenã')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (36,'Bakairí')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (37,'Banawa')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (38,'Baniwa')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (39,'Bará')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (40,'Barasána')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (41,'Baré')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (42,'Bóra')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (43,'Borari')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (44,'Bororo')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (45,'Botocudo')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (46,'Catokin')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (47,'Chamakóko')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (48,'Charrua')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (49,'Chiquitáno')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (50,'Cinta Larga')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (51,'Dâw')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (52,'Dení')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (53,'Desána')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (54,'Diahói')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (55,'Djeoromitxí - Jabutí')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (56,'Enawenê-Nawê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (57,'Fulni-ô')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (58,'Galibi do Oiapoque')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (59,'Galibí Marwórno')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (60,'Gavião de Rondônia')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (61,'Gavião Krikatejê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (62,'Gavião Parkatejê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (63,'Gavião Pukobiê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (64,'Guaikurú')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (65,'Guajá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (66,'Guaraní')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (67,'Guarani Kaiowá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (68,'Guarani Mbya')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (69,'Guarani Nhandeva')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (70,'Guató')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (71,'Hahaintesu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (72,'Halotesu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (73,'Hixkaryána')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (74,'Hupda')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (75,'Ikpeng')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (76,'Ingarikó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (77,'Irántxe')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (78,'Issé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (79,'Jamamadí')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (80,'Jarawára')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (81,'Jaricuna')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (82,'Javaé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (83,'Jeripancó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (84,'Juma')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (85,'Kaapor')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (86,'Kadiwéu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (87,'Kaeté')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (88,'Kahyana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (89,'Kaiabi')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (90,'Kaimbé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (91,'Kaingang')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (92,'Kaixana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (93,'Kalabaça')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (94,'Kalankó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (95,'Kalapalo')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (96,'Kamakã')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (97,'Kamayurá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (98,'Kamba')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (99,'Kambéba')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (100,'Kambiwá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (101,'Kambiwá-Pipipã')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (102,'Kampé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (103,'Kanamanti')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (104,'Kanamarí')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (105,'Kanela')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (106,'Kanela Apaniekra')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (107,'Kanela Rankocamekra')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (108,'Kanindé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (109,'Kanoé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (110,'Kantaruré')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (111,'Kapinawá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (112,'Kapon Patamóna')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (113,'Karafawyana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (114,'Karajá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (115,'Karapanã')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (116,'Karapotó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (117,'Karijó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (118,'Karipuna')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (119,'Karipúna do Amapá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (120,'Kariri')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (121,'Kariri - Xocó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (122,'Karitiana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (123,'Katawixí')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (124,'Katuena')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (125,'Katukina')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (126,'Katukina do Acre')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (127,'Kawahíb')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (128,'Kaxarari')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (129,'Kaxinawá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (130,'Kaxixó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (131,'Kaxuyana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (132,'Kayapó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (133,'Kayuisiana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (134,'Kinikinau')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (135,'Kiriri')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (136,'Kisêdjê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (137,'Kithaulu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (138,'Koiupanká')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (139,'Kokama')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (140,'Kokuiregatejê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (141,'Kontanawá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (142,'Korúbo')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (143,'Krahô')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (144,'Krahô-Kanela')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (145,'Krenák')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (146,'Krenyê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (147,'Krikati')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (148,'Kubeo')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (149,'Kuikuro')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (150,'Kujubim')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (151,'Kulina Madijá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (152,'Kulina Páno')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (153,'Kuripako')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (154,'Kuruáya')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (155,'Kwazá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (156,'Laiana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (157,'Lakondê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (158,'Latundê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (159,'Makú')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (160,'Makúna')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (161,'Makuráp')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (162,'Makuxí')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (163,'Mamaindê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (164,'Manao')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (165,'Manchineri')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (166,'Manduka')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (167,'Maragua')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (168,'Marimã')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (169,'Marúbo')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (170,'Matipú')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (171,'Matís')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (172,'Matsés')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (173,'Mawayána')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (174,'Maxakali')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (175,'Maya')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (176,'Maytapu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (177,'Mehináku')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (178,'Migueléno')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (179,'Miránha')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (180,'Mirititapuia')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (181,'Mucurim')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (182,'Múra')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (183,'Mynky')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (184,'Nadëb')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (185,'Nahukuá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (186,'Nambikwára')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (187,'Naravute')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (188,'Nawa')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (189,'Negarotê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (190,'Ninám')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (191,'Nukiní')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (192,'Ofayé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (193,'Oro Win')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (194,'Paiaku')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (195,'Pakaa Nova')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (196,'Palikur')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (197,'Panará')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (198,'Pankará')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (199,'Pankararé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (200,'Pankararú')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (201,'Pankararú - Karuazu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (202,'Pankaru')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (203,'Papavó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (204,'Parakanã')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (205,'Paresí')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (206,'Parintintim')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (207,'Pataxó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (208,'Pataxo Há-Há-Há')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (209,'Paumarí')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (210,'Paumelenho')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (211,'Pirahã')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (212,'Piratapuya')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (213,'Piri-Piri')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (214,'Pitaguari')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (215,'Potiguara')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (216,'Poyanáwa')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (217,'Puri')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (218,'Puroborá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (219,'Rikbaktsa')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (220,'Sabanê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (221,'Sakurabiat')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (222,'Salamãy')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (223,'Sanumá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (224,'Sapará')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (225,'Sarare')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (226,'Sawentesu')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (227,'Shanenáwa')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (228,'Siriano')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (229,'Suruí de Rondônia')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (230,'Suruí do Pará')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (231,'Tabajara')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (232,'Tamoio')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (233,'Tapajós')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (234,'Tapayuna')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (235,'Tapirapé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (236,'Tapiuns')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (237,'Tapuia')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (238,'Tariana')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (239,'Taulipáng')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (240,'Tawandê')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (241,'Tembé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (242,'Tenetehara')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (243,'Tenharim')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (244,'Terena')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (245,'Tikúna')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (246,'Timbira')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (247,'Tingui-Botó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (248,'Tiriyó')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (249,'Torá')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (250,'Tremembé')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (251,'Truká')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (252,'Trumái')
INSERT INTO edcenso_indigenous_people (id_indigenous_people,name) VALUE (253,'Tukano')



ALTER TABLE student_disorder
  ADD COLUMN disorders_impact_learning TINYINT(1) DEFAULT 0,
  ADD COLUMN dyscalculia TINYINT(1) DEFAULT 0,
  ADD COLUMN dysgraphia TINYINT(1) DEFAULT 0,
  ADD COLUMN dyslalia TINYINT(1) DEFAULT 0,
  ADD COLUMN dyslexia TINYINT(1) DEFAULT 0,
  ADD COLUMN tpac TINYINT(1) DEFAULT 0;

ALTER TABLE student_identification
ADD COLUMN resource_additional_time TINYINT DEFAULT 0;

UPDATE edcenso_alias
SET attr = 'resource_braille_test'
WHERE register = 301 and year = 2025 and cdesc = "Prova em Braille";

UPDATE edcenso_alias
SET attr = 'resource_additional_time'
WHERE register = 301 and year = 2025 and cdesc = "Tempo adicional";

UPDATE edcenso_alias
SET attr = 'disorders_impact_learning'
WHERE register = 301 and year = 2025 and cdesc = "Pessoa física com transtorno(s) que impacta(m) o desenvolvimento da aprendizagem";


UPDATE edcenso_alias
SET attr = 'dyscalculia'
WHERE register = 301 and year = 2025 and cdesc = "Discalculia ou outro transtorno da matemática e raciocínio lógico";

UPDATE edcenso_alias
SET attr = 'dysgraphia'
WHERE register = 301 and year = 2025 and cdesc = "Disgrafia, Disortografia ou outro transtorno da escrita e ortografia";

UPDATE edcenso_alias
SET attr = 'dyslalia'
WHERE register = 301 and year = 2025 and cdesc = "Dislalia ou outro transtorno da linguagem e comunicação";

UPDATE edcenso_alias
SET attr = 'dyslexia'
WHERE register = 301 and year = 2025 and cdesc = "Dislexia";

UPDATE edcenso_alias
SET attr = 'tdah'
WHERE register = 301 and year = 2025 and cdesc = "Transtorno do Déficit de Atenção com Hiperatividade (TDAH)";

UPDATE edcenso_alias
SET attr = 'tpac'
WHERE register = 301 and year = 2025 and cdesc = "Transtorno do Processamento Auditivo Central (TPAC)";

