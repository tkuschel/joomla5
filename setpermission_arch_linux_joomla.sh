#!/bin/bash

echo "Set Permission of JOOMLA5"
sudo chown -R http:http . 
sudo find . -type d -exec chmod 0770 {} \;
sudo find . -type f -exec chmod 0660 {} \;

