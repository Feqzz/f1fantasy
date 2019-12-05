#pragma once
#include <iostream>
#include <vector>
#include "race.h"
class season
{
	std::vector<race*> races;
	std::vector<constructor*> constructors;
	std::vector<driver*> drivers;
};

