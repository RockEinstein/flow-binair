<?php

namespace RockEinstein\FlowBinair;

/**
 * Description of ConditionProcessor
 *
 * @author anderson
 */
class ConditionProcessor {

    private $condition;
    private $preProcessed;
    private $operator;

    public function __construct($condition) {
        $this->condition = $condition;

        return $this;
    }

    public function prepare() {
        $conditionsExplode = explode(" ", $this->condition);
        $this->preProcessed = $conditionsExplode;

        return $this;
    }

    /**
     * 
     * @return Bool
     * @throws \Exception
     */
    public function process($params = array()) {
        $group = "";
        $val = false;

        $generalString = "";
        $closeParenthesis = "";
        $parenthesisControl = 0;

        foreach ($this->preProcessed as $process) {
            for ($i = 0; $i < strlen($process); $i++) {
                $letter = $process[$i];

                if ($letter == "&" && $process[$i + 1] == "&") {
                    $this->operator = "&&";
                    $generalString .= " & ";
                    $i++;
                    continue;
                }
                if ($letter == "|" && $process[$i + 1] == "|") {
                    $this->operator = "||";
                    $generalString .= " | ";
                    $i++;
                    continue;
                }

                if ($letter == "(") {
                    $generalString .= "(";
                    $parenthesisControl += 1;
                } elseif ($letter == ")") {
                    $closeParenthesis = ")";
                    $parenthesisControl -= 1;
                } else {
                    $group .= $letter;
                }
            }

            $group = trim($group);
            if ($group != "") {                
                $preResponse = $this->processObject($group, $params);
                if ($preResponse == false) {
                    $generalString .= " " . 0 . " ";
                } else {
                    $generalString .= " " . 1 . " ";
                }

                $generalString .= $closeParenthesis;
                $closeParenthesis = "";

                $group = "";
            }
        }

        if ($parenthesisControl == 0) {
            $generalString = "\$val = $generalString;";
            eval($generalString);
        } else {
            throw new \Exception("Parenthesis in excess");
        }

        return $val;
    }

    public function processObject($path, $params) {
        $objectManager = new ObjectManager($path);
        $response = $objectManager->prepare()->process($params);

        if (!is_bool($response)) {
            throw new \Exception("Invalid {$path} response type: must be boolean");
        }

        return $response;
    }

}
