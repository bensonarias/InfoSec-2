<?php

define("EMAIL", "/\S+@\S+\.\S+/"); 
define("NAME", "/^[a-z A-Z,.\-\ñ\Ñ]{3,16}$/i"); 

define("PASSWORD", "/^(?=\P{Ll}*\p{Ll})(?=\P{Lu}*\p{Lu})(?=\P{N}*\p{N})(?=[\p{L}\p{N}]*[^\p{L}\p{N}])[\s\S]{8,19}$/"); 


<<<<<<< HEAD
define("SUBJECT", "/^[\w,.!\-]{4,15}$/i"); 
=======
define("SUBJECT", "/^[\w,.!\-]{4,15}$/i"); // CERCEAS HANAPAN MO KO NG REGEX NA 4-15 characters yung range, pede numbers, tapos bawal special characters except ? ! _ -
/**
 * Tignan mo to joms hindi ako makapag decide kung ano ililimit ko sa text are kasi diba halos wala ng nililimit sa mga text area
 * lalo na sa ganyan na text box. Kaya inallow ko nalang lahat and hanggang 280 lang ginaya ko sa twitter.
 * check mo tong link for anti cross site scripting para sa text area natin.
 * https://security.stackexchange.com/questions/225210/how-to-prevent-xss-when-inserting-untrusted-data-into-a-textarea
 */
define("BODY", "/^[\s\S\w\W]{,280}$/"); // CERCEAS IKAW MAGISIP NG LIMITATION DITO BASTA MAY RANGE DAPAT TAPOS GAWAN MO NA DIN REGEX HEHEHEHE

>>>>>>> f4269ff8a9c04b925d100c6a135be3fb87bebfe2

define("BODY", "^[\s\S\w\W]{,280}$"); 


function isFirstNameValid($name) {
    return preg_match(NAME, $name);
}

function isLastNameValid($name) {
    return preg_match(NAME, $name);
}

function isEmailValid($email) {
    return preg_match(EMAIL, $email);
}

function isPasswordValid($password) {
    return preg_match(PASSWORD, $password);
}

function isSubjectValid($subject) {
    return preg_match(SUBJECT, $subject);
}

function isBodyValid($body) {
    return preg_match(BODY, $body);
}
function formValidate($data) {
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

?>