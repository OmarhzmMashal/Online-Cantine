<html>
  <head>
      <meta name="viewport" content="width=device-width, initial-scale=1" /> 
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
      <link rel="icon" href="logooo.png">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">

      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <title>Login</title>
  </head>

  <style>
    body {  background-color:white; }
    h1,h3{color:#747474; font-family:Verdana, Geneva, Tahoma, sans-serif;}
    h2,h4,h5,h6{color:black; font-family:Verdana, Geneva, Tahoma, sans-serif;}
    p{color: #24305e; font-size:30px;}
    img {border-radius: 50%; clip-path: circle();} 
    input[type=checkbox] {transform: scale(2);}
    input[type=submit] {background-color: #14a76c; color:white; font-size:18px; width:100px; border-radius:10px;}
    input[type=text] {background-color: white; font-size:20px; text-align:center; border-radius:10px;}
    div{display: block; overflow: auto;}
  </style>

  <body>
    <div class="container"  style="padding-top: 12%;  padding-left: 7%;padding-right: 7%; width=100%;" >
      <form method="POST" style=" text-align:center;"> 
        <p style="text-align:center;"><img height="150"  width="150" src="logo.jpg" ></p>
        <p> 
          <input type="text" style="font-size:large;" size="20" placeholder ="Enter cardcode" name="cardcode">
          <input type="submit" value="Enter" style=" color:white; ">
        </p> 
        <?php
          $pdo = new PDO('mysql:host=localhost;port=3306;dbname=skooola', 'root','');
          session_start();
          if (isset($_SESSION['cardcode'])){
            header("Location: home.php");      
          } 
          if (isset($_SESSION['error'])){  
            echo '<h6 style="text-align:center; color:red">Invalid Cardcode.</h6>';
            unset($_SESSION['error']);  
          }
          if (isset($_POST['cardcode'])){
            unset($_SESSION["cardcode"]);
            $sql = "SELECT card_barcode FROM students WHERE card_barcode = :c";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':c' => $_POST['cardcode'],
            ));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row > 1){
              $_SESSION['cardcode'] = $row['card_barcode'];
              $_SESSION['logo'] = substr($row['card_barcode'], 0, 2) ;
              $_SESSION['correc']="1";
              header("Location: home.php");      
            }
            else{
                $_SESSION['error']="1";
                header("location:loginpage.php");
            }
          }
        ?>
        <h2 style="color:#24305e; "><b>We make sure your kid eats healthy!</b></h2>
        <h5  style="color:#24305e;">An online school's cantine credit card for a healthy life.</h5>
        <p  style=" font-size:10px ;color:#727272; text-align: center;">Copyrights © 2021. All rights reserved.</p>
      </form>   
    </div>
  </body>

</html>