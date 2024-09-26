Requirements:
-
- docker
- docker-compose

Build project:
- 
- from project directory:
```
docker-compose build --force-rm --no-cache
```

Static code analysis:
-
- from ``keepit_app`` docker container:
```
vendor/bin/phpstan
```
Run App:
-
from ``keepit_app`` docker container:
```
php bin/console app:find-max-ul <URL>
```

Run Tests:
-
from ``keepit_app`` docker container:
```
vendor/bin/phpunit
```
