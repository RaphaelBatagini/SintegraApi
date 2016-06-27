# upLexis

API system to search CNPJ in Sintegra/ES database.

Technologies used for the development of API:

* PHP 5.6.14
* Laravel Framework 5.1
* MySQL Database
* Bootstrap 3

Setup of the application:

* Run database.sql to get the database working;

You can access any action of this system through some of the URLs below:

* Search CNPJ (cnpj param required)
    domain.example.com/api/find-cnpj

* Delete owned search history (id param required)
    domain.example.com/api/destroy

* List owned searchs
    domain.example.com/api

All function require username(email) and password;
