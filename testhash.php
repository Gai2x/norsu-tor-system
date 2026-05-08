<?php
echo password_hash("SuperAdmin123", PASSWORD_DEFAULT);
?>

<!-- debugging. put on login -->

<!-- //     public function login(array $input) {
//     if (session_status() === PHP_SESSION_NONE) {
//         session_start();
//     }

//     if (isset($input['login'])) {

//         $email = trim($input['email'] ?? '');
//         $password = trim($input['password'] ?? '');

//         $user = $this->model->findByEmailOrStudentId($email);

//         echo "<pre>";
//         echo "INPUT EMAIL: " . $email . "<br>";
//         echo "INPUT PASSWORD: " . $password . "<br><br>";

//         if ($user) {
//             print_r($user);

//             echo "<br><br>";
//             echo "DB HASH: " . $user['password'] . "<br><br>";

//             echo "VERIFY RESULT: ";
//             var_dump(password_verify($password, $user['password']));
//         } else {
//             echo "NO USER FOUND";
//         }

//         exit();
//     }
// } -->