#include <stdio.h>
#include <string.h>


void print_str(char *cp)
{
	printf("str : %s\n", cp);
	while(*cp != '\0')
	{
			printf("%c\n", *cp);
			cp ++;
	}
}


int main(int arg, int **argv)
{
	char str[14] = "Hello World!\n";
	print_str(str);
#if 0
	int in = 12345678;	
	char ch = 'A';  //8bit : -128~127
	short sh = 1234;
	int in = 12345678;
	long lo = 1;
	long long ll = 2;
	float fl = 3.14;
	double du = 1.234;


	printf("&in : %p\n", &in);
	printf("&ch : %p\n", &ch);
	printf("&sh:  %p\n", &sh);
	printf("&lo : %p\n", &lo);
	printf("&ll:  %p\n", &ll);
	printf("&fl : %p\n", &fl);
	printf("&du : %p\n", &du);
	printf("Hello World\n");
	printf("%p\n","Hello World!\n");
	printf("%p\n","Hello World!\n");
	printf("%c\n","Hello World!\n"[0]);


	char str[20] = "Hello World!\n";
	printf("%p\n", "Hello World!\n");
	printf("str: %p\n", str);
	printf("str: %s\n", str);
	
	//str = "hi";
	str[0] = 'h';
	str[1] = 'i';
	str[2] = '0';
	printf("%s\n",str);
	strcpy(str,"hi");
	//strcpy_user(str,"hi");
	//void strcpy_user(str,);


	printf("%s\n",str);





	char *sp;
	sp = "hi";
	printf("%s\n",sp);
#endif

	return 0;
}
