<?php 

require_once('core/init.php');

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true),   

        ));

        if($validation->passed()) {
            $user = new User();

            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);

            if($login) {
                Redirect::to('../index.php');
            } else {
                echo '<p>Log in failed</p>';
            }

        } else {
            foreach($validation->errors() as $error) {
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
    <section class="vh-100 ">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-custom text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">
            <div class="mb-md-5 mt-md-4 pb-5">
            <h2 class="fw-bold mb-2 mb-4 text-uppercase">Login</h2>
            <form action="" method="post">
            <div class="field mb-4">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" autocomplete="off">
            </div>

            <div class="field mb-4">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" autocomplete="off">
            </div>

            <div class="field mb-4">
                <label for="remember">
                    <input type="checkbox" name="remember" id="remember"> Remember me
                </label>
            </div>

            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <input type="submit" class="field mb-4" value="Log in">
            <div>
            <p class="mb-0">Don't have an account? <a href="register.php" class="text-white-50 fw-bold">Sign Up</a>
            </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</head>
</html>
