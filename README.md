# roadtravel-php-dwa

For english variant please go to `eng_README.md` (coming soon)

## Demo

A. Public host [Link către site](https://vanadium.idigit.ro/public/login/index)

**Datele de conectare sunt ascunse pentru a preveni spam public.**

B. Local

#### Cerințe

- `PHP 7` sau o versiune mai nouă
- `Apache/2.4.52` sau o versiune mai nouă
- MySQL Database Server `Ver 15.1 Distrib 10.6.12-MariaDB` sau o versiune mai nouă
- _Optional_: `PHPMyAdmin`

#### Instalare

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
7. Repository: reprezintă clase utilitare prin care se realizează acțiuni CRUD asupra bazei de date. De asemenea, aceasta apelează proceduri create în cadrul bazei de date.

## Inițializarea aplicației

Pentru a inițializa aplicația, mai exact, pentru a încărca fișierele și dependențele necesare unei funcționări corecte, folosim un fișier de tip `Bootstrap`. Pentru a asigura securitatea aplicației, toate fișierele accesibile publicului sunt plasate în directorul `public`, inclusiv cele statice precum CSS, JavaScript sau fișiere media. Directorul `app` conține fișierele și structura internă a aplicației. Aceste fișiere nu ar trebui să fie accesibile publicului. Astfel, prin amplasarea fișierului `init.php`  în directorul `app`, acesta devine inaccesibil direct din browser, fiind o măsură de securitate. În același timp, fișierul de bootsrap este plasat în directorul `public`, unde poate fi accesat direct de către serverul web și utilizat pentru a configura aplicația.
Observăm că singura linie de cod prezentă în `index.php`, fișierul Bootstrap aflat în directorul `public` este cea de încărcare a fișierului `init.php`.
```php
require_once '../app/init.php';
```
La accesarea site-ului, server-ul se folosește de fișierul init.php și încarcă toate dependențele necesare.

```php
<?php
session_start();
/**
 * Bootstraping for the modules responsable for the MVC Arhitecture.
 */
require_once 'core/Router.php';
require_once 'core/Request.php';
require_once 'core/Response.php';
require_once 'core/Controller.php';
require_once 'core/Middleware.php';
require_once 'core/DatabaseConnection.php';
require_once 'core/Cookie.php';
require_once 'core/GeolocationApi.php';
require_once 'core/WeatherApi.php';

/**
 * Not inclunded in mvc_project_mds
 */
require_once 'core/EmailSender.php';

/**
 * Repositories
 */

require_once 'repositories/CredentialRepository.php';
require_once 'repositories/UserRepository.php';
require_once 'repositories/LocationRepository.php';
require_once 'repositories/BusRepository.php';
require_once 'repositories/TripRepository.php';
require_once 'repositories/DiscountRepository.php';
require_once 'repositories/BookingRepository.php';
require_once 'repositories/RoleRepository.php';
require_once 'repositories/StatisticsRepository.php';

require_once 'vendor/fpdf/fpdf.php';

// debug statements
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$router = Router::getInstance();
$request = Request::parse();

$router->route($request);
```

După ce sunt încărcate toate fișierele necesare, se incepe procesul de rutare. Se obține o instanță a clasei de rutare, după se parsează cererea de la client. După ce se parsează cererea de la client, aceasta este transmisă clasei de rutare.

## Clasa `Request`

Clasa `Request` este o implementare a unui obiect de tip cerere. Aceasta prezintă 3 proprietăți: `descriptor`, `method`, `data`. `descriptor` reprezintă un identificator pentru fiecare cale specifică aplicației, fiind asociată în fișierele `Routes.php`, `Middlewares.php` și `FormRules.php` pentru ca controllere și funcțiile specifice ale acestuia să fie apelate corespunzător. De asemenea, pe baza descriptorului, apelăm procesarea cererilor de către Middleware-uri și realizăm parsarea formularelor specifice fiecărei pagini.

Metoda statică "parse" este folosită pentru a extrage informațiile relevante din cererea HTTP primită și a crea un obiect de tip "Request" cu aceste detalii. Mai întâi, se verifică existența și se preia valoarea parametrului "url" din cadrul variabilei globale `$_GET`. Dacă acesta există, se împarte în segmente separate, eliminând eventualele caractere nedorite și filtrându-le pentru a asigura securitatea. Primul segment obținut reprezintă controlerul (controller), iar al doilea segment reprezintă acțiunea (action) dorită.  Astfel obținem descriptorul.

Metoda cererii HTTP este obținută din variabila globală `$_SERVER['REQUEST_METHOD']`.

În funcție de metoda cererii, se determină modul de preluare a datelor. Dacă este o cerere de tip "POST", se obțin datele din variabila globală `$_POST`. În caz contrar, dacă există mai mult de două segmente în URL, se obțin valorile segmentelor începând cu al treilea segment și se stochează într-un vector numit "requestData". Dacă nu există segmente suplimentare, se atribuie valoarea "null" variabilei "requestData".

La final, se creează și se returnează un nou obiect "Request", având în componență un descriptor format din concatenarea controlorului și a acțiunii separate prin "@" (exemplu: "`controller@action`"), metoda cererii și datele obținute în urma analizei cererii.

Clasa "Request" mai conține și alte metode de acces (getteri și setteri) pentru a obține și a seta valorile variabilelor private "descriptor", "method" și "data".

Pentru a asigura o parsare eficientă a descriptorului, ne folosim de fișiere de configurare `.htaccess`.

În public, avem următorul fișier `.htaccess`:

```php
#Rules to control the behaviour of the web server
Options -MultiViews
RewriteEngine On

RewriteBase /roadtravel/public

# If the request is not a directory and not a file,
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f

# Rewrite the URL to index.php and pass the requested url to the routing class
RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
```

Prima oară, dezactivăm opțiunea MultiViews, care se ocupă cu afișarea unei pagini web alternative atunci când nu găsește pagina cerută. Avem clasa `Response` (răspuns) să ne afișeze mesaje de eroare pe baza codurilor HTTP.

Cerem server-ului Apache, pe următoarea linie, să pornească modulul `mod_rewrite` pentru a permite utilizarea regulilor de rescriere a URL-urilor.

Baza URL-uluui este determinată de `RewriteBase /roadtravel/public`. Orice regulă de rescriere o vom aplica directorului `public`.

Urmează cele două condiții esențiale pentru rescrierea URL-ului:
Prima condiție, "`RewriteCond %{REQUEST_FILENAME} !-d`", verifică dacă cererea nu este pentru un director existent pe server. Dacă este adevărat, această condiție este satisfăcută.

A doua condiție, "`RewriteCond %{REQUEST_FILENAME} !-f`", verifică dacă cererea nu este pentru un fișier existent pe server. Dacă este adevărat, această condiție este satisfăcută.

Regula finală, "`RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]`", este regula de rescriere efectivă a URL-ului. Aceasta preia toate cererile care trec de condițiile anterioare și le rescrie în index.php, transmitând parametrul cererii originale sub forma "url". Parametrul "QSA" (Query String Append) este utilizat pentru a păstra și a adăuga orice parametri de interogare existente în URL-ul rescris. Flag-ul "L" (Last) specifică că rescrierea trebuie oprită și procesarea regulilor următoare trebuie evitată.

Astfel, în raport cu clasa "Request" prezentată anterior, aceste reguli de rescriere a URL-urilor sunt concepute pentru a captura URL-ul cererii și a-l transmite către clasa "Request" prin parametrul "url" pentru a fi analizat și utilizat în obținerea controlerului, acțiunii și altor detalii relevante ale cererii. Astfel, aceste reguli de rescriere a URL-urilor permit obiectului de tip "Request" să fie creat și inițializat corespunzător, bazându-se pe informațiile primite din URL.

Un exemplu de rescriere:
	Presupunem că scriem următoarea adresă URL: `/public/home/index/a/b/c`. În `$_GET[`url`]` vom avea următoarea structură:

`Array( [0] => home, [1] => index, [2] => a, [3] => b, [4] => c)`

Știm că primele două valori din vector sunt controller-ul și acțiunea specifică lor. Le concatenăm, între ele amplasând caracterul `@` pentru o identificare mai ușoară. La final, vom obține descriptorul: `home@index`. Pentru a apela controller-ul specific, ne folosim de Routes.php din `app\config`:

'home@index' => array('controller'=>'HomeController','action'=>'index')

**De reținut, că rădăcina URL-ului se identifică doar prin `@`, fiindcă cele două poziții din vector sunt goale!**

## Clasa "`Response`"


Pentru a afișa mesajele de eroare de la o cerere invalidă sau o acțiune care nu a fost realizată complet în cadrul aplicației web, ne folosim de clasa `Response` (răspuns).

Aceasta prezintă următoarele proprietăți: $statusCode, $content și $redirect. Primele două sunt folosite pentru a afișa prin intermediul funcției send(), o eroare specifică sau o confirmare a unei acțiuni realizate cu succes. Dacă dorim să redirecționăm utilizatorul, în cazul în care acesta nu este conectat, către pagina de conectare, ne folosim de proprietatea $redirect. Dacă aceasta este setată înainte de a trimite răspunsul, prin intermediul funcției header() redirecționăm utilizatorul.

Aceasta este folosită atât în middlewares cât și în controllere.

De exemplu, la înregistrare, verificăm dacă utilizatorul a confirmat parola. În cazul în care cele două parole nu sunt egale, trimitem un răspuns cu codul 403 HTTP utilizatorul și îi spunem că parola nu a fost confirmată:
```php
if(strcmp($data['password'],$data['confirmPassword']) !== 0)
{
    $res = new Response("Password is not confirmed",403);
    return $res;
}
```

În cazul unei operații CRUD, care este administrată de controller, dacă de exemplu avem chei secundare asociate unei tabele, întoarcem un răspuns specific de eroare:
```php
if($checkBookings > 0)
{
    $res = new Response("For this trip exists bookings. You can't delete it before deleting the bookings associated with it!",500);
    $res->send();
    exit();
}
```

## Implementarea unui middleware

Pentru a implementa un middleware, ne folosim de o interfață. Aceasta ne asigură faptul că structura fiecărui middleware va fi identică.
```php
interface Middleware{
    public function __invoke($data);
}
```

În PHP, metoda magică __invoke permite unui obiect să fie apelat ca și cum ar fi o funcție. Atunci când o clasă implementează metoda __invoke, se permite instanțelor acelei clase să fie tratate ca obiecte apelabile.

Metoda __invoke este automat invocată atunci când un obiect este utilizat ca o funcție și este apelat direct folosind paranteze. Aceasta poate fi utilă în scenarii în care doriți să tratați un obiect ca o funcție și să efectuați anumite acțiuni atunci când obiectul este invocat.

Ne folosim de acest mecanism în realizarea fiecărui middleware. Fiindcă ele manipulează cererile, mereu returnăm o cerere. În cazul unei erori în middleware, returnăm un răspuns cu eroare specifică.

Astfel, pentru a implementa un nou middleware, tot ce trebuie făcut este să se realizeze o nouă clasă ce implementează această interfață cu logica pe care o dorim. De asemenea, este important să menționăm middleware-ul pe baza descriptorului în fișierului `Middlewares.php` pentru a ne asigura că procesarea se face pe ruta care o dorim.

În funcție de tipul de obiect returnat (Response / Request), aplicația fie returnează o eroare, fie trimite cererea la un alt middleware, fie o trimite controller-ului.

