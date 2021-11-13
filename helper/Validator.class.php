<?php

namespace helper;

class Validator {

    private $rules;

    private $errors = [];

    public function __construct($rules = []) {
        $this->rules = $rules;
    }

    public function addError($error) {
        array_push($this->errors, $error);
    }

    private function getRuleValue($field, $rule) {
        if(!isset($this->rules[$field][$rule])) {
            return null;
        }

        return $this->rules[$field][$rule];
    }
    
    private function validateType($requiredType, $value) : bool {
        switch($requiredType) {
            case "numeric": {
                return is_numeric($value);
            } break;

            case "email": {
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            } break;
        }
    }

    public function validate($data) : bool {
        foreach($this->rules as $fieldName=>$fieldRules) {

            $name = $this->getRuleValue($fieldRules, "name");

            if(isset($data[$fieldName])) {

                $value = $data[$fieldName];

                foreach($fieldRules as $rule => $ruleValue) {
                    switch($rule) {
                        case "minLength": {
                            if(strlen($value) < $ruleValue) {
                                $this->addError("{$name} must be at least {$ruleValue} characters long");
                            }
                        } break;

                        case "maxLength": {
                            if(strlen($value) > $ruleValue) {
                                $this->addError("{$name} must be at most {$ruleValue} characters long");
                            }
                        } break;

                        case "type": {
                            if(!$this->validateType($ruleValue, $value)) {
                                $this->addError("{$name} is in incorrect format");
                            }
                        } break;

                        case "regex": {
                            if(preg_match($ruleValue, $value) == 0) {
                                $this->addError("{$name} is in incorrect format.");
                            }
                        } break;

                        case "match": {
                            $name2 = $this->getRuleValue($ruleValue, "name");

                            if(!isset($data[$ruleValue])) {
                                $this->addError("{$name2} must match {$name}");
                            }

                            $matchValue = $data[$ruleValue];

                            if($matchValue != $value) {
                                $this->addError("{$name2} must match {$name}");
                            }
                        } break;
                    }
                }
            } else {
                $required = $this->getRuleValue($fieldRules, "required");

                if($required) {
                    $this->addError("Field {$name} is required");
                }
            }

        }

        return empty($this->errors);
    }
}