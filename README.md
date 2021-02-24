[ENGLISH VERSION](/README_EN.md)

# Snowdog Academy - zadanie rekrutacyjne

Zadanie rekrutacyjne polega na rozbudowaniu funkcjonalności aplikacji służącej do kupowania kryptowalut. W aktualnej wersji zarejestrowani użytkownicy mogą wyświetlać listę dostępnych kryptowalut.

## Uruchomienie aplikacji
Aplikację można uruchomić bezpośrednio na hoście lub z wykorzystaniem Dockera.

### Docker
Należy utworzyć plik `.env` w głównym katalogu (na bazie `.env.example`).

Z głównego folderu aplikacji uruchomić:
```
docker-compose up -d
``` 
Zostanie utworzony kontener z aplikacją oraz bazą danych. Następnie należy zainstalować wymagane biblioteki:
```
docker exec -it snowdog-academy_php_1 sh -c 'composer install'
```
Aplikacja będzie dostępna pod adresem http://127.0.0.1:8000.

Aby usunąć utworzone kontenery należy wykonać komendę:
```
docker-compose down
```

### Host
Wymagania:

* [Composer](https://getcomposer.org/)
* [PHP 7.4](https://www.php.net/manual/en/install.php)
* [MySQL 5.7](https://dev.mysql.com/doc/refman/5.7/en/installing.html)

W folderze głównym uruchomić komendę instalującą wymagane zależności:
```
composer install
```

Następnie należy uruchomić wbudowany w PHP serwer:
```
php -S 0.0.0.0:8000 -t web/
```
Aplikacja dostępna będzie pod adresem http://127.0.0.1:8000.

## Stworzenie struktury bazy danych
Konfiguracja bazy danych znajduje się w pliku `config.ini` - można go utworzyć na podstawie pliku `config.ini.example` oraz wypełnić odpowiednimi danymi.

Po pierwszym uruchomieniu aplikacji należy wykonać skrypt, który utworzy niezbędne tabele w bazie danych oraz doda do nich kilka testowych pozycji.

Dla środowiska opartego o Dockera:
```
docker exec -it snowdog-academy_php_1 sh -c 'php console.php migrate_db'
```

Dla środowiska utworzonego na hoście (uruchamiane z głównego folderu aplikacji):
```
php console.php migrate_db
```

## Zadania

### Zadanie 0 
Zrób forka tego repozytorium i wszystkie commity wysyłaj do niego. Każde zadanie powinno być osobnym, odpowiednio opisanym commitem.

### Zadanie 1
Dodaj możliwość kupowania wybranej kryptowaluty.

### Zadanie 2
Dodaj możliwość sprzedawania posiadanej kryptowaluty.

### Zadanie 3
Dodaj pobieranie aktualnych cen kryptowalut z API [CoinCap](https://docs.coincap.io/), [CoinGecko](https://www.coingecko.com/api/documentations/v3) lub innego wybranego przez Ciebie za pomocą komendy `php console.php update_prices`. W przypadku tych dwóch propozycji kolumna `id` w tabeli `cryptocurrencies` wskazuje na ID waluty w tych systemach.

### Zadanie 4
Dodaj nową pozycję w menu o nazwie **Add Funds**. Po wejściu w nią powinien wyświetlić się prosty formularz pozwalający na dodanie środków do konta (jedno pole na kwotę oraz przycisk wysłania formularza).

## Uwagi
Jeżeli uważasz, że kod aplikacji wymaga refaktoryzacji, że coś można napisać lepiej lub wydajniej niż jest to zrobione teraz - możesz to zrobić. Na pewno wpłynie to pozytywnie na wyniki rekrutacji.
