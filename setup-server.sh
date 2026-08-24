#!/bin/bash
set -e

./setup_network.sh
./install-printer-management.sh
./install.sh

docker restart $(docker ps -a -q)