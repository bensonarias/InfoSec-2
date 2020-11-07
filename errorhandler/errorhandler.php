<?php

function customError($errno, $errstr, $errfile, $errline) {
    echo "<b>Custom error:</b> [$errno] $errstr<br>";
}

class customException extends Exception {
    public function errorMessage() {
      
      $errorMsg = $this->getMessage();
      return $errorMsg;
    }
    public function errorCode() {
      
      $errorCode = $this->getCode();
      return $errorCode;
    }
  }
 

?>