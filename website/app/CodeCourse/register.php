<?php

require_once 'core/init.php';


if(Input::exists()){
    if(Token::check(Input::get('token'))){
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users',
                'regex' => '/^[A-Za-z][A-Za-z0-9_]{1,19}$/'
            ),
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50,
                'regex' => '/^[A-Za-z][A-Za-z0-9_]{1,19}$/'
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            )
        ));

        if($validation->passed()){
            $user = new User();

            $salt = Hash::salt(32);
            
            try {
                $user->create(array(
                    'username' => Input::get('username'),
                    'name' => Input::get('name'),
                    'password' => Hash::make(Input::get('password'), $salt),
                    'salt' => $salt,
                    'joined' => date('Y-m-d H:i:s'),
                    'group' => 1
                ));

                Session::flash('home', 'You have been registered and can now log in!');
                Redirect::to('../index.php');

            } catch(Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach($validation->errors() as $error){
                echo "<script>alert('$error');</script>";
            }
        }
    }
}
?>
<html>
<head>
<body class="d-flex flex-column min-vh-100 gradient-custom">
    <!-- Bootstrap CSS test-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-custom text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">
            <div class="mb-md-5 mt-md-4 pb-5">
            <h2 class="fw-bold mb-2 text-uppercase">Signup</h2>
            

<form action="" method="post">
    <div data-mdb-input-init class="field mb-4">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
    </div>

    <div class="field mb-4">
        <label for="name">Enter name</label>
        <input type="text" name="name" value="<?php echo escape(Input::get('name')); ?>" autocomplete="off" id="name">
    </div>  

    <div class="field mb-4">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>  

    <div class="field mb-4">
        <label for="password_again">Enter your password again</label>
        <input type="password" name="password_again" id="password_again">
    </div>  

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Register">
            </form>
            </div>
            <p class="mb-0">Already have an account? <a href="login.php" class="text-white-50 fw-bold">Log in</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>
