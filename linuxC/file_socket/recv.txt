#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <arpa/inet.h>
#include <sys/socket.h>

#include <fcntl.h>


#define BUF_SIZE 100


void error_handling(char *message);

int main(int argc, char* argv[])
{
	int sock;
	struct sockaddr_in serv_addr;
	char message[30];
	int str_len;
	//
	int fd, read_cnt;
	char buf[BUF_SIZE];


	if(argc!=4){
		printf("Usage : %s <IP> <port> <file>\n", argv[0]);
		exit(1);
	}
    //

	char *send_filename =argv[3];

	sock=socket(PF_INET, SOCK_STREAM, IPPROTO_TCP);
	if(sock < 0)
		error_handling("socket() error");

	memset(&serv_addr, 0, sizeof(serv_addr));
	serv_addr.sin_family=AF_INET;
	serv_addr.sin_addr.s_addr=inet_addr(argv[1]);
	serv_addr.sin_port=htons(atoi(argv[2]));

	if(connect(sock, (struct sockaddr*)&serv_addr, sizeof(serv_addr)) < 0) 
		error_handling("connect() error!");

	str_len=read(sock, message, sizeof(message)-1);
	if(str_len < 0)
		error_handling("read() error!");

	message[str_len] = '\0';
	printf("Message from server: %s \n", message);  


//	
	fd = open(send_filename, O_RDONLY);
	if(fd == -1){
			perror("open() send file error");
			close(sock);
			return 1;
	}


do {
		read_cnt = read(fd, buf, sizeof(buf));
		printf("read:%d\n",read_cnt);
		if(read_cnt>0)
			write(sock, buf, read_cnt);
		else if(read_cnt==0)
		{
			fputs("Done ... \n",stdout);
			break;
		}
		else
		{
			perror("read()");
			break;
		}
	} while(1);
//


	//file close
	close(fd);
	close(sock);
	return 0;
}

void error_handling(char *message)
{
	fputs(message, stderr);
	fputc('\n', stderr);
	exit(1);
}
