<?php

    // configuration
    require_once("includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["login_email"]))
        {
            apologize("You must provide your email.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }

        // query database for user
        $rows = query("SELECT * FROM customers WHERE customer_email = ?", $_POST["login_email"]);

        // if we found user, check password
        if (count($rows) == 1)
        {
            // first (and only) row
            $row = $rows[0];

            // compare hash of user's input against hash that's in database
            if (crypt($_POST["login_pass"], $row["customer_pass"]) == $row["customer_pass"])
            {
                // remember that user's now logged in by storing user's name in session
                $_SESSION["id"] = $row["customer_id"];
                $_SESSION["name"] = $row["customer_name"];

                // redirect to index
                redirect("/expat/");
            }
        }

        // else apologize
        apologize("Invalid username and/or password.");
    }
    else
    {
        // else render form
        render("v_login_form.php", array("title" => "Log In"));
    }

?>
