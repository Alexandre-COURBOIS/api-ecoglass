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
* Copy the content .env in .env.local and setup your own informations :
    * On "lexik/jwt-authentication-bundle part".
    * On "markitosgv/JWT-Refresh-Token-bundle".
    * On "doctrine/doctrine-bundle".
    * On "symfony/mailer"
    * On "nelmio/cors-bundle".
    
Example :

```
# In all environments, the following files are loaded if they exist,
# the latter taking precedence over the former:
#
#  * .env                contains default values for the environment variables needed by the app
#  * .env.local          uncommitted file with local overrides
#  * .env.$APP_ENV       committed environment-specific defaults
#  * .env.$APP_ENV.local uncommitted environment-specific overrides
#
# Real environment variables win over .env files.
#
# DO NOT DEFINE PRODUCTION SECRETS IN THIS FILE NOR IN ANY OTHER COMMITTED FILES.
#
# Run "composer dump-env prod" to compile .env files for production use (requires symfony/flex >=1.2).
# https://symfony.com/doc/current/best_practices.html#use-environment-variables-for-infrastructure-configuration

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=0e73006c22c86d9a05a49ee8a093a399
###< symfony/framework-bundle ###

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=YOURPASSPHRASE
JWT_TTL=YOURTOKENTTL
###< lexik/jwt-authentication-bundle ###

###< markitosgv/JWT-Refresh-Token-bundle ###
REFRESH_JWT_TTL=YOURTTLONREFRESHTOKEN
###< markitosgv/JWT-Refresh-Token-bundle ###

###> doctrine/doctrine-bundle ###
# Format described at https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# IMPORTANT: You MUST configure your server version, either here or in config/packages/doctrine.yaml
#
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
# DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
DATABASE_URL="postgresql://USERNAMEONPOSTGRES:PASSWORD@127.0.0.1:5432/YOURDATABASENAME"
###< doctrine/doctrine-bundle ###

###> symfony/mailer ###
MAILER_DSN=gmail://EMAIL:PASSWORD@default?verify_peer=0
###< symfony/mailer ###

###> nelmio/cors-bundle ###
CORS_ALLOW_ORIGIN=^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$
###< nelmio/cors-bundle ###
```

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
