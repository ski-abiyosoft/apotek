# Capybara

> No dependency, just PHP!

## Overview
Database versioning could be boring, especially when you are a database administrator. We come to solve the problem. Managing database should be fun and chill like Capybara.

## How to Use it

### Installing App

Once you have download this package, we assume that you have installed PHP CLI and Composer in your computer. You can run `composer install`, then you can use this program as you go.

### Creating Migration File

When you want to make a migration file you can run this command: 

> `php capybara make:migration $file_name $table_name`

$file_name: migration file name that you want to create. You can start with word 'create' to create table or 'modify' to modify a table. Example: 'create_table_test'.

$table_name: the table name that you want to create or modify. This is optional the Capybara will use the filename that you input as table name.

After run that command, you can check migration file in the Migrations folder. You can edit them if the auto generated table name is not same as you want.

### Creating Column

When you want to adding column to your table, use table helper to create specified column. You can read the available function in the next session.

Example:

```
Schema::new('users', function ($table) {
    $table->id();
    $table->string('username');
    $table->date('date_of_birth');
})
```

### Migrating Database

Once you have made migration file, you can migrate it into your database by using this command:

> `php capybara migrate`

Capybara will make migrations table in your database as a list of migration files that have been migrated. Capybara will make cursor based on this table, so do not delete this table, or Capybara will migrate all files in the Migrations folder.

## Available Function

Capybara has many built ini method for creating table column such as varchar, integer, date, etc. We provide some usefull method listed in the table below:

|Command|Explaination|
|-------|------------|
|`$table->id()`| Creating `id` column with auto increment and used as primary key. |
|`$table->string($name, $length)`| Creating `VARCHAR` column with given `$name` and `$length`. |
|`$table->tinyText($name)`| Creating `TINY TEXT` column with given `$name`. |
|`$table->text($name)`| Creating `TEXT` column with given `$name`. |
|`$table->mediumText($name)`| Creating `MEDIUM TEXT` column with given `$name`. |
|`$table->longText($name)`| Creating `LONG TEXT` column with given `$name`. |
|`$table->integer($name)`| Creating `INT` column with given `$name`. |
|`$table->float($name)`| Creating `FLOAT` column with given `$name`. |
|`$table->date($name)`| Creating `DATE` column with given `$name`. |
|`$table->datetime($name)`| Creating `DATETIME` column with given `$name`. |

Hope you enjoy it, Bro.

Regards,

PT SKI DIY
