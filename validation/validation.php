<?php

// Registration Validation
define("EMAIL", "/\S+@\S+\.\S+/"); // Basic Email Format
define("NAME", "/^[a-z A-Z,.\-\ñ\Ñ]{3,16}$/i"); // No Special Characters. Accepts . , -Cers(Additional Regex A-Z for accepting capetilize and 3 min - 16 max for input)

/**
 * ^ Assert position at the start of the line.
 *(?=\P{Ll}*\p{Ll}) Ensure at least one lowercase letter (in any script) exists.
 *(?=\P{Lu}*\p{Lu}) Ensure at least one uppercase letter (in any script) exists.
 *(?=\P{N}*\p{N}) Ensure at least one number character (in any script) exists.
 *(?=[\p{L}\p{N}]*[^\p{L}\p{N}]) Ensure at least one of any character (in any script) that isn't a letter or digit exists.
 *[\s\S]{8,} Matches any character 8 or more times.
 *$ Assert position at the end of the line.
 *Reference: https://stackoverflow.com/questions/48345922/reference-password-validation
 */

define("PASSWORD", "/^(?=\P{Ll}*\p{Ll})(?=\P{Lu}*\p{Lu})(?=\P{N}*\p{N})(?=[\p{L}\p{N}]*[^\p{L}\p{N}])[\s\S]{8,19}$/"); 


define("SUBJECT", "/^[\w,.!\-]{4,15}$/i"); // CERCEAS HANAPAN MO KO NG REGEX NA 4-15 characters yung range, pede numbers, tapos bawal special characters except ? ! _ -
/**
 * Tignan mo to joms hindi ako makapag decide kung ano ililimit ko sa text are kasi diba halos wala ng nililimit sa mga text area
 * lalo na sa ganyan na text box. Kaya inallow ko nalang lahat and hanggang 280 lang ginaya ko sa twitter.
 * check mo tong link for anti cross site scripting para sa text area natin.
 * https://security.stackexchange.com/questions/225210/how-to-prevent-xss-when-inserting-untrusted-data-into-a-textarea
 */
define("BODY", "^[\s\S\w\W]{,280}$"); // CERCEAS IKAW MAGISIP NG LIMITATION DITO BASTA MAY RANGE DAPAT TAPOS GAWAN MO NA DIN REGEX HEHEHEHE


//^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d][A-Za-z\d!@#$%^&*()_+]{8,19}$ (At least * chars, 1 lowercase, uppercase, number and special character)


// Validation
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


//echo isFirstNameValid("Cerceas Jr.");

// echo isEmailValid("jomari");
// echo isEmailValid("jomari@gmail.com");

?>