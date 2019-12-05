#include "season.h"

void season::updateRaces()
{
	fetchToFile("https://ergast.com/api/f1/2019/20/results", "output.txt");
	parse("output.txt");
}

void season::printAllRaces()
{
	for (int i = 0; i < races.size(); i++)
	{
		races[i]->print();
	}
}

size_t writeData(void* ptr, size_t size, size_t nmemb, FILE* stream)
{
	size_t written;
	written = fwrite(ptr, size, nmemb, stream);
	return written;
}

void season::fetchToFile(std::string url, const char* outfilename)
{
	CURL* curl;
	FILE* fp;
	CURLcode res;
	curl = curl_easy_init();
	if (curl) {
		fp = fopen(outfilename, "wb");
		curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
		curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, writeData);
		curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
		res = curl_easy_perform(curl);
		curl_easy_cleanup(curl);
		fclose(fp);
	}
}

void season::parse(const char* outFileName)
{
	tinyxml2::XMLDocument doc;
	doc.LoadFile(outFileName);
	if (doc.ErrorID() == 0)
	{
		tinyxml2::XMLElement* pRoot;
		tinyxml2::XMLElement* driverInfo;
		tinyxml2::XMLElement* raceInfo;
		pRoot = doc.FirstChildElement("MRData");

		raceInfo = pRoot->FirstChildElement("RaceTable")->FirstChildElement("Race");
		driverInfo = raceInfo->FirstChildElement("ResultsList");

		//Getting Race information and making a race object

		int season = raceInfo->IntAttribute("season");
		int round = raceInfo->IntAttribute("round");
		std::string raceName = raceInfo->FirstChildElement("RaceName")->GetText();
		std::string circuitId = raceInfo->FirstChildElement("Circuit")->Attribute("circuitId");
		std::string circuitName = raceInfo->FirstChildElement("Circuit")->FirstChildElement("CircuitName")->GetText();
		std::string country = raceInfo->FirstChildElement("Circuit")->FirstChildElement("Location")->FirstChildElement("Country")->GetText();
		std::string date = raceInfo->FirstChildElement("Date")->GetText();

		for (int i = 0; i < races.size(); i++)
		{
			if (races[i]->getRound() == round) return; //Return if race already have been added.
		}

		race* r = new race(season, round, raceName, circuitId, circuitName, country, date);
		races.push_back(r);

		//Getting driver information

		for (const tinyxml2::XMLElement* child = driverInfo->FirstChildElement("Result");
			child;
			child = child->NextSiblingElement())
		{
			int resultNumber = child->IntAttribute("number");
			int position = child->IntAttribute("position");
			int points = child->IntAttribute("points");
			std::string driverId = child->FirstChildElement("Driver")->Attribute("driverId");
			std::string code = child->FirstChildElement("Driver")->Attribute("code");
			int permanentNumber = std::stoi(child->FirstChildElement("Driver")->FirstChildElement("PermanentNumber")->GetText());
			std::string givenName = child->FirstChildElement("Driver")->FirstChildElement("GivenName")->GetText();
			std::string familyName = child->FirstChildElement("Driver")->FirstChildElement("FamilyName")->GetText();
			std::string dateOfBirth = child->FirstChildElement("Driver")->FirstChildElement("DateOfBirth")->GetText();
			std::string nationality = child->FirstChildElement("Driver")->FirstChildElement("Nationality")->GetText();
			std::string constructorId = child->FirstChildElement("Constructor")->Attribute("constructorId");
			std::string constructorName = child->FirstChildElement("Constructor")->FirstChildElement("Name")->GetText();
			std::string constructorNationality = child->FirstChildElement("Constructor")->FirstChildElement("Nationality")->GetText();
			int fastestLapRank = child->FirstChildElement("FastestLap")->IntAttribute("rank");
			std::string fastestLapTime = child->FirstChildElement("FastestLap")->FirstChildElement("Time")->GetText();
	
			bool constructorAlreadyExists = false;
			bool driverAlreadyExists = false;
			constructor* driverConstructor = nullptr;
			driver* currentDriver = nullptr;

			for (int i = 0; i < constructors.size(); i++)
			{
				if (constructors[i]->getConstructorId() == constructorId)
				{
					constructorAlreadyExists = true;
					driverConstructor = constructors[i];
				}
			}

			if (!constructorAlreadyExists)
			{
				driverConstructor = new constructor(constructorId, constructorName, constructorNationality);
				constructors.push_back(driverConstructor);
			}

			r->addConstructor(driverConstructor);

			for (int i = 0; i < drivers.size(); i++)
			{
				if (drivers[i]->getDriverId() == driverId)
				{
					currentDriver = drivers[i];
					if (drivers[i]->getConstructor() != driverConstructor)
					{
						drivers[i]->changeConstructor(driverConstructor);
					}
					currentDriver->increasePoints(points);
					driverAlreadyExists = true;
				}
			}
			if (!driverAlreadyExists)
			{
				currentDriver = new driver(permanentNumber, code, points, givenName, familyName, dateOfBirth, nationality,
					driverId, *driverConstructor);
				drivers.push_back(currentDriver);
			}

			r->addDriver(currentDriver);

			raceResult* driversRaceResult = new raceResult(currentDriver, driverConstructor, position,
				points, fastestLapRank, fastestLapTime);

			r->addRaceResult(driversRaceResult);

			if (fastestLapRank == 1)
			{
				r->addFastestLapInformation(fastestLapTime, currentDriver);
			}
		}
	}
}
