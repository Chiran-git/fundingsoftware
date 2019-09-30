#!/bin/bash
# Delete all contents of web root
cd /var/www/html
sudo rm -rf *
sudo rm -rf .[a-zA-Z_-]*
#