
#include <iostream>
#include <string>
#include <filesystem>
#include <fstream>
#include <vector>

namespace fs = std::filesystem;

namespace {
	int globCnt = 0;
}

std::string process_l(const std::string& line)
{
	auto p1 = line.find("$styr[");
	if (p1 == std::string::npos) return line;
	auto p2 = line.find("][", p1+1);
	if (p2 == std::string::npos) return line;
	auto p3 = line.find("]", p2+1);
	if (p3 == std::string::npos) return line;
	
	std::string repl = line.substr(0, p1);
	repl += "get_styr($styr, ";
	repl += line.substr(p1+6, p2-p1-6);
	repl += ", ";
	repl += line.substr(p2+2, p3-p2-2);
	repl += ", $variant)";
	repl += line.substr(p3+1);
	
	globCnt += 1;
	
	return process_l(repl);
}

void process_f(const fs::path& pth)
{
	std::vector<std::string> vs;
	{
		std::ifstream ifs{pth};
		std::string line;
		while (std::getline(ifs, line))
		{
			vs.push_back(process_l(line));
		}
	}
	{
		std::ofstream ofs{pth};
		for (const auto& line : vs)
		{
			ofs << line << "\n";
		}
	}
}

int main(int argc, char** argv)
{
	fs::path base = ".";
	if (argc==2)
		base = argv[1];

	auto rdi = fs::recursive_directory_iterator{base};
	for (auto const& de : rdi)
	{
        if (de.is_regular_file())
			if (de.path().string().find(".php") != std::string::npos)
				process_f(de);
	}
	
	std::cout << globCnt << " replacements\n";
	std::getchar();
}

// foo($styr[$aa]['bb']);



