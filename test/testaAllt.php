<?php

declare (strict_types=1);

require_once './testActivities.php';
require_once './testCompilation.php';
require_once './testTasks.php';

/**
 * Testar alla funktioner som heter test_
 * @return string html-sträng med alla resultat för testerna
 */
function testaAllaFunktioner(): string {
    // Läser in alla definierade funktioner
    $allaFunktioner = get_defined_functions();
    $retur = "<h1>Testa alla funktionsanrop</h1>";

    // Loopa igenom alla user-funktioner
    foreach ($allaFunktioner['user'] as $funk) {
        if (substr($funk, 0, 5) === "test_") {
            // Anropa funktioner vars namn inleds med test_
            $retur .= call_user_func($funk) . "\n";
        }
    }

    return $retur;
}
