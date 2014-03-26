# DbReflection
Reflection classes for databases.

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
reflect
  reflect:column [path]        Get the information about a particular column.
  reflect:database [database]  Get the information about a particular database.
  reflect:table [path]         Get the information about a particular table.


  php artisan reflect:database mocking_db
  -------------------------------
  | name      | mocking_db      |
  | charset   | utf8            |
  | collation | utf8_unicode_ci |
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
  | key          | PRI                             |
  | extra        | auto_increment                  |
  | privileges   | select,insert,update,references |
  | comment      |                                 |
  | table        | app_user                        |
  | database     | mocking_db                      |
  --------------------------------------------------

```

#### Reflection Classes
```php
  ReflectionDatabase::name
  ReflectionDatabase::charset
  ReflectionDatabase::collation
  ReflectionDatabase::tables

  ReflectionTable::name
  ReflectionTable::type
  ReflectionTable::length
  ReflectionTable::maxLength
  ReflectionTable::increment
  ReflectionTable::createdAt
  ReflectionTable::updatedAt
  ReflectionTable::checksum
  ReflectionTable::options
  ReflectionTable::comment
  ReflectionTable::database
  ReflectionTable::columns

  ReflectionColumn::name;
  ReflectionColumn::position
  ReflectionColumn::defaultValue
  ReflectionColumn::isNullable
  ReflectionColumn::dataType
  ReflectionColumn::precision
  ReflectionColumn::maxLength
  ReflectionColumn::columnType
  ReflectionColumn::charSet
  ReflectionColumn::key
  ReflectionColumn::extra
  ReflectionColumn::privileges
  ReflectionColumn::comment
  ReflectionColumn::parentItem
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
