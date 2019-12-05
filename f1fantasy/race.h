#pragma once
#include "raceResult.h"
#include <string>
#include <vector>
class race 
{
public:
	race(int season_, int round_, std::string raceName_,
		std::string circuitId_, std::string circuitName_,
		std::string country_, std::string date_);
	void print();
	void addDriver(driver* driver);
	void addConstructor(constructor* constructor);
	void addFastestLapInformation(std::string fastestLapTime_, driver* fastestLapDriver_);
	void addRaceResult(raceResult* raceResult_);
	int getRound();
private:
	int season;
	int round;
	std::string raceName;
	std::string circuitId;
	std::string circuitName;
	std::string country;
	std::string date;
	std::string fastestLapTime;
	driver* fastestLapDriver;
	std::vector<driver*> drivers;
	std::vector<constructor*> constructors;
	std::vector<raceResult*> raceResults;
};

