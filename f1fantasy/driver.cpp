#include "driver.h"

driver::driver(int permanentNumber_, std::string code_, int points_, std::string givenName_,
	std::string familyName_, std::string dateOfBirth_, std::string nationality_, std::string driverId_, constructor constructor_)
	:permanentNumber(permanentNumber_), code(code_), points(points_), givenName(givenName_),
	familyName(familyName_), dateOfBirth(dateOfBirth_), nationality(nationality_), driverId(driverId_)
{
	currentConstructor = &constructor_;
}

std::string driver::getDriverId()
{
	return driverId;
}

std::string driver::getFullName()
{
	std::string fullName = givenName;
	fullName += " ";
	fullName += familyName;
	return fullName;
}

void driver::setNewPrice(int newPrice)
{
	price = newPrice;
	std::cout << "New price for driver " << givenName << " " << familyName << " has been set to $" << price << std::endl;
}

void driver::changeConstructor(constructor* newConstructor)
{
	currentConstructor = newConstructor;
}

void driver::increasePoints(int newPoints)
{
	points += newPoints;
}

void driver::print()
{
	std::cout << driverId << std::endl;
}

constructor* driver::getConstructor()
{
	return currentConstructor;
}
