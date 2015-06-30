<?php

namespace RockEinstein\FlowBinair;

/**
 * Description of steps
 *
 * @author anderson
 */
class Steps {

    private $steps;

    public function __construct($steps) {
        $this->steps = $steps;
    }

    function getSteps() {
        return $this->steps;
    }

    function getStep($token) {
        if (array_key_exists($token, $this->steps)) {
            return $this->steps[$token];
        } else {
            throw new \Exception("Step not found");
        }
    }

}
