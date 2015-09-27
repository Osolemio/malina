#!/bin/bash

# write here consumed ah or (and) dod when server_off.sh will be called
server_off_ah=0
server_off_dod=0
#########################################################################

declare -A to_map

if /bin/pidof mapd 1>/dev/null
     then
     
if /bin/pidof batmon 1>/dev/null
     then
    

out=(`mysql -NBu root -pmicroart map -e "SELECT * from data WHERE number=(SELECT MAX(number) from data)"`)
mode=${out[3]}

if [ $mode -eq 2 ]; then

out1=(`mysql -NBu root -pmicroart battery -e "SELECT dod_off, ah_off from battery_info WHERE id=0"`)
out2=(`mysql -NBu root -pmicroart battery -e "SELECT ROUND(C_current_percent,0), ROUND(integral_dCdt,0) from battery_cycle WHERE number=(SELECT MAX(number) from battery_cycle)"`)


let percent=100-${out2[0]}
let ah=0-${out2[1]}

dod_off=${out1[0]}
ah_off=${out1[1]}

flag=0
to_map['stop']='\xFF\x00\x00\x01'

    if [ $percent -gt $dod_off ] && [ $dod_off -gt 0 ]; then

     flag=1
    fi 

    if [ $ah -gt $ah_off ] && [ $ah_off -gt 0 ]; then

     flag=1
    fi 

    if [ $flag -eq 1 ]; then
	echo -ne ${to_map['stop']} > /var/map/to_map
	fi

flag_server=0

#--------------------- servers shutdows script call

    if [ $percent -gt $server_off_dod ] && [ $server_off_dod -gt 0 ]; then

     flag_server=1
    fi 

    if [ $ah -gt $server_off_ah ] && [ $server_off_ah -gt 0 ]; then

     flag_server=1
    fi 

    if [ $flag_server -eq 1 ]; then
	/usr/sbin/server_off.sh 1>/dev/null 2>/dev/null
	touch /var/map/.lock_server
	fi
    
    if [ $flag_server -eq 0 ]; then
	rm /var/map/.lock_server
	fi





fi
fi
fi

