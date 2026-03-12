<?php

    include "../classes/User.php";

    $user = new User;

    $user->update($_POST, $_FILES);
    /**
     * 
     * $_POST [first_name, last_name, email, username]
     * $_FILES [name_of_input_photo][the_phoe_uploaded]
     * 
     */