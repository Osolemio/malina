#!/bin/bash
#
if [ $# -eq 0 ]; then 
    exit;
    fi
touch /var/map/.lock_mysql
mysqldump -u root -pmicroart -A > $1
sync
rm /var/map/.lock_mysql

