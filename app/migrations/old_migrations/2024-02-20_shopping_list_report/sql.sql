update classroom
	set turn = (case
		when (initial_hour > 6 and initial_hour <= 13) and (final_hour <= 13) then 'M' 	
		when (initial_hour > 11) and (final_hour <= 18) then 'T'
		when (initial_hour > 17) then 'N' 		
		else 'I'
	end)