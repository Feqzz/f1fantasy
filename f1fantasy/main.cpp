#include <tinyxml2.h>
#include <curl/curl.h>
#include <iostream>
#include "getSite.h"



int main()
{
	getSite s;
	s.fetchToFile("https://ergast.com/api/f1/2019/6/results", "output.txt");
}