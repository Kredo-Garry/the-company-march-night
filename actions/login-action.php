<?php

    include "../classes/User.php";

    # Instantiate an obj
    $user = new User;

    # Call the method
    $user->login($_POST); # this contains the username and the password