set -e

#Pull Code & Build.
cd /home/quickvil/public_html/backend
GIT_SSH_COMMAND="ssh -i ~/.ssh/backend" git pull origin master
composer install
