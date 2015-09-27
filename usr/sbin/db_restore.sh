#!/bin/bash
#
if [ $# -eq 0 ]; then 
    exit;
    fi
touch /var/map/.lock_mysql
mysql -u root -pmicroart < $1
sync
rm /var/map/.lock_mysql
