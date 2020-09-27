<html>

<head>
   <?php
   session_start();
   if (isset($_SESSION['user_id'])) {
      header("Location: index");
   }
   include("navbar.php");
   ?>
   <link rel="stylesheet" type="text/css" href="./css/sign.css">
</head>

<body>
   <div class="container">
      <br>
      <div class="row">
         <div class="col-sm-4"></div>
         <div class="col-sm-4 mx-auto">
            <div id="first">
               <div class="myform form">
                  <div class="logo mb-3">
                     <div class="col-md-12 text-center">
                        <h1>Login</h1>
                     </div>
                  </div>
                  <form id="login">
                     <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Password</label>
                        <input type="password" name="password" id="password" class="form-control" aria-describedby="emailHelp" placeholder="Enter Password">
                     </div>
                     <div class="form-group">
                        <p class="text-center">By signing up you accept our <a href="#">Terms Of Use</a></p>
                     </div>
                     <div class="form-group">
                        <p id="status"></p>
                     </div>
                     <div class="col-md-12 text-center ">
                        <button type="submit" class=" btn btn-block mybtn btn-primary tx-tfm">Login</button>
                     </div>
                     <div class="col-md-12 ">
                        <div class="login-or">
                           <hr class="hr-or">
                           <span class="span-or">or</span>
                        </div>
                     </div>
                     <div class="col-md-12 mb-3">
                        <p class="text-center">
                           <a href="javascript:void();" class="google btn mybtn"><i class="glyphicon glyphicon-google-plus">
                              </i> Signup using Google
                           </a>
                        </p>
                     </div>
                     <div class="form-group">
                        <p class="text-center">Don't have account? <a href="#" id="signup">Sign up here</a></p>
                     </div>
                  </form>

               </div>
            </div>
            <div id="second">
               <div class="myform form">
                  <div class="logo mb-3">
                     <div class="col-md-12 text-center">
                        <h1>Signup</h1>
                     </div>
                  </div>
                  <form id="registration">
                     <div class="form-group">
                        <label for="exampleInputEmail1">First Name</label>
                        <input type="text" name="firstname" class="form-control" id="firstname" aria-describedby="emailHelp" placeholder="Enter Firstname">
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                     </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Password</label>
                        <input type="password" name="password" id="password" class="form-control" aria-describedby="emailHelp" placeholder="Enter Password">
                     </div>
                     <div class="form-group">
                        <p id="status"></p>
                     </div>

                     <div class="col-md-12 text-center mb-3">
                        <button type="submit" class="btn btn-block mybtn btn-primary tx-tfm">Get Started For Free</button>
                     </div>
                     <div class="col-md-12">
                        <div class="form-group">
                           <p class="text-center"><a href="#" id="signin">Already have an account?</a></p>
                        </div>
                     </div>
               </div>
               </form>
            </div>
         </div>
      </div>
   </div>

   <script>
      $("form").submit(function(event) {
         var request;
         event.preventDefault();
         if (request) {
            request.abort();
         }
         var $form = $(this);
         var $inputs = $form.find("input, button");
         $inputs.prop("disabled", true);
         request = $.ajax({
            type: "POST",
            url: "auth",
            contentType: "application/x-www-form-urlencoded",
            data: {
               action: $(this).attr("id"),
               email: $(this).find("#email").val(),
               password: $(this).find("#password").val(),
               name: $(this).find("#firstname").val()
            }
         });
         request.done(function(response, textStatus, jqXHR) {
            if (response == "1") {
               $form.find("#status").html("<p id='status'>" + '<div class="alert alert-success">Success!</div>' + "</p>");
               <?php
               if (isset($_SESSION['RETURN_URL'])) {
                  echo 'window.location.href = "' . $_SESSION['RETURN_URL'] . '";';
               }
               ?>
            } else {
               $inputs.prop("disabled", false);
               $form.find("#status").html("<p id='status'>" + '<div class="alert alert-danger">An error occurred! Try again.</div>' + "</p>");
            }
         });
         request.fail(function(jqXHR, textStatus, errorThrown) {
            console.error(
               "The following error occurred: " +
               textStatus, errorThrown
            );
         });
         request.always(function() {});
      });
   </script>

   <script src="./js/sign.js"></script>
</body>

</html>
