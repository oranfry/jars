# Jars

## Install Jars

I this document, I'm using:

- `$HOME/Projects/jars` for the installation directory, but you could use anything. For production I use `/var/www/jars`.
- `$HOME/Projects/jars/var` as the variable files directory, but for production I would use `/var` (the default).

Get the project:

```php
cd "$HOME/Projects"
git clone https://github.com/oranfry/jars.git
cd jars

composer install
npm install
./build
```

## Test Jars

```
cd "$HOME/Projects/jars"
./test
```

## Initialise a blank database for your portal

An empty database is just an empty directory:

```
mkdir -p "$HOME/Projects/jars/var/dbs/myportal"
```

## Create your portal

```
mkdir -p "$HOME/Projects/jars/var/portals/myportal"

// Generate a sequence secret
php -r 'echo base64_encode(random_bytes(63)) . "\n";'
```

Create the file `$HOME/Projects/jars/var/dbs/myportal/portal.php` with these contents, replacing SECR3T with your secret as generated above

```php
<?php

return (object) [
    'root_username' => 'root',
    'root_password' => 'test123',
    'sequence' => (object) [
        'secret' => 'SECR3T',
        'banned_chars' => ['/', '=', '+'],
    ],
];
```

For the life of the portal, you must not change your secret or banned_chars. Doing so would result in data corruption as the database gets used.

Now, scan the first million IDs for collions (unusable IDs):

```
cd "$HOME/Projects/jars"
bin/jars "--portal-home=$HOME/jars/var/portals/acme" "--db-home=$HOME/Unsynched/jars/var/dbs/acme" -u acme -p test123 collisions 1000000
```

To apply the findings of the above command, merge its output with the sequence setup in `portal.php`:

```php
<?php

return (object) [
    'root_username' => 'root',
    'root_password' => 'test123',
    'sequence' => (object) [
        'secret' => 'SECR3T',
        'banned_chars' => ['/', '=', '+'],
        'max' => 1000000,                   // example added line
        'collisions' => [398734, 541078],   // example added line
    ],
];
```

Chances are there will be no collitions under a million, in which case collisions will be an empty array.

You should never remove collisions recorded here, nor increase the max to value higher than what has actually been scanned. If you do, a new ID might be issued which has already been issued before!

Once jars hits the max, it will refuse to issue any new IDs as a safeguard. You will not be able to add data to your database, but everything else will work as normal.

You may increase the max at any time by re-running the collision command with a higher argument, and overwriting the relevant fields in the portal config with those given by the command; the collision command will re-find all the known collisions plus any new ones in range.

You may need to give PHP some more memory when scanning many millions of IDs.

## Expose jars admin

Point your web server to `$HOME/Projects/jars/public`.
