ALTER TABLE student_documents_and_address ADD cpf_reason SMALLINT DEFAULT 0 NOT NULL;

UPDATE
	student_documents_and_address
SET
	cpf_reason = CASE
		WHEN cpf IS NULL
		OR cpf = '' THEN 0
		ELSE 4
	END;
