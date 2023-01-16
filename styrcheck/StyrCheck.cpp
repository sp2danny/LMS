
#include <iostream>
#include <filesystem>
#include <string>
#include <cstring>
#include <fstream>
#include <algorithm>
#include <map>
#include <cstddef>
#include <version>
#include <iterator>
#include <optional>
#include <initializer_list>

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
	requires requires (T& t) {t.sort();}
	void sort(T& t)
	{
		t.sort();
	}
	template<typename T>
	requires requires (T& t) {std::sort(std::begin(t), std::end(t));}
	void sort(T& t)
	{
		std::sort(std::begin(t), std::end(t));
	}
	
	template<typename T>
	requires requires (T& t) {t.unique();}
	void unique(T& t)
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

std::vector<std::string> explode(const std::string& s, char delim)
{
	std::vector<std::string> result;
	std::istringstream iss(s);

	for (std::string token; std::getline(iss, token, delim); )
	{
		result.push_back(std::move(token));
	}

	return result;
}

template<typename T, typename U>
bool oneof(const T& value, std::initializer_list<U> list)
{
	for (auto&& item : list)
		if (value == item)
			return true;
	return false;
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

	const bool has_segment(const std::string& segn) const
	{
		auto i = segs.find(segn);
		return (i != segs.end());
	}

	std::optional<std::string> bang_value(const std::string& segment, std::string command) const
	{
		auto i = segs.find(segment);
		if (i == segs.end()) return {};
		auto&& pl = i->second.posts;
		for (auto&& p : pl)
		{
			if (p.cmd != command) continue;
			if (p.param.size() != 1) return {};
			return p.param.front();
		}
		return {};
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
		if (seg == "") seg = "default"s;
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

extern std::string params(const StrVec& sv, const std::string& sep = "; ");

bool validate_styr(const StyrFil& sf, std::ostream& out)
{
	bool allok = true;
	if (sf.bang_value("default", "format") != "2") {
		allok=false;
		out << "format is not '2' \n";
	}
	int n = 0;
	try {
		n = std::stoi(*sf.bang_value("default", "max"));
	} catch(...) {
		allok=false;
		out << "could not read size \n";
	}

	if (!allok) return allok;

	for (int i=1; i<=n; ++i)
	{
		std::string sn = "segment-"s + std::to_string(i);
		if (!sf.has_segment(sn)) {
			out << "missing segment " << sn << std::endl;
			allok=false;
		}
	}

	for (auto&& seg : sf)
	{
		if (seg.first == "default"s) continue;
		auto p = seg.first.find("segment-"s);
		if (p==0) continue;
		out << "bonus segment " << seg.first << std::endl;
		allok=false;
	}

	for (auto&& seg : sf)
	{
		if (seg.first == "default")
			continue;
		for (auto&& cmd : seg.second.posts)
		{
			/****/ if (oneof(cmd.cmd, {"b"s, "break"s}      )) {
			} else if (oneof(cmd.cmd, {"t"s, "T"s, "text"s} )) {
			} else if (oneof(cmd.cmd, {"qstart"s}           )) {
			} else if (oneof(cmd.cmd, {"q"s, "query"s}      )) {
			} else if (oneof(cmd.cmd, {"i"s, "image"s}      )) {
			} else if (oneof(cmd.cmd, {"I"s, "embed"s}      )) {
			} else if (oneof(cmd.cmd, {"e"s, "video"s}      )) {
			} else if (oneof(cmd.cmd, {"qstop"s}            )) {
			} else if (oneof(cmd.cmd, {"f"s, "one"s}        )) {
			} else if (oneof(cmd.cmd, {"s"s}                )) {
			} else if (oneof(cmd.cmd, {"onestop"s}          )) {
			} else if (oneof(cmd.cmd, {"discdisplay"s}      )) {
			} else if (oneof(cmd.cmd, {"always"s}           )) {
			} else if (oneof(cmd.cmd, {"discquery"s}        )) {
			} else if (oneof(cmd.cmd, {"next"s}             )) {
			} else if (oneof(cmd.cmd, {"nextbatt"s}         )) {
			} else if (oneof(cmd.cmd, {"back"s}             )) {
			} else if (oneof(cmd.cmd, {"link"s}             )) {
			} else if (oneof(cmd.cmd, {"tq-start"s}         )) {
			} else if (oneof(cmd.cmd, {"tq-query"s}         )) {
			} else if (oneof(cmd.cmd, {"tq-stop"s}          )) {
			} else if (oneof(cmd.cmd, {"sound"s}            )) {
			} else {
				out << "error : unrecognized command : " << cmd.cmd << " " << params(cmd.param) << std::endl;
				allok=false;
			}
		}
	}

	return allok;
}

// !batt 4
// !max 7
// !format 2

std::string params(const StrVec& sv, const std::string& sep)
{
	if (sv.empty()) return "";
	std::string out;
	long long i = 0, n = std::ssize(sv);
	while (true)
	{
		out += sv[i];
		++i;
		if (i < n)
			out += sep;
		else
			break;
	}
	return out;
}

struct Query {
	std::string query;
	StrVec answers;
	int corr;
};

struct QBlock
{
	std::string text;
	std::string start = "Starta", score = "score.php", timer = "130", sound = "", corr = "Rätta", done = "Klar";
	std::vector<Query> querys;
};

std::string quote(const std::string s)
{
	std::string res;
	bool in = false;
	for (char c : s)
	{
		if ((c=='"') || (c=='\'')) {
			if (in) {
				res += "´´";
				in = false;
			} else {
				res += "``";
				in = true;
			}
		} else {
			res += c;
		}
	}
	return res;
}

void outq(std::ostream& out, const QBlock& qb)
{
	out << "!one " << qb.start <<
		" ; " << qb.score <<
		" ; " << qb.timer <<
		" ; " << qb.sound <<
		" ; " << qb.corr << std::endl;
	for (auto&& q : qb.querys)
	{
		out << "!query " << quote(q.query) << " ; " << params(q.answers) << std::endl;
	}
	out << "!onestop" << std::endl;
}

void setif(std::string& str, const StrVec& sv, int idx)
{
	if (idx < 0) return;
	auto n = std::ssize(sv);
	if (idx >= n) return;
	auto s = sv[idx];
	if (s.empty()) return;
	str = s;
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
		bool inq = false;
		QBlock qb;
		std::string ptxt;
		for (auto&& cmd : seg.second.posts)
		{
			if (oneof(cmd.cmd, {"b"s, "break"s})) {
				out << "!break " << params(cmd.param) << std::endl;
			} else if (oneof(cmd.cmd, {"t"s, "T"s, "text"s})) {
				if (!ftxt) {
					ftxt = true;
					out << "!qstart" << std::endl;
				}
				if (inq)
					ptxt += params(cmd.param, " ");
				else
					out << "!text " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "qstart") {
				if (!ftxt) {
					ftxt = true;
					out << "!qstart" << std::endl;
				}
			} else if (oneof(cmd.cmd, {"q"s, "query"s})) {
				Query q;
				q.query = ptxt + cmd.param[0];
				q.answers.assign(cmd.param.begin()+1, cmd.param.end());
				ptxt.clear();
				qb.querys.push_back(q);
				//out << "!query " << params(cmd.param) << std::endl;
			} else if (oneof(cmd.cmd, {"i"s, "image"s})) {
				out << "!image " << params(cmd.param) << std::endl;
			} else if (oneof(cmd.cmd, {"I"s, "embed"s})) {
				out << "!embed " << params(cmd.param) << std::endl;
			} else if (oneof(cmd.cmd, {"e"s, "video"s})) {
				out << "!video " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "qstop"s) {
				if (!stpt) {
					stpt = true;
					out << "!qstop" << std::endl;
				}
			} else if (oneof(cmd.cmd, {"f"s, "one"s})) {
				ff = cmd.param;
				inq = true;
				if (ftxt && !stpt) {
					stpt = true;
					out << "!qstop" << std::endl;
				}
				setif(qb.start, cmd.param, 0);
				setif(qb.score, cmd.param, 1);
				setif(qb.timer, cmd.param, 2);
				setif(qb.sound, cmd.param, 3);
				setif(qb.corr,  cmd.param, 4);
				ptxt.clear();
				//out << "!one " << params(pp) << std::endl;
			} else if (cmd.cmd == "s"s) {
				setif(qb.corr,  cmd.param, 0);
				outq(out, qb);
				inq = false;
			} else if (cmd.cmd == "onestop"s) {
				outq(out, qb);
				inq = false;
				// out << "!query " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "discdisplay"s) {
				out << "!discdisplay " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "always"s) {
				out << "!always " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "discquery"s) {
				out << "!discquery " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "next"s) {
				out << "!next " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "nextbatt"s) {
				out << "!nextbatt " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "back"s) {
				out << "!back " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "link"s) {
				out << "!link " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "tq-start") {
				out << "!tq-start " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "tq-query") {
				out << "!tq-query " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "tq-stop") {
				out << "!tq-stop " << params(cmd.param) << std::endl;
			} else if (cmd.cmd == "sound") {
				out << "!sound " << params(cmd.param) << std::endl;
			}
			/*
			else if (cmd.cmd == "xxx")
			{
				out << "!xxx " << params(cmd.param) << std::endl;
			}
			else if (cmd.cmd == "xxx")
			{
				out << "!xxx " << params(cmd.param) << std::endl;
			}
			else if (cmd.cmd == "xxx")
			{
				out << "!xxx " << params(cmd.param) << std::endl;
			}
			else if (cmd.cmd == "xxx")
			{
				out << "!xxx " << params(cmd.param) << std::endl;
			}
			else if (cmd.cmd == "xxx")
			{
				out << "!xxx " << params(cmd.param) << std::endl;
			}
			*/
			else 
			{
				out << "#error " << cmd.cmd << " " << params(cmd.param) << std::endl;
			}
		}
		if (inq) {
			outq(out, qb);
			inq = false;
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
			if (expl[0] == "image"s) {
				want_files.push_back(boost::trim_copy(expl.back()));
			} else if (expl[0] == "audio"s) {
				want_files.push_back(boost::trim_copy(expl.back()));
			} else if (expl[0] == "one"s) {
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
	namespace fs = std::filesystem;
	using namespace std::literals;

	fs::path base{"."s};
	if (argc >= 2)
		base = argv[1];

	bool verbose = false;
	bool nomod = false;

	for (int i=2; i<argc; ++i)
	{
		std::string s{argv[i]};
		if (s == "--verbose"s) verbose = true;
		if (s == "--nomod"s) nomod = true;
	}

	if (verbose)
		std::cout << base << std::endl;

	fs::directory_iterator di{base};
	for (auto&& de : di)
	{
		if (!de.is_directory())
			continue;
		std::string nm = de.path().filename().string();

		if (tolower(nm.substr(0, 5)) != "batt-"s)
			continue;
		nm = nm.substr(5);
		if (verbose)
			std::cout << nm << std::endl;

		auto ofn = de.path() / "styr_old.txt"s;
		auto nfn = de.path() / "styr.txt"s;

		if (!fs::exists(nfn)) {
			std::cout << "error: 'styr.txt' missing"s << std::endl;
			continue;
		}

		std::ifstream ifs;

		if (nomod) {
			ifs.open(nfn);
		} else {
			if (fs::exists(ofn)) {
				fs::remove(ofn);
			}
			fs::copy(nfn, ofn);
			ifs.open(ofn);
		}

		StrVec want_files = { "index.php"s, "local.css"s, "styr.txt"s, "styrkant.txt"s };
		auto ini = readin(ifs);
		ifs.close();

		int ver = fval(ini, ""s, "format"s, 1);
		if (ver==1)
			want_1(ini, want_files);
		else if (ver == 2)
			want_2(ini, want_files);

		if (!nomod) {
			std::ofstream ofs{nfn};
			writestyr(readstyr(ini), ofs);
			ofs.close();
		} else {
			// no modifications!
			StyrFil sf = readstyr(ini);
			bool ok = validate_styr(sf, std::cerr);
			if (!ok) std::cerr << "\nfinished with error\n" << std::endl;
		}

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
				if (fn != "styr_ny.txt"s) {
					if (!binary_search(want_files, fn)) {
						std::cout << "\tExcess local file : " << fn << "\n";
					}
				}
			}
		}
	}

	std::cout << "\nDone.\n";
	fgetc(stdin);
}

