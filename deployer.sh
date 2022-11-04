set -e
echo "Deployment Start"
GIT_SSH_COMMAND="ssh -i ~/.ssh/backend" git pull origin master
sudo chown -R $USER composer.json
sudo chown -R $USER composer.lock
composer update
php artisan migrate
echo "Deployment Finished"
