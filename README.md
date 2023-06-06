# roadtravel-php-dwa

For english variant please go to `eng_README.md` (coming soon)

## Cuprins

- [roadtravel-php-dwa](#roadtravel-php-dwa)
  * [Demo](#demo)
      - [Cerințe](#cerin-e)
      - [Instalare](#instalare)
  * [Prezentare generala](#prezentare-generala)
  * [Componente generale](#componente-generale)
  * [Inițializarea aplicației](#ini-ializarea-aplica-iei)
  * [Clasa `Request`](#clasa--request-)
  * [Clasa "`Response`"](#clasa---response--)
  * [Implementarea unui middleware](#implementarea-unui-middleware)
  * [Implementarea unui controller](#implementarea-unui-controller)
  * [Implementarea sistemului de rutare](#implementarea-sistemului-de-rutare)
  * [Parsarea fiecărui formular](#parsarea-fiec-rui-formular)
  * [Integrarea de informații](#integrarea-de-informa-ii)
  * [Categorii de utilizatori](#categorii-de-utilizatori)
  * [Statistica site-ului](#statistica-site-ului)


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

## Implementarea unui controller

Pentru a implementa un controller, ne folosim de moștenire. Clasa de bază `Controller` prezintă următoarea structură:
```php
class Controller{

    protected $viewData;
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->viewData = array();
    }

    public function render($view, $data = array())
    {
        require_once '../app/views/' . $view . '.php';
    }
}
```

Aceasta, la instanțiere, preia cererea de la server și construiește un vector numit `viewData` pentru a reține datele ce vor fi afișate fiecărei pagini din directorul `app/views`.

Fiindcă structura fiecărui controller este specifică Java (numele clasei trebuie să fie identic cu numele fișierului), funcția de render poate afișa fiecare pagină specifică din site.

## Implementarea sistemului de rutare

Router-ul este o componentă esențială în arhitectura MVC (Model-View-Controller), responsabilă de gestionarea cererilor făcute de utilizator. Acest cod implementează clasa Router, care se ocupă de gestionarea rutei și de crearea unei instanțe corespunzătoare a controller-ului pentru a executa acțiunea solicitată de utilizator.

Clasa `Router` este implementată ca un singleton pentru a asigura că există o singură instanță a acesteia în întreaga aplicație. Aceasta are o metodă privată de construcție pentru a preveni crearea de instanțe directe și o metodă statică `getInstance()` pentru a obține instanța existentă sau a crea una nouă, dacă nu există deja.

Metoda `route($request)` este responsabilă pentru gestionarea rutei și executarea acțiunii corespunzătoare. Aceasta primește obiectul Request, care conține informațiile despre cerere, cum ar fi descriptorul (calea cererii) și metoda HTTP. În funcție de descriptor și metoda HTTP, se caută ruta corespunzătoare în tabelul de rute.

Dacă ruta nu este găsită, se trimite un răspuns de eroare "`Page not found`" cu codul `404`. 

```php
if(empty($descriptor))
{
    $response = new Response("Page not found.",404);
    $response->send();
    exit();
}
```

În caz contrar, se obțin middleware-urile asociate cu ruta și se adaugă middleware-urile wildcard în stiva de middleware-uri. Middleware-urile sunt componente intermediare care pot efectua operații precum validarea datelor, autorizarea accesului etc., înainte de a ajunge la controller.

```php
$middlewares = $this->middlewaresTable[$descriptor] ?? [];

// add the wildcard middlewares to the stack
$wildcardMiddlewares = $this->middlewaresTable['*'] ?? [];
$middlewares = array_merge($wildcardMiddlewares, $middlewares);

```


Dacă există middleware-uri definite pentru ruta respectivă, acestea sunt executate în ordine. Fiecare middleware este inclus prin fișierul corespunzător și o instanță a acestuia este creată. Apoi, cererea este trecută prin middleware, care poate modifica cererea sau trimite un răspuns în cazul în care este necesar. Dacă un middleware returnează un obiect `Response`, acesta este trimis direct către client, încheindu-se astfel procesul de rutare.

```php
if(!empty($middlewares))
{
    foreach($middlewares as $middlewareName)
    {
        $middlewareFilename = '../app/middlewares/' . $middlewareName . '.php';

        if (!file_exists($middlewareFilename)) 
        {
            $response = new Response("Middleware not found: $middlewareFilename",500);
            $response->send();
            exit();
        }

        require_once $middlewareFilename;

        $middleware = new $middlewareName;
        $request = $middleware($request);
        // check if middleware returned a response
        if($request instanceof Response)
        {
            $request->send();
            exit();
        }
    }
}
```

Dacă nu există middleware-uri sau dacă acestea au fost executate cu succes, se trece la controller. Numele controller-ului și acțiunea sunt preluate din ruta găsită. Dacă fișierul controller-ului nu există, se trimite un răspuns de eroare "`Controller not found`" cu codul 500. Dacă acțiunea nu există în controller, se trimite un răspuns de eroare "`Action not found`" cu codul 500.

```php
$controllerName = $route['controller'];
$controllerFileName = '../app/controllers/' . $controllerName . '.php';

if(!file_exists($controllerFileName))
{
    // controller file was not found
    $response = new Response("Controller not found.",500);
    $response->send();
    exit();
}

// create an instance of the controller
require_once $controllerFileName;
$controller = new $controllerName($request);

// check if the action exists
$actionName = $route['action'];
if(!method_exists($controller,$actionName))
{
    // action was not found
    $response = new Response("Action not found.",500);
    $response->send();
    exit();
}
```

Dacă controller-ul și acțiunea există, se creează o instanță a controller-ului și se apelează acțiunea corespunzătoare. Acțiunea poate primi datele cererii ca parametri sau poate fi apelată fără niciun parametru. Controller-ul are și o metodă `render($view, $data)` pentru a afișa o pagină specifică și a transmite date către aceasta.

```php
// execute the controller action
// the action will be called with the request data if it receives it
// if not the action will be called with no parameters.
$controller->$actionName();
```

## Parsarea fiecărui formular

În general, pentru a valida fiecare formular, ne folosim de un middleware ce tratează fiecare formular specific fiecărei rute.

Fișierul `FormRules.php` din directorul `conffig/FormRules.php` conține toate informațiile necesare pentru a valida fiecare formular:

```php
<?php

return array(
    'login@process'=>array(
        'email'=>array('type'=>'email','opt'=>false),
        'password'=>array('type'=>'password','opt'=>false)
    ),
    'register@process'=>array(
        'firstName'=>array('type'=>'text','opt'=>false),
        'lastName'=>array('type'=>'text','opt'=>false),
        'emailAddress'=>array('type'=>'email','opt'=>false),
        'password'=>array('type'=>'password','opt'=>false),
        'confirmPassword'=>array('type'=>'password','opt'=>false),
        'dateOfBirth'=>array('type'=>'date','opt'=>false),
        'phoneNumber'=>array('type'=>'phone','opt'=>false),
        'address'=>array('type'=>'text','opt'=>false)
    ),
    // ...
);
```

Acesta reprezintă un vector asociativ pe fiecare descriptor specific unui URL obținut din clasa `Request`. Fiecare câmp este identificat prin atributul `name`. Asupra fiecărui input se specifică ce tip de date se așteaptă să primească și dacă este un câmp opțional sau nu.

Middleware-ul `FormParse.php` preia aceste informații și realizează validarea și parsarea conform următoarelor reguli:

```
 * 1. Sanitaze input
 * 2. Validate data
 * 3. Escape output
```

```php
public function __invoke($req)
{
    if($req->getMethod() === 'GET')
        return $req;

    $descriptor = $req->getDescriptor();
    $rulesTable = require_once '../app/config/FormRules.php';

    $rules = $rulesTable[$descriptor] ?? [];

    $values = $req->getData();

    if(empty($values))
        return $req;

    if(empty($rules))
    {
        $res = new Response('Error at validation',500);
        $res->send();
        die();
    }

    foreach($rules as $inputName => $inputRule)
    {
        $value = $values[$inputName];
        $inputType = $inputRule['type'];
        $isOpt = $inputRule['opt'];

        if($isOpt && empty($value)) {
            continue; // Skip processing optional fields with empty values
        }

        if(!empty($value))
        {
            switch($inputType)
            {
                case 'email':
                    $sanitizedValue = filter_var($value,FILTER_SANITIZE_EMAIL);
                    break;
                case 'password':
                    $sanitizedValue = $this->sanitizePassword($value);
                    break;
                case 'datetime':
                    $sanitizedValue = $this->sanitizeDatetime($value);
                    break;
                case 'integer':
                    $sanitizedValue = $this->sanitizeInteger($value);
                    break;
                case 'text':
                    $sanitizedValue = $this->sanitizeText($value);
                    break;
                case 'phone':
                    $sanitizedValue = $this->sanitizePhoneNumber($value);
                    break;
                case 'range':
                    $sanitizedValue = $this->sanitizeRange($value);
                    break;
                case 'checkbox':
                    $sanitizedValue = $this->sanitizeCheckbox($value);
                    break;
                case 'date':
                    $sanitizedValue = $this->sanitizeDate($value);
                    break;
                case 'time':
                    $sanitizedValue = $this->sanitizeTime($value);
                    break;
                default:
                    break;
            }

            $isValid = $this->validate($inputType,$sanitizedValue);


            if(!$isValid)
            {
                $res = new Response('Error at validation',403);
                return $res;
            }

            $values[$inputName] = $sanitizedValue;
        }
        else
        {
            if(!$isOpt){
                $res = new Response('Required field is not filled',403);
                return $res;
            }
        }
    }

    $req->setData($values);
    return $req;
}
```

**De asemenea, ca măsură de securitate, folosim abstractizarea PDO ce previne injectarea SQL**

## Integrarea de informații

Pentru a integra informații legate de poziția fiecărei locații aflate în aplicație, ne folosim de OpenMeteo API.

Fișierele responsabile pentru a folosi API-ul sunt cele aflate în directorul `core` mai exact: 

- `GeolocationApi.php`
```php
class GeolocationApi{
// utility class
private function __construct() {}
    public static function getGeopos($locationName)
    {
        $locationUrl = urlencode($locationName);
        $url = "https://geocoding-api.open-meteo.com/v1/search?name=".$locationUrl."&count=1&language=en&format=json";
        $data = file_get_contents($url);
        $geopos = json_decode($data, true);

        if(empty($geopos['results'][0]['latitude']) || empty($geopos['results'][0]['longitude']))
            return null;

        return array(
            'latitude' => $geopos['results'][0]['latitude'],
            'longitude' => $geopos['results'][0]['longitude']
        );
    }
}
```

Observăm că operațiile realizate sunt:
1. Realizăm un query pe URL-ul specific API-ul OpenMeteo
2. Obținem datele sub format JSON și le importăm într-o structură de dată de tip vector în PHP
3. În caz de eșec, nu vom realiza niciun vector de tip `Geopos`. Dar, dacă obținem informații de la API, vom returna latitudinea și longitudinea specifică locației trimisă ca parametru.


- `WeatherApi.php`

Aceasta folosește aceeași metodă ca cea prezentată de mai sus.

## Categorii de utilizatori

Prin utilizarea unui middleware, asigurăm faptul că utilizatorii au drepturile specifice categoriei lor.

```php
return array(
    'default' => array('@','home','login','register','reset','tickets','booking','contact','logout','profile'),
    'admin' => array('*')
);
```

După cum observăm, categoria `default` de utilizator prezintă doar acțiuni specifice aplicație de călatorii. Pot să își schimbe informațiile profilului, să vadă biletele specifice lor și să le genereze. Pot trimite formulare de contact și pot planifica călatorii.

Administratorii au acces la toate rutele de pe site.

Middleware-ul `RoleMiddleware.php` se ocupă de permisiuni
```php
class RoleMiddleware implements Middleware
{
    public function __invoke($request)
    {
        $roleRules = require_once '../app/config/RoleRules.php';


        $descriptor = $request->getDescriptor();

        if($descriptor === '@')
            $controller = '@';
        else
        {
            $controllerAction = explode('@', $descriptor);
            $controller = $controllerAction[0];
        }

        $role = $_SESSION['role'] ?? 'default';
        $allowedActions = $roleRules[$role] ?? [];

        if (in_array('*', $allowedActions) || in_array($controller, $allowedActions)) {
            // Access granted
            return $request;
        } else {
            // Access denied
            $response = new Response("Access denied",403);
            $_SESSION['prev_url'] = ProtocolConfig::getProtocol() . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
            return $response;
        }
    }
}
```

Pe scurt, dacă utilizatorul accesează un controller la care are permisiune definită în fișierul `RoleRules.php` din directorul `config`, atunci îi permitem accesul. Altfel, afișăm mesajul `Access denied`.

Acest lucru poate fi configurat foarte ușor pentru a include și mai multe categorii de utilizatori pe viitor. Simplu definim o nouă categorie și specificăm ce controllere pot accesa fiecare utilizator.

**Pentru control mai granular, putem să ne folosim de `descriptor` pentru a oferi drepturi pe fiecare rută specifică!**

## Statistica site-ului

De fiecare dată când un utilizator intră pe site, se înregistrează în baza de date IP-ul său și momentul accesări.

```php
final class HitMiddleware implements Middleware
{
    public function __invoke($req)
    {
        if(!isset($_SESSION['visit']))
        {
            $_SESSION['visit'] = true;
            $ip = $_SERVER['REMOTE_ADDR'];

            $sql = "INSERT INTO hits(ip_address,timestamp) VALUES('$ip',NOW())";
            $conn = DatabaseConnection::getConnection();

            if($conn->query($sql) === false)
            {
                $req = new Response("Error registering hit" . $conn->error,500);
                return $req;
            }
        }

        return $req;
    }
}
```

După, folosind formule de statistică specifice site-urilor web, calculăm fiecare valoare din tabelul de statistică prezent pe pagina principală. Acest lucru se realizează în `StatisticsRepository.php`:

```php
    public static function generate()
    {
        $conn = DatabaseConnection::getConnection();
        $sql = "SELECT COUNT(*) AS total_hits FROM hits";
        $statement = $conn->query($sql);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $totalHits = $row['total_hits'];
        $sql = "SELECT COUNT(DISTINCT ip_address) AS unique_visitors FROM hits";
        $statement = $conn->query($sql);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $uniqueVisitors = $row['unique_visitors'];
        $meanHitsPerVisitor = $totalHits / $uniqueVisitors;
        $sql = "SELECT DATE(timestamp) AS date, COUNT(*) AS hits_per_day FROM hits GROUP BY DATE(timestamp)";
        $statement = $conn->query($sql);
        $hitsPerDay = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $hitsPerDay[$row['date']] = $row['hits_per_day'];
        }
        $maxHitsPerDay = max($hitsPerDay);
        $averageHitsPerDay = array_sum($hitsPerDay) / count($hitsPerDay);
        $variance = 0;
        foreach ($hitsPerDay as $hits) {
            $variance += pow($hits - $averageHitsPerDay, 2);
        }
        $variance /= count($hitsPerDay);
        $stdDevHitsPerDay = sqrt($variance);
        self::$stats = array(
            'totalHits' => $totalHits,
            'uniqueVisitors' => $uniqueVisitors,
            'meanHitsPerVisitor' => $meanHitsPerVisitor,
            'maxHitsPerDay' => $maxHitsPerDay,
            'averageHitsPerDay' => $averageHitsPerDay,
            'stdDevHitsPerDay' => $stdDevHitsPerDay,
            'hitsPerDay' => $hitsPerDay
        );
        return true;
    }
```

Pentru a genera un fișier PDF cu statistica, ne folosim de librăria `FPDF`:

```php
function generateReport($stats)
{
    $this->pdf->AddPage();

    // Report title
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(0, 10, 'Statistica accesari', 0, 1, 'L');
    $this->pdf->Ln(10);

    // Statistics table
    $this->pdf->SetFont('Arial', 'B', 12);
    $cellPadding = 2;
    $this->pdf->Cell(40, 10, 'Statistica', 1, 0, 'L', false, '', 0, $cellPadding);
    $this->pdf->Cell(60, 10, 'Valoare', 1, 1, 'L', false, '', 0, $cellPadding);
    $this->pdf->SetFont('Arial', '', 12);

    $this->pdf->Cell(40, 10, 'Total accesari', 1, 0, 'L', false, '', 0, $cellPadding);
    $this->pdf->Cell(60, 10, $stats['totalHits'], 1, 1, 'L', false, '', 0, $cellPadding);
    $this->pdf->Cell(40, 10, 'Vizitatori unici', 1, 0, 'L', false, '', 0, $cellPadding);
    $this->pdf->Cell(60, 10, $stats['uniqueVisitors'], 1, 1, 'L', false, '', 0, $cellPadding);
    $this->pdf->Cell(40, 10, 'Medie acc. viz.', 1, 0, 'L', false, '', 0, $cellPadding);
    $this->pdf->Cell(60, 10, $stats['meanHitsPerVisitor'], 1, 1, 'L', false, '', 0, $cellPadding);
    $this->pdf->Cell(40, 10, 'Max acc. pe zi', 1, 0, 'L', false, '', 0, $cellPadding);
    $this->pdf->Cell(60, 10, $stats['maxHitsPerDay'], 1, 1, 'L', false, '', 0, $cellPadding);
    $this->pdf->Cell(40, 10, 'Medie acc. pe zi', 1, 0, 'L', false, '', 0, $cellPadding);
    $this->pdf->Cell(60, 10, $stats['averageHitsPerDay'], 1, 1, 'L', false, '', 0, $cellPadding);
    $this->pdf->Cell(40, 10, 'Deviatie std.', 1, 0, 'L', false, '', 0, $cellPadding);
    $this->pdf->Cell(60, 10, $stats['stdDevHitsPerDay'], 1, 1, 'L', false, '', 0, $cellPadding);
    $this->pdf->Ln(10);

    // Generate the graph
    $this->pdf->SetFont('Arial', 'B', 14);
    $this->pdf->Cell(0, 10, 'Accesari pe zi', 0, 1, 'L');
    
    $graphWidth = 160;
    $graphHeight = 80;
    $margin = 20;
    $barSpacing = 0.5; 
    $xStart = $this->pdf->GetX();
    $yStart = $this->pdf->GetY();

    // Calculate the scale factor for the bars
    $maxHits = max($stats['hitsPerDay']);
    $scaleFactor = ($graphHeight - $barSpacing * (count($stats['hitsPerDay']) - 1)) / $maxHits;

    // Draw the bars for each day
    $barWidth = ($graphWidth - 2 * $margin) / count($stats['hitsPerDay']);
    $x = $xStart + $margin;
    foreach ($stats['hitsPerDay'] as $hits) {
        $barHeight = $hits * $scaleFactor;
        $this->pdf->Rect($x, $yStart + $graphHeight - $barHeight, $barWidth, $barHeight, 'F');
        $x += $barWidth + $barSpacing;
    }

    // Draw the axis and labels
    $this->pdf->SetFont('Arial', '', 10);
    $this->pdf->SetDrawColor(0, 0, 0);
    $this->pdf->Line($xStart + $margin, $yStart, $xStart + $margin, $yStart + $graphHeight); // Y-axis
    $this->pdf->Line($xStart + $margin, $yStart + $graphHeight, $xStart + $graphWidth, $yStart + $graphHeight); // X-axis

    // Add labels for each day
    $x = $xStart + $margin;
    $labelMargin = 5;
    foreach ($stats['hitsPerDay'] as $date => $hits) {
        $this->pdf->Text($x + $barWidth / 2, $yStart + $graphHeight + $labelMargin, substr($date, 5)); // Display only the month and day
        $x += $barWidth + $barSpacing;
    }

    $this->pdf->Ln($graphHeight + 2 * $labelMargin);

    // Output the PDF
    $this->pdf->Output('hits_report.pdf', 'I');
}
```

Acest cod este prezent în `HitReport.php` din directorul `Model.php` fiind o prelucrare ce nu ține de baza de date.

