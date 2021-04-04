#!/bin/sh

IMAGES=$(sudo docker images registry.gitlab.com/adrianconza/medic-hospital --format "{{.ID}}")
echo '-- Images docker --'
echo $IMAGES
echo '------------------------------'

if [ ! -z $IMAGES ];
then
	sudo docker rmi -f $IMAGES
fi

VOLUMES=$(docker volume ls -q)
echo '-- Volumes docker --'
echo $VOLUMES
echo '------------------------------'
if [ ! -z $VOLUMES ];
then
	sudo docker volume rm $VOLUMES
fi
