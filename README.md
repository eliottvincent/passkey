PassKey
-----------------

PassKey is a key manager for the ENSSAT.

## Getting started

1. PHP >7.0.x is required
2. Install [Composer](#composer-installation) locally (in the project's root)
3. Install dependencies with the following command:
```
$ php composer.phar install
```
4. Setup [URL rewriting](https://www.google.fr/search?q=apache+url+rewriting) on your server so that all requests are handled by **index.php**
5. Be sure to enable override by htaccess in apache2.conf by including this code :
```
<Directory /var/www/>
Options Indexes FollowSymLinks
AllowOverride All
Require all granted
</Directory>
```
