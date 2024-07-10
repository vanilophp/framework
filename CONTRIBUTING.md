# Contributing to Vanilo Framework

## Running Tests

By default, tests are ran against an SQLite database.

If you'd like to run against a real DB engine, the fastest way to do so is to spin up a container for the given
DB engine, and configure a local `phpunit.xml` accordingly

### MySQL 5.7

To spin up a temporary MySQL 5.7 DB use the following command:

```bash
docker run --name vanilo_test_mysql57 -e MYSQL_ROOT_PASSWORD=mypass123 -d -p 3307:3306 mysql:5.7
```

> You can choose any other container name, password or port instead of the ones in the example above

Then copy `phpunit.xml.dist` to `phpunit.xml` and add the following values before the closing `</phpunit>` tag:

```xml
    <php>
        <env name="APP_KEY" value="base64:gm0Aq0Xod/x8Rn2nhZLioYx55ojBcnjzaD4+GQ8M8mM="/>
        <env name="APP_ENV" value="testing"/>
        <env name="APP_DEBUG" value="true"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TEST_DB_ENGINE" value="mysql"/>
        <env name="TEST_DB_PORT" value="3307"/>
        <env name="TEST_DB_PASSWORD" value="mypass123"/>
    </php>
```

### Postgres

To spin up a temporary Postgres Database use the following command:

```bash
docker run --name postgres_test -e POSTGRES_PASSWORD=pgpass123 -d -p 5452:5432 postgres   
```

> You can choose any other container name, password or port instead of the ones in the example above

Then copy `phpunit.xml.dist` to `phpunit.xml` and add the following values before the closing `</phpunit>` tag:

```xml
    <php>
        <env name="APP_KEY" value="base64:gm0Aq0Xod/x8Rn2nhZLioYx55ojBcnjzaD4+GQ8M8mM="/>
        <env name="APP_ENV" value="testing"/>
        <env name="APP_DEBUG" value="true"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TEST_DB_ENGINE" value="pgsql"/>
        <env name="TEST_DB_PORT" value="5452"/>
        <env name="TEST_DB_PASSWORD" value="pgpass123"/>
    </php>
```

