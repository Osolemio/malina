#!/bin/bash

out=(`mysql -NBu root -pmicroart map -e "SELECT * from data WHERE number=(SELECT MAX(number) from data)"`)

declare -A data_fields
declare -A alias

data_fields['_Uacc']=${out[5]}
data_fields['_UOUTmed']=${out[15]}
data_fields['_MODE']=${out[3]}
data_fields['_Temp_Grad0']=${out[22]}

alias['_Uacc']="Acc voltage "
alias['_UOUTmed']="Output AC "
alias['_MODE']="Input AC "
alias['_Temp_Grad0']="Acc temp "




report_number=`cat /var/map/report_number.dat`

index=(`mysql -NBu root -pmicroart map -e "SELECT MAX(number) from sms_alert"`)

for ((i=1; i<=$index; i++)) do

cmpv=(`mysql -NBu root -pmicroart map -e "SELECT field, le, ge from sms_alert WHERE number=$i"`)
text=`mysql -Nu root -pmicroart map -e "SELECT sms from sms_alert WHERE number=$i"`

result_cmp=(`perl -e "if(${cmpv[1]}==${cmpv[2]}){print 0;}else{print 1;}"`)

if test $result_cmp -eq 0; then
  result=(`perl -e "if(${data_fields[${cmpv[0]}]}==${cmpv[2]}){print 0;}else{print 1;}"`)
 else
  result=(`perl -e "if(${data_fields[${cmpv[0]}]}>=${cmpv[2]} || ${data_fields[${cmpv[0]}]}<=${cmpv[1]}){print 0;}else{print 1;}"`)
fi

if test $result -eq 0; then

 if test ! -e "/var/map/temp_sms_$i"; then
    cur_t=`date`
    echo "To: $report_number" > /var/map/temp_sms_$i
    echo " " >> /var/map/temp_sms_$i
    echo $text >> /var/map/temp_sms_$i
    echo "value: ${data_fields[${cmpv[0]}]} $cur_t" >> /var/map/temp_sms_$i
    tmp_name=`date +%s`
    cp /var/map/temp_sms_$i /var/spool/sms/outgoing/$tmp_name.sms
 fi
 fi

 if test $result -eq 1; then

 if test -e "/var/map/temp_sms_$i"; then
    cur_t=`date`
    rm /var/map/temp_sms_$i
    echo "To: $report_number" > /var/map/temp_sms_ok_$i
    echo " " >> /var/map/temp_sms_ok_$i
    echo "${alias[${cmpv[0]}]} is in range. $cur_t" >> /var/map/temp_sms_ok_$i
    tmp_name=`date +%s`
    mv /var/map/temp_sms_ok_$i /var/spool/sms/outgoing/$tmp_name.sms

 fi
 fi

done
