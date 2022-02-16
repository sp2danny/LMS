
#include <experimental/filesystem>
#include <iostream>

//using fs = std::experimental::filesystem;

int main()
{
	using namespace std::experimental;
	const filesystem::path path{"."};
    for (const auto& dir_entry : filesystem::directory_iterator{path}) 
    {
        std::cout << dir_entry << '\n';
    }

}
