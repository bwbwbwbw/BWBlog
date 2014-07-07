BWBlog
======

A lightweight and super fast PHP blog system.

- Use Markdown when writing.

- Use MongoDB as database storage.

- Use [Disqus](http://disqus.com/) as discussion provider.

## Requirement

PHP >= 5.4

MongoDB >= 2.2

[Composer](https://getcomposer.org/)

## Setup

1. Use Composer to install dependencies:
   
   ```
   php composer.phar install
   ```

2. Grant write permission to the `runtime` directory:

   ```
   chmod -R 777 runtime
   ```

3. See [Blog Configuration](#blog-configuration)

4. See [Web Server Configuration](#web-server-configuration)

5. Visit `/bwblog` to access the dashboard and start your writing!

## Blog Configuration

1. Copy `/includes/config.php.default` to `/includes/config.php`

2. Modify `/includes/config.php`.

#### ADMIN_USER

The admin username of BWBlog Dashboard.

#### ADMIN_PASS

The hash of admin password **(Hash password using SHA1 for 12 times)**.

To generate 12 times SHA1 result:

```php
<?php

$mypass = 'hello_world';
for ($i = 0; $i < 12; ++$i) {
    $mypass = sha1($mypass);
}
echo $mypass;
```

#### CONFIG_ENFORCESSL

Set to `true` if your site has SSL certificate and want to enforce visitors using SSL. Otherwise set to `false`.

Default: `false`

#### CONFIG_DEBUG

Show debug information when something get wrong. In production environment, please set it to `false`. If you would like to report these issues, set to `true`.

Default: `true`

#### CONFIG_HOST

Your blog domain, for example, `example.com`. BWBlog will redirect all requests of other domain to this domain.

#### CONFIG_TIMEZONE

The time zone to use. [Available timezone list](http://www.php.net/manual/en/timezones.php).

Default: `Asia/Shanghai` (+8)

#### CONFIG_DATE_FORMAT

The format of displaying date. [Format parameters](http://www.php.net/manual/en/function.date.php).

Default: `dS F, Y`

#### CONFIG_TIME_FORMAT

The format of displaying time. [Format parameters](http://www.php.net/manual/en/function.date.php).

Default: `h:i:s A`

#### P_PREFIX

The prefix of all URLs.

If the blog is under your root web directory (for example, visit via `http://example.com`), leave it blank (default);

If not (for example, visit via `http://example.com/my/blog`), set the value to the directory: `/my/blog`.

Default: (empty)

#### P_THEME

The theme to use.

Default: `default`

#### P_POSTS_PER_PAGE

Show how many posts per page.

Default: `10`

#### P_TITLE

Your blog title.

#### P_DISQUS_ID

Your Disqus shortname for the blog. Disqus is used to provide comment services in BWBlog. [What's a shortname](http://help.disqus.com/customer/portal/articles/466208-what-s-a-shortname-).

#### P_HTML_FILTER

Filter dangerous HTML tags when publishing (for example, `<script>`).

Default: `false`

#### MONGO_PREFIX

Database collection prefix.

Default: `blog-`

#### MONGO_PATH

The MongoDB connection path. Specific host and port here.

Default: `mongodb://127.0.0.1:27017`

#### MONGO_DB

The Database name.

#### MONGO_USERNAME

Database username.

#### MONGO_PASSWORD

Database password.

#### MONGO_TIMEOUT

Database connection timeout(ms).

Default: `2000`

## Web Server Configuration

### Apache

Create `.htaccess` file in the project root directory:

```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^.+$ index.php [L]
</IfModule>
```

Notice: If BWBlog is not under your root web directory, for example, you visit the blog via `http://example.com/my/blog` instead of `http://example.com`), modify the third line to:

```
RewriteBase /my/blog/
```

### Nginx

//TODO

## Themes

Themes are located in `/themes/` directory. BWBlog uses [Twig](http://twig.sensiolabs.org/) as template engine.

The default theme uses [stylus](http://learnboost.github.io/stylus/) & [autoprefixer](https://github.com/ai/autoprefixer) to compile CSS. If you are going to modify the default theme, please install [grunt](http://gruntjs.com) and use grunt to do the compiling task:

```
grunt production
```

Or for debug purpose, disable Js & CSS compression & watch file changes:

```
grunt
```

Learn more: [Grunt getting started](http://gruntjs.com/getting-started)

## TODO

1. Support auto-save when writing

2. Support delete post.

3. Show pages somewhere.

## Request A New Feature (NFR)

Please [open an issue](https://github.com/breeswish/BWBlog/issues).