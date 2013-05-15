# About #

Inspired by <http://lorempixel.com/>.

Dead simple PHP script to serve a random image inside subdirectories.

# Usage #

1. Create subdirectories
2. Put some images in these
3. ???
4. Profit!

~~~
http://domain.com/index.php
http://domain.com/index.php?dir=cats
http://domain.com/index.php?dir=cats&mode=redirect
~~~

* No parameters : picks a random directory, then a random image from it.
* `dir` use this directory
* `mode`: only if `allow_mode_change` is set to `true` in configuration
  * `output`: PHP will read the file and output it in the response
    * 1 request to get the image
  * `redirect`: Returns a `location` header with picked image's URL
    * 2 requests to get the image
  


# Config #

~~~
$config['mode'] = 'output';
$config['allow_mode_change'] = true;
~~~

* `mode`: Default working mode (see Usage section)
* `allow_mode_change`: Enables mode switching via the `mode` parameter in request


# Change log #

V.0.1 : Initial release

# TODO #

* Resize/Crop
* Select image
* Directories cache