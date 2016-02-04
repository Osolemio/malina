#!/bin/bash

out=(`mysql -NBu root -pmicroart map -e "SELECT * from data WHERE number=(SELECT MAX(number) from data)"`)

declare -A data_fields
declare -A alias
declare -A min
declare -A max


#here you have to define fields to check, min & max values, their aliases for mail, your email, items number.

#_Uacc
data_fields[1]=${out[5]}
#_UOUTmed
data_fields[2]=${out[15]}
#_MODE
data_fields[3]=${out[3]}
#_Temp_Grad0
data_fields[4]=${out[22]}

alias[1]="Acc voltage "
alias[2]="Output AC "
alias[3]="Input AC "
alias[4]="Acc temp "

min[1]=44.0
min[2]=200
min[3]=200
min[4]=3

max[1]=59.0
max[2]=250
max[3]=250
max[4]=30

# Number of items
index=4


mail_recipient="ivan_belarus@inbox.ru"
from_email="microart_test@mail.ru"

for ((i=1; i<=$index; i++)) do

  result=(`perl -e "if(${data_fields[$index]}>=${max[$index]} || ${data_fields[$index]}<=${min[$index]}){print 0;}else{print 1;}"`)

if test $result -eq 0; then

 if test ! -e "/var/map/temp_email_$i"; then
    cur_t=`date`
    echo "To: $mail_recipient" > /var/map/temp_email_$i
    echo "From: $from_email" > /var/map/temp_email_$i
    echo "Subject: email from MAP" > /var/map/temp_email_$i
    echo " " >> /var/map/temp_email_$i
    echo $text >> /var/map/temp_email_$i
    echo "value: ${data_fields[$index]} $cur_t" >> /var/map/temp_email_$i
    tmp_name=`date +%s`
    /usr/sbin/ssmtp -t < /var/map/temp_email_$i
 fi
 fi

 if test $result -eq 1; then

 if test -e "/var/map/temp_email_$i"; then
    cur_t=`date`
    rm /var/map/temp_email_$i
    echo "To: $mail_recipient" > /var/map/temp_email_ok_$i
    echo "From: $from_email" > /var/map/temp_email_ok_$i
    echo "Subject: email from MAP" > /var/map/temp_email_ok_$i
    echo " " >> /var/map/temp_email_ok_$i
    echo $text >> /var/map/temp_email_ok_$i
    echo "value: ${alias[$index]} is in range $cur_t" >> /var/map/temp_email_ok_$i
    tmp_name=`date +%s`
    /usr/sbin/ssmtp -t < /var/map/temp_email_ok_$i
    rm /var/map/temp_email_ok_$i
 fi
 fi

done
