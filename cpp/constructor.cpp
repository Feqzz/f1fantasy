#include "constructor.h"

constructor::constructor(std::string constructorId_, std::string name_, std::string nationality_)
	:constructorId(constructorId_), name(name_), nationality(nationality_)
{
}

void constructor::print()
{
	std::cout << name << std::endl;
}

std::string constructor::getConstructorId()
{
	return constructorId;
}

std::string constructor::getFullName()
{
	return name;
}
