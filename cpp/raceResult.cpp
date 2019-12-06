#include "raceResult.h"

raceResult::raceResult(driver* driver_, constructor* constructor_, int position_, int points_,
	int fastestLapRank_, std::string fastestLapTime_)
	:raceDriver(driver_), driversConstructor(constructor_), position(position_), points(points_),
	fastestLapRank(fastestLapRank_), fastestLapTime(fastestLapTime_)
{
}

void raceResult::print()
{
	std::cout << "POS ";
	if (position < 10) std::cout << " ";
	std::cout << position << " | " << raceDriver->getFullName() << " | " << driversConstructor->getFullName() << " | " << points << std::endl;
}
