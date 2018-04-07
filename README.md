[![No Maintenance Intended](https://img.shields.io/badge/No%20Maintenance%20Intended-x-red.svg?style=flat-square&longCache=true)](http://unmaintained.tech/)

# DbReflection
Reflection classes for databases. [Has only been tested against MySQL]

### Install
You can install it by downloading the [zip](https://github.com/belsrc/db-reflection/archive/master.zip) and including it in your project or, preferably, using Composer.
```
{
    "require": {
        "belsrc/db-reflection": "dev-master"
    }
}
```
If you are using Laravel you can also include the ServiceProvider in the ```app/config/app.php``` 'providers' array.
```
    'Belsrc\DbReflection\DbReflectionServiceProvider'
```
as well as the Facade in the 'aliases' array.
```
    'DbReflection' => 'Belsrc\DbReflection\Facades\DbReflection'
```

#### Quick Example
```php
$col = DbReflection::getColumn( 'column', 'table', 'database' );
```
You can also use the short-hand, cleaner route.
```php
$col = DbReflection::getColumn( 'database.table.column' );
```

#### Commands
If using Laravel, the package will also expose a few artisan command.
```bash
reflect:column path        Get the information about a particular column.
  Argument: path           The 'path' of the column (i.e. 'database.table.column')

reflect:database database  Get the information about a particular database.
  Argument: database       The name of the database.

reflect:table path         Get the information about a particular table.
  Argument: path           The 'path' of the table (i.e. 'database.table.column')


  php artisan reflect:database mocking_db
  -------------------------------
  | name      | mocking_db      |
  | charset   | utf8            |
  | collation | utf8_unicode_ci |
  | tables    | 25              |
  -------------------------------

  php artisan reflect:table mocking_db.app_user
  ------------------------------------
  | name      | app_user             |
  | type      | BASE TABLE           |
  | length    | 16384                |
  | maxLength | 0                    |
  | increment | 16                   |
  | createdAt | 2014-02-18 12:00:41  |
  | updatedAt |                      |
  | checksum  |                      |
  | options   |                      |
  | comment   | Authorized app users |
  | database  | mocking_db           |
  | columns   | 12                   |
  ------------------------------------

  php artisan reflect:column mocking_db.app_user.entity_id
  --------------------------------------------------
  | name         | entity_id                       |
  | position     | 1                               |
  | defaultValue |                                 |
  | isNullable   | NO                              |
  | dataType     | int                             |
  | precision    | 10                              |
  | maxLength    |                                 |
  | columnType   | int(10) unsigned                |
  | charset      |                                 |
  | extra        | auto_increment                  |
  | privileges   | select,insert,update,references |
  | comment      |                                 |
  | table        | app_user                        |
  | database     | mocking_db                      |
  | constraints  | Primary Key                     |
  --------------------------------------------------

```

#### Code Behind
```php
  print_r( DbReflection::getDatabase( 'mysql' ) );
  /*
    Belsrc\DbReflection\Reflection\ReflectionDatabase Object
    (
      [name] => mysql
      [charset] => utf8
      [collation] => utf8_general_ci
      [tables] => Array
      (
        [0] => columns_priv
        [1] => db
        [2] => event
        [3] => func
        [4] => general_log
        [5] => help_category
        [6] => help_keyword
        [7] => help_relation
        [8] => help_topic
        [9] => host
        [10] => ndb_binlog_index
        [11] => plugin
        [12] => proc
        [13] => procs_priv
        [14] => proxies_priv
        [15] => servers
        [16] => slow_log
        [17] => tables_priv
        [18] => time_zone
        [19] => time_zone_leap_second
        [20] => time_zone_name
        [21] => time_zone_transition
        [22] => time_zone_transition_type
        [23] => user
      )
    )
   */

  print_r( DbReflection::getTable( 'mysql.help_topic' ) );
  /*
    Belsrc\DbReflection\Reflection\ReflectionTable Object
    (
      [name] => help_topic
      [type] => BASE TABLE
      [length] => 444876
      [maxLength] => 281474976710655
      [increment] =>
      [createdAt] => 2012-04-19 09:45:09
      [updatedAt] => 2012-04-19 15:45:10
      [checksum] =>
      [options] =>
      [comment] => help topics
      [database] => mysql
      [columns] => Array
      (
        [0] => help_topic_id
        [1] => name
        [2] => help_category_id
        [3] => description
        [4] => example
        [5] => url
      )
    )
  */


  print_r( DbReflection::getColumn( 'mysql.help_topic.help_topic_id' ) );
  /*
    Belsrc\DbReflection\Reflection\ReflectionColumn Object
    (
      [name] => help_topic_id
      [position] => 1
      [defaultValue] =>
      [isNullable] => NO
      [dataType] => int
      [precision] => 10
      [maxLength] =>
      [columnType] => int(10) unsigned
      [charSet] =>
      [extra] =>
      [privileges] => select,insert,update,references
      [comment] =>
      [table] => help_topic
      [database] => mysql
      [constraints] => Array
      (
        [0] => Belsrc\DbReflection\Reflection\ReflectionConstraint Object
        (
          [type] => Primary Key
          [name] => PRIMARY
          [column] => help_topic_id
          [table] => help_topic
          [database] => mysql
          [foreign_db] =>
          [foreign_table] =>
          [foreign_column] =>
        )
      )
    )
  */


```

## License ##
DbReflection is released under a BSD 3-Clause License

Copyright &copy; 2013-2014, Bryan Kizer
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

* Redistributions of source code must retain the above copyright notice,
  this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.
* Neither the name of the Organization nor the names of its contributors
  may be used to endorse or promote products derived from this software
  without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
