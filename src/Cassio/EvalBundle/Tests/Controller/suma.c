#include <stdio.h>
#include <unistd.h>

int main(int argc, char *argv[])
{
  int n1, n2, res;

 
  scanf("%d %d", &n1, &n2);
  do{
    res = n1 + n2; 
    printf("%d\n", res);
    scanf("%d %d", &n1, &n2);
  }while(n1 != 0 && n2 != 0);

  return 0;
}
