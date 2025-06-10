<?php
class ExpiredException extends Exception {
    public function __construct($message = "El certificado ha caducado.", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    public function getErrorMessage() {
        return "Error: " . $this->getMessage() . " in " . $this->getFile() . " on line " . $this->getLine();
    }
}