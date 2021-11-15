# Pane of Hope

Work in progress...


## Quickstart

1. Clone the git repo to your webserver.
1. Copy/Rename `settings.php.template` to `settings.php` and edit it for your needs.
1. Create the tables in your database.
1. Register a new user.\*
1. Run the SQL query: `UPDATE users SET permission_level=100 WHERE id=1;`
1. Add new characters.\*

*\*Using the Web UI.*

## Database

MariaDB 10.5.12 is used for testing.
Check the [table creator SQL file][table-creator-sql] to prepare your database.


[table-creator-sql]: CREATE_TABLE.sql
