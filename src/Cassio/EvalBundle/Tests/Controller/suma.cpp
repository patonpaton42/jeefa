#include <iostream>
#include <stdlib.h>

using namespace std;

int main(int argc, char *argv[])
{
  int n1, n2, res;

  if(argc != 3){
    cout << "faltan o hay muchos argumentos: " << argc;
    exit(1);
  }

  n1 = atoi(argv[1]);
  n2 = atoi(argv[2]);

  res = n1 + n2; 

  if(n2 < 0)
  {
    cout << n1 <<"+("<< n2 <<")="<< res;
    //printf("%d+(%d)=%d", n1, n2, res);  
  }else
  {
    cout << n1 <<"+"<< n2 <<"="<< res;
    //printf("%d+%d=%d", n1, n2, res);  
  }

  return 0;
}
