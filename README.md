# roadtravel-php-dwa

For english variant please go to `eng_README.md` (coming soon)

## Demo

A. Public host [Link către site](https://vanadium.idigit.ro/public/login/index)

**Datele de conectare sunt ascunse pentru a preveni spam public.**

B. Local

#### Requirements

- `PHP 7` sau o versiune mai nouă
- `Apache/2.4.52` sau o versiune mai nouă
- MySQL Database Server `Ver 15.1 Distrib 10.6.12-MariaDB` sau o versiune mai nouă
- _Optional_: `PHPMyAdmin`

#### Instalare

1. Make sure that the `Apache2` installation has `.htaccess` functionality is enabled.

1. Fiți sigur că instalarea `Apache2` are funcționalitatea `mod_rewrite` activată (`.htaccess`)

2. Fiți siguri că **PHP Short Tags** sunt activate în `php.ini`

3. Importați fișierul `roadtravel.sql` aflat în directorul `export`.

4. Fiți siguri că `DbConfig.php` include informațiile corecte pentru a accesa baza de date (_IP,Name of database, Username to access the database and password_)

5. Clonați repository-ul către directorul specific Apache `httpd`
- Pentru XAMPP: `.htdocs`
- Pentru LAMP stack instalat pe o distribuție Ubuntu : `www`

6. Configurați server-ul ca rădăcina site-ului să fie directorul `public` 

7. Folosiți aplicația accesând `localhost`

## Prezentare generala

Acest proiect abordează tema unei "Companii de transport". Soluția web "RoadTravel" reprezintă o platformă ce facilitează achiziționarea de bilete pentru diverse călătorii oferite de o companie de transport pentru persoane. Implementarea este realizată prin intermediul limbajului PHP și bazei de date MySQL. În vederea asigurării reutilizabilității codului și securității aplicației web, am optat pentru utilizarea unei arhitecturi Model-View-Controller (MVC), care pune la dispoziție o serie de facilități esențiale. Acestea includ funcționalități precum trimiterea de e-mail-uri, utilizarea de middleware-uri, o clasă specializată în tratarea cererilor (request-urilor), o clasă dedicată transmiterii răspunsurilor de la server, un sistem de administrare a cookie-urilor, clase de bază pentru controlere și asigurarea conexiunii către baza de date prin intermediul tehnicii singleton.

## Componente generale

În cadrul aplicației web “RoadTravel”, arhitectura Model-View-Controller (MVC) facilitiează gestionarea eficientă a diferitelor funcționalități esențiale ale aplicației. 
1.	Rutarea: se referă la procesul de direcționare a cererilor HTTP primite către rutele corespunzătoare definite prin intermediul fișierului `Routes.php` aflat în directorul `app\config`. În MVC, acest proces este realizat de o clasă specializată denumite `Router`, care prin intermediul vectorului asociativ furnizat de `Routes.php`, instanțiază controller-ul specific rutei și apelează funcția specifică rutei. Astfel, asigurăm că cererea este trimisă către controller-ul potrivit pentru a fi procesată.
2.	Middleware-uri: reprezintă un mecanism flexibil și reutilizabil pentru a procesa cererile înainte sau după ce acestea ajung la controller. Aceste componente pot executa diverse operațiuni. În cadrul aplicației observăm că acestea se ocupă de: verificarea autentificării, validarea datelor de la formulare (soluție generală de parsare), înregistrarea accesărilor de către utilizatori în vederea realizării unei statistici și verificarea paginilor ce le pot accesa un utilizator pe baza rolului său.
3.	Controller-ul: componenta principală a logicii aplicației care primește cererea și realizează prelucrări. Acesta interacționează cu modelele și afișează informațiile necesare prin intermediul paginilor specializate. Pe scurt, acesta coordonează fluxul de control și acțiunile necesare pentru a trata cererile și pentru a pregăti datele pentru a fi prezentate utilizatorului.
4.	Cerere: reprezintă informațiile trimise de către utilizator (web browser) către server pentru a solicita o anumită acțiune. Cererea conține informații precum descriptorul, metoda HTTP (GET/POST), parametrii  pentru a identifica și procesa acțiunea dorită de utilizator.
5.	Răspuns: reprezintă informațiile returnate de către server către web browser în urma procesării cererii. În acest caz, răspunsurile sunt folosite fie pentru a redirecționa utilizatorul către anumite pagini web, fie pentru a raporta o eroare (atât umană, de exemplu, completarea greșită a unui formular, cât și de server, baza de date nu funcționează, etc..)
6.	Modele: reprezintă clase de tip șablon prin care stocăm informațiile preluate din tabelele specifice bazei de date. Acestea sunt create atât în cadrul controller-ului (în cazul unei operații de tip Create pe baza de date) cât și în repo-uri (clase specializate în a extrage informațiile necesare din baza de date pe baza unor chei primare sau secundare)
Prin intermediul acestor componente-cheie, arhitectura MVC oferă un cadru de lucru structurat și modular pentru dezvoltarea aplicațiilor web. În cele ce urmează, voi explica implementarea fiecărei facilități prezentate.
7. Repository: reprezintă clase utilitare prin care se realizează acțiuni CRUD asupra bazei de date. De asemenea, această apelează proceduri create în cadrul bazei de date.

