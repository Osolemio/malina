#!/bin/bash

COMMAND_CHAR="#"
ALLOW_PHONES=`cat /var/map/allowed_numbers.dat`
SEND_BACK_REPORT=YES
declare -A mode
declare -A to_map
mode['0']="MAP is off. No input AC"
mode['1']="MAP is off. AC in"
mode['2']="MAP is ON. No AC in. Generation"
mode['3']="MAP is ON. Relay"
mode['4']="MAP is ON. Relay+charge"
mode['10']="Forced generation"
mode['11']="Max tarif. Forced generation"
mode['12']="Min tarif"
mode['13']="Relay. ECO Injection"
mode['14']="Relay. Sell to network"
mode['15']="Waiting for an externall full charge"
mode['16']="Tarif zone. Relay+ECO injection"
mode['17']="Tarif zone. Relay+Sale"


to_map['stop']='\xFF\x00\x00\x01'
to_map['start']='\xFF\x00\x00\x02'
to_map['charge_off']='\xFF\x00\x00\x04'
to_map['charge_on']='\xFF\x00\x00\x05'


[ "$1" = "RECEIVED" ] || exit 0

to_log(){
    text=$1
    export LANG=en_EN
    log_date=`date "+%b %d %H:%M:%S "`
    log_host=`hostname -s`
    echo "$log_date $log_host $text" >> /var/log/smsctrl.log
}

File=$2

Allow=0
for Phone in $ALLOW_PHONES; do
    cat $File | grep "From: $Phone" > /dev/null 2>&1
    [ $? -eq 0 ] && Allow=1
done;

[ $Allow -eq 0 ] && exit 0

cat $INCOMING_DIR/$File | grep "$COMMAND_CHAR"
[ $? -ne 0 ] && exit 0

FromPhone=`cat $File | grep "From:" | cut -d " " -f2`

command=`cat $File | grep "$COMMAND_CHAR" | cut -d "$COMMAND_CHAR" -f2`
to_log "Incoming command: $command from $FromPhone"
#$command`

if [ "$command" = "report" ]; then
  out=(`mysql -NBu root -pmicroart map -e "SELECT * from data WHERE number=(SELECT MAX(number) from data)"`)
  SEND_BACK_REPORT="NO"

  enet=`perl -e "print ${out[27]}/100;"`
  eacc=`perl -e "print ${out[28]}/100;"`
  eacc_chg=`perl -e "print ${out[29]}/100;"`

  sendsms $FromPhone "${out[2]}. ${mode[${out[3]}]}, Uacc:${out[5]}V, Unet:${out[10]}V, Uout:${out[15]}V, AccTemp:${out[22]}C, Pnet:${out[12]}W, Pacc:${out[7]}W, Enet:${enet}kWh, Eacc:${eacc}kWh, Eacc_charge:${eacc_chg}kWh"
  to_log "Sent report to $FromPhone: $out"
  
fi

if [ ${to_map[$command]} ]; then

    echo -ne ${to_map[$command]} > /var/map/to_map

fi



if [ "$SEND_BACK_REPORT" = "YES" ]; then
    sendsms $FromPhone "$command received"
    to_log "Sent sms to $FromPhone: $out"
fi
rm -f $File
to_log "Deleting file $File"