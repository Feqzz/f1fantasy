#pragma once
#include "season.h"
class player
{
public:
	player(std::string playerName_);
	void changeDrivers();
	void changeConstructor();
private:
	std::string playerName;
	int money;
	int points;
	constructor* currentConstructor;
	std::vector<driver*> currentDrivers;
};

