<?php
$session_start();
// Variable declaration
$name = $email = $phone = $address = $password = $cpassword = $gender = "";
// Error variable declaration
$nameerr = $emailErr = $phoneErr = $genderErr = $passwordErr = $cpasswordErr = "";

//
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//Name validation
    if (empty($_POST["name"])) {
        $nameerr = "* Name is Required";
    } else {
        $name = input_data($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameerr = "* Only Alphabets and space are allowed.";
        }
    }
	//Email validation
    if (empty($_POST["email"])) {
        $emailErr = "* Email is Required";
    } else {
        $email = input_data($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "* Invalid email format";
        }
    }
	//Phone Number validation
    if (empty($_POST["phone"])) {
        $phoneErr = "* Phone number is Required";
    } else {
        $phone = input_data($_POST["phone"]);
        if (!preg_match("/^\d{10}$/", $phone)) {
            $phoneErr = "* Only Numeric value is allowed.";
        }
    }
	//Gender validation
    if (empty($_POST["gender"])) {
        $genderErr = "* Gender is Required";
    } else {
        $gender = input_data($_POST["gender"]);
    }

    // Validates password & confirm passwords.
    if (empty($_POST["password"])) {
        $passwordErr = "* .";
    } else {
        $password = input_data($_POST["password"]);
        $cpassword = input_data($_POST["cpassword"]);
        if (strlen($_POST["password"]) < 8) {
            $passwordErr = "* Your Password Must Contain At Least 8 Characters!";
        } elseif (!preg_match("#[0-9]+#", $password)) {
            $passwordErr = "* Your Password Must Contain At Least 1 Number!";
        } elseif (!preg_match("#[A-Z]+#", $password)) {
            $passwordErr = "* Your Password Must Contain At Least 1 Capital Letter!";
        } elseif (!preg_match("#[a-z]+#", $password)) {
            $passwordErr = "* Your Password Must Contain At Least 1 Lowercase Letter!";
        } elseif ($password !== $cpassword) {
            $cpasswordErr = "* Passwords do not match!";
        }
    }
}

function input_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>