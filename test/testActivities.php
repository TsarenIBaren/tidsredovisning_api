<?php

declare (strict_types=1);
require_once '../src/activities.php';
/**
 * Funktion för att testa alla aktiviteter
 * @return string html-sträng med resultatet av alla tester
 */
function allaActivityTester(): string {
    // Kom ihåg att lägga till alla funktioner i filen!
    $retur = "";
    $retur .= test_HamtaAllaAktiviteter();
    $retur .= test_HamtaEnAktivitet();
    $retur .= test_sparaNyAktivitetAktivitet();
    $retur .= test_UppdateraAktivitet();
    $retur .= test_RaderaAktivitet();

    return $retur;
}

/**
 * Funktion för att testa en enskild funktion
 * @param string $funktion namnet (utan test_) på funktionen som ska testas
 * @return string html-sträng med information om resultatet av testen eller att testet inte fanns
 */
function testActivityFunction(string $funktion): string {
    if (function_exists("test_$funktion")) {
        return call_user_func("test_$funktion");
    } else {
        return "<p class='error'>Funktionen test_$funktion finns inte.</p>";
    }
}

/**
 * Tester för funktionen hämta alla aktiviteter
 * @return string html-sträng med alla resultat för testerna 
 */
function test_HamtaAllaAktiviteter(): string {
    $retur = "<h2>test_HamtaAllaAktiviteter</h2>";
    try {
    $svar=hamtaAllaAktiviteter();
    //kontrollerar statuskoden
    if(!$svar->getStatus()===200) {
        $retur .="<p class='error'>Felaktig statuskod förväntade 200 fick {$svar->getStatus()}</p>";
    } else {
        $retur .="<p class='ok'>Korrekt statuskod 200</p>";
    }
    //kontrollerar egenskaperna
    foreach ($svar->getContent() as $aktivitet) {
        if($aktivitet->activity==="") {
            $retur .= "<p class='error'>Egenskapen id saknas</p>";
            break;
        }
        if(!isset($aktivitet->activity)) {
            $retur .="<p class='error'Egenskapen activity saknas</p>";
            break;
        }
    }
    }   catch (Exception $ex){
    $retur .= "<p class='error'> Något gick fel, meddelandet säger: <br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

/**
 * Tester för funktionen hämta enskild aktivitet
 * @return string html-sträng med alla resultat för testerna 
 */
function test_HamtaEnAktivitet(): string {
    $retur = "<h2>test_HamtaEnAktivitet</h2>";
    try{
            // Testa negativt tal
        $svar= hamtaEnskildAktivitet(-1);
        if ($svar->getStatus()===400) {
            $retur .= "<p class='ok'>Hämta enskild med negativt tal ger förväntat svar 400</p>";
        } else {
            $retur .="<p class='error'>Hämta enskild med negativt tal ger [$ex->getStatus()] "
            . "inte förväntat svar 400</p>";
        }

            // Testa stort tal
        $svar= hamtaEnskildAktivitet(100);
        if ($svar->getStatus()===400) {
            $retur .= "<p class='ok'>Hämta enskild med stort tal ger förväntat svar 400</p>";
        } else {
            $retur .="<p class='error'>Hämta enskild med stort (100) ger [$ex->getStatus()] "
            . "inte förväntat svar 400</p>";
        }

            //Testa bokstäver
        $svar= hamtaEnskildAktivitet((int) "sju");
        if ($svar->getStatus()===400) {
            $retur .= "<p class='ok'>Hämta enskild med bokstäver ger förväntat svar 400</p>";
        } else {
            $retur .="<p class='error'>Hämta enskild med bokstäver ('sju') tal ger [$ex->getStatus()] "
            . "inte förväntat svar 400</p>";
        }

                // Testa giltigt tal
        $svar= hamtaEnskildAktivitet(3);
        if ($svar->getStatus()===200) {
            $retur .= "<p class='ok'>Hämta enskild med giltigt tal ger förväntat svar 200</p>";
        } else {
            $retur .="<p class='error'>Hämta enskild med 3 ger [$ex->getStatus()] "
            . "inte förväntat svar 200</p>";
        }
    } catch (Exception $ex) {
        $retur .= "<p class='error'> något gick fel, meddelandet säger:{$ex->getMessage()}</p>";
    }
return $retur;
}


/**
 * Tester för funktionen spara aktivitet
 * @return string html-sträng med alla resultat för testerna 
 */
function test_sparaNyAktivitetAktivitet(): string {
    $retur = "<h2>test_sparaNyAktivitetAktivitet</h2>";

//testa tom aktivitet
$aktivitet="";
$svar=sparaNyAktivitet($aktivitet);
if($svar->getStatus()===400) {
    $retur .= "<p class='ok'>Spara tom aktivitet misslyckades som förväntat</p>";
} else {
    $retur .= "<p class='error'>Spara tom aktivitet returnerade {$svar->getStatus()} förväntades 400</p>";
}

//testa lägg till 
$db= connectDb();
$db->beginTransaction();
$aktivitet="Nizze";
$svar= sparaNyAktivitet($aktivitet);
if($svar->getStatus()===200) {
    $retur .= "<p class='ok'>Spara aktivitet lyckades som förväntat</p>";
} else {
    $retur .= "<p class='error'>Spara aktivitet returnerade {$svar->getStatus()} förväntades 200</p>";
}
$db->rollBack();
//testa lägg till samma

$db->beginTransaction();
$aktivitet="Nizze";
$svar= sparaNyAktivitet($aktivitet);
$svar= sparaNyAktivitet($aktivitet);
if($svar->getStatus()===400) {
    $retur .= "<p class='ok'>Spara aktivitet två gånger misslyckades som förväntat</p>";
} else {
    $retur .= "<p class='error'>Spara aktivitet två gånger returnerade {$svar->getStatus()} förväntades 400</p>";
}
$db->rollBack();

return $retur;
}

/**
 * Tester för uppdatera aktivitet
 * @return string html-sträng med alla resultat för testerna
 */
function test_UppdateraAktivitet(): string {
    $retur = "<h2>test_UppdateraAktivitet</h2>";

    try {
    //testa uppdatera med ny text i aktivitet
    $db = connectDb();
    $db->beginTransaction();
    $nypost=sparaNyAktivitet("Nizze");
    if($nypost->getStatus()!==200) {
        throw new Exception("Skapa ny post misslyckades, 10001");
    }
    $uppdateringsId=(int) $nypost->getContent()->id;
    $svar=uppdateraAktivitet($uppdateringsId, "Pelle");
        if($svar->getStatus()===200 && $svar->getContent()->result===true) {
        $retur .= "<p class='ok'>Uppdatera aktivitet lyckades</p>";
    } else {
        $retur .= "<p class='error'>Uppdatera aktivitet misslyckades ";
                if (isset($svar->getContent()->result)) {
                    $retur .= var_export($svar->getContent()->result) . " returnerades ustället för förväntat 'true'";
                } else {
                    $retur .= "{$svar->getStatus()} returnerades istället för förväntant 200";
                }
                $retur .= "</p>";
    }

    $db->rollBack();

    //testa uppdatera med samma text i aktivitet

    $db->beginTransaction();
    $nypost=sparaNyAktivitet("Nizze");
    if($nypost->getStatus()!==200) {
        throw new Exception("Skapa ny post misslyckades, 10001");
    }
    $uppdateringsId=(int) $nypost->getContent()->id;
    $svar=uppdateraAktivitet($uppdateringsId, "Nizze");
    if($svar->getStatus()===200 && $svar->getContent()->result===false) {
        $retur .= "<p class='ok'>Uppdatera aktivitet med samma text lyckades</p>";
    } else {
        $retur .= "<p class='error'>Uppdatera aktivitet med samma text misslyckades ";
                if (isset($svar->getContent()->result)) {
                    $retur .= var_export($svar->getContent()->result) . " returnerades ustället för förväntat 'false'";
                } else {
                    $retur .= "{$svar->getStatus()} returnerades istället för förväntant 200";
                }
                $retur .= "</p>";
    }

    $db->rollBack();

    //cipis bugg - blah blah blah

    $db->beginTransaction();
    $nypost=sparaNyAktivitet("Nizze");
        if($nypost->getStatus()!==200) {
            throw new Exception("Skapa ny post misslyckades, 10001");
        }
        $uppdateringsId=(int) $nypost->getContent()->id;
        $svar=uppdateraAktivitet($uppdateringsId, "");
        if($svar->getStatus()===400) {
            $retur .= "<p class='ok'> Uppdatera aktivitet med mellanslag misslyckades som förväntat</p>";
        } else {
            $retur .= "<p class='error'> Uppdatera aktivitet med mellanslag returnerades"
                    . " {$svar->getStatus()} instället för förväntat 400 </p>";
        }
    $db->rollBack();


    //testa med tom aktivitet

    $db->beginTransaction();
    $nypost=sparaNyAktivitet("Nizze");
    if($nypost->getStatus()!==200) {
        throw new Exception("Skapa ny post misslyckades, 10001");
    }
    $uppdateringsId=(int) $nypost->getContent()->id;
    $svar=uppdateraAktivitet($uppdateringsId,"");
    if($svar->getStatus() === 400) {
        $retur .= "<p class='ok'> Uppdatera aktivitet med tom text misslyckades</p>";
    } else {
        $retur .= "<p class='error'> Uppdatera aktivitet med tom text returnerades"
                . " {$svar->getStatus()} instället för förväntat 400 </p>";
    }
    $db->rollBack();

    //testa med ogiltigt id (-1)

    $db->beginTransaction();
    $uppdateringsId = -1;
    $svar = uppdateraAktivitet($uppdateringsId, "Test");
    if($svar->getStatus() === 400) {
        $retur .= "<p class='ok'> Uppdatera aktivitet med ogiltigt id (-1) text misslyckades</p>";
    } else {
        $retur .= "<p class='error'>  uppdatera aktivitet med ogiltigt id (-1) returnerades"
                . " {$svar->getStatus()} instället för förväntat 400 </p>";
    }
    $db->rollBack();

    //testa med obefintligt id (100)

    $db->beginTransaction();
    $uppdateringsId = 100;
    $svar = uppdateraAktivitet($uppdateringsId, "Test");
    if($svar->getStatus() === 200 && $svar->getContent()->result===false) {
        $retur .= "<p class='ok'>Uppdatera aktivitet med obefintligt id (100) misslyckades som förväntat</p>";
    } else {
        $retur .= "<p class='error'> Uppdatera aktivitet med obefintlig id (100) misslyckades";
                if (isset($svar->getContent()->result)) {
                    $retur .= var_export($svar->getContent()->result) . " returnerades ustället för förväntat 'false'";
                }else {
                    $retur .= "{$svar->getStatus()} returnerades istället för förväntant 200";
                }
                $retur .= "</p>";
    
    }  
    $db->rollBack();

    } catch (exception $ex) {
        $db->rollBack();
        if($ex->getCode()===10001){
            $retur .= "<p class='error'>Spara ny post misslyckades, uppdatera går inte att testa!!!</p>"; 
        } else {
            $retur .= "<p class='error'>Fel inträffade:<br>{$ex->getMessage()}</p>";
        }
    }

    return $retur;
}

/**
 * Tester för funktionen radera aktivitet
 * @return string html-sträng med alla resultat för testerna 
 */
function test_RaderaAktivitet(): string {
    $retur = "<h2>test_RaderaAktivitet</h2>";

try {
    // Testa felaktigt id (-1)
    $svar = raderaAktivitet (-1);
    if ($svar->getStatus() == 400) {
        $retur .= "<p class='ok'>Radera post med negativt tal ger förväntat svar 400</p>";
    } else {
        $retur .= "<p class='error'>Radera post med negativt tal ger {$svar->getStatus()} "
        . "inte förväntat svar 400</p>";
    }

    // Testa felaktigt id (sju)
    $svar = raderaAktivitet((int)"sju");
    if ($svar->getStatus() == 400) {
        $retur .= "<p class='ok'>Radera post med felaktigt id ('sju') ger förväntat svar 400</p>";
    } else {
        $retur .= "<p class='error'>Radera post med felaktigt id ('sju') tal ger {$svar->getStatus()} "
        . "inte förväntat svar 400</p>";
    }

    // Testa id som inte finns (100)
    $svar = raderaAktivitet (100);
    if ($svar->getStatus() == 200 && $svar->getContent()->result===false) {
        $retur .= "<p class='ok'>Radera post med id som inte finns (100) ger förväntat svar 200</p>";
    } else {
        $retur .= "<p class='error'>Radera post med id som inte finns (100) ger {$svar->getStatus()} "
        . "inte förväntat svar 200</p>";
    }
    // Testa radera nyskapat id
    $db=connectDb();
    $db->beginTransaction();
    $nyPost=sparaNyAktivitet("Nizze");
    if($nyPost->getStatus()!==200) {
        throw new Exception("Skapa ny post misslyckades, 10001");
    }

    $nyttId = (int) $nyPost ->getContent()->id; // nya postens id
    $svar = raderaAktivitet($nyttId);
    if ($svar->getStatus() == 200 & $svar->getContent()->result===true) {
        $retur .= "<p class='ok'>Radera post med nyskapat id som ger förväntat svar 200 "
            ."och result=false</p>";
    } else {
        $retur .= "<p class='error'>Radera post med id som inte finns (100) ger ($svar->getStatus()) "
            . "inte förväntat svar 200</p>";
    }
    $db->rollBack();

} catch (Exception $ex) {
    if($ex->getCode()===10001){
        $retur .= "<p class='error'>Spara ny post misslyckades, uppdatera går inte att testa!!!</p>"; 
    } else {
        $retur .= "<p class='error'>Fel inträffade:<br>{$ex->getMessage()}</p>";
    }
}
    return $retur;
}
