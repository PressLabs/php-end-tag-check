# php-end-tag-check

This plugin checks for php end tag `?>` at the end of plugin and theme files, and alerts users to remove it. The `?>` end tags can cause many problems like poor SEO ranking or bad page rendering.

You can read more about this issue in [this article](http://hardcorewp.com/2013/always-omit-closing-php-tags-in-wordpress-plugins/).

[WordPress rules](http://www.php-fig.org/psr/psr-2/) also state that the php tag must not be close.
