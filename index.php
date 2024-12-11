<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Beyond D' Crust - Login</title>
    <link rel="website icon" type="png" href="resource/logoicon.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
      }
      html, body {
        display: grid;
        height: 100vh;
        width: 100%;
        place-items: center;
        background-image: url(resource/bghomee.jpg);
        background-size: cover;
        background-position: center;
      }
      ::selection {
        background: #bb6f19;
      }
      .container {
        background: #fff;
        max-width: 350px;
        width: 350px;
        padding: 25px 30px;
        border-radius: 5px;
        box-shadow: 0 10px 10px rgba(0, 0, 0, 0.15);
        height: 522px;
        border: 3px solid #fddf07;
      }
      .container form .title {
        font-size: 30px;
        font-weight: 600;
        margin: 20px 0 10px 0;
        position: relative;
      }
      .container form .title:before {
        content: '';
        position: absolute;
        height: 4px;
        width: 33px;
        left: 0px;
        bottom: 3px;
        border-radius: 5px;
        background: linear-gradient(to right, #7f0001 0%, #7f0001 100%);
      }
      .container form .input-box {
        width: 100%;
        height: 45px;
        margin-top: 25px;
        position: relative;
      }
      .container form .input-box input {
        width: 100%;
        height: 100%;
        outline: none;
        font-size: 16px;
        border: none;
      }
      .container form .underline::before {
        content: '';
        position: absolute;
        height: 2px;
        width: 100%;
        background: #ccc;
        left: 0;
        bottom: 0;
      }
      .container form .underline::after {
        content: '';
        position: absolute;
        height: 2px;
        width: 100%;
        background: linear-gradient(to right, #7f0001 0%, #7f0001 100%);
        left: 0;
        bottom: 0;
        transform: scaleX(0);
        transform-origin: left;
        transition: all 0.3s ease;
      }
      .container form .input-box input:focus ~ .underline::after,
      .container form .input-box input:valid ~ .underline::after {
        transform: scaleX(1);
        transform-origin: left;
      }
      .container form .button {
        margin: 40px 0 20px 0;
      }
      .container .input-box input[type="submit"] {
        background: linear-gradient(to right, #7f0001 0%, #7f0001 100%);
        font-size: 17px;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
      }
      .container .input-box input[type="submit"]:hover {
        letter-spacing: 1px;
        background: linear-gradient(to left, #7f0001 0%, #7f0001 100%);
      }
      .container .option {
        font-size: 14px;
        text-align: center;
      }
      .container .facebook a,
      .container .twitter a {
        display: block;
        height: 45px;
        width: 100%;
        font-size: 15px;
        text-decoration: none;
        padding-left: 20px;
        line-height: 45px;
        color: #fff;
        border-radius: 5px;
        transition: all 0.3s ease;
      }
      .container .facebook i,
      .container .twitter i {
        padding-right: 12px;
        font-size: 20px;
      }
      .container .twitter a {
        background: linear-gradient(to right, #00acee 0%, #1abeff 100%);
        margin: 20px 0 15px 0;
      }
      .container .twitter a:hover {
        background: linear-gradient(to left, #00acee 0%, #1abeff 100%);
        margin: 20px 0 15px 0;
      }
      .container .facebook a {
        background: linear-gradient(to right, #3b5998 0%, #476bb8 100%);
        margin: 20px 0 50px 0;
      }
      .container .facebook a:hover {
        background: linear-gradient(to left, #3b5998 0%, #476bb8 100%);
        margin: 20px 0 50px 0;
      }
      .header {
        background-color: #7f0001;
        border-bottom: 3px solid #fddf07;
        color: white;
        padding: 6px;
        text-align: center;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 3;
      }
      .header .logo {
        color: #fff;
        font-weight: 600;
        font-size: 4rem;
        text-decoration: none;
        text-shadow: 2px 2px 15px rgba(0, 0, 0, 25);
      }
      .footer {
        background-color: #7f0001;
        border-top: 3px solid #fddf07;
        color: white;
        padding: 6px;
        text-align: center;
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 3;
      }
      .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 80px; /* Adjust the margin to prevent overlap with the header */
        padding-bottom: 80px; /* Adjust the padding to prevent overlap with the footer */
      }
      .forgot-password-container {
        display: flex;
        justify-content: flex-end;
        margin-top: 30px;
      }
      .wrapper {
        margin-right: 200px; /* Space between the logo and login form */
      }
    </style>
  </head>
  <body>
    <header class="header">
      <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
          <a class="logo"><b>BEYOND THE CRUST</b></a>
        </div>
      </nav>
    </header>
    <div class="login-container">
      <div class="wrapper">
        <a class="navbar-brand" href="">
          <img src="resource/logobtc.jpg" alt="Beyond D' Crust Logo" width="500" height="500">
        </a>
      </div>
      <div class="container">
        <form action="indexVerify.php" method="post">
          <div class="title">Login</div>
          <div class="input-box underline">
            <input type="email" name="uContact" placeholder="Enter Your Email Address" required>
            <div class="underline"></div>
          </div>
          <div class="input-box">
            <input type="password" name="uPassword" placeholder="Enter Your Password" required>
            <div class="underline"></div>
          </div>
          
          <div class="input-box button">
            <br><input type="submit" name="" value="LOGIN">
          </div>
        </form>
        <br><br>
        <p>
          <div class="option">Don't have an account yet? <a href="register.php">Sign up</a></div>
        </p>
      </div>
    </div>
    <footer class="footer">
      <div class="text-center p-3">
        &copy; 2024 Beyond the Crust
      </div>
    </footer>
  </body>
</html>
