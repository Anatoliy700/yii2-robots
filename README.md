Robots расширение для Yii2
--------------------------

Генерирует контент robots.txt файла

Установка
------------

Устанвливается через [Composer](http://getcomposer.org/download/) 

```bash
composer require anatoliy700/yii2-robots
```
или добавить 

```json
"anatoliy700/yii2-robots": "*"
```

в разделе `require` вашего composer.json файла.

Использование
-------------

Подключить в конфигурации приложения в разделе модули:

```php
'modules' => [
    'robots' => [
         'class' => 'anatoliy700\robots\Module',
    ]
]
```

Для установки правил маршрутизации автоматически добавть в bootstrap

```php
'bootstrap' => ['robots']
```

Описать зависимости в контейнере

```php
    'container' => [
        'definitions' => [
             'anatoliy700\robots\IRobots' => [
                 'class' => 'anatoliy700\robots\Robots',
                 'directives' => [
                     'User-Agent' => '*',
                     'Disallow' => [
                         '/backend',
                     ],
                 ],
             ],
             'anatoliy700\robots\directives\IDirective' => 'anatoliy700\robots\directives\Directive',
        ],
    ]
```

В свойстве `directives` можно указать директивы, которые будут отдаваться роботу.
Если `User-Agent` не будет указан, то будет установлен по умолчанию `User-Agent: *`.

В свойстве `blockResource` можно заблокировать весь ресурс,
 в этом случае все остальные директивы будут проигнорированы.

Итеграция блокировки сраниц в модули контента
---------------------------------------------

Для интеграции необходимо в модель, которая описывает контент страницы в админке, добавить поведение:
```php

  'RobotsBehavior' => [
      'class' => 'anatoliy700\robots\behaviors\RobotsModelBehaviors',
      'route' => ['content/default/index', 'alias' => 'alias']
  ]
```

В свойстве `route` необходимо обязательно маршрут и параметры,
в значение в массиве параметров указывается имя свойства данной модели из которого надо подставить данные.

В правила модели добавить:
```php
[['robotsBlockingFlag'], 'safe'],
```

Так же необходимо добавить в форму `checkbox`:
```php
<?= $form->field($model, 'robotsBlockingFlag')->checkbox() ?>
```    

Описать зависимость репозитория, который реализует хранилище для динамических директив :
```php
  'container' => [
      'definitions' => [
           'anatoliy700\robots\repositories\IRepository' => [
               'class' => 'anatoliy700\robots\repositories\ActiveRecordRepository',
               'activeRecord' => 'anatoliy700\robots\repositories\adapters\RedisAdapter',
           ],
      ],
  ]
```

У данного репозитория есть свойство `activeRecord`, в котором указывается класс адаптера(в данном случае реализован адаптер для `Redis`).
Если использовать адаптер для `Redis`, необходимо подключить компонент, который обеспечивает работу с `Redis`:

```php
  'components' => [
      'redis' => [
         'class' => 'yii\redis\Connection',
         'hostname' => 'REDIS_HOST',
         'port' => 'REDIS_PORT',
         'database' => 2,
      ],
  ]
```

Для генерации `URI` из роута, необходима описать зависимость `IRouteGenerator`:
```php
    'container' => [
        'definitions' => [
            'anatoliy700\robots\generators\IRouteGenerator' => [
                'class' => 'anatoliy700\robots\generators\RouteGenerator',
                'urlManager' => [
                    'class' => 'yii\web\UrlManager',
                    'enablePrettyUrl' => true,
                    'showScriptName' => false,
                    'rules' => [...],
                ],
            ],
        ],
    ]
```
В свойство `urlManager` необходимо передать конфигурацию urlManager с действующими правилами маршрутизации.
