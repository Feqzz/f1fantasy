#pragma once
#include "driver.h"
class raceResult
{
public:
	raceResult(driver* driver_, constructor* constructor_, int position_, int points_, int fastestLapRank_,
		std::string fastestLapTime_);
	void print();
private:
	driver* raceDriver;
	constructor* driversConstructor;
	int position;
	int points;
	int fastestLapRank;
	std::string fastestLapTime;
};

