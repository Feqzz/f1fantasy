#pragma once
#include <tinyxml2.h>
#include <curl/curl.h>
#include <iostream>
#include <vector>
#include "race.h"

#pragma warning(disable:4996)
#define _CRT_SECURE_NO_WARNING

class season
{
public:
	void updateRaces();
	void printAllRaces();
protected:
	std::vector<race*> races;
	std::vector<constructor*> constructors;
	std::vector<driver*> drivers;
private:
	void fetchToFile(std::string url, const char* outFileName);
	void parse(const char* outFileName);
};

