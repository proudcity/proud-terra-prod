# Installs a terra hosting enviornment with terra ui set up at ui.dev

wget https://raw.githubusercontent.com/terra-ops/terra-app/master/install.sh
bash install.sh

ssh-keygen -t rsa -N "" -f ~/.ssh/id_rsa

drush dl drupalorg_drush

cd /usr/share/terra
git remote set-url origin https://github.com/albatrossdigital/terra-cli.git
git pull >> /dev/null

terra url-proxy:enable

echo "
Host *
    StrictHostKeyChecking no" >> ~/.ssh/config 

# terra_ui stuff can be omitted
cd ~/
git clone https://github.com/albatrossdigital/terra-callback.git
terra a:a ui https://github.com/terra-ops/terra-ui-prototype.git
php ~/terra-callback/receiver.php

#terra a:a proud https://github.com/proudcity/proud-terra-dev.git
