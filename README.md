# ItSquad Test task

![Magento 2 Standards](https://github.com/eugene-petrov/itsquad-test-task/actions/workflows/magento-standards.yml/badge.svg)
![M2 PHPStan](https://github.com/eugene-petrov/itsquad-test-task/actions/workflows/phpstan.yml/badge.svg)
![M2 Mess Detector](https://github.com/eugene-petrov/itsquad-test-task/actions/workflows/mess-detector.yml/badge.svg)
![M2 Copy Paste Detector](https://github.com/eugene-petrov/itsquad-test-task/actions/workflows/copy-paste-detector.yml/badge.svg)

The initial tech task:

Stwórz moduł Magento 2 w którym zostanie zaimplementowany controller '/pokedex' z polem, w którym możemy wpisać ID pokemona i po kliknięciu „szukaj” otrzymamy wynik wyszukiwania (np. nazwa pokemona i jakies podstawowe dane).
1. Utworzenie nowego modułu, a w nim controllera '/pokedex'
2. Pobieranie danych z zewnętrznego API https://pokeapi.co/
3. Utworzenie pluginu 'after' na funkcji, która zwraca dane z API, w którym będzie dodany logger zapisujący dane na temat wyszukiwania do pliku pokemon.log. Np. Query: 11 - Result: metapod
