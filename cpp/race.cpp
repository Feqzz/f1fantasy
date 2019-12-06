#include "race.h"

race::race(int season_, int round_, std::string raceName_, std::string circuitId_, std::string circuitName_, std::string country_, std::string date_)
	:season(season_), round(round_), raceName(raceName_), circuitId(circuitId_), circuitName(circuitName_), country(country_), date(date_)
{
}

void race::print()
{
	std::cout << "Season " << season << " Round " << round << std::endl;
	std::cout << "Race Name: " << raceName << std::endl;
	std::cout << "Country: " << country << " " << date << std::endl;
	std::cout << "Fastest Lap: " << fastestLapDriver->getFullName() << " " << fastestLapTime << std::endl;
	std::cout << "---------------- Results ----------------" << std::endl;

	for (int i = 0; i < raceResults.size(); i++)
	{
		raceResults[i]->print();
	}

	std::cout << "-----------------------------------------" << std::endl;
}

void race::addDriver(driver* driver)
{
	drivers.push_back(driver);
}

void race::addConstructor(constructor* constructor)
{
	bool alreadyInVector = false;
	for (int i = 0; i < constructors.size(); i++)
	{
		if (constructors[i] == constructor)
		{
			alreadyInVector = true;
		}
	}
	if (!alreadyInVector)
	{
		constructors.push_back(constructor);
	}
}

void race::addFastestLapInformation(std::string fastestLapTime_, driver* fastestLapDriver_)
{
	fastestLapTime = fastestLapTime_;
	fastestLapDriver = fastestLapDriver_;
}

void race::addRaceResult(raceResult* raceResult_)
{
	raceResults.push_back(raceResult_);
}

int race::getRound()
{
	return round;
}
