#pragma once
#include "driver.h"
class race
{
	int season;
	int round;
	std::string raceName;
	std::string circutId;
	std::string circuitName;
	std::string country;
	std::vector<driver*> drivers;
};

