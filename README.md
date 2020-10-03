<!-- chapel_upload  -->

Examples are given in Linux since no scripting should ever exist on Windows
please replace the ./ and / to the \\ as necessary. Or use the DIRECTORY_SEPARATOR macro.  PHP scripts may run differently from cmd line than as shown below. -->

<!-- Windows initial setup 
Download PHP -->
https://windows.php.net/download#php-7.4
https://windows.php.net/downloads/releases/php-7.4.11-nts-Win32-vc15-x64.zip

Extract zip file to c:\php by Right click, Extract all...
Edit Environment Variables for PATH
c:\php

Open cmd and type 'php -v'
_PHP 7.4.11 (cli) (built: Sep 29 2020 13:17:42) ( NTS Visual C++ 2017 x64 )
_Copyright (c) The PHP Group
-Zend Engine v3.4.0, Copyright (c) Zend Technologies

<!-- Download GIT -->
https://git-scm.com/download/win
https://github.com/git-for-windows/git/releases/download/v2.28.0.windows.1/Git-2.28.0-64-bit.exe
C:\Program Files\Git is the default install directory
In the setup wizard, click next with defaults EXCEPT the screen 'configuring the line ending conversions', choose the middle option.
Add these to PATH
C:\Program Files\Git
C:\Program Files\Git\bin

Open cmd and type git
You should see the help print out

in a cmd shell, create a project folder, or if you want to run it in c:\chapel_upload, just got c:\
<!-- chapel upload repo -->
git clone https://github.com/tincupchalice/chapel_upload.git
u:tincupchalice
p:***************
<!-- vimeo.php submodule -->
git submodule init
git submodule update

cp config.json.example config.json
<!-- change values for that specific location -->

<!-- Before using this script, you need to setup the environment with composer... -->
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '795f976fe0ebd8b75f26a6dd68f78fd3453ce79f32ecb33e7fd087d39bfeb978342fb73ac986cd4f54edd0dc902601dc') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

./composer.phar require vimeo/vimeo-api
./composer.phar update

-- Create vimeo php script with appropriate config.json
<!-- see config.json.example -->

php chapel_upload.php

<!-- EDITING... -->
<!-- create a branch from master -->
git pull origin master
git checkout -b "BN_FIX_what_is_broken"
git checkout -b "BN_FTR_new_feature"
git add [ files edited or added ]
<!-- note if config.json is appended, config.json.example must be updated to match" -->
git commit -m "Message of what is being committed - wordy is better"
git push origin BRANCH_NAME

<!-- MERGE Branch to master -->
git checkout master
git merge BRANCH_NAME
<!-- if errors, fix them then -->
git add
git commit
git push origin master

<!-- testing branch BN_RDM_Add_to_Readme -->

