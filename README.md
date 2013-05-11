Aburisk
=======

Burghelea Alexandru George
342C5
Tema 4
===============
IMPORTANT: Trebuie deploiat la /aburisk
NU am avut timp sa elimin harcodarile de link
(nici acum)
===============
E scrisa pentru PHP 5.4
OpenId prin intermediul LighOpenId. Iau de la google emailul
si il folosesc pe post de username. Cand ma loghez prin OpenId
daca exista in baza mea de date un user cu emailul respectiv
il autentific pe acela, daca nu il creez cu emailul pe post
de parola.

Recaptcha e fix de pe site-ul oficial

Logurile sunt scrise in fisier. Logerul face cate un fisier
pe zi.

Salted passwords sunt facute prin intermediul functie
crypt care imi garanteaza ca pentru orice
$salted_password = crypt($password, $random_salt) rezulta
$salted_password == crypt($password, $salted_password).

Mici refactorizari ale ecranului de login