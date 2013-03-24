Aburisk
=======

Burghelea Alexandru George
342C5
Tema 1

Baza de date:
Singura modificare care am adus-o bazei de date este adaugarea coloanei `id` in tabelul planets_games. Legaturile intre
tabele sunt realizate prin chei straine.

Backend:
Implementarea este conforma cu enuntul. Iar comentariile sunt foarte explicite pentru a putea fi inteleasa
implementarea.

Structura:
dao -> Clase care extind GenericDao (care la randul lui contine un obiect de tip scaffold) pentru acces la DB.
database -> Clase pentru conectarea la DB.
game -> Clase pentru logica jocului
generic -> Clase pentru interactiune generica cu BD
interface -> Interfetele din enunt
scripts -> Scripturile de interactiune cu jocul.
database_mysql -> Scriptul de generare al bazei de date (contine si date dummy).