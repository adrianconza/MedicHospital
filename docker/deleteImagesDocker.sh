#!/bin/sh
sudo docker rmi -f $(sudo docker images registry.gitlab.com/adrianconza/medic-hospital --format "{{.ID}}")
