#pragma once
#include <iostream>
#include <tinyxml2.h>
#include <curl/curl.h>

#pragma warning(disable:4996)
#define _CRT_SECURE_NO_WARNING

class getSite
{
public:
	void fetchToFile(std::string url, const char* outFileName);
	//size_t writeData(void* ptr, size_t size, size_t nmemb, FILE* stream);
	void parse(const char* outFileName);
};

