<?php

declare (strict_types=1);

/**
 * Hämtar rutt information från inskickad URI
 * @param string $querystring
 * @param string $method
 * @return Route
 */
function getRoute(string $querystring, string $method = "GET"): Route {
    // Ta bort avslutande snedstreck
    if (substr($querystring, -1) === "/") {
        $querystring = substr($querystring, 0, -1);
    }

    // Dela upp strängen till en array
    $uri = explode("/", $querystring);
    $parametrar = [];

    // Räkna antalet delar och fördela dem mellan rutt och parametrar
    switch (count($uri)) {
        case 0:
        case 1:
        case 2:
            $rutt = "/";
            break;
        case 3:
            $rutt = "/{$uri[2]}/";
            break;
        default :
            $rutt = "/{$uri[2]}/";
            $parametrar = array_slice($uri, 3);
    }

    // Kontrollera inskickad metod och läs av eventuell $_POST[action]
    if ($method === "POST") {
        $metod = RequestMethod::POST;
        if (isset($_POST["action"]) && $_POST["action"] === "delete") {
            $metod = RequestMethod::DELETE;
        } elseif (isset($_POST["action"]) && $_POST["action"] === "save" && count($params) > 0) {
            $metod = RequestMethod::PUT;
        }
    } else {
        $metod = RequestMethod::GET;
    }

    // Skapa och returnera ett Route-objekt
    return new Route($rutt, $parametrar, $metod);
}
