/*
 * mpptd.c
 *  for EP-Tracer. Network version
 *  Release 1.01b
 *  Created on: October 12. 2015.
 *      Author: Osolemio
 */
 
#include <fcntl.h>
#include <unistd.h>
#include <termios.h>
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
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <netdb.h>

 #define host_ip "192.168.6.10"
 #define http_port "80"
 #define ID "1"
 #define DAEMON_NAME "MPPT-EP Solar Network Reader daemon"
 #define size_of_buffer 100 
 #define true 1
 #define false 0
 #define SHARED_MEMORY_KEY 1999
 
extern errno;

unsigned char Buffer[size_of_buffer];

unsigned char sum;

struct timeval tv1,tv2,dtv;



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
//-----------------------------Network functions ----------------------

int connectsock(const char *host, const char *port, const char *transport)
{
    struct hostent *phe;
    struct servent *pse;
    struct protoent *ppe;
    struct sockaddr_in sin;
    int s, type;


    memset(&sin, 0, sizeof(sin));
    sin.sin_family = AF_INET;
    sin.sin_port = htons((unsigned short)atoi(port)); 	
    if(phe = gethostbyname(host))
	memcpy(&sin.sin_addr, phe->h_addr, phe->h_length);
    if((ppe = getprotobyname(transport)) == 0)
	{
	    printf("    : %s\n", strerror(errno));	
	    return -1;			
	}	
    if(strcmp(transport, "udp") == 0)
	type = SOCK_DGRAM;
    else
	type = SOCK_STREAM;			
    s = socket(PF_INET, type, ppe->p_proto);
    if(s < 0)
	{
	    printf("  : %s\n", strerror(errno));	 
	    return -1;
	}
    if(connect(s, (struct sockaddr *)&sin, sizeof(sin)) < 0)
	{
	    printf("    : %s\n", strerror(errno));	
	    return -1;			
	}
    return s;
}


//-----------------------------MAIN------------------------------------


  int main(int argc, char* argv[])
  {
   
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
	
	struct mppt_bat *batmon;
	
	int shm;
	int sock;
	char *parse_buf;
	char parsed_arr[12][20];


	long delay_var;
	int status;
	int pid;	  
	int i;
	char query[355];
   
   
   
   MYSQL mysql;
   MYSQL_ROW row;
   MYSQL_RES *res;
 
  
  struct termios port_cfg; 
   
  struct tm *newtime;
  
  struct tm tim;
  time_t ltime;

	struct sigaction my_signal;

	bzero(&my_signal, sizeof(my_signal)); 
   
   struct mppt_info {
	   float		Vc_PV;
	   float		Ic_PV;
	   float		V_Bat;
	   unsigned	int	P_PV;
	   unsigned	int	P_Out;
	   unsigned	int	P_Load;
	   unsigned	int	P_curr ;
	   float		I_Ch;
	   float		I_Out;
	   signed  char		Temp_Int;
	   signed  char		Temp_Bat;
           float	        Pwr_kW;
	   unsigned	char 	Sign_C0;
	   unsigned	char 	Sign_C1;
	   unsigned	int	I_EXTS0;
	   unsigned	int	I_EXTS1;
	   unsigned	int	P_EXTS0;
	   unsigned	int	P_EXTS1;
	   unsigned 	char 	Relay_C;
	   unsigned	char	RSErrSis;
	   char		 	Mode;
	   char 		Sign;
	   char			MPP;
	   unsigned 	int	windspeed;
   };

   struct mppt_info mppt_data;

		setlogmask(LOG_UPTO(LOG_NOTICE));
        openlog(DAEMON_NAME, LOG_CONS | LOG_NDELAY | LOG_PERROR | LOG_PID, LOG_USER);

        syslog(LOG_NOTICE, "Entering Daemon");

/*

	mysql.reconnect=true;
	mysql_init(&mysql);  
	if (mysql_real_connect(&mysql,"localhost","monitor","energy","map",0,NULL,0)==NULL)
    {
    syslog(LOG_ERR, "Can't connect to MySql database"); return -1;
    }

	pid = fork();
      if (pid == -1)
      {
          syslog(LOG_INFO,"Error: Start Daemon failed (%s)\n");

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
*/

/*
//----------------------- signals handler ------------------------------
	 my_signal.sa_sigaction=&signal_hdl;
	 my_signal.sa_flags=SA_SIGINFO;
	    if (sigaction((SIGTERM | SIGHUP | SIGINT), &my_signal, NULL) < 0)
		{
		 syslog(LOG_ERR, "sigaction");
		 return 1;
		 }

//-----------------Create shared memory segment------------

	shm=shmget(SHARED_MEMORY_KEY, 1024, 0644 | IPC_CREAT);
	if (shm==-1)
	    {
	    syslog(LOG_ERR,"Unable to create shared memory segment.");
	    return -1;
	    }

// map segmant into daemon's address space-----------------


	batmon = shmat(shm,(void *)0,0);
	 if (batmon == (void *)(-1)) 
		    {
	    syslog(LOG_ERR,"Unable get pointer to shared memory segment.");
	    return -1;
	    }

	     batmon->battery_id=1; //default value

//-------------------- main cycle -------------------------------
*/

   do {
  


	sock = connectsock(host_ip, http_port, "tcp");
	if(sock < 0)
	    return -1;
	else 
	    {
		sprintf(Buffer, "GET /RTMonitor?id=%s HTTP/1.0\r\nHost:%s\r\n",ID,host_ip);

		if(write(sock, Buffer, sizeof(Buffer)) > 0)
		 {
		bzero(Buffer, size_of_buffer);
		if(read(sock, Buffer, sizeof(Buffer)) > 0)
		    {
			    i=0;
			    parse_buf=strtok(Buffer,":");
			    while (parse_buf!=NULL) 
			    {
				sprintf(parsed_arr[i++],"%s",parse_buf);
				parse_buf=strtok(NULL,":");

			    }
			mppt_data.Vc_PV=atof(parsed_arr[0]);
			
			mppt_data.V_Bat=atof(parsed_arr[0]);
			mppt_data.P_PV=atof(parsed_arr[3]);
			mppt_data.Ic_PV=(mppt_data.Vc_PV>0)?mppt_data.P_PV/mppt_data.Vc_PV:0;
			mppt_data.P_Out=atof(parsed_arr[3]);
			mppt_data.P_Load=atof(parsed_arr[3]);
			mppt_data.P_curr=atof(parsed_arr[3]);
			mppt_data.I_Ch=atof(parsed_arr[2]);
			mppt_data.I_Out=atof(parsed_arr[2]);
			mppt_data.Temp_Int=atof(parsed_arr[11]);
			mppt_data.Temp_Bat=atof(parsed_arr[10]);
			mppt_data.Pwr_kW=atoi(parsed_arr[4]);
			mppt_data.Sign_C0=0;
			mppt_data.Sign_C1=0;
			mppt_data.I_EXTS0=0;
			mppt_data.I_EXTS1=0;
			mppt_data.P_EXTS0=0;
			mppt_data.P_EXTS1=0;
			mppt_data.Relay_C=0;
			mppt_data.RSErrSis=0;
			mppt_data.Mode='E';
			mppt_data.Sign='P';
			mppt_data.MPP='-';
			mppt_data.windspeed=65535;

	    /* Get the time in seconds */
	     time(&ltime);
            /* Convert it to the structure tm */
             newtime = localtime(&ltime);
	     tim=*newtime;


			 sprintf(query, "INSERT INTO mppt VALUES (NULL,'%d-%d-%d', '%d:%d:%d','%.1f','%.1f','%.1f','%d','%d','%d','%d','%.1f','%.1f','%d','%d','%.3f','%d','%d','%d','%d','%d','%d','%d','%d','%c','%c','%c','%d')",
			tim.tm_year+1900,tim.tm_mon+1,tim.tm_mday,tim.tm_hour,tim.tm_min,
			tim.tm_sec,mppt_data.Vc_PV,mppt_data.Ic_PV,mppt_data.V_Bat,
			mppt_data.P_PV,mppt_data.P_Out,mppt_data.P_Load,mppt_data.P_curr,
			mppt_data.I_Ch,mppt_data.I_Out,mppt_data.Temp_Int,mppt_data.Temp_Bat,
			mppt_data.Pwr_kW,mppt_data.Sign_C0,mppt_data.Sign_C1,mppt_data.I_EXTS0,
			mppt_data.I_EXTS1,mppt_data.P_EXTS0,mppt_data.P_EXTS1,mppt_data.Relay_C,
			mppt_data.RSErrSis,mppt_data.Mode, mppt_data.Sign,mppt_data.MPP,mppt_data.windspeed);

		    }
		}    
	    close(sock);
	    }
	  usleep(900000);
	} while (true);
/*


/*        
	if (mysql_query(&mysql,query)) { syslog(LOG_ERR,"\nError adding in MySQL\n"); return -1;}
	    
	bzero(Buffer,size_of_buffer);          
	
//----------------- drop kWh counter everyday at 23:30 ----------------

// mysql_close(&mysql);
 closelog(); 
 return 0;
 
 }
/*
      else
      {
     return 0;
      }
  }

*/
}