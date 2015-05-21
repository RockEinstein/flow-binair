<?php

require 'vendor/autoload.php';


echo "<pre>";

/**
 * Array de Configuração do fluxo
 * [tokenAtual] => array(
 *      [proximoToken] => "Caminho\Para\Classe\Validação->metodo && Caminho\Para\Classe\Validação->metodo",
 *      [proximoToken] => "Caminho\Para\Classe\Validação->metodo || Caminho\Para\Classe\Validação->metodo",
 *      [proximoToken] => "(Caminho\Para\Classe\Validação->metodo || Caminho\Para\Classe\Validação->metodo) && Caminho\Para\Classe\Validação->metodo",
 * );
 */
$stepsArray = array(
    "01" => array(
        "01.01" => "((RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyTwo || RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyOne) "
        . "&& (RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyTwo || RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyOne) "
        . "&& RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyTwo)",
        "01.02" => "RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyTwo",
    ),
    "01.01" => array(
        "01.02" => "RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyTwo",
    ),
    "01.02" => array(
        "02" => "RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyTwo && RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyThree"
    ),
    "02" => array(
        "02.01" => "RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyOne || RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyThree",
        "02.02",
    ),
    "02.01" => array(
        "02.02" => "RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyTwo || RockEinstein\FlowBinair\RulesExample\TrueFalseVerify->verifyOne",
    ),
    "02.02" => array(
        "02.03"
    ),
    "02.03"
);

$token = "01";

$steps = new RockEinstein\FlowBinair\Steps($stepsArray);
$walk = new RockEinstein\FlowBinair\Walk($token, $steps);
try {
    echo $token . "\n";
    $token = $walk->advanceTo('01.01');
    echo $token . "\n";
    $token = $walk->advanceTo('01.02');
    echo $token . "\n";
    $token = $walk->advanceTo('02');
    echo $token . "\n";
    $token = $walk->advanceTo('02.01');
    echo $token . "\n";
    $token = $walk->advanceTo('02.02');
    echo $token . "\n";
    $token = $walk->advanceTo('02.03');
    echo $token . "\n";
} catch (\Exception $ex) {
    echo $ex->getMessage() . "\n";
    exit;
}
