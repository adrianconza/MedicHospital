#!/bin/sh

echo $CI_DEPLOY_PASSWORD | sudo docker login -u $CI_DEPLOY_USER --password-stdin $CI_REGISTRY

export PRODUCTION_IMAGE=$PRODUCTION_IMAGE
export PRODUCTION_PORT=$PRODUCTION_PORT

echo '-- Start docker compose Down --'
sudo -E docker-compose down
echo '-- Finish docker compose Down --'
echo '------------------------------'

echo '-- Delete images docker --'
sh deleteImagesDocker.sh
echo '-- Finish delete images docker --'
echo '------------------------------'

echo '-- Start docker compose Up --'
sudo -E docker-compose up -d
echo '-- Finish docker compose Up --'
echo '------------------------------'

if $MIGRATE
then
    echo '-- Start migrate --'
	sudo sudo docker exec -i medic-hospital-app php artisan migrate --force
	echo '-- Finish migrate --'
	echo '------------------------------'
fi

echo '-- Check the App --'
wget -t inf localhost:$PRODUCTION_PORT -O /dev/null
echo '-- Finish check the App --'
echo '------------------------------'
