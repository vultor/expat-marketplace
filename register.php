<?php

    // configuration
    require("includes/config.php");

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {   
        // validate submission
        if (empty($_POST["signup_email"]))
        {
            apologize("You must provide your email.");
        }
        else if (empty($_POST["signup_pass"]))
        {
            apologize("You must provide your password.");
        }

        // query database for user
        $rows = query("INSERT INTO customers (customer_email, customer_pass)
			VALUES(?, ?)", $_POST["signup_email"], crypt($_POST["signup_pass"]));

        if ($rows !== false)
        {
            // find out which id was assigned
            $rows = query("SELECT LAST_INSERT_ID() AS id");
            $id = $rows[0]["id"];
            
            // remember that user's now logged in by storing user's ID in session
            $_SESSION["id"] = $id;

            render("v_customer_profile_edit.php", array("title" => "Edit Your Profile"));
        }
        
        // else apologize
        apologize("Invalid username and/or password.");
    }
    else
    {
        /* TODO check if already logged in
        if (!empty($_SESSION["id"]))
        {
            apologize("You're already logged in.");
        }
        */

        // else render form
        render("v_customer_register_form.php", array("title" => "Sign up for an Account"));
    }
?>
