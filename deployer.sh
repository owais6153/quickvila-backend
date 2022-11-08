set -e
echo "Deployment Start"
git checkout .
GIT_SSH_COMMAND="ssh -i ~/.ssh/backend" git pull origin master
php artisan migrate
echo "Deployment Finished"
