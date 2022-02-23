# Pane of Hope

Work in progress...


## Quickstart

1. Clone the git repo to your webserver.
1. Set up URL rewriting. (Guide below.)
1. Copy/Rename `settings.php.template` to `settings.php` and edit it for your needs.
1. Create the tables in your database using [this template][table-creator-sql].
1. Register a new user.\*
1. Run the SQL query: `UPDATE accounts SET permission_level=100 WHERE id=1;`
1. Add new characters.\*
1. (optional) Character image uploading:
    1. Create the folder: `data/` (`$ mkdir data`)
    1. Set needed permissions. (`$ chmod 777 data` or `chown USER:GROUP data`)
    1. Upload images for characters.\*
1. Add new sources.\*
1. Link characters to sources.\*

*\*Using the Web UI.*

## URL Rewrite

You'll need to set up URL rewrite rules that rewrite requests to `router.php?r=RELATIVE_PATH`.

For example:
`/pane-of-hope/example/request?key=value`
should become
`/pane-of-hope/router.php?r=example/request&key=value`

Exceptions:

* `/pane-of-hope/data`
* `/pane-of-hope/favicon.png`

**Note:** You can change the default `pane-of-hope` name but be sure to change it in the settings as well.

### Sample Nginx rewrite rules

```
rewrite ^/pane-of-hope/favicon.png$ "/pane-of-hope/favicon.png" last;
rewrite ^/pane-of-hope/data/(.*)$ "/pane-of-hope/data/$1" last;
rewrite ^/pane-of-hope/(.*)$ "/pane-of-hope/router.php?r=$1" last;
```

*(These Nginx rewrite rules were used for testing.)*

## Database

MariaDB 10.5.12 is used for testing.
Check the [table creator SQL file][table-creator-sql] to prepare your database.


[table-creator-sql]: CREATE_TABLE.sql
