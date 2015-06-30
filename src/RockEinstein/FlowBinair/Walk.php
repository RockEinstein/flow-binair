<?php

namespace RockEinstein\FlowBinair;

/**
 * Description of Walk - To move over a surface by taking steps with the feet at a pace slower than a run. :)
 *
 * @author anderson
 */
class Walk {

    public $token;

    /**
     *
     * @var Steps 
     */
    public $steps;

    public function __construct($token, Steps $steps) {
        $this->steps = $steps;
        $this->token = $token;
    }

    public function advanceTo($requestStep, $params) {

        try {
            if ($requestStep == $this->token) {
                throw new \Exception("Can't advance to same step");
            }
            $canAdvanceTo = $this->steps->getStep($this->token);

            if (in_array($requestStep, $canAdvanceTo)) {
                $this->token = $requestStep;
                return $requestStep;
            } elseif (array_key_exists($requestStep, $canAdvanceTo)) {
                $conditionProcessor = new ConditionProcessor($canAdvanceTo[$requestStep]);

                if ($conditionProcessor->prepare()->process($params)) {
                    $this->token = $requestStep;
                    return $requestStep;
                }
                throw new \Exception("Can't go to this step");
            } else {
                throw new \Exception("Can't go to this step");
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

}
