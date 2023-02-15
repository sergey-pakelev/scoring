## Requirements
* Docker
* Docker-compose
* GNU Make

## Make targets
Run dev server on http://localhost:8080
```
make dev
```

Stop docker containers
```
make stop
```

Enter to the app console
```
make php-sh
```

Run app tests
```
make run-tests
```

Create fixtures
```
make fixtures
```

Run client score command for all clients
```
make client-score
```

Run client score command for a specific client
```
make client-score id=
```
