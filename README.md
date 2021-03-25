# Ecoglass Project

### Recover the depot with git
* SSH : git@github.com:Alexandre-COURBOIS/api-ecoglass.git
* HTTPS : https://github.com/Alexandre-COURBOIS/api-ecoglass.git

### To proceed to all the next steps go inside the project when cloning is finished.

### First step
* Tap the command:
```
composer install
```

### Second step
* Create the file .env.local
* Copy the content .env in .env.local and setup your own informations.

### Third step
* Change the DATABASE_URL and use Postgres.
* And write command:
```
./bin/console d:d:c

or

php bin/console d:d:c
```

### Fourth step

* | FORCED STEP | Set up postgis in extension in postgres database.

Update the entity for the project
```

./bin/console d:s:u --force

or

php bin/console d:s:u --force
```

### Fifth step

And finaly : Run the application with this command

```
symfony server:start
```
