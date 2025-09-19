-- CRIAR O EDCENSO_ALIAS 2024 PRA CONSEGUIR GERAR O ARQUIVO DO ANO VIGENTE

insert into edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
select register, corder, attr, cdesc, `default`, stable, '2024' from edcenso_alias where `year` = '2023';