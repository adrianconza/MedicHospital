#!/bin/sh

IMAGES=$(sudo docker images registry.gitlab.com/adrianconza/medic-hospital --format "{{.ID}}")
echo $IMAGES

if [ ! -z $IMAGES ];
then
	sudo docker rmi -f $IMAGES
fi
