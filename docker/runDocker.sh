#!/bin/sh

echo $CI_DEPLOY_PASSWORD | sudo docker login -u $CI_DEPLOY_USER --password-stdin $CI_REGISTRY

export PRODUCTION_IMAGE=$PRODUCTION_IMAGE
export PRODUCTION_PORT=$PRODUCTION_PORT

sudo -E docker-compose down
sudo -E docker-compose up -d

if [ $MIGRATE = true ];
then
	sudo docker-compose exec app php artisan migrate
fi

wget -t inf localhost:$PRODUCTION_PORT -O /dev/null
