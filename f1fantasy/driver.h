#pragma once
#include "getSite.h"
#include "constructor.h"
#include <vector>
class driver
{
public:
	driver(int permanentNumber, int driverCode, int points, std::string givenName,
		std::string familyName, std::string dateOfBirth, std::string nationality,
		std::string driverId, std::string currentConstructor);
	int getDriverCode();
	int setNewPrice(int newPrice);
	void changeConstructor(constructor newConstructor);
	void increasePoints(int points);
private:
	int permanentNumber;
	int driverCode;
	int points;
	int price;
	std::string givenName;
	std::string familyName;
	std::string dateOfBirth;
	std::string nationality;
	std::string driverId;
	constructor* currentConstructor;
};

