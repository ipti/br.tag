lint:
	composer lint

fix:
	composer fix

analyse:
	composer analyse

rector-dry:
	docker exec tag-app ./vendor/bin/rector process --dry-run

rector-run:
	docker exec tag-app ./vendor/bin/rector process

mess:
	composer mess
