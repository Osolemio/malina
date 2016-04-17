#!/bin/bash


 if test -e "/microart/monitor.ini"; then
    pi_mode=`cat /microart/monitor.ini | grep -v '^#' | grep 'mode' | cut -f2 -d=`
    echo $pi_mode
    if [ ${pi_mode:='enabled'} == 'restricted' ]; then
	touch /var/map/.restricted
	else
	rm /var/map/.restricted;
    fi
  fi


 if test -e "/microart/monitor.ini"; then
    ip_mode=`cat /microart/monitor.ini | grep -v '^#' | grep 'ip' | cut -f2 -d=`
    echo $ip_mode
    if [ $ip_mode == 'auto' ]; then
	cp /etc/network/interfaces_dhcp /etc/network/interfaces
	
	fi
    if [ $ip_mode == 'default' ]; then
	cp /etc/network/interfaces_static /etc/network/interfaces
	ifdown eth0 && ifup eth0
    fi
    
  fi

if test -e "/microart/monitor.ini"; then
    lang_mode=`cat /microart/monitor.ini | grep -v '^#' | grep 'language' | cut -f2 -d=`
    echo $lang_mode
    if [ $lang_mode == 'RU' ]; then
	cp /var/www/html/local/RU/* /var/www/html/local
	fi
    if [ $lang_mode == 'ENG' ]; then
	cp /var/www/html/local/EN/* /var/www/html/local
    fi
  fi

if test -e "/microart/monitor.ini"; then
    pass_mode=`cat /microart/monitor.ini | grep -v '^#' | grep 'protected' | cut -f2 -d=`
    echo $pass_mode
    if [ $pass_mode == 'system' ]; then
	cp /etc/nginx/sites-available/default_setup /etc/nginx/sites-available/default
	service nginx restart
	fi
    if [ $pass_mode == 'all' ]; then
	cp /etc/nginx/sites-available/default_all /etc/nginx/sites-available/default
	service nginx restart
    fi
  fi





if test -e "/var/update/update.tar"; then 
    echo "Some update is availavle and being unpacked....."
    tar -xf /var/update/update.tar -C /var/update/
      if test $? -eq 0; then echo "Update unpacked ok" 
        else "Update unpacked with errors. Update failed"
	exit 1;
	    fi
        if test -e "/var/update/main_script.sh"; then

	    echo  "update script is in /var/update/  Start updating"
	    chmod +x /var/update/main_script.sh
	    chown root:root /var/update/main_script.sh
	    /var/update/main_script.sh
	    rm  -r /var/update/*
	fi

else
    echo "No updates available";
    exit 0;    
fi
