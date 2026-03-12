<?php
    // This is an example changes I made in User.php
    require_once 'Database.php';
    session_start();

    class User extends Database{
        
        public function store($request){
            $first_name = $request['first_name'];
            $last_name = $request['last_name'];
            $username = $request['username'];
            $password = $request['password']; # john.smith -> 129HGTRUkjd+2*&&%2kdo2,fl(**}

            $password = password_hash($password, PASSWORD_DEFAULT);

            # SQL Query string
            $sql = "INSERT INTO users(`first_name`, `last_name`, `username`, `password`) VALUES('$first_name', '$last_name', '$username', '$password')";

            # $this->conn --> server_name, username, password, database name
            if ($this->conn->query($sql)) { # if the execution is true (no error)
                header('location: ../views'); # go to index.php (login page)
                exit;
            }else {
                die('Error in creating the user: ' . $this->conn->error);
            }
        }


        # Method that handles the login
        public function login($request){
            $username = $request['username'];
            $password = $request['password'];

            # Query string
            $sql = "SELECT * FROM users WHERE username = '$username'"; # john
            $result = $this->conn->query($sql);

            # check if username exists
            if ($result->num_rows == 1) { # true
                # check if the password is correct
                $user =  $result->fetch_assoc();
                # $user = ['id' => 1, 'username' => 'john', 'password' => $2y$10$c9v...]

                if (password_verify($password, $user['password'])) { # true
                    # Create sessino variables for future use
                    $_SESSION['id']         = $user['id'];
                    $_SESSION['username']   = $user['username'];
                    $_SESSION['full_name']  = $user['first_name'] . " " . $user['last_name']; 

                    header('location: ../views/dashboard.php');
                    exit;
                } else {
                    die('Password is incorrect');
                }
            } else {
                die('Username not found');
            }
        }

        /**
         * Method to handle logout
         */
        public function logout(){
            session_unset();
            session_destroy();

            header('location: ../views');
            exit;
        }

        /**
         * Retrieved all the users from the users
         */
        public function getAllUsers(){
            $sql = "SELECT id, first_name, last_name, username, photo FROM users";

            if ($result = $this->conn->query($sql)) {
                return $result;
            } else {
                die('Error retrieving all users: ' . $this->conn->error);
            }
            
        }


        /**
         * Retrieve details of the login user
         */
        public function getUser($id){
            $sql = "SELECT * FROM users WHERE id = $id";
            if ($result = $this->conn->query($sql)) {
                return $result->fetch_assoc();
            }else {
                die("Error retrieving the user: " . $this->conn->error);
            }
        }

        /**
         * Method/function to handle the actual update
         */
        public function update($request, $files){
            $id = $_SESSION['id']; # id of the current loged-in user
            $first_name = $request['first_name'];
            $last_name = $request['last_name'];
            $username = $request['username'];
            $photo = $files['photo']['name'];
            $tmp_photo = $files['photo']['tmp_name'];

            $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', username = '$username' WHERE id = $id";

            if ($this->conn->query($sql)) { # true or okay
                $_SESSION['username'] = $username;
                $_SESSION['full_name'] = "$first_name $last_name";

                # Check if the user uploaded a photo, save it to the Db and save the file to the images folder
                if ($photo) { # true
                    $sql = "UPDATE users SET photo = '$photo' WHERE id = $id";
                    $destination = "../assets/images/$photo";

                    # Save the image to the Db
                    if ($this->conn->query($sql)) {
                        # Save the image to the images folder
                        if (move_uploaded_file($tmp_photo, $destination)) {
                            header('location: ../views/dashboard.php');
                            exit;
                        }else {
                            die('Error moving the photo');
                        }
                    } else {
                        die('Error uploading the photo: ' . $this->conn->error);
                    }
                }
                header('location: ../views/dashboard.php');
                exit;
            } else {
                die('Error update the user: ' . $this->conn->error);
            }
        }

        /**
         * Method responsible for delete user account
         */
        public function delete(){
            $id = $_SESSION['id'];
            $sql = "DELETE FROM users WHERE id = $id";

            if ($this->conn->query($sql)) {
                # call the logout function to delete session, and redirect to login page
                $this->logout(); 
            } else {
                die('Error deleting your account: ' . $this->conn->error);
            }
        }

    }