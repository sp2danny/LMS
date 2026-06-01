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

typedef std::vector<std::string> VS;
typedef std::vector<VS> VVS;

void out_html(std::ostream& out, const VS& hdr, const VVS& data)
{
    std::println(out, "<table>");

    std::println(out, "\t<tr>");
    for (auto& i : hdr)
    {
        std::println(out, "\t\t<th> {} </th>", i);
    }
    std::println(out, "\t</tr>");

    for (auto& l : data)
    {
        std::println(out, "\t<tr>");
        for (auto& i : l)
        {
            std::println(out, "\t\t<td> {} </td>", i);
        }
        std::println(out, "\t</tr>");
    }
    std::println(out, "</table>");
}

void out_js(std::ostream& out, const VS& hdr, const VVS& data)
{
    std::print(out, "headers = [");
    bool first = true;
    for (auto& s : hdr)
    {
        if (!first)
            std::print(out, ", ");
        std::print(out, "'{}'", s);
        first = false;
    }
    std::println(out, "];");
    std::println(out, "data = [");
    long long i, n = std::ssize(data);
    for (i = 0; i < n; ++i)
    {
        std::print("\t[");
        first = true;
        for (auto& s : data[i])
        {
            if (!first)
                std::print(out, ", ");
            std::print(out, "'{}'", s);
            first = false;
        }
        std::print("]");
        if (i!=(n-1))
            std::print(",");
        std::println("");
    }
    std::println(out, "];");

}

void out_report(std::ostream& out, const VS& hdr, const VVS& data)
{
    VS ahdr = {"Datum", "Email", "Namn", "Telefon", "Pris", "Antal"};
        
    std::println(out, "<table>");

    std::println(out, "\t<tr>");
    for (auto& i : ahdr)
    {
        std::println(out, "\t\t<th> {} </th>", i);
    }
    std::println(out, "\t</tr>");

    // 00 - 'order_id'
    // 01 - 'reference'
    // 02 - 'merchant_reference1'
    // 03 - 'merchant_reference2'
    // 04 - 'created_at'
    // 05 - 'expires_at'
    // 06 - 'merchant_id'
    // 07 - 'billing_address_email'
    // 08 - 'billing_address_given_name'
    // 09 - 'billing_address_family_name'
    // 10 - 'billing_address_phone'
    // 11 - 'initial_payment_method'
    // 12 - 'order_amount'
    // 13 - 'purchase_currency'
    // 14 - 'captured'
    // 15 - 'fully_captured'
    // 16 - 'expired'
    // 17 - 'cancelled'
    // 18 - 'amount_left_to_capture'

    int tot_p = 0;
    int tot_a = 0;

    for (auto& l : data)
    {
        if (l[17] == "y") continue;

        auto dt = l[4];
        std::string ymd = dt.substr(0, 10);
        if (ymd <= "2026-05-05") continue;
        int pr = std::stoi(l[12]);
        if (pr < 9900) continue;
        int ant = 1;
        pr = pr / 100;
        /**/ if ((pr % 147) == 0)
            ant = pr / 147;
        else if ((pr % 295) == 0)
            ant = pr / 295;
        else
            continue;

        tot_p += pr;
        tot_a += ant;

        // VS ahdr = { "Datum", "Email", "Namn", "Telefon", "Pris", "Antal" };
        std::println(out, "\t<tr>");

        std::println(out, "\t\t<td> {} </td>", ymd); // datum

        std::println(out, "\t\t<td> {} </td>", l[7]); // email

        std::println(out, "\t\t<td> {} </td>", l[9] + " " + l[8]); // namn

        std::println(out, "\t\t<td> {} </td>", l[10]); // tfn

        std::println(out, "\t\t<td> {} </td>", pr); // pris

        std::println(out, "\t\t<td> {} </td>", ant); // ant

        std::println(out, "\t</tr>");

    }


    std::println(out, "\t<tr>");

    std::println(out, "\t\t<td colspan=4> </td>"); 

    std::println(out, "\t\t<td> {} </td>", tot_p); // pris

    std::println(out, "\t\t<td> {} </td>", tot_a); // ant

    std::println(out, "\t</tr>");


    std::println(out, "</table>");

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

    out_report(std::cout, headers, data);

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

