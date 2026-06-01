// table.cpp : This file contains the 'main' function. Program execution begins and ends there.
//

#include <iostream>
#include <string>
#include <fstream>
#include <vector>
#include <sstream>
#include <print>

struct Res
{
    bool ok = true;
    std::string msg;

    Res() = default;
    Res(std::string s) : ok(false), msg(s) {}
};

template <typename Out>
void split(const std::string& s, char delim, Out result) {
    std::istringstream iss(s);
    std::string item;
    while (std::getline(iss, item, delim)) {
        *result++ = item;
    }
}

std::vector<std::string> split(const std::string& s, char delim) {
    std::vector<std::string> elems;
    split(s, delim, std::back_inserter(elems));
    return elems;
}

Res Main(std::vector<std::string> arg)
{
    if (arg.empty())
    {
        return { "Expected parameters" };
    }

    std::ifstream ifs{ arg.front() };

    std::vector<std::string> headers;

    std::string line;

    if (!std::getline(ifs, line))
        return { "Empty file" };

    headers = split(line, ';');

    std::vector<std::vector<std::string>> data;

    while (std::getline(ifs, line))
    {
        data.push_back(split(line, ';'));
    }

    std::println("<table>");
    std::println("\t<tr>");
    for (auto& i : headers)
    {
        std::println("\t\t<th> {} </th>", i);
    }
    std::println("\t</tr>");

    for (auto& l : data)
    {
        std::println("\t<tr>");
        for (auto& i : l)
        {
            std::println("\t\t<td> {} </td>", i);
        }
        std::println("\t</tr>");
    }
    std::println("</table>");

    return {};
}

int main(int argc, char** argv)
{
    std::vector<std::string> arg;
    for (int i = 1; i < argc; ++i)
        arg.push_back(argv[i]);

    Res r = Main(arg);
    if (r.ok) {
        return 0;
    } else {
        std::cerr << argv[0] << " : " << r.msg << std::endl;
        return -1;
    }
}

