compile: supd.out

CC = clang++-15
OOO = -std=c++2b
OPT = -x c++ $(OOO) -Wall -Wextra -Werror -pedantic

supd.out: bin/main.o
	$(CC) -o supd.out $(OOO) bin/main.o

bin/main.o: src/main.cpp
	$(CC) -o bin/main.o $(OPT) -c src/main.cpp

