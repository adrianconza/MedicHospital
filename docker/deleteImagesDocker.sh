#!/bin/sh

IMAGES=$(sudo docker images registry.gitlab.com/adrianconza/medic-hospital --format "{{.ID}}")
echo '-- Images docker --'
echo $IMAGES
echo '------------------------------'

if [ ! -z $IMAGES ];
then
	sudo docker rmi -f $IMAGES
fi
