# chapel_upload

# Examples are given in Linux since no scripting should ever exist on Windows
# please replace the ./ and / to the \\ as necessary.  PHP scripts may run differently from cmd line than as shown below.

# Before using this script, you need to setup the environment with composer...
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '795f976fe0ebd8b75f26a6dd68f78fd3453ce79f32ecb33e7fd087d39bfeb978342fb73ac986cd4f54edd0dc902601dc') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

./composer.phar require vimeo/vimeo-api

-- Create vimeo php script with appropriate config.json
# see config.json.example


