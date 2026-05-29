// table.cpp : This file contains the 'main' function. Program execution begins and ends there.
//

#include <iostream>
#include <string>
#include <fstream>
#include <vector>

struct Res
{
    bool ok = true;
    std::string msg;

    Res() = default;
    Res(std::string s) : ok(false), msg(s) {}
};


Res Main(std::vector<std::string> arg)
{
    if (arg.empty())
    {
        return { "Expected parameters" };
    }


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

