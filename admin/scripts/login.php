<?php

function login($username, $password, $ip){

   $pdo = Database::getInstance()->getConnection();
   //Check instance
    $check_exist_query = 'SELECT COUNT(*) FROM tbl_user WHERE user_name= :username'; 
    $user_set = $pdo->prepare($check_exist_query);
    $user_set->execute(
        array(
            ':username' => $username,
        )
    );

    if($user_set->fetchColumn()>0){
        //user exist
        //$message = 'User Exists!';

        $get_user_query = 'SELECT * FROM tbl_user WHERE user_name = :username;';
        $get_user_query .= ' AND user_pass = :password';
        $user_check = $pdo->prepare($get_user_query);
        $user_check->execute(
            array(
                ':username'=>$username,
                ':password'=>$password
            )
        );


        //TODO: finish the folowing lines so that when user logged in, the user_ip column updated by the $ip
        while($found_user = $user_check->fetch(PDO::FETCH_ASSOC)){
            $id = $found_user['user_id'];
            $message = 'You just logged in!';

            //creating a locker for the user
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $found_user['user_fname'];


            
            $update_query = 'UPDATE tbl_user SET user_ip = :ip WHERE user_id = :id'; //instead of diaplying the id, display the ip instead
            $update_set = $pdo->prepare($update_query);
           //echo $update_query; exit;
            
            $update_set->execute(
               array(
                   ':ip'=>$ip,
                   ':id'=>$id
               )
            );
        } 

        if(isset($id)){

            $edited_user = 1; //if($edited_user == 1){} Trying to target the 'edited' column in db which is a boolean and is set to null

            $checking_edit_query = 'SELECT * FROM tbl_user WHERE user_id =:id AND edited =:edited';
            $checking_edit = $pdo->prepare($checking_edit_query);
            $checking_edit_result = $checking_edit->execute(
                array(
                    ':id'=>$id,
                    ':edited'=>$edited_user
                )
            );

            while($row = $checking_edit->fetch(PDO::FETCH_ASSOC)){

                redirect_to('index.php');
                
            }
        redirect_to('admin_edituser.php'); 

            //to redirect to admin after first login
            // if($edited_user = NULL){
            //     redirect_to('admin_edituser.php'); 
            // }
        }
    }
    else{
        
        //User does not exist
        //$message = 'User does not exist';
 
   
}
return $message;
}



?>

<?php 

// function login($username, $password, $ip){
// } THIS IS ALREADY AT THE TOP

function confirm_logged_in(){ // storing login data into the user locker/server
    if(!isset($_SESSION['user_id'])){
        redirect_to('admin_login.php');

        // if (!$id > 2) {
        //     redirect_to('admin_edituser.php');
        // } else {
        //     redirect_to('index.php');
        //     
        // }
    }
}

function logout(){ //destroying the login data from the user locker/server
    session_destroy();
    redirect_to('admin_login.php');
}


?>

<?php

//to track the user's login activity. By doing so, we can tell if a user is new or old.
// function isLogged(){
//     // we're connecting to the db
//     $pdo = Database::getInstance()->getConnection();
//     $id = $_SESSION['user_id'];


//     $query = 'SELECT firstLog FROM tbl_user WHERE user_id = :id'; DIDNT WORK!!
//     $log_user_set = $pdo->prepare($query);
//     $log_user_set->execute( 
//         array(
//             ':id' => $id
//         )
//     );

//     $log_user = $log_user_set->fetch(PDO::FETCH_ASSOC); //executes (makes everything true)

//     if($log_user['firstLog'] == 1){ 
//         redirect_to('admin_edituser.php');
//     }
// }

?>