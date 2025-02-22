## Package Laravel Leaflet

[![Laravel 10|11](https://img.shields.io/badge/Laravel-10|11-orange.svg)](http://laravel.com)
[![Latest Stable Version](https://img.shields.io/packagist/v/ijideals/laravel-leaflet)](https://packagist.org/packages/ijideals/laravel-leaflet)
[![Total Downloads](https://poser.pugx.org/ijideals/laravel-leaflet/downloads.png)](https://packagist.org/packages/ijideals/laravel-leaflet)
[![License](https://img.shields.io/github/license/mashape/apistatus.svg)](https://packagist.org/packages/ijideals/laravel-leaflet)

Permet de créer des pages avec des cartes Leaflet de manière intégrée avec Laravel.

Comment installer :

Installer un projet Laravel de base.

```bash
composer create-project laravel/laravel:^10.0 example-app
 
cd example-app
```

Exécuter cette commande pour installer le paquet et configurer le projet Laravel

```bash
composer require ijideals/laravel-leaflet
```

Exécuter le serveur

```bash
php artisan serve
```

Ouvrez votre navigateur "http://127.0.0.1:8000/map"

Vous verrez :

<img src="https://github.com/ginocampra/laravel-leaflet/blob/master/images/itworks.png" alt="LaraLeaflet" height="350">

## Composant Blade

Vous pouvez utiliser ceci pour injecter une carte Leaflet dans vos vues Blade

Implémenter $title et les données de la carte dans votre contrôleur et les passer à la vue.  Vous pouvez également utiliser les icônes Font Awesome pour personnaliser vos marqueurs. La gestion du dark mode est intégrée.

```php

    $options = [
        'center' => [
            'lat' => -23.347509137997484,
            'lng' => -47.84753617004771
        ],
        'googleview' => true,
        'zoom' => 18,
        'zoomControl' => true,
        'minZoom' => 13,
        'maxZoom' => 18,
        'darkMode' => true // Active le dark mode
    ];
    $initialMarkers = [
        [
            'position' => [
                'lat' => -23.347509137997484,
                'lng' => -47.84753617004771
            ],
            'draggable' => false,
            'title' => 'Tatuí - SP',
            'icon' => 'fas fa-map-marker-alt' // Utilisation d'une icône Font Awesome
        ]
    ];
    $initialPolygons = [
        [
            [-23.34606370264136 , -47.84818410873414],
            [-23.34575341324051 , -47.84759938716888],
            [-23.34615728184211 , -47.84729361534119],
            [-23.34651189716213 , -47.84792125225068]
        ]
    ];
    $initialPolylines = [
            [
                [-23.348914298657980 , -47.850147485733040],
                [-23.347850469110245 , -47.848109006881714],
                [-23.349209805352476 , -47.847293615341194],
                [-23.347781516900888 , -47.844675779342660]               
            ]
    ];
    $initialRectangles = [
        [
            [-23.347683013682527 , -47.85067319869996],
            [-23.346727528670904 , -47.84879565238953]
        ]
    ];
    $initialCircles = [
        [
            'position' => [ 
                'lat' => -23.346569922234977, 
                'lng' => -47.84376382827759
            ],
            'radius' => 80.68230575309364
        ]
    ];
    $title = 'Carte Initiale';
    
    return view('welcome',compact('options','title','initialMarkers','initialPolygons','initialPolylines','initialRectangles','initialCircles'));

```

Déposer le composant blade dans la vue

```php

        <div>
            <x-laravel-map :title="$title" :initialMarkers="$initialMarkers" :initialPolygons="$initialPolygons" :initialPolylines="$initialPolylines" :initialRectangles="$initialRectangles" :initialCircles="$initialCircles" :options="$options"/>
        </div>

```

## Licence

The MIT License (MIT). Veuillez consulter [License File](https://github.com/ginocampra/laravel-leaflet/blob/master/LICENSE.md) pour plus d'informations.
