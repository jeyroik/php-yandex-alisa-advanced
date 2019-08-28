# [deprecated] php-yandex-alisa-advanced
Библиотека для реализации навыков для Алисы Яндекса (Library for creating skills for Yandex.Alice).

Данная реализация больше не поддерживается. Пожалуйста, используйте [jeyroik/alice-extas](https://github.com/jeyroik/alice-extas "Перейти к библиотеке alice-extas"), для которой есть готовый пример использования [jeyroik/alice-extas-example](https://github.com/jeyroik/alice-extas-example "Перейти к alice-extas-example").

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

# release notes

В будущем планируется добавить:

1. Простую обёртку для репозитория. Для случаев, когда вам нужно в навыке обрабатывать элементы списка.
2. Пример обработчика, использующего обёртку для репозитория.
3. Переписать логирование на Monolog.
4. Добавить debug-режим для отладки.
