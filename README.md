# [deprecated] php-yandex-alisa-advanced
Библиотека для реализации навыков для Алисы Яндекса (Library for creating skills for Yandex.Alice).

Данная реализация больше не поддерживается. Пожалуйста, используйте [jeyroik/extas-alice](https://github.com/jeyroik/extas-alice "Перейти к библиотеке extas-alice"), для которой есть готовый пример использования [jeyroik/extas-alice-example](https://github.com/jeyroik/extas-alice-example "Перейти к extas-alice-example").

# install

```
composer require jeyroik/yandex-alisa-advanced:*
```

# using

1. В configs/skills.php пропишите свой навык - токен и алиас.
2. Напишите обработчик для планируемого запроса.
3. В configs/skills_dispatchers.php пропишите для своего навыка (по его алиасу) нужные обработчики.
4. Запустите Алису:

```
jeyroik\alice\Alice::run()
```

# using если не понятно

Воспользуйтесь исходниками: в configs, в диспетчерах и т.п. есть тестовые реализации, которые помогут разобраться.
