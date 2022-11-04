set -e
echo "Deployment Start"
GIT_SSH_COMMAND="ssh -i ~/.ssh/backend" git pull origin master
composer update
php artisan migrate
echo "Deployment Finished"
