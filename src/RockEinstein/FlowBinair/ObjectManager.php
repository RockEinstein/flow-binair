<?php

namespace RockEinstein\FlowBinair;

/**
 * Description of ObjectManager
 *
 * @author anderson
 */
class ObjectManager {

    private $path;
    private $objectPath;
    private $method;

    public function __construct($path) {
        $this->path = $path;

        return $this;
    }

    public function prepare() {

        $pathExplode = explode("->", $this->path);

        if (count($pathExplode) > 2) {
            throw new \Exception("Can use only one method by object (" . $this->path . ")");
        }

        $this->objectPath = $pathExplode[0];
        $this->method = $pathExplode[1];

        return $this;
    }

    public function process($params = array()) {
        if (class_exists($this->objectPath)) {
            $reflection = new \ReflectionClass($this->objectPath);
            $methods = $reflection->getMethods();

            foreach ($methods as $m) {
                if ($m->getName() == $this->method) {
                    $parameters = $m->getParameters();
                    $args = array();

                    if (empty($parameters)) {
                        $class = new $this->objectPath;
                        $method = $this->method;
                        return $class->$method();
                    } else {
                        foreach ($parameters as $p) {
                            try {
                                $defVal = $p->getDefaultValue();
                                $args[] = $defVal;
                            } catch (\Exception $e) {
                                if (!array_key_exists($p->getName(), $params)) {
                                    throw new \Exception('Required parameter not found: ' . $p->getName());
                                }

                                $args[] = $params[$p->getName()];
                            }
                        }

                    }

                    return $m->invokeArgs(new $this->objectPath, $args);
                }
            }
        } else {
            throw new \Exception("Class " . $this->objectPath . " doesn't exists");
        }
    }
}
