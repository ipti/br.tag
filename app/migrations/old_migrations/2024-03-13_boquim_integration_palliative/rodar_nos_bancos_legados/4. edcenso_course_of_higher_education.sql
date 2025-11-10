-- FAZER ADEQUAÇÃO DO EDCENSO_COURSE_OF_HIGHER_EDUCATION PRA INSERIR O CINE_ID E EVITAR EXCEPTION NA TELA DO EDUCACENSO


SET FOREIGN_KEY_CHECKS=0;

delete from edcenso_course_of_higher_education;

ALTER TABLE edcenso_course_of_higher_education ADD cine_id varchar(10) NULL;

INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (1,'Educação','142A01','Processos Escolares - Tecnológico','Tecnológico','0111P013'),
	 (1,'Educação','142C01','Pedagogia (Ciências da Educação) - Bacharelado','Bacharelado','0113P012'),
	 (1,'Educação','142P01','Pedagogia - Licenciatura','Licenciatura','0113P011'),
	 (1,'Educação','144F12','Licenciatura Interdisciplinar em Ciências Humanas - Licenciatura','Licenciatura','0188P011'),
	 (1,'Educação','144F13','Licenciatura Intercultural Indígena - Licenciatura ','Licenciatura','0113E031'),
	 (1,'Educação','145F01','Ciências Biológicas - Licenciatura','Licenciatura','0114B011'),
	 (1,'Educação','145F02','Ciências Naturais - Licenciatura','Licenciatura','0114C021'),
	 (1,'Educação','145F05','Educação Religiosa - Licenciatura','Licenciatura','0114E071'),
	 (1,'Educação','145F08','Filosofia - Licenciatura','Licenciatura','0114F011'),
	 (1,'Educação','145F09','Física - Licenciatura','Licenciatura','0114F021');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (1,'Educação','145F10','Geografia - Licenciatura','Licenciatura','0114G011'),
	 (1,'Educação','145F11','História - Licenciatura','Licenciatura','0114H011'),
	 (1,'Educação','145F14','Letras - Língua Estrangeira (Espanhol) - Licenciatura','Licenciatura','0115L02'),
	 (1,'Educação','145F15','Letras - Língua Portuguesa - Licenciatura','Licenciatura','0115L131'),
	 (1,'Educação','145F17','Letras - Língua Portuguesa e Estrangeira (Inglês) - Licenciatura','Licenciatura','0115L15'),
	 (1,'Educação','145F18','Matemática - Licenciatura','Licenciatura','0114M011'),
	 (1,'Educação','145F21','Química - Licenciatura','Licenciatura','0114Q011'),
	 (1,'Educação','145F24','Ciências Sociais - Licenciatura','Licenciatura','0114C031'),
	 (1,'Educação','145F28','Libras - Licenciatura','Licenciatura','0115L071'),
	 (1,'Educação','146F02','Licenciatura Interdisciplinar em Artes (Educação Artística) - Licenciatura','Licenciatura','0114A011');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (1,'Educação','146F04','Artes Visuais - Licenciatura','Licenciatura','0114A021'),
	 (1,'Educação','146F05','Informática - Licenciatura','Licenciatura','0114C051'),
	 (1,'Educação','146F07','Dança - Licenciatura','Licenciatura','0114D011'),
	 (1,'Educação','146F09','Licenciatura Interdisciplinar em Educação no Campo - Licenciatura','Licenciatura','0114E021'),
	 (1,'Educação','146F15','Educação Física - Licenciatura','Licenciatura','0114E031'),
	 (1,'Educação','146F20','Música - Licenciatura','Licenciatura','0114M021'),
	 (1,'Educação','146F22','Teatro - Licenciatura','Licenciatura','0114T011'),
	 (1,'Educação','146P01','Licenciatura para a Educação Profissional e Tecnológica - Licenciatura','Licenciatura','0114E061'),
	 (2,'Humanidades e Artes','210A01','Bacharelado Interdisciplinar em Artes - Bacharelado','Bacharelado','0213A012'),
	 (2,'Humanidades e Artes','211A02','Artes Visuais - Bacharelado','Bacharelado','0213A032');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (2,'Humanidades e Artes','212C02','Produção cênica - Tecnológico','Tecnológico','0215A013'),
	 (2,'Humanidades e Artes','212D01','Dança – Bacharelado','Bacharelado','0215D012'),
	 (2,'Humanidades e Artes','212M02','Música – Bacharelado','Bacharelado','0215M012'),
	 (2,'Humanidades e Artes','212T01','Teatro - Bacharelado','Bacharelado','0215T012'),
	 (2,'Humanidades e Artes','213A05','Produção audiovisual - Tecnológico','Tecnológico','0211P013'),
	 (2,'Humanidades e Artes','213C06','Design gráfico - Tecnológico','Tecnológico','0211D013'),
	 (2,'Humanidades e Artes','213C07','Carnaval - Tecnológico','Tecnológico','0211P033'),
	 (2,'Humanidades e Artes','213F01','Fotografia - Tecnológico','Tecnológico','0211F013'),
	 (2,'Humanidades e Artes','213P02','Produção multimídia - Tecnológico','Tecnológico','0211P053'),
	 (2,'Humanidades e Artes','213P03','Produção fonográfica - Tecnológico','Tecnológico','0211P043');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (2,'Humanidades e Artes','213P05','Produção publicitária - Tecnológico','Tecnológico','0414P013'),
	 (2,'Humanidades e Artes','213P07','Produção Cultural - Tecnológico','Tecnológico','0211P033'),
	 (2,'Humanidades e Artes','214D02','Design de moda - Tecnológico','Tecnológico','0212M013'),
	 (2,'Humanidades e Artes','214D05','Design - Bacharelado','Bacharelado','0212D022'),
	 (2,'Humanidades e Artes','214D06','Design de Interiores  - Tecnológico','Tecnológico','0212D033'),
	 (2,'Humanidades e Artes','214M01','Moda - Bacharelado','Bacharelado','0212M012'),
	 (2,'Humanidades e Artes','214P01','Design de produto - Tecnológico','Tecnológico','0212D043'),
	 (2,'Humanidades e Artes','215C02','Conservação e restauro - Tecnológico','Tecnológico','0222C013'),
	 (2,'Humanidades e Artes','215F01','Fabricação de Instrumentos Musicais - Tecnológico','Tecnológico','0214F013'),
	 (2,'Humanidades e Artes','220H01','Bacharelado Interdisciplinar Ciências Humanas - Bacharelado ','Bacharelado','0288P012');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (2,'Humanidades e Artes','220L03','Letras - Língua Portuguesa e Estrangeira - Bacharelado','Bacharelado','0231L22'),
	 (2,'Humanidades e Artes','221T01','Teologia - Bacharelado','Bacharelado','0221T012'),
	 (2,'Humanidades e Artes','222L01','Letras - Língua Estrangeira - Bacharelado','Bacharelado','0231L22'),
	 (2,'Humanidades e Artes','223C01','Comunicação assistiva - Tecnológico','Tecnológico','0211C023'),
	 (2,'Humanidades e Artes','223L01','Letras - Língua Portuguesa - Bacharelado','Bacharelado','0231L122'),
	 (2,'Humanidades e Artes','223L02','Libras - Bacharelado','Bacharelado','0231L082'),
	 (2,'Humanidades e Artes','225A01','Arqueologia - Bacharelado','Bacharelado','0222A012'),
	 (2,'Humanidades e Artes','225H01','História – Bacharelado','Bacharelado','0222H012'),
	 (2,'Humanidades e Artes','225M01','Museologia - Bacharelado','Bacharelado','0322M012'),
	 (2,'Humanidades e Artes','225M02','Museografia - Tecnológico','Tecnológico','0322M013');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (2,'Humanidades e Artes','226F01','Filosofia - Bacharelado','Bacharelado','0223F012'),
	 (3,'Ciências Sociais, Negócios e Direitos','310C02','Ciências Sociais - Bacharelado','Bacharelado','0312C022'),
	 (3,'Ciências Sociais, Negócios e Direitos','311P02','Psicologia - Bacharelado','Bacharelado','0313P012'),
	 (3,'Ciências Sociais, Negócios e Direitos','312A01','Antropologia - Bacharelado','Bacharelado','0312A012'),
	 (3,'Ciências Sociais, Negócios e Direitos','313C01','Ciência política - Bacharelado','Bacharelado','0312C012'),
	 (3,'Ciências Sociais, Negócios e Direitos','313R01','Relações Internacionais - Bacharelado','Bacharelado','0312R012'),
	 (3,'Ciências Sociais, Negócios e Direitos','314E02','Ciências Econômicas - Bacharelado','Bacharelado','0311E012'),
	 (3,'Ciências Sociais, Negócios e Direitos','321C01','Cinema e Audiovisual - Bacharelado','Bacharelado','0211C012'),
	 (3,'Ciências Sociais, Negócios e Direitos','321C02','Comunicação Social (Área Geral) - Bacharelado','Bacharelado','0321C012'),
	 (3,'Ciências Sociais, Negócios e Direitos','321J01','Jornalismo - Bacharelado','Bacharelado','0321J012');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (3,'Ciências Sociais, Negócios e Direitos','321R01','Radio, TV, Internet - Bacharelado','Bacharelado','0321J012'),
	 (3,'Ciências Sociais, Negócios e Direitos','322A01','Arquivologia - Bacharelado','Bacharelado','0322A012'),
	 (3,'Ciências Sociais, Negócios e Direitos','322B01','Biblioteconomia - Bacharelado','Bacharelado','0322B012'),
	 (3,'Ciências Sociais, Negócios e Direitos','340N02','Comércio exterior - Tecnológico','Tecnológico','0413C013'),
	 (3,'Ciências Sociais, Negócios e Direitos','341N01','Negócios imobiliários - Tecnológico','Tecnológico','0416N013'),
	 (3,'Ciências Sociais, Negócios e Direitos','342C01','Comunicação institucional - Tecnológico','Tecnológico','0414R013'),
	 (3,'Ciências Sociais, Negócios e Direitos','342M02','Marketing - Tecnológico','Tecnológico','0414M013'),
	 (3,'Ciências Sociais, Negócios e Direitos','342P02','Publicidade e Propaganda - Bacharelado','Bacharelado','0414P012'),
	 (3,'Ciências Sociais, Negócios e Direitos','342R01','Relações Públicas - Bacharelado','Bacharelado','0414R012'),
	 (3,'Ciências Sociais, Negócios e Direitos','343S01','Gestão de Seguros - Teconológico','Tecnológico','0412S013');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (3,'Ciências Sociais, Negócios e Direitos','344C02','Ciências Contábeis - Bacharelado','Bacharelado','0411C012'),
	 (3,'Ciências Sociais, Negócios e Direitos','345A01','Administração - Bacharelado','Bacharelado','0413A012'),
	 (3,'Ciências Sociais, Negócios e Direitos','345A02','Gestão de cooperativas  - Tecnológico','Tecnológico','0413G043'),
	 (3,'Ciências Sociais, Negócios e Direitos','345A07','Gestão hospitalar  - Tecnológico','Tecnológico','0413G113'),
	 (3,'Ciências Sociais, Negócios e Direitos','345A10','Gestão pública  - Tecnológico','Tecnológico','0413G123'),
	 (3,'Ciências Sociais, Negócios e Direitos','345C01','Processos gerenciais  - Tecnológico','Tecnológico','0413G073'),
	 (3,'Ciências Sociais, Negócios e Direitos','345G09','Gestão de recursos humanos  - Tecnológico','Tecnológico','0413G073'),
	 (3,'Ciências Sociais, Negócios e Direitos','345G10','Gestão da qualidade  - Tecnológico','Tecnológico','0413G023'),
	 (3,'Ciências Sociais, Negócios e Direitos','345G13','Logística  - Tecnológico','Tecnológico','0413L013'),
	 (3,'Ciências Sociais, Negócios e Direitos','345G16','Gestão comercial  - Tecnológico','Tecnológico','0416G013');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (3,'Ciências Sociais, Negócios e Direitos','345G17','Gestão financeira  - Tecnológico','Tecnológico','0412G013'),
	 (3,'Ciências Sociais, Negócios e Direitos','345G26','Gestão de segurança privada - Tecnológico','Tecnológico','1032S033'),
	 (3,'Ciências Sociais, Negócios e Direitos','346S01','Secretariado  - Tecnológico','Tecnológico','0415S013'),
	 (3,'Ciências Sociais, Negócios e Direitos','346S03','Secretariado Executivo - Bacharelado','Bacharelado','0415S012'),
	 (3,'Ciências Sociais, Negócios e Direitos','380D01','Direito - Bacharelado','Bacharelado','0421D012'),
	 (4,'Ciências, Matemática e Computação','421B07','Biomedicina - Bacharelado','Bacharelado','0914B012'),
	 (4,'Ciências, Matemática e Computação','421B12','Biotecnologia - Tecnológico','Tecnológico','0512B023'),
	 (4,'Ciências, Matemática e Computação','421C01','Ciências Biológicas - Bacharelado','Bacharelado','0511B012'),
	 (4,'Ciências, Matemática e Computação','422S01','Saneamento ambiental - Tecnológico','Tecnológico','0712S013'),
	 (4,'Ciências, Matemática e Computação','440C01','Bacharelado Interdisciplinar em Ciência e Tecnologia  - Bacharelado','Bacharelado','0588P012');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (4,'Ciências, Matemática e Computação','441F01','Física – Bacharelado','Bacharelado','0533F012'),
	 (4,'Ciências, Matemática e Computação','441R01','Física Medica e Radioterapia - Bacharelado','Bacharelado','0533F032'),
	 (4,'Ciências, Matemática e Computação','442Q01','Química – Bacharelado','Bacharelado','0531Q012'),
	 (4,'Ciências, Matemática e Computação','443C01','Ciência da Terra - Licenciatura','Licenciatura',NULL),
	 (4,'Ciências, Matemática e Computação','443G03','Geofísica - Bacharelado','Bacharelado','0532G012'),
	 (4,'Ciências, Matemática e Computação','443G05','Geografia - Bacharelado','Bacharelado','0312G012'),
	 (4,'Ciências, Matemática e Computação','443G06','Geologia - Bacharelado','Bacharelado','0532G022'),
	 (4,'Ciências, Matemática e Computação','443M01','Meteorologia - Bacharelado','Bacharelado','0532M012'),
	 (4,'Ciências, Matemática e Computação','443O01','Oceanografia - Bacharelado','Bacharelado','0532O012'),
	 (4,'Ciências, Matemática e Computação','461M01','Matemática – Bacharelado','Bacharelado','0541M012');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (4,'Ciências, Matemática e Computação','462C01','Ciências Atuariais - Bacharelado','Bacharelado','0542C012'),
	 (4,'Ciências, Matemática e Computação','462E01','Estatística - Bacharelado','Bacharelado','0542E012'),
	 (4,'Ciências, Matemática e Computação','481A01','Redes de computadores - Tecnológico','Tecnológico','0612R013'),
	 (4,'Ciências, Matemática e Computação','481B01','Banco de dados - Tecnológico','Tecnológico','0612B013'),
	 (4,'Ciências, Matemática e Computação','481C01','Ciência da Computação - Bacharelado','Bacharelado','0614C012'),
	 (4,'Ciências, Matemática e Computação','481T01','Gestão da tecnologia da informação - Tecnológico','Tecnológico','0612G013'),
	 (4,'Ciências, Matemática e Computação','481T02','Jogos Digitais - Tecnológico','Tecnológico','0613J013'),
	 (4,'Ciências, Matemática e Computação','482U01','Sistemas para internet - Tecnológico','Tecnológico','0615S033'),
	 (4,'Ciências, Matemática e Computação','483S01','Análise e desenvolvimento de sistemas / Segurança da informação - Tecnológico','Tecnológico','0615S013'),
	 (4,'Ciências, Matemática e Computação','483S02','Sistemas de Informação - Bacharelado','Bacharelado','0615S022');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (5,'Engenharia, Produção e Construção','520A01','Automação industrial - Tecnológico','Tecnológico','0714A013'),
	 (5,'Engenharia, Produção e Construção','520E01','Engenharia - Bacharelado','Bacharelado','0710E012'),
	 (5,'Engenharia, Produção e Construção','520E04','Engenharia de Materiais - Bacharelado','Bacharelado','0722E012'),
	 (5,'Engenharia, Produção e Construção','520E05','Engenharia de Produção - Bacharelado','Bacharelado','0725E022'),
	 (5,'Engenharia, Produção e Construção','520E09','Engenharia Ambiental e Sanitária - Bacharelado','Bacharelado','0712E022'),
	 (5,'Engenharia, Produção e Construção','520G01','Geoprocessamento - Tecnológico','Tecnológico','0532G033'),
	 (5,'Engenharia, Produção e Construção','520M01','Manutenção industrial - Tecnológico','Tecnológico','0715M013'),
	 (5,'Engenharia, Produção e Construção','520P02','Gestão da produção industrial - Tecnológico','Tecnológico','0725P023'),
	 (5,'Engenharia, Produção e Construção','520T01','Gestão de telecomunicações - Tecnológico','Tecnológico','0714G013'),
	 (5,'Engenharia, Produção e Construção','521E05','Engenharia Mecânica - Bacharelado','Bacharelado','0715E022');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (5,'Engenharia, Produção e Construção','521E06','Engenharia Metalúrgica - Bacharelado','Bacharelado','0715E032'),
	 (5,'Engenharia, Produção e Construção','521M03','Mecânica de precisão - Tecnológico','Tecnológico','0715M023'),
	 (5,'Engenharia, Produção e Construção','521T02','Processos metalúrgicos - Tecnológico','Tecnológico','0715P013'),
	 (5,'Engenharia, Produção e Construção','521T03','Fabricação mecânica - Tecnológico','Tecnológico','0715M023'),
	 (5,'Engenharia, Produção e Construção','522D02','Sistemas elétricos - Tecnológico','Tecnológico','0713S013'),
	 (5,'Engenharia, Produção e Construção','522E06','Engenharia Elétrica - Bacharelado','Bacharelado','0713E052'),
	 (5,'Engenharia, Produção e Construção','522E08','Sistemas de energia - Tecnológico','Tecnológico','0713S013'),
	 (5,'Engenharia, Produção e Construção','522R01','Refrigeração/Aquecimento - Tecnológico','Tecnológico','0713R013'),
	 (5,'Engenharia, Produção e Construção','522T02','Eletrotécnica industrial - Tecnológico','Tecnológico','0713E013'),
	 (5,'Engenharia, Produção e Construção','523B01','Engenharia Biomédica - Bacharelado','Bacharelado','0714E032');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (5,'Engenharia, Produção e Construção','523E04','Engenharia de Computação - Bacharelado','Bacharelado','0714E042'),
	 (5,'Engenharia, Produção e Construção','523E09','Engenharia Eletrônica - Bacharelado','Bacharelado','0714E082'),
	 (5,'Engenharia, Produção e Construção','523E10','Engenharia mecatrônica - Bacharelado','Bacharelado','0714E092'),
	 (5,'Engenharia, Produção e Construção','523E11','Engenharia de Controle e Automação - Bacharelado','Bacharelado','0714E052'),
	 (5,'Engenharia, Produção e Construção','523E12','Engenharia de Telecomunicações - Bacharelado','Bacharelado','0714E072'),
	 (5,'Engenharia, Produção e Construção','523M01','Sistemas biomédicos - Tecnológico','Tecnológico','0714S013'),
	 (5,'Engenharia, Produção e Construção','523S03','Sistemas eletrônicos - Tecnológico','Tecnológico','0714E013'),
	 (5,'Engenharia, Produção e Construção','523T01','Redes de telecomunicações / Sistemas de telecomunicações - Tecnológico','Tecnológico','0714S023'),
	 (5,'Engenharia, Produção e Construção','523T04','Mecatrônica industrial - Tecnológico','Tecnológico','0714M013'),
	 (5,'Engenharia, Produção e Construção','523T05','Telemática - Tecnológico','Tecnológico','0714T013');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (5,'Engenharia, Produção e Construção','523T06','Eletrônica industrial - Tecnológico','Tecnológico','0714E013'),
	 (5,'Engenharia, Produção e Construção','524E01','Engenharia de Bioprocessos - Bacharelado','Bacharelado','0711E012'),
	 (5,'Engenharia, Produção e Construção','524E06','Engenharia nuclear - Bacharelado','Bacharelado','0713E062'),
	 (5,'Engenharia, Produção e Construção','524E07','Engenharia Química - Bacharelado','Bacharelado','0711E052'),
	 (5,'Engenharia, Produção e Construção','524T03','Processos químicos - Tecnológico','Tecnológico','0531Q023'),
	 (5,'Engenharia, Produção e Construção','524T04','Biocombustíveis - Tecnológico','Tecnológico','0711B013'),
	 (5,'Engenharia, Produção e Construção','525A01','Mecanização Agrícola - Tecnológico','Tecnológico','0715M023'),
	 (5,'Engenharia, Produção e Construção','525C04','Construção naval - Tecnológico','Tecnológico','0716C013'),
	 (5,'Engenharia, Produção e Construção','525E04','Engenharia Aeronáutica - Bacharelado','Bacharelado','0716E022'),
	 (5,'Engenharia, Produção e Construção','525E05','Engenharia automotiva - Bacharelado','Bacharelado','0716E032');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (5,'Engenharia, Produção e Construção','525E08','Engenharia Naval - Bacharelado','Bacharelado','0716E052'),
	 (5,'Engenharia, Produção e Construção','525M01','Manutenção de aeronaves - Tecnológico','Tecnológico','0716M013'),
	 (5,'Engenharia, Produção e Construção','525S01','Sistemas Automotivos - Tecnológico','Tecnológico','0716S013'),
	 (5,'Engenharia, Produção e Construção','540F02','Produção Joalheira/Design de jóias e gemas - Tecnológico','Tecnológico','0722P033'),
	 (5,'Engenharia, Produção e Construção','540F03','Produção Gráfica  - Tecnológico','Tecnológico','0725P013'),
	 (5,'Engenharia, Produção e Construção','541E01','Engenharia de Alimentos - Bacharelado','Bacharelado','0721E012'),
	 (5,'Engenharia, Produção e Construção','541I02','Laticínios  - Tecnológico','Tecnológico','0721L013'),
	 (5,'Engenharia, Produção e Construção','541P05','Processamento de Carnes  - Tecnológico','Tecnológico','0721P013'),
	 (5,'Engenharia, Produção e Construção','541P09','Viticultura e Enologia  - Tecnológico','Tecnológico','0811V013'),
	 (5,'Engenharia, Produção e Construção','541T01','Alimentos  - Tecnológico','Tecnológico','0721A013');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (5,'Engenharia, Produção e Construção','541T02','Produção Sucroalcooleira  - Tecnológico','Tecnológico','0721P033'),
	 (5,'Engenharia, Produção e Construção','541T03','Produção de Cachaça  - Tecnológico','Tecnológico','0721P023'),
	 (5,'Engenharia, Produção e Construção','542B01','Bioenergia - Tecnológico','Tecnológico','0713E023'),
	 (5,'Engenharia, Produção e Construção','542E03','Engenharia Têxtil - Bacharelado','Bacharelado','0723E012'),
	 (5,'Engenharia, Produção e Construção','542I01','Produção de vestuário  - Tecnológico','Tecnológico','0723P013'),
	 (5,'Engenharia, Produção e Construção','542I02','Produção têxtil  - Tecnológico','Tecnológico','0723P023'),
	 (5,'Engenharia, Produção e Construção','543C01','Cerâmica - Tecnológico','Tecnológico','0722C013'),
	 (5,'Engenharia, Produção e Construção','543F03','Produção moveleira  - Tecnológico','Tecnológico','0722P043'),
	 (5,'Engenharia, Produção e Construção','543F05','Papel e celulose  - Tecnológico','Tecnológico','0722P013'),
	 (5,'Engenharia, Produção e Construção','543P06','Polímeros - Tecnológico','Tecnológico','0722P023');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (5,'Engenharia, Produção e Construção','544E01','Engenharia de Minas - Bacharelado','Bacharelado','0724E012'),
	 (5,'Engenharia, Produção e Construção','544E05','Petróleo e gás  - Tecnológico','Tecnológico','0724P013'),
	 (5,'Engenharia, Produção e Construção','544E07','Engenharia de Petróleo - Bacharelado','Bacharelado','0724E022'),
	 (5,'Engenharia, Produção e Construção','544M02','Mineração e extração - Tecnológico','Tecnológico','0724M013'),
	 (5,'Engenharia, Produção e Construção','544R01','Rochas ornamentais  - Tecnológico','Tecnológico','0724R013'),
	 (5,'Engenharia, Produção e Construção','544T01','Tecnologia de Mineração - Tecnológico','Tecnológico','0724M013'),
	 (5,'Engenharia, Produção e Construção','581A05','Arquitetura e Urbanismo - Bacharelado','Bacharelado','0731A022'),
	 (5,'Engenharia, Produção e Construção','582A01','Obras hidráulicas - Tecnológico','Tecnológico','0732C023'),
	 (5,'Engenharia, Produção e Construção','582A02','Agrimensura - Tecnológico','Tecnológico','0731A013'),
	 (5,'Engenharia, Produção e Construção','582C05','Construção de Edifícios - Tecnológico','Tecnológico','0732C013');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (5,'Engenharia, Produção e Construção','582E02','Engenharia Cartográfica e de Agrimensura - Bacharelado','Bacharelado','0731E012'),
	 (5,'Engenharia, Produção e Construção','582E03','Engenharia Civil - Bacharelado','Bacharelado','0732E012'),
	 (5,'Engenharia, Produção e Construção','582M02','Material de construção - Tecnológico','Tecnológico','0732M013'),
	 (5,'Engenharia, Produção e Construção','582O01','Controle de obras - Tecnológico','Tecnológico','0732C023'),
	 (5,'Engenharia, Produção e Construção','582T04','Estradas - Tecnológico','Tecnológico','0732E053'),
	 (6,'Agricultura e Veterinária','621A03','Agroindústria - Tecnológico','Tecnológico','0811A023'),
	 (6,'Agricultura e Veterinária','621A04','Agronomia - Bacharelado','Bacharelado','0811A042'),
	 (6,'Agricultura e Veterinária','621A06','Agroecologia - Tecnológico','Tecnológico','0811A013'),
	 (6,'Agricultura e Veterinária','621E03','Engenharia Agrícola - Bacharelado','Bacharelado','0811E012'),
	 (6,'Agricultura e Veterinária','621M02','Produção Agrícola - Tecnológico','Tecnológico','0811M013');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (6,'Agricultura e Veterinária','621T01','Irrigação e Drenagem - Tecnológico','Tecnológico','0811I013'),
	 (6,'Agricultura e Veterinária','621T03','Agronegócio - Tecnológico','Tecnológico','0811A033'),
	 (6,'Agricultura e Veterinária','621T04','Cafeicultura - Tecnológico','Tecnológico','0811C013'),
	 (6,'Agricultura e Veterinária','621T05','Produção de Grãos - Tecnológico','Tecnológico','0811M013'),
	 (6,'Agricultura e Veterinária','621Z01','Zootecnia - Bacharelado','Bacharelado','0811Z012'),
	 (6,'Agricultura e Veterinária','622H01','Horticultura - Tecnológico','Tecnológico','0812H013'),
	 (6,'Agricultura e Veterinária','623E01','Engenharia Florestal - Bacharelado','Bacharelado','0821E012'),
	 (6,'Agricultura e Veterinária','623S01','Silvicultura - Tecnológico','Tecnológico','0821S013'),
	 (6,'Agricultura e Veterinária','624A01','Aquicultura - Tecnológico','Tecnológico','0831A013'),
	 (6,'Agricultura e Veterinária','624E01','Engenharia de Pesca - Bacharelado','Bacharelado','0831E012');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (6,'Agricultura e Veterinária','624T01','Produção Pesqueira - Tecnológico','Tecnológico','0831P013'),
	 (6,'Agricultura e Veterinária','641M01','Medicina Veterinária - Bacharelado','Bacharelado','0841M012'),
	 (7,'Saúde e Bem-estar Social','720E01','Educação Física - Bacharelado','Bacharelado','0915E012'),
	 (7,'Saúde e Bem-estar Social','720N01','Naturologia - Bacharelado','Bacharelado','0917P012'),
	 (7,'Saúde e Bem-estar Social','720S01','Bacharelado Interdisciplinar Ciências da Saúde - Bacharelado','Bacharelado','0988P012'),
	 (7,'Saúde e Bem-estar Social','721M01','Medicina - Bacharelado','Bacharelado','0912M012'),
	 (7,'Saúde e Bem-estar Social','721O02','Oftálmica - Tecnológico','Tecnológico','0914O013'),
	 (7,'Saúde e Bem-estar Social','723E01','Enfermagem - Bacharelado','Bacharelado','0913E012'),
	 (7,'Saúde e Bem-estar Social','724O01','Odontologia - Bacharelado','Bacharelado','0911O012'),
	 (7,'Saúde e Bem-estar Social','725T06','Radiologia - Tecnológico','Tecnológico','0914R013');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (7,'Saúde e Bem-estar Social','726F01','Fisioterapia - Bacharelado','Bacharelado','0915F012'),
	 (7,'Saúde e Bem-estar Social','726F03','Fonoaudiologia - Bacharelado','Bacharelado','0915F022'),
	 (7,'Saúde e Bem-estar Social','726N02','Nutrição - Bacharelado','Bacharelado','0915N012'),
	 (7,'Saúde e Bem-estar Social','726O01','Óptica e Optometria - Tecnológico ','Tecnológico','0914O023'),
	 (7,'Saúde e Bem-estar Social','726Q01','Quiropraxia - Bacharelado','Bacharelado','0917P012'),
	 (7,'Saúde e Bem-estar Social','726T01','Terapia Ocupacional - Bacharelado','Bacharelado','0915T012'),
	 (7,'Saúde e Bem-estar Social','727F01','Farmácia - Bacharelado','Bacharelado','0916F012'),
	 (7,'Saúde e Bem-estar Social','762S01','Serviço Social - Bacharelado','Bacharelado','0923S012'),
	 (8,'Serviços','811G01','Gastronomia - Tecnológico','Tecnológico','1013G013'),
	 (8,'Serviços','811H02','Hotelaria - Tecnológico','Tecnológico','1015H013');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (8,'Serviços','811H03','Hotelaria Hospitalar - Tecnológico','Tecnológico','1015H013'),
	 (8,'Serviços','812E01','Eventos - Tecnológico','Tecnológico','1015E013'),
	 (8,'Serviços','812P01','Gestão de Turismo - Tecnológico','Tecnológico','1015T013'),
	 (8,'Serviços','812T01','Turismo - Bacharelado','Bacharelado','1015T012'),
	 (8,'Serviços','813F02','Futebol - Tecnológico','Tecnológico','1014F013'),
	 (8,'Serviços','813G02','Gestão desportiva e de lazer - Tecnológico','Tecnológico','1014G013'),
	 (8,'Serviços','814E02','Economia doméstica - Bacharelado','Bacharelado','1011E012'),
	 (8,'Serviços','815E01','Estética e Cosmética - Tecnológico','Tecnológico','1012E013'),
	 (8,'Serviços','840A01','Pilotagem profissional de aeronaves - Tecnológico','Tecnológico','1041C013'),
	 (8,'Serviços','840C04','Ciências Aeronáuticas - Bacharelado*','Bacharelado','1041C012');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (8,'Serviços','840C05','Ciências Navais - Bacharelado*','Bacharelado','0716E052'),
	 (8,'Serviços','840N02','Sistemas de navegação fluvial - Tecnológico','Tecnológico','0716S023'),
	 (8,'Serviços','840S01','Gestão portuária - Tecnológico','Tecnológico','1041G013'),
	 (8,'Serviços','840S02','Transporte aéreo - Tecnológico','Tecnológico','1041T013'),
	 (8,'Serviços','840T02','Transporte terrestre - Tecnológico','Tecnológico','1041T023'),
	 (8,'Serviços','850G01','Processos ambientais / Gestão ambiental - Tecnológico','Tecnológico','0712G013'),
	 (8,'Serviços','861S02','Segurança no trânsito / Segurança pública - Tecnológico','Tecnológico','1032S033'),
	 (8,'Serviços','861S03','Serviços penais - Tecnológico','Tecnológico','1032S043'),
	 (8,'Serviços','862S01','Segurança no trabalho - Tecnológico','Tecnológico','1022S013'),
	 (8,'Serviços','863C01','Ciências Militares - Bacharelado*','Bacharelado','1031C012');
INSERT INTO edcenso_course_of_higher_education (cod,area,id,name,`degree`,cine_id) VALUES
	 (8,'Serviços','863C02','Ciências da Logística - Bacharelado*','Bacharelado','0413L012'),
	 (8,'Serviços','863F01','Formação Militar - Bacharelado*','Bacharelado','1031C012'),
	 (9,'Outros','999990','Outro curso de formação superior - Licenciatura','Licenciatura',NULL),
	 (9,'Outros','999991','Outro curso de formação superior - Bacharelado','Bacharelado',NULL),
	 (9,'Outros','999992','Outro curso de formação superior - Tecnológico','Tecnológico',NULL);


SET FOREIGN_KEY_CHECKS=1;