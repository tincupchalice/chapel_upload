# chapel_upload

Examples are given in Linux since no scripting should ever exist on Windows
please replace the ./ and / to the \\ as necessary. Or use the DIRECTORY_SEPARATOR macro.  PHP scripts may run differently from cmd line than as shown below. -->

## Windows initial setup 
## Download PHP
https://windows.php.net/download#php-7.4

https://windows.php.net/downloads/releases/php-7.4.11-nts-Win32-vc15-x64.zip

* Extract zip file to c:\php by Right click, Extract all...
* Edit Environment Variables for PATH
* c:\php
* Open cmd and type 'php -v'
> PHP 7.4.11 (cli) (built: Sep 29 2020 13:17:42) ( NTS Visual C++ 2017 x64 )
> Copyright (c) The PHP Group
> Zend Engine v3.4.0, Copyright (c) Zend Technologies

## WINDOWS
* cp c:\php\php.ini.development c:\php.ini
* Edit this file and uncomment 
* ext=openssl
* ext=curl
* Download this file http://curl.haxx.se/ca/cacert.pem and place it in C:\php\extras\ssl, then uncomment the cainfo line in php.ini and put the path to that file in quotes after equal.
* If you want the mail functions to work, you will also need to set the proper config values in php.ini

## Download GIT
https://git-scm.com/download/win

https://github.com/git-for-windows/git/releases/download/v2.28.0.windows.1/Git-2.28.0-64-bit.exe

* C:\Program Files\Git is the default install directory
* In the setup wizard, click next with defaults EXCEPT the screen 'configuring the line ending conversions', choose the middle option.
* Add these to PATH
* C:\Program Files\Git
* C:\Program Files\Git\bin
* Open cmd and type git

> You should see the help print out

## Setup the project

* in a cmd shell, create a project folder, or if you want to run it in c:\chapel_upload, just got c:\
* git clone https://github.com/tincupchalice/chapel_upload.git
* u:tincupchalice
* p:***************
* cd chapel_upload\vimeo.php
* git submodule init
* git submodule update
* cd ..
* cp config.json.example config.json
  * change values for that specific location, or upload a secret file you have

## Composer
* php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
* php -r "if (hash_file('sha384', 'composer-setup.php') === '795f976fe0ebd8b75f26a6dd68f78fd3453ce79f32ecb33e7fd087d39bfeb978342fb73ac986cd4f54edd0dc902601dc') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
* php composer-setup.php
* php -r "unlink('composer-setup.php');"
* on WINDOWS you may need to del composer-setup.php
* php composer.phar require vimeo/vimeo-api
* php composer.phar update

## Running
php chapel_upload.php





## BEFORE EDITING ANY FILES
* git pull origin master
* git checkout -b "BN_FIX_what_is_broken"
* git checkout -b "BN_FTR_new_feature"
* Edit files
* TEST SUCCESS
* git add [ files edited or added ]
* git commit -m "Message of what is being committed - wordy is better"
* git push origin BRANCH_NAME

## Merge branch back into master
* git checkout master
* git merge BRANCH_NAME
*if errors, fix them then*
* git add
* git commit
* git push origin master
