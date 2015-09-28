/*
 * batmon.c
 *
 *  Release 1.03b
 *  Created on: July 10. 2015.
 *      Author: Osolemio
 */
 
#include <fcntl.h>
#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>
#include <syslog.h>
#include <time.h>
#include <string.h>
#include <./mysql/mysql.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <errno.h>
#include <signal.h>
#include <sys/ipc.h>
#include <sys/shm.h>
#include <math.h>

 #define DAEMON_NAME "Battery monitor daemon"
 #define true 1
 #define false 0
 #define SHARED_MEMORY_KEY 1998
 #define SHARED_MEMORY_KEY_MPP 1999 
 #define SHARED_MEMORY_KEY_BMS 1997 
 
 #define TIME_INTEGRAL 60


struct timeval tv1,tv2,dtv;

float ocv[101], v_bms[32];
float estimated_soc=-1, ah_accumulator=0, U_min_bms_avg=0, U_invalid_min=0, U_invalid_max=0; 
int bms_number=0;

void time_start() { gettimeofday(&tv1, &timezone); }

long time_stop()

{ gettimeofday(&tv2, &timezone);

  dtv.tv_sec= tv2.tv_sec -tv1.tv_sec;
  
    dtv.tv_usec=tv2.tv_usec-tv1.tv_usec;
    
      if(dtv.tv_usec<0) { dtv.tv_sec--; dtv.tv_usec+=1000000; }
      
        return dtv.tv_sec*1000+dtv.tv_usec/1000;
        
        }
//-------------------------------------------------------------------------



void signal_hdl(int sig, siginfo_t *siginfo, void *context)
  {
      struct tm *newtime;

        struct tm tim;
        time_t ltime;



          switch(sig)
          {
                  case SIGTERM:
                  case SIGINT:
                  case SIGQUIT:
		  case SIGHUP:
			time (&ltime);
            		 newtime = localtime (&ltime);
        		tim =*newtime;

                          syslog(LOG_NOTICE,"stopped on signal at %s",asctime(newtime));
                          closelog();
                          exit(0);
                          break;
                  default:
                          syslog(LOG_NOTICE,"got signal %d. ignore...",sig);
                          break;
          }
  }


    int file_exists(char * filename)
    {
       return (access(filename, 0) == 0);
    }


    double Round(double Argument, int Precision)
	{ 
	double div = 1.0;
	if(Precision < 0) while(Precision++) div /= 10.0;
	else while(Precision--) div *= 10.0;
	return floorf(Argument * div + 0.5) / div;
	}

    float ocv_prev_not_null(int index)
	{
	    int i;
	    for (i=index;i<=0;--i)
		if (ocv[i]>0) return ocv[i];
	return 0;

	}
    
    float estimate_SOC(float u_cell)
	{
	 int i, di=0, index1=255, index2=255;
	    if (U_invalid_min!=U_invalid_max)
		{
		    if (u_cell>=U_invalid_min && u_cell<=U_invalid_max) return -1;
		}
	    if (u_cell<=ocv[0]) return 0.0;
	    if (u_cell>=ocv[100]) return 100.0;
	    for (i=100;i>=0;i--)
		{
		if (u_cell<ocv[i] && ocv[i]>0) index2=i; //looking for first enter

		if (u_cell==ocv[i]) 
		    {
		    //if graph has a flat area
		    if (i>0 && u_cell==ocv_prev_not_null(i)) return -1; //unable to estimate
		    return i;
		    }
		}

	    for (i=0;i<=100;i++) 
		{
		 
		 if (u_cell>ocv[i] && ocv[i]>0) index1=i; //looking for last condition
		 
		}
	return Round((ocv[index1]*(index2-index1)-index1*(ocv[index2]-ocv[index1])-(index2-index1)*u_cell)/(ocv[index1]-ocv[index2]),1);;
      } //estimate

    
 void u_min_bms(void) // look for min cell in bms
	{    
	int i=0;
	float v=0;

	for (i=0;i<bms_number;i++) if (v_bms[i]==0 || v_bms[i]>9) return;
	i=0;
	 do {
		    
		    if (v==0) v=v_bms[i]; else
		    if (v>v_bms[i]) v=v_bms[i];
	    } while (++i<bms_number);

	    if (v>0) U_min_bms_avg=(U_min_bms_avg==0)?v:(U_min_bms_avg+v)/2;
	 return;
	}

//-----------------------------MAIN------------------------------------


  int main(int argc, char* argv[])
  {

	struct battery_cycle_struct
	{
	float integral_dCdt;
	float C_current_Ah;
	float C_current_percent;
	float I_avg;
	float user_counter;
	float estimated_SOC;
	};

	struct battery_cycle_struct battery_cycle_prev, battery_cycle_curr;

	struct battery_state_struct
	{
	float deepest_discharge;
	float last_discharge;
	float average_discharge;
	unsigned int discharge_cycles;
	unsigned int full_discharges;
	float summary_ah;
	float lowest_voltage;
	float highest_voltage;
	char last_charge_date[11];
	unsigned int number_autosync;
	float E_summary_to_battery;
	float E_summary_from_battery;    
	float E_from_battery_since_ls;
	float E_alt_daily;
	float E_alt_monthly;    
	float E_alt_summ;
	float E_alt_user;
	};
	    
	struct battery_state_struct battery_state;

	// initializing battery_state
	battery_state.deepest_discharge=0;
	battery_state.last_discharge=0;
	battery_state.average_discharge=0;
	battery_state.discharge_cycles=0;
	battery_state.full_discharges=0;
	battery_state.summary_ah=0;
	battery_state.lowest_voltage=0;
	battery_state.highest_voltage=0;
	sprintf(battery_state.last_charge_date,"2015-01-01");
	battery_state.number_autosync=0;
	battery_state.E_summary_to_battery=0;
	battery_state.E_summary_from_battery=0;    
	battery_state.E_from_battery_since_ls=0;
	battery_state.E_alt_daily=0;
	battery_state.E_alt_monthly=0;    
	battery_state.E_alt_summ=0;
	battery_state.E_alt_user=0;

	struct map_bat
	{
	unsigned char battery_id;
	unsigned long int timestamp;
	float current;
	char tbat;
	float Ubat;
	float Imppt; 
	};
	
	struct map_bat *batmon;
	

	int shm=-1; // shared memory id

	struct mppt_bat
	{
	unsigned char battery_id;
	unsigned long int timestamp;
	float current1;
	float current2;
	float current_ch;
	char tbat;
	float Ubat;
	};
	
	struct mppt_bat *batmon_mpp;
	
	int shm_mpp=-1; //shared memory id
	
	struct bms_struct 
        {
    	unsigned char cell_number;
        float v;
        float i;
        char t;
          };

      struct bms_struct *bms;


	int shm_bms=-1;


	long delay_var;
	int status;
	int pid;	  
	int i;
	char query[255];
   
	float C_curr,dCdt=0, C_measured, C_moment, C_peukert, C_20,
	     dEdt, dEdt_mppt, integral_E_0_t=0, integral_Emppt_0_t=0,
	    integral_C_0_t=0, I_cycle_average, U_batt_average,
	    n_peukert, coulomb_eff, charged_voltage, min_charged_current,
	    alpha=0.002, chg_eff, I_summ=0, I_summ_mppt, u_batt, C_summ=0, E_summ=0, dC_per100,
	    valid_SOC=-1, U_whole_bat_avg, delta_SOC=0, delta_C=0, estimated_C=0;
	unsigned long int time_map=0,time_mpp=0, rest_time_counter=0;   
	unsigned char dt_map=0,dt_mpp=0, percent_bat=100, voltage;
	char t_bat, cells_number, need_sync=1, I_source;
	unsigned int Cn,In,Tn, time_interval=0, rest_time;    
   
   MYSQL mysql;
   MYSQL_ROW row;
   MYSQL_RES *result;
   unsigned int num_fields;
  
  struct tm *newtime;
  
  struct tm tim;
  time_t ltime;

	struct sigaction my_signal;

	bzero(&my_signal, sizeof(my_signal)); 
   

	setlogmask(LOG_UPTO(LOG_NOTICE));
        openlog(DAEMON_NAME, LOG_CONS | LOG_NDELAY | LOG_PERROR | LOG_PID, LOG_USER);
	
	sleep(10); // sleeps 10 sec while mapd and mpptd being started


i=1;

//-----------------Create shared memory segment------------

	if (file_exists("/var/map/.map")) 
	 {
	do {
	shm=shmget(SHARED_MEMORY_KEY, 1024, 0644);
	if (shm==-1)
	    {
	    syslog(LOG_ERR,"Unable to obtain shared memory segment for MAP. %d attempt", i);
	    sleep(10);
	    }
	  } while (shm==-1 && i++<12);

	if (shm==-1) 
	    {
	    syslog(LOG_ERR,"No shared memory segment for MAP available. Error. Stopped.");
		return -1;
	    }

	 shm_bms=shmget(SHARED_MEMORY_KEY_BMS, 1024, 0644);
	if (shm_bms==-1)
	    {
	    syslog(LOG_ERR,"Unable to obtain shared memory segment for BMS.");
	    }
	 }
i=1;
	
	if (file_exists("/var/map/.mppt"))
	 { 
	do {
	shm_mpp=shmget(SHARED_MEMORY_KEY_MPP, 1024, 0644);
	if (shm_mpp==-1)
	    {
	    syslog(LOG_ERR,"Unable to obtain shared memory segment for MPPT. %d attempt", i);
	    sleep(10);
	    }
	 } while (shm_mpp==-1 && i++<12);
	
	    if (shm_mpp==-1) 
	    {
	    syslog(LOG_ERR,"No shared memory segment for MPPT available. Error. Stopped.");
		return -1;
	    }

	} 

	if (shm==-1 && shm_mpp==-1) 
	{
	    syslog(LOG_ERR,"No shared memory segment available. Stopped.");
		return -1;
	}


// map segmant into daemon's address space-----------------

	if (shm!=-1) {
	batmon = shmat(shm,(void *)0,0);
	 if (batmon == (void *)(-1)) 
		    {
	    syslog(LOG_ERR,"Unable get pointer to shared memory segment.");
	    return -1;
	    }
	    }

	if (shm_mpp!=-1) {
    	batmon_mpp = shmat(shm_mpp,(void *)0,0);
	 if (batmon_mpp == (void *)(-1)) 
		    {
	    syslog(LOG_ERR,"Unable get pointer to shared memory segment.");
	    return -1;
	    }
	  }

	if (shm_bms!=-1) {
    	bms = shmat(shm_bms,(void *)0,0);
	 if (bms == (void *)(-1)) 
		    {
	    syslog(LOG_ERR,"Unable get pointer to shared memory segment.");
	    return -1;
	    }
	  }

	bzero(bms,32 * sizeof(struct bms_struct));
	bzero(v_bms,32);
	bzero(ocv,101); // fill OCV array with 0. index - capacity in %. value - voltage. 0 - no data


	mysql.reconnect=true;
	mysql_init(&mysql);  
	if (mysql_real_connect(&mysql,"localhost","monitor","energy","battery",0,NULL,0)==NULL)
    {
    syslog(LOG_ERR, "Can't connect to MySql database"); return -1;
    }
      sprintf(query,"SELECT * FROM battery_info LIMIT 0,1");
     if (mysql_query(&mysql,query)) 
    { syslog(LOG_ERR,"Error SELECT from MySQL battery_info"); return -1;}      
      result=mysql_store_result(&mysql);
	if (result)
	    {
		row=mysql_fetch_row(result);
		cells_number=atoi(row[1]);
		Cn=atoi(row[4]);
		C_measured=atof(row[5]);
		Tn=atoi(row[6]);
		alpha=atof(row[7]);
		n_peukert=atof(row[8]);
		voltage=atoi(row[10]);
		coulomb_eff=atof(row[9]);
		charged_voltage=atof(row[11]);
		min_charged_current=atof(row[12]);
		rest_time=atoi(row[13]);
		U_invalid_min=atof(row[15]);
		U_invalid_max=atof(row[16]);
		I_source=atoi(row[17]);
	    mysql_free_result(result);
	    }
//--------------------------------------------------------------
    if (shm_bms!=-1) bms_number=voltage/3;

//---------------------------------------------------------------
	    sprintf(query,"SELECT * FROM battery_state WHERE number=1");
    	    if (mysql_query(&mysql,query))  
		{ syslog(LOG_ERR,"Error SELECT from MySQL battery_state. Resetting all the variables");
		    } else {
    		    result=mysql_store_result(&mysql);
			if (result)
			    {
				if (row=mysql_fetch_row(result)) {
				
				
				battery_state.deepest_discharge=atof(row[1]);
				battery_state.last_discharge=atof(row[2]);
				battery_state.average_discharge=atof(row[3]);
				battery_state.discharge_cycles=atoi(row[4]);
				battery_state.full_discharges=atoi(row[5]);
				battery_state.summary_ah=atof(row[6]);
				battery_state.lowest_voltage=atof(row[7]);
				battery_state.highest_voltage=atof(row[8]);
//				sprintf(battery_state.last_charge_date,"%s",row[9]);
				battery_state.number_autosync=atoi(row[10]);
				battery_state.E_summary_to_battery=atof(row[11]);
				battery_state.E_summary_from_battery=atof(row[12]);
				battery_state.E_from_battery_since_ls=atof(row[13]);
				battery_state.E_alt_daily=atof(row[14]);
				battery_state.E_alt_monthly=atof(row[15]);
				battery_state.E_alt_summ=atof(row[16]);
				battery_state.E_alt_user=atof(row[17]);
				mysql_free_result(result);
			     }
			    }
			}

//---------------- READ OCV Table --------------------------------


	     sprintf(query,"SELECT * FROM work_table LIMIT 101");
    	    if (mysql_query(&mysql,query)) 
		{ syslog(LOG_ERR,"Error SELECT from MySQL work_table"); return -1;}      
	      result=mysql_store_result(&mysql);
	    
		while (row=mysql_fetch_row(result))
		    {
		ocv[atoi(row[0])]=atof(row[1]);
		    }
		mysql_free_result(result);
	    
//--------------------------------------------------------------


//--------------------------------------------------------------


	pid = fork();
      if (pid == -1)
      {
          syslog(LOG_ERR,"Error: Start Daemon failed (%s)\n");

          return -1;
      }
      else if (!pid)
      {
          umask(0);
          setsid();
          chdir("/");

          close(STDIN_FILENO);
          close(STDOUT_FILENO);
          close(STDERR_FILENO);


//----------------------- signals handler ------------------------------
	 my_signal.sa_sigaction=&signal_hdl;
	 my_signal.sa_flags=SA_SIGINFO;
	    if (sigaction((SIGTERM | SIGHUP | SIGINT), &my_signal, NULL) < 0)
		{
		 syslog(LOG_ERR, "sigaction");
		 return 1;
		 }

        syslog(LOG_NOTICE, "Entering Daemon");
//

time_interval=0;
I_cycle_average=0;
U_batt_average=0;
time(&ltime);
rest_time_counter=ltime;

//-------------------- main cycle -------------------------------
   do {
	battery_cycle_curr.estimated_SOC=-1;

	I_summ=0;
	I_summ_mppt=0;
	
	for (i=0;i<bms_number;i++) v_bms[i]=bms[i].v;
	if (bms_number>0) u_min_bms();


    if (shm!=-1) // MAP Summary current calculation
	{
	 if (I_source==0) 
	  {
	    I_summ+=batmon->Imppt;
	    I_summ_mppt+=batmon->Imppt;
	    }
	 I_summ+=batmon->current;
	 t_bat=batmon->tbat;
	 u_batt=batmon->Ubat;

    
	}

    if (shm_mpp!=-1 && I_source==1) // MPPT Summary current calculation
	{
	 
	 I_summ+=batmon_mpp->current1;
	 I_summ+=batmon_mpp->current2;
	 I_summ+=batmon_mpp->current_ch;
	 I_summ_mppt+=batmon_mpp->current_ch;
	 t_bat=batmon_mpp->tbat;
	 u_batt=batmon_mpp->Ubat;
	}

	if (I_summ>0) //charge acc
	    {
	    time(&ltime);
	    rest_time_counter=ltime; // drop timer if there is any current
	    dCdt=(I_summ/3600)*coulomb_eff;
	    dEdt=I_summ*u_batt/3600;
	    battery_state.E_summary_to_battery+=dEdt;
	    }
	

	if (I_summ<0) //discharge acc
	    {
	    time(&ltime);
	    rest_time_counter=ltime; // drop timer if there is any current
	    dCdt=I_summ/3600;
	    dEdt=fabs(I_summ*u_batt/3600);
	    battery_state.E_summary_from_battery+=dEdt;
	    }

	if (I_summ==0) // zero state;
	    {
	    dCdt=0;dEdt=0;
	    }


        dEdt_mppt=I_summ_mppt*u_batt/3600;
	if (u_batt>battery_state.highest_voltage) battery_state.highest_voltage=u_batt;
	if ((u_batt<battery_state.lowest_voltage) || (battery_state.lowest_voltage==0)) battery_state.lowest_voltage=u_batt;


	integral_C_0_t+=dCdt;
	integral_E_0_t+=dEdt;
	integral_Emppt_0_t+=dEdt_mppt;
	I_cycle_average+=I_summ;
	U_batt_average+=u_batt;
	

//------------------------ each TIME_INTEGRAL doing all the calculatons -----------------
    
	if (++time_interval==TIME_INTEGRAL)
	    {

//--------- read last stored data ------------------------------

		 sprintf(query,"SELECT integral_dCdt, C_current_Ah, C_current_percent, I_avg, user_counter FROM battery_cycle WHERE number=(SELECT MAX(number) from battery_cycle)");
    	        if (mysql_query(&mysql,query)) 
		  syslog(LOG_ERR,"\nError SELECT from MySQL battery_cycle. Battery info will be incorrect\n");     
    		result=mysql_store_result(&mysql);
		    if (result)
			{
			row=mysql_fetch_row(result);
			battery_cycle_prev.integral_dCdt=atof(row[0]);
			battery_cycle_prev.C_current_Ah=atof(row[1]);
			battery_cycle_prev.C_current_percent=atof(row[2]);
			battery_cycle_prev.I_avg=atof(row[3]);
			battery_cycle_prev.user_counter=atof(row[4]);
	    		mysql_free_result(result);
			}
		    else
			{
			battery_cycle_prev.integral_dCdt=0;
			battery_cycle_prev.C_current_Ah=C_measured;
			battery_cycle_prev.C_current_percent=100.0;
			battery_cycle_prev.I_avg=0;
			battery_cycle_prev.user_counter=0;
			}

//------------------------ end of the section ----------------------

//--------------- alt Enery meters in kWh----------------------------------
		    battery_state.E_alt_summ+=integral_Emppt_0_t/1000;
		    battery_state.E_alt_daily+=integral_Emppt_0_t/1000;
		    battery_state.E_alt_monthly+=integral_Emppt_0_t/1000;
		    battery_state.E_alt_user+=integral_Emppt_0_t/1000;
		    
//-------------------------------------------------------------------

		I_cycle_average=Round((I_cycle_average/TIME_INTEGRAL),2);
		U_whole_bat_avg=Round(((U_batt_average/TIME_INTEGRAL)),1);
		U_batt_average=(U_min_bms_avg==0)?Round(((U_batt_average/TIME_INTEGRAL)/cells_number),3):Round(U_min_bms_avg,3);

		
		In=C_measured/Tn; // nominal current 
		C_peukert=powf(In,n_peukert)*Tn; // reduced capacity
		C_moment=C_peukert*(1+alpha*(t_bat-25));
		C_20=powf(C_moment/20,1/n_peukert)*20;
		
		battery_cycle_curr.integral_dCdt=battery_cycle_prev.integral_dCdt+integral_C_0_t;
		if (battery_cycle_curr.integral_dCdt>0) battery_cycle_curr.integral_dCdt=0;
		battery_cycle_curr.user_counter=battery_cycle_prev.user_counter+integral_C_0_t;
		battery_cycle_curr.C_current_Ah=0;

//		if (fabs(battery_cycle_curr.integral_dCdt)>battery_cycle_curr.C_current_Ah) battery_cycle_curr.C_current_Ah=fabs(battery_cycle_curr.integral_dCdt);

//		if (battery_cycle_curr.integral_dCdt==0) battery_cycle_curr.C_current_Ah=C_20;
//-------------------------------------------------------------- 
		if (integral_C_0_t<0) dC_per100=0-(100/(C_moment/(powf(fabs(integral_C_0_t*TIME_INTEGRAL),n_peukert))*60));
//--------------------------------------------------------------
		float C_diff=battery_cycle_curr.integral_dCdt;
		
		if (integral_C_0_t>0) dC_per100=integral_C_0_t/(fabs(battery_cycle_prev.integral_dCdt)/(100-battery_cycle_prev.C_current_percent));

//--------------------------------------------------------------
		if (integral_C_0_t==0) dC_per100=0;
//--------------------------------------------------------------

		battery_cycle_curr.C_current_percent=battery_cycle_prev.C_current_percent+dC_per100;
		if (battery_cycle_curr.C_current_percent>100) battery_cycle_curr.C_current_percent=100;
		if (battery_cycle_curr.C_current_percent<0) battery_cycle_curr.C_current_percent=0;


// trying to estimate %SOC through OCV---------------------------

		time(&ltime);    
		if ((ltime-rest_time_counter) > (rest_time*60)) 
		{
		
		battery_cycle_curr.estimated_SOC=estimate_SOC(U_batt_average);
		
		if (battery_cycle_curr.estimated_SOC>=0)
		    {
		     if (ah_accumulator!=0) 
			{
			    delta_SOC=fabs(valid_SOC-battery_cycle_curr.estimated_SOC);
			    delta_C=fabs(ah_accumulator);
			}

		     valid_SOC=battery_cycle_curr.estimated_SOC;
		     ah_accumulator=0;
		    }
		}

// ------- calculate precise %SOC after battery rest time

		

//------------------------------------------------------------

		battery_cycle_curr.I_avg=I_cycle_average;

//------------- auto sync on 100% -------------------------------

	if ((battery_cycle_curr.I_avg>0) && (battery_cycle_prev.I_avg>0)) //last 2 min charge mode
		    {
		
		if ((battery_cycle_curr.I_avg<(min_charged_current*C_measured)) && (battery_cycle_prev.I_avg<(min_charged_current*C_measured)) && (U_whole_bat_avg>=charged_voltage ))
		      {
			battery_cycle_curr.C_current_percent=100; // drop all counters
			battery_cycle_curr.integral_dCdt=0;
			
			if (ah_accumulator!=0) 
			{
			    delta_SOC=fabs(valid_SOC-100);
			    delta_C=fabs(ah_accumulator);
			}
			valid_SOC=100;
			ah_accumulator=0;
//-------------------------- add statistics -----------------------------------------
			time (&ltime);
        		newtime = localtime (&ltime);
        		tim = *newtime;
			
			battery_state.number_autosync++;
			sprintf(battery_state.last_charge_date,"%d-%d-%d",tim.tm_year+1900,tim.tm_mon+1,tim.tm_mday);
		      }
		    }
//--------------------------drop integral_dCdt if battery is charged on 100% -------------------------------------

		if (C_diff==0) { battery_cycle_curr.C_current_percent=100;}

// count Ah after succesful SOC sync---------------------------

		if (valid_SOC>=0 && valid_SOC<100) ah_accumulator+=integral_C_0_t;


// -------------------------------add some statistics ------------------------------------------------------------

		if (battery_state.deepest_discharge<(100-battery_cycle_curr.C_current_percent))
			battery_state.deepest_discharge=100-battery_cycle_curr.C_current_percent;

//---------------------------------------------------------------------------------------
		  time (&ltime);
        	  newtime = localtime (&ltime);
        	  tim = *newtime;

	         sprintf(query, "INSERT INTO battery_cycle VALUES (NULL,'%d-%d-%d','%d:%d:%d','%.3f','%.3f','%.3f','%.2f','%.3f','%.1f','%.2f','%d')",
                 tim.tm_year+1900,tim.tm_mon+1,tim.tm_mday,tim.tm_hour,tim.tm_min,tim.tm_sec,
                 battery_cycle_curr.integral_dCdt, battery_cycle_curr.C_current_Ah,
		 battery_cycle_curr.C_current_percent, battery_cycle_curr.I_avg,
		 battery_cycle_curr.user_counter, battery_cycle_curr.estimated_SOC, ah_accumulator, ltime-rest_time_counter);
    		    
		    if (mysql_query(&mysql,query)) { syslog(LOG_ERR,"\nError adding in MySQL - battery_cycle\n");}

		sprintf(query, "TRUNCATE TABLE battery_state");
		    if (mysql_query(&mysql,query)) { syslog(LOG_ERR,"\nError truncate MySQL table - battery_state\n");} else {

		sprintf(query, "INSERT INTO battery_state VALUES ('1','%.1f','%.1f','%.1f','%d','%d','%.1f','%.2f','%.2f','%s','%d','%.1f','%.1f','%.1f','%.3f','%.2f','%.1f','%.3f')",
		battery_state.deepest_discharge,battery_state.last_discharge,battery_state.average_discharge,battery_state.discharge_cycles,
		battery_state.full_discharges,battery_state.summary_ah,battery_state.lowest_voltage,battery_state.highest_voltage,
		battery_state.last_charge_date,battery_state.number_autosync,battery_state.E_summary_to_battery,battery_state.E_summary_from_battery,    
		battery_state.E_from_battery_since_ls,battery_state.E_alt_daily,battery_state.E_alt_monthly,battery_state.E_alt_summ,battery_state.E_alt_user);

		   if (mysql_query(&mysql,query)) { syslog(LOG_ERR,"\nError adding in MySQL - battery_state\n");}
		}

//--------------------Calculate estimated nominal C -----------------------		

		    if (delta_SOC!=0) estimated_C=(100*delta_C)/delta_SOC;

//-----------------Store it in the table if it has a value-------------------------		
		    if (estimated_C>0)
		     {
			sprintf(query, "INSERT INTO estimate VALUES (NULL,'%d-%d-%d', '%d:%d:%d','%.1f','%.1f','%.1f')",
                	tim.tm_year+1900,tim.tm_mon+1,tim.tm_mday,tim.tm_hour,tim.tm_min,tim.tm_sec,
                	delta_SOC, delta_C, estimated_C);
		    if (mysql_query(&mysql,query)) { syslog(LOG_ERR,"\nError adding in MySQL - estimate\n");}

		 estimated_C=0;
		    }
//----------------------drop all the cycle variables ----------------------------

		time_interval=0;
		integral_C_0_t=0;
		integral_E_0_t=0;
		integral_Emppt_0_t=0;
		I_cycle_average=0;
		U_batt_average=0;
		U_min_bms_avg=0;
		delta_C=0;
		delta_SOC=0;
//----------------------drop E counters----------------------------------------------------------
		if (tim.tm_mday==1 && tim.tm_hour==0) battery_state.E_alt_monthly=0;
		if (tim.tm_hour==0) battery_state.E_alt_daily=0;
		if (file_exists("/var/map/.eureset")) 
		{
		    battery_state.E_alt_user=0;
		    unlink("/var/map/.eureset");
		}
		
//-----check whether battery settings have been changed and reload them if so

		if (file_exists("/var/map/.bset"))
		 {
		    unlink("/var/map/.bset");

//---------------- READ OCV Table --------------------------------


	     sprintf(query,"SELECT * FROM work_table LIMIT 100");
    	
	    if (mysql_query(&mysql,query)) 
		{ 
		syslog(LOG_ERR,"Error SELECT from MySQL work_table. Can't reload settings\n");
		}
		 else
		{      
	    	result=mysql_store_result(&mysql);
	    
		while (row=mysql_fetch_row(result))
		    {
		ocv[atoi(row[0])]=atof(row[1]);
		    }
		syslog(LOG_NOTICE,"OCV table has been reloaded");     
		mysql_free_result(result);
		    }
	    
//------------------READ MAIN SETTINGS--------------------------------------------

		sprintf(query,"SELECT * FROM battery_info LIMIT 0,1");
    	    if (mysql_query(&mysql,query)) 
	        { 
	    syslog(LOG_ERR,"Error SELECT from MySQL battery_info. Can't reload seettings");
		} 
		else 
		{     
    	    result=mysql_store_result(&mysql);
	    if (result)
		{
		row=mysql_fetch_row(result);
		cells_number=atoi(row[1]);
		Cn=atoi(row[4]);
		C_measured=atof(row[5]);
		Tn=atoi(row[6]);
		alpha=atof(row[7]);
		n_peukert=atof(row[8]);
		voltage=atoi(row[10]);
		coulomb_eff=atof(row[9]);
		charged_voltage=atof(row[11]);
		min_charged_current=atof(row[12]);
		rest_time=atoi(row[13]);
		U_invalid_min=atof(row[15]);
		U_invalid_max=atof(row[16]);
		I_source=atoi(row[17]);
		syslog(LOG_NOTICE,"Battery settings have been reloaded");

	    mysql_free_result(result);
		}
//--------------------------------------------------------------
	    if (shm_bms!=-1) bms_number=voltage/3;
	    }



//---------------------------------------------------------------------
		}
//----------------------------------------------
		usleep(930000); //less sleep if there were DB transactions
	    }
	else usleep(1000000); //more sleep if no DB transactions
    
    } while (1); //do

    mysql_close(&mysql);
    closelog(); 
 return 0;
 
 } // fork
      else
      {
     return 0;
      }

  }


