# chapel_upload

# Examples are given in Linux since no scripting should ever exist on Windows
# please replace the ./ and / to the \\ as necessary. Or use the DIRECTORY_SEPARATOR macro.  PHP scripts may run differently from cmd line than as shown below.

# chapel upload repo
git clone https://github.com/tincupchalice/chapel_upload.git
# vimeo.php submodule
git submodule init
git submodule update

cp config.json.example config.json
# change values for that specific location

# Before using this script, you need to setup the environment with composer...
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '795f976fe0ebd8b75f26a6dd68f78fd3453ce79f32ecb33e7fd087d39bfeb978342fb73ac986cd4f54edd0dc902601dc') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

./composer.phar require vimeo/vimeo-api
./composer.phar update

-- Create vimeo php script with appropriate config.json
# see config.json.example

php chapel_upload.php

# EDITING...
# create a branch from master
git pull origin master
git checkout -b "BN_FIX_what_is_broken"
git checkout -b "BN_FTR_new_feature"
git add [ files edited or added ]
# note if config.json is appended, config.json.example must be updated to match"
git commit -m "Message of what is being committed - wordy is better"
git push origin BRANCH_NAME

# MERGE Branch to master
git checkout master
git merge BRANCH_NAME
# if errors, fix them then
git add
git commit
git push origin master

# testing branch BN_RDM_Add_to_Readme

