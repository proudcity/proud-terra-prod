drush @proud.local site-install proud --db-url=mysql://drupal:drupal@database/drupal --sites-subdir=default -y  > ~/Downloads/a.txt

drush @proud.local user-create test --mail="test@test.com" --password="letmein"
drush @proud.local user-add-role "administrator" --mail="test@test.com"
drush @proud.local en -y proud_agency  proud_document  proud_event  proud_faq  proud_landingpage  proud_news  proud_people  proud_search  proud_social  proud_webform

drush @proud.local uli >> /dev/null
drush @proud.local uli test --browser=firefox >> /dev/null

