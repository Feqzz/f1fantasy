#pragma once
#include "constructor.h"
#include <vector>
class driver
{
public:
	driver(int permanentNumber_, std::string code_, int points_, std::string givenName_,
		std::string familyName_, std::string dateOfBirth_, std::string nationality_,
		std::string driverId_, constructor constructor);
	void setNewPrice(int newPrice);
	void changeConstructor(constructor* newConstructor);
	void increasePoints(int newPoints);
	void print();
	std::string getDriverId();
	std::string getFullName();
	constructor* getConstructor();
private:
	int permanentNumber;
	int points = 0;
	std::string code;
	int price;
	std::string givenName;
	std::string familyName;
	std::string dateOfBirth;
	std::string nationality;
	std::string driverId;
	constructor* currentConstructor;
};

