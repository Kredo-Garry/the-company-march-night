<?php

    include "../classes/User.php";

    # Instantiate/create object
    $user = new User;

    # Call the method
    $user->store($_POST);
    /**
     * $_POST [
     * 
     *  first_name = John
     *  last_name = Smith
     *  username = john.smith
     *  password = john.smith
     * 
     * ]
     */