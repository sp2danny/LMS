// StyrCheck.cpp : This file contains the 'main' function. Program execution begins and ends there.
//

#include <iostream>
#include <filesystem>
#include <string>
#include <cstring>
#include <fstream>
#include <algorithm>
#include <ranges>

#include <boost/algorithm/string.hpp>

std::string tolower(const std::string& str)
{
	std::string ret;
	ret.reserve(str.size());
	for (char c : str)
		ret += (char)std::tolower(c);
	return ret;
}

std::vector<std::string> explode(std::string const& s, char delim)
{
	std::vector<std::string> result;
	std::istringstream iss(s);

	for (std::string token; std::getline(iss, token, delim); )
	{
		result.push_back(std::move(token));
	}

	return result;
}

int main(int argc, char* argv[])
{
	std::filesystem::path base{"."};
	if (argc == 2)
		base = argv[1];

	std::filesystem::directory_iterator di{base};
	for (auto&& de : di)
	{
		if (!de.is_directory())
			continue;
		std::string nm = de.path().filename().string();

		if (tolower(nm.substr(0, 5)) != "batt-")
			continue;
		nm = nm.substr(5);
		std::cout << nm << std::endl;
		std::ifstream ifs{de.path() / "styr.txt"};
		std::vector<std::string> want_files;
		std::string line;
		while (std::getline(ifs, line))
		{
			boost::trim(line);
			if (line.size() < 2)
				continue;
			if (line[1] != '=')
				continue;
			auto expl = explode(line.substr(2), ',');
			switch (line[0])
			{
			case 'i':
				want_files.push_back(expl.back());
				break;
			case 'a':
				want_files.push_back(expl.back());
				break;
			case 'f':
				want_files.push_back(expl[1]);
				want_files.push_back(expl[3]);
				break;
			}
		}

		std::ranges::sort(want_files);
		auto r = std::ranges::unique(want_files);
		want_files.erase(r.begin(), r.end());
		std::cout << "Want:";
		for (const auto& s : want_files)
		{
			std::cout << "\t" << s << "\n";
		}

		//std::filesystem::directory_iterator di2{de};
		//std::vector<std::string> have_files;
	}

}



/*

#  b = 3                                     3 st <br>
#  i = 75, img.bmp                            img.bmp i 75 % storlek
#  t = hej hopp                              'hej hopp' som textrad.får innehålla html - taggar
#  e = 597209255                             en inbäddad film från vimeo, med id 597209255
#  a = snd.mp3                               en ljuduppspelare.filen ska vara lokal
#  I = attr, fil                              en inbäddad fil, med html attribut
#  l = black                                 en färgsatt horisontell linje
#
#  f = Starta, score.php, 130, lugn.mp3      startar ett frågeformulär
#  T = text                                  en text rad inne i ett fråge - formulär
#  q = fråga, svar1, _svar2                  en fråga med 2 svarsalternativ. 'svar2' är rätt
#  s = Rätta, Klar                           en rättnings knapp, med texten 'Rätta' och en submit - knapp med texten Klar.Stänger frågeformuläret och går till poäng
#
#  n = Nästa                                 en knapp med texten 'Nästa', för att gå till nästa sida, utan frågerättning
#

*/


