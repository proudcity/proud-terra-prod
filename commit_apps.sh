# using
#  bash commit_apps.sh "git commit msg"

msg="$1"

for d in /home/jeff/Apps/proud/local/www/sites/all/modules/apps/* ; do (echo $d; cd $d; git add .; git commit -m "{$msg}"; git push); done
