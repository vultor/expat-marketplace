<?php
class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();
    } 

    public function index() {
        # Send them back to the main index.
        Router::redirect("/");
    }

    public function signup($error = NULL) {

        # Setup view
        $this->template->content = View::instance('v_users_signup');
        $this->template->title   = "Sign Up";

        if($error == 'alias') {
            # State the error
            $error = "Sorry. That alias is taken. Be more creative.";
        }
        elseif($error == 'email') {
            # State the error
            $error = "Whoa, whoa, whoa... one alias at a time, please. (your email is already signed up.)";
        }
        elseif($error == 'length') {
            # State the error
            $error = "Your alias and password must be at least 3 characters.";
        }
        elseif($error == 'alpha') {
            # State the error
            $error = "Your alias must be alpanumeric and contain no spaces.<br>
            Allowed: 0-9, a-z, A-Z, !@#$%._-";
        }
        else {
            $error = NULL;
        }

        # Pass data to the view
        $this->template->content->error = $error;

        # Render template
        echo $this->template;
    }

    public function p_signup() {

        # Dump out the results of POST to see what the form submitted
        // print_r($_POST);

        # Make sure alias and password are at least 3 characters long
        if(strlen($_POST['alias']) < 3 || strlen($_POST['password']) < 3) {

            # Send them back to the sign up page
            Router::redirect("/users/signup/length");
        }

        if(!preg_match('/^[0-9A-Za-z!@#$%._-]+$/',$_POST['alias'])) {
            
            # Send them back to the sign up page
            Router::redirect("/users/signup/alpha");
        } 

        # More data we want stored with the user
        $_POST['created']  = Time::now();
        $_POST['modified'] = Time::now();

        # Encrypt the password  
        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);            

        # Create an encrypted token via their email address and a random string
        $_POST['token'] = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());

        # Check if user email exists
        $email_exist = DB::instance(DB_NAME)->select_field('SELECT email 
            FROM users 
            WHERE email = "'.$_POST['email'].'"');

        if($email_exist) {
            # Send them to the sign up page
            Router::redirect("/users/signup/email");
        }

        # Check if user alias exists
        $alias_exist = DB::instance(DB_NAME)->select_field('SELECT alias FROM users WHERE alias = "'.$_POST['alias'].'"');

        if($alias_exist){
            # Send them to the sign up page
            Router::redirect("/users/signup/alias");
        }

        # If email and alias are availble register the user
        # Insert this user into the database insert($table, $data)
        $user_id = DB::instance(DB_NAME)->insert('users', $_POST);

        $token = $_POST['token'];

        /* 
        Store this token in a cookie using setcookie()
        Important Note: *Nothing* else can echo to the page before setcookie is called
        Not even one single white space.
        param 1 = name of the cookie
        param 2 = the value of the cookie
        param 3 = when to expire
        param 4 = the path of the cookie (a single forward slash sets it for the entire domain)
        */
        setcookie("token", $token, strtotime('+1 year'), '/');

        # Redirect to edit view
        Router::redirect("/users/profile/".$_POST['alias']);

    }

    public function login($error = NULL) {

        # Setup view
        $this->template->content = View::instance('v_users_login');
        $this->template->title   = "Login";

        if($error == 'alias') {
            # State the error
            $error = "Sorry. That alias does not exist. Try again.";
        }
        elseif($error == 'pass') {
            # State the error
            $error = "Whoops! Incorrect password. Please, try again.";
        }
        elseif($error == 'login') {
            # State the error
            $error = "You must be logged in to view this content.";
        }
        else {
            $error = NULL;
        }

        # Pass data to the view
        $this->template->content->error = $error;

        # Render template
        echo $this->template;

    }

    public function p_login() {

        # Sanitize the user entered data to prevent any funny-business (re: SQL Injection Attacks)
        $_POST = DB::instance(DB_NAME)->sanitize($_POST);

        # Hash submitted password so we can compare it against one in the db
        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

        # Search the db for this alias
        $q = "SELECT alias, user_id 
            FROM users 
            WHERE alias = '".$_POST['alias']."'";

        $alias_exist = DB::instance(DB_NAME)->select_row($q);

        # If we didn't find a the alias in the database, it means login failed
        if(!$alias_exist) {

            # Send them back to the login page
            Router::redirect("/users/login/alias");
        }
        else {
            # Search for token and password
            # Retrieve the token if it's available
            $q = "SELECT token
                FROM users 
                WHERE alias = '".$_POST['alias']."'
                AND password = '".$_POST['password']."'";

            $token = DB::instance(DB_NAME)->select_field($q);

            # If we didn't find a matching token in the database, it means login failed
            if(!$token) {

                # State the error
                $this->error = "Incorrect password. Please, try again.";

                # Send them back to the login page
                Router::redirect("/users/login/pass");

            # But if we did, login succeeded! 
            } else {

                //Store time data
                $last_login = Array("last_login" => Time::now());

                DB::instance(DB_NAME)->update("users", $last_login, "WHERE user_id = ".$alias_exist['user_id']);

                /* 
                Store this token in a cookie using setcookie()
                Important Note: *Nothing* else can echo to the page before setcookie is called
                Not even one single white space.
                param 1 = name of the cookie
                param 2 = the value of the cookie
                param 3 = when to expire
                param 4 = the path of the cookie (a single forward slash sets it for the entire domain)
                */
                setcookie("token", $token, strtotime('+1 year'), '/');

                # Send them to the main page - or whever you want them to go
                Router::redirect("/");

            }   
        }

    }

    public function logout() {

        # Generate and save a new token for next login
        $new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());

        # Create the data array we'll use with the update method
        # In this case, we're only updating one field, so our array only has one entry
        $data = Array("token" => $new_token);

        # Do the update
        DB::instance(DB_NAME)->update("users", $data, "WHERE token = '".$this->user->token."'");

        # Delete their token cookie by setting it to a date in the past - effectively logging them out
        setcookie("token", "", strtotime('-1 year'), '/');

        # Send them back to the main index.
        Router::redirect("/");

    }

    public function profile($alias = NULL, $success = NULL) {

        if($alias == $this->user->alias) {
            $this->template->content = View::instance('v_users_profile_edit');
            $this->template->title = "Edit profile for $alias";

            if($success == 'success') {
                $this->template->content->success = "Success! Profile updated! Now go <a href='/courses'>browse some courses</a>.";
            }
            elseif($success == 'length') {
                $this->template->content->error = "Your alias must be at least 3 characters.";
            }
            elseif($success == 'filetype') {
                $this->template->content->error = "Your image file must be .jpg, .png or .gif and less than 35 KB.";
            }
            elseif($success == NULL) {
                $this->template->content->success = "Hey there! Update your profile here. Then <a href='/courses'>browse lessons</a> and start improving your English!";
            }
            else {
                $this->template->content->error = "Watchu talkin' bout, ".$success."?";
            }
        }
        elseif($alias == NULL) {
            # Send them back to the main index.
            Router::redirect("/");
        }
        else {
            $this->template->content = View::instance('v_users_profile');
            $this->template->title = "Profile for $alias";
        }

        # Build the query to get the alias' info
        $q = "SELECT *
            FROM users
            WHERE users.alias = '".$alias."'";

        # Execute the query to get the user's info. 
        # Store the result array in the variable $alias
        $alias = DB::instance(DB_NAME)->select_row($q);

        if(empty($alias)) {
            $this->template->content->error   = "Sorry, this alias does not exist.";
        }

        # Pass information to the view instance
        $this->template->content->alias = $alias;

        # Render View
        echo $this->template;
    }


    public function p_edit() {

        # Make sure alias and password are at least 3 characters long
        if(strlen($_POST['alias']) < 3) {

            # Send them back to the sign up page
            Router::redirect("/users/profile/".$this->user->alias."/length");
        }

        # More data we want stored with the user
        $_POST['modified'] = Time::now();

        # Get the users' info
        $q = "SELECT *
            FROM users
            WHERE user_id = ".$this->user->user_id;

        # Execute the query to get the user's info. 
        # Store the result array in the variable $user
        $user = DB::instance(DB_NAME)->select_row($q);

        # Store the user_id in the variable $user_id
        $user_id = $user['user_id'];

        if($_FILES['logo']['name'] != '') {

            # Upload the logo image
            $logo = Upload::upload($_FILES, "/uploads/avatars/", array("jpg", "jpeg", "gif", "png"), $user_id);

            if($logo == 'Invalid file type.') {
                // return an error
                Router::redirect("/users/profile/".$_POST['alias']."/filetype"); 
            }

            $_POST['logo'] = $user_id;
            # resize the image
            
            $imgObj = new Image($_SERVER["DOCUMENT_ROOT"] . '/uploads/avatars/' . $logo);
            $imgObj->resize(100,100, "crop");
            $imgObj->save_image($_SERVER["DOCUMENT_ROOT"] . '/uploads/avatars/' . $logo); 
        }

        # Set the where condtion to update
        $where_condition = "WHERE user_id = ".$user_id;

        # Update the database update($table, $data, $where)
        $new_user = DB::instance(DB_NAME)->update('users', $_POST, $where_condition);
    
        # Send to success page if it worked, error if it didn't
        if (count($new_user) == 1){
            Router::redirect("/users/profile/".$_POST['alias']."/success");
        }
        else {
            Router::redirect("/users/profile/");
            $this->template->content->error   = "Something went wrong with the update query. Try again?";
        }
    }

} # end of the class