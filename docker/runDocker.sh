#!/bin/sh

echo $CI_DEPLOY_PASSWORD | sudo docker login -u $CI_DEPLOY_USER --password-stdin $CI_REGISTRY

export PRODUCTION_IMAGE=$PRODUCTION_IMAGE
export PRODUCTION_PORT=$PRODUCTION_PORT

sudo -E docker-compose down
echo 'Finish docker compose Down'
echo ''

sudo -E docker-compose up -d
echo 'Finish docker compose Up'
echo ''

if $MIGRATE
then
    echo 'Start migrate'
	sudo docker-compose exec app php artisan migrate
	echo 'Finish migrate'
	echo ''
fi

wget -t inf localhost:$PRODUCTION_PORT -O /dev/null
