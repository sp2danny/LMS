// StyrCheck.cpp : This file contains the 'main' function. Program execution begins and ends there.
//

#include <iostream>
#include <filesystem>
#include <string>
#include <cstring>
#include <fstream>
#include <algorithm>
//#include <ranges>
#include <map>
#include <cstddef>
#include <version>
#include <iterator>

#ifndef __cpp_lib_ssize
namespace std {

	template<typename T>
	long long ssize(const T& t)
	{
		return (long long)std::size(t);
	}
}
#endif

namespace {
	
	template<typename T>
	auto sort(T& t)
		-> decltype(t.sort(), void())
	{
		t.sort();
	}
	template<typename T>
	void sort(T& t, ...)
	{
		std::sort(std::begin(t), std::end(t));
	}
	
	template<typename T>
	auto unique(T& t)
		-> decltype(t.unique(), void())
	{
		t.unique();
	}
	template<typename T>
	void unique(T& t, ...)
	{
		auto itr = std::unique(std::begin(t), std::end(t));
		t.erase(itr, std::end(t));
	}
	
	template<typename T, typename U>
	bool binary_search(const T& t, const U& u)
	{
		return std::binary_search(std::begin(t), std::end(t), u);
	}

}


using namespace std::literals;

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

typedef std::vector<std::string> StrVec;

typedef std::map<std::string, StrVec> IniFile;

struct Post
{
	std::string cmd;
	StrVec param;
};

struct Segment
{
	std::string name;
	std::vector<Post> posts;
};

struct StyrFil
{
	auto begin() { return segs.begin(); }
	auto end() { return segs.end(); }
	auto begin() const { return segs.cbegin(); }
	auto end() const { return segs.cend(); }

	std::vector<Post>& operator[](const std::string& name)
	{
		auto&& p = segs[name];
		p.name = name;
		return p.posts;
	}

	const std::vector<Post>& operator[](const std::string& name)  const
	{
		auto i = segs.find(name);
		if (i == segs.end()) throw "error";
		return i->second.posts;
	}

	std::map<std::string, Segment> segs;
};

IniFile readin(std::ifstream& ifs)
{
	IniFile ini;
	std::string segment = "";
	std::string line;
	while (std::getline(ifs, line))
	{
		boost::trim(line);
		if (line.empty()) continue;
		if (line[0] == '#') continue;
		if (line[0] == '[') {
			segment = line.substr(1, line.size() - 2);
			continue;
		}
		ini[segment].push_back(line);
	}
	return ini;
}

extern int fval(const IniFile& ini, const std::string& seg, const std::string& name, int def);

StyrFil readstyr(const IniFile& ini)
{
	StyrFil styr;
	styr["default"];

	const auto np = std::string::npos;

	for (auto&& mi : ini)
	{
		std::string seg = mi.first;
		if (seg == "") seg = "default";
		for (auto vi : mi.second)
		{
			if (vi[0] == '!')
			{
				auto p = vi.find(' ');
				std::string cmd;
				StrVec param;
				if (p == np) {
					cmd = vi.substr(1);
				}
				else {
					cmd = vi.substr(1, p - 1);
					std::string rst = vi.substr(p + 1);
					param = explode(rst, ';');
				}
				Post pp;
				pp.cmd = cmd;
				pp.param = param;
				styr[seg].push_back(pp);
			}
			else if (auto p = vi.find('='); p != np)
			{
				std::string cmd;
				cmd = vi.substr(0, p);
				std::string rst = vi.substr(p + 1);
				Post pp;
				pp.cmd = cmd;
				pp.param = explode(rst, ',');
				styr[seg].push_back(pp);
			}
		}
	}
	return styr;
}


// !batt 4
// !max 7
// !format 2

std::string params(const StrVec& sv)
{
	if (sv.empty()) return "";
	std::string out;
	long long i = 0, n = std::ssize(sv);
	while (true)
	{
		out += sv[i];
		++i;
		if (i < n)
			out += "; ";
		else
			break;
	}
	return out;
}

void writestyr(const StyrFil& styr, std::ostream& out)
{
	auto&& def = styr["default"];

	int max = -1;
	int batt = -1;
	for (auto&& v : def)
	{
		if (v.cmd == "batt")
			batt = std::stoi(v.param[0]);
		if (v.cmd == "max")
			max = std::stoi(v.param[0]);
	}

	if (max <= 0)
		max = (int)std::ssize(styr.segs) - 1;

	out << "!format 2" << std::endl;
	out << "!max " << max << std::endl;
	out << "!batt " << batt << std::endl;

	for (auto&& seg : styr)
	{
		if (seg.first == "default")
			continue;
		out << std::endl << "[" << seg.first << "]" << std::endl;
		bool ftxt = false;
		bool stpt = false;
		StrVec ff;
		for (auto&& cmd : seg.second.posts)
		{
			/**/ if (cmd.cmd == "b" || cmd.cmd == "break") {
				out << "!break " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "t" || cmd.cmd == "text") {
				if (!ftxt) {
					ftxt = true;
					out << "!qstart" << std::endl;
				}
				out << "!text " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "qstart") {
				if (!ftxt) {
					ftxt = true;
					out << "!qstart" << std::endl;
				}
			} else if (cmd.cmd == "q" || cmd.cmd == "query") {
				out << "!query " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "i" || cmd.cmd == "image") {
				out << "!image " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "I" || cmd.cmd == "embed") {
				out << "!embed " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "e" || cmd.cmd == "video") {
				out << "!video " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "qstop") {
				if (!stpt) {
					stpt = true;
					out << "!qstop" << std::endl;
				}
			} else if (cmd.cmd == "f" || cmd.cmd == "one") {
				ff = cmd.param;
				if (ftxt && !stpt) {
					stpt = true;
					out << "!qstop" << std::endl;
				}
				out << "!one " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "q" || cmd.cmd == "query") {
				out << "!query " << params(cmd.param) << std::endl;
			} else {
				out << "#error " << cmd.cmd << " " << params(cmd.param) << std::endl;
			}
		}
	}
}

void want_1(const IniFile& ini, StrVec& want_files)
{
	for (auto&& mi : ini)
	{
		auto&& sv = mi.second;
		for (auto&& line : sv)
		{
			if (line.size() < 2)
				continue;
			if (line[1] != '=')
				continue;
			auto expl = explode(line.substr(2), ',');
			auto sz = std::ssize(expl);
			switch (line[0])
			{
			case 'i':
			case 'a':
			case 'I':
				if (sz>0)
					want_files.push_back(boost::trim_copy(expl.back()));
				break;
			case 'f':
				if (sz>1)
					want_files.push_back(boost::trim_copy(expl[1]));
				if (sz>3)
					want_files.push_back(boost::trim_copy(expl[3]));
				break;
			}
		}
	}
}

void want_2(const IniFile& ini, StrVec& want_files)
{
	for (auto&& mi : ini)
	{
		auto&& sv = mi.second;
		for (auto&& line : sv)
		{
			if (line.size() < 2)
				continue;
			if (line[1] != '!')
				continue;
			auto expl = explode(line.substr(1), ' ');
			if (expl.size() < 2)
				continue;
			auto command = expl[0];
			expl = explode(expl[1], ';');
			if (expl[0] == "image") {
				want_files.push_back(boost::trim_copy(expl.back()));
			} else if (expl[0] == "audio") {
				want_files.push_back(boost::trim_copy(expl.back()));
			} else if (expl[0] == "one") {
				want_files.push_back(boost::trim_copy(expl[1]));
				want_files.push_back(boost::trim_copy(expl[3]));
			}
		}
	}
}

int fval(const IniFile& ini, const std::string& seg, const std::string& name, int def)
{
	auto mi = ini.find(seg);
	if (mi == ini.end())
		return def;
	StrVec sv = mi->second;
	for (auto&& s : sv)
	{
		if (s.empty()) continue;
		if (s[0] != '!') continue;
		auto expl = explode(s.substr(1), ' ');
		if (expl.size() < 2) continue;
		if (expl[0] != name) continue;
		try {
			return std::stoi(expl[1]);
		} catch (...) {
			return def;
		}
	}
	return def;
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
		StrVec want_files = { "index.php"s, "local.css"s, "styr.txt"s };
		auto ini = readin(ifs);
		ifs.close();
		int ver = fval(ini, "", "format", 1);
		if (ver==1)
			want_1(ini, want_files);
		else if (ver == 2)
			want_2(ini, want_files);

		std::ofstream ofs{de.path() / "styr_ny.txt"};
		writestyr(readstyr(ini), ofs);
		ofs.close();

		sort(want_files);
		unique(want_files);

		for (const auto& s : want_files)
		{
			std::filesystem::path p = de.path() / s;
			if (!std::filesystem::exists(p)) {
				if (s[0] == '.')
					std::cout << "\tExternal file missing : " << s << "\n";
				else
					std::cout << "\tLocal file missing : " << s << "\n";
			}
		}

		std::filesystem::directory_iterator di2{de};
		//std::vector<std::string> have_files;
		for (auto&& de2 : di2) {
			if (de2.is_regular_file()) {
				std::string fn = de2.path().filename().string();
				if (fn != "styr_ny.txt"s)
					if (!binary_search(want_files, fn)) {
						std::cout << "\tExcess local file : " << fn << "\n";
					}
			}
		}
	}

	std::cout << "\nDone.\n";
	fgetc(stdin);
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


