<?php
// export-postman.php

// Définir l'URL de base sans slash final
$baseUrl = rtrim("http://localhost:8000", '/');

// Chemin du fichier de routes généré par Laravel
$routesFile = __DIR__ . '/routes.json';
if (!file_exists($routesFile)) {
    die("Le fichier routes.json n'existe pas. Veuillez d'abord générer la liste des routes avec 'php artisan route:list --json > routes.json'.\n");
}

// Lire et décoder le fichier JSON contenant les routes
$routesData = json_decode(file_get_contents($routesFile), true);
if (!$routesData) {
    die("Erreur lors de la lecture ou du décodage de routes.json.\n");
}

// Initialiser la structure de la collection Postman
$postmanCollection = [
    "info" => [
        "_postman_id" => uniqid(),
        "name"       => "APP_NS_V1",
        "schema"     => "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    ],
    "item" => []
];

// Regrouper les routes par le premier segment d'URI (exemple : "users", "roles", etc.)
$groupedRoutes = [];
foreach ($routesData as $route) {
    // Récupérer l'URI et retirer le slash initial s'il existe
    $uri = ltrim($route['uri'] ?? '', '/');
    
    // On ne traite que les routes commençant par "api"
    if (strpos($uri, 'api') !== 0) {
        continue;
    }
    
    // Pour le regroupement, supprimer le préfixe "api/" s'il est présent
    $uriForGrouping = $uri;
    if (strpos($uri, 'api/') === 0) {
        $uriForGrouping = substr($uri, strlen('api/'));
    }
    $segments = explode('/', trim($uriForGrouping, '/'));
    $group = $segments[0] ?? 'Autres';

    // Construire l'URL complète sans double slash
    $requestUrl = $baseUrl . '/' . $uri;

    // Créer la structure de la requête Postman
    $request = [
        "method" => strtoupper($route['method'] ?? 'GET'),
        "header" => [],
        "url"    => [
            "raw"      => $requestUrl,
            "protocol" => "http",
            "host"     => ["localhost"],
            "port"     => "8000",
            "path"     => explode('/', $uri)
        ]
    ];
    
    $item = [
        "name"    => $uri,
        "request" => $request,
        "response"=> []
    ];
    
    // Ajouter la requête dans le groupe correspondant
    $groupedRoutes[$group][] = $item;
}

// Construire les items de la collection en regroupant par section
foreach ($groupedRoutes as $groupName => $items) {
    $postmanCollection["item"][] = [
        "name" => ucfirst($groupName),
        "item" => $items
    ];
}

// Exporter la collection au format JSON dans un fichier
$outputFile = __DIR__ . '/postman_collection.json';
file_put_contents($outputFile, json_encode($postmanCollection, JSON_PRETTY_PRINT));

echo "La collection Postman a été générée avec succès dans le fichier : $outputFile\n";
