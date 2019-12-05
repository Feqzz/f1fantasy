#pragma once
#include <iostream>
#include <vector>
#include <string>
class constructor
{
public:
	constructor(std::string constructorId_, std::string name_, std::string nationality_);
	void print();
	std::string getConstructorId();
	std::string getFullName();
private:
	std::string constructorId;
	std::string name;
	std::string nationality;
	//std::vector<driver*> drivers;
};

