#include "getSite.h"

size_t writeData(void* ptr, size_t size, size_t nmemb, FILE* stream)
{
	size_t written;
	written = fwrite(ptr, size, nmemb, stream);
	return written;
}

void getSite::fetchToFile(std::string url, const char* outfilename)
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

void getSite::parse(const char* outFileName)
{
	tinyxml2::XMLDocument doc;
	doc.LoadFile(outFileName);
	if (doc.ErrorID() == 0)
	{
		tinyxml2::XMLElement* pRoot;
		tinyxml2::XMLElement* result;
		pRoot = doc.FirstChildElement("MRData");

		while (pRoot)
		{

		}
	}
}


