use unoAunoCoordinacionesTIgo;

SELECT * FROM casos_especiales;
--select * from Miembro_Fisico where MiembroID = 968104;
-- SELECT * from Teleoperador;
--
-- SELECT getdate();
-- delete from casos_especiales where id = 22;

--    DELETE FROM casos_especiales ;
--    DBCC CHECKIDENT ('casos_especiales', RESEED, 1);

SELECT * FROM   casos_especiales WHERE estado = 'Finalizada' AND fecha_finalizado >= (getdate()-1)