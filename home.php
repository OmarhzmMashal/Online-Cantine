<?php
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=skooola', 'root','');
    session_start();
    if(!isset($_SESSION["cardcode"])){
        header("location:loginpage.php");    
    }
?>
<html>
    <head>
            <meta name="viewport" content="width=device-width, initial-scale=1" /> 
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
            <link href="assets/bootstrap2-2/css/bootstrap-responsive.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

            <link rel="icon" href="logooo.png">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <title>Card</title>
    </head>

    <style>
            body {background-color:white;}

            h1,h2,h3,h4,h5,h6{color:#747474; font-family:Verdana, Geneva, Tahoma, sans-serif;}
            p{color: ##747474; font-size:20px;}
           
            input[type=checkbox] {transform: scale(2); }
            
            input[type=submit] {background-color: #14a76c; color:white; font-size:18px; width:100px; border-radius:10px;}
            input[type=submit]:hover{color:white;}

            input[type=text] {background-color: white; font-size:18px; text-align:center; border-radius:10px;}

            
            .d{display: block; overflow: auto;  border-radius: 25px;}     
            a{color:white; font-family:Verdana, Geneva, Tahoma, sans-serif; font-size:18px;}
            a:hover{color:#d79922;}
            
            table{border-color: ;}
            .nav-pills {background-color:white; font-size:10px;}
            .nav {font-size:10px;}
            #pills-tab{background-color:#24305e; font-size:smaller;}
            td {padding: 15px;}
            table{ margin-left:30%; margin-right:30%; margin-top:3%;margin-bottom:3%;}
    </style>

    <script>
        //  function enablesave(){document.getElementById("s").disabled =false;}
        //  function disenablesave(){document.getElementById("s").disabled =true;}
        function search(){document.getElementById("search_products").submit();}
    </script>

    <body>
        <nav class="navbar navbar-expand-lg  navbar-light" style="background-color:white">
            <?php
            echo '<img style="margin-left:5%;" height="100" width="100" src="'; echo ($_SESSION['logo']); echo '.png"> <h4 style="margin-left:1%;"> School Name</h4>' ;
            ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav" style="margin-right:5%;">
                <ul class="navbar-nav ml-auto" style="font-size:20px; margin-right:10px;">
                <li class="nav-item">
                    <a class="nav-link active" href="home.php">Card</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="stats.php">Purchases</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color:#f13c20;" href="logout.php">Exit</a>
                </li>
                </ul>
            </div>
        </nav>

        <div class="" style="background-color:white;">  
            <h2 style="text-align:center; margin-top:2%;  color:#d79922;"><b>Card's Information</b></h2>
            <hr>
            <?php
                if (isset($_SESSION['saved'])){
                    echo '<p style="text-align:center; color:#14a76c;">Changes are saved successfully.</p>';
                    unset($_SESSION['saved']);
                }
                #WHEN SAVE IS CLICKED
                if (isset($_POST['save-subm'])){
                    #UPDATE DAILY MAX ATTRIBUTE
                    if (isset($_POST['dailymax'])){
                        $sql = "UPDATE students SET daily_max = :dm WHERE card_barcode = :cb";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(array(
                            ':dm' => $_POST['dailymax'],
                            ':cb' => $_SESSION['cardcode']
                        ));
                    }
                    #MAKE ALL PRODUCTS NOT ALLOWED
                    $sql = "UPDATE students_products SET allow = '0' WHERE card_barcode = :cb";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                        ':cb' => $_SESSION['cardcode']
                    ));
                    #ALLOW THE CHOSEN ONE
                    foreach ($_POST['products'] as $value){
                        $sql = "UPDATE students_products SET allow = '1' WHERE card_barcode = :cb AND product_id = :pid";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(array(
                            ':cb' => $_SESSION['cardcode'],
                            ':pid' => $value
                        ));   
                    }
                    $_SESSION['saved'] = "1";
                    header("location:home.php");
                }
            ?>
   
            <form method="POST"> 
                <?php    
                    $sql = "SELECT balance, daily_max FROM students WHERE card_barcode = :c";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                        ':c' => $_SESSION['cardcode']
                    ));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo '<h5 style="text-align:center;">Balance:&nbsp;<b>'; echo ($row['balance']);
                    echo '</b>&nbsp; Daily max: <input type="text" size="2" name="dailymax" value="'; echo ($row['daily_max']); 
                    echo '">&nbsp; <input type="submit" style="width:70px;" id="s" name="save-subm" value="Save""></h5>';  
                ?>

                <hr>

                <ul class="nav justify-content-center" id="pills-tab" role="tablist" style="">
                    <li class="nav-item">
                       <a class="nav-link active" id="pills-meals-tab" data-toggle="pill" href="#pills-meals" role="tab" aria-controls="pills-meals" aria-selected="true">Meals</a>
                    </li>
                    <li class="nav-item">
                       <a class="nav-link" id="pills-drinks-tab" data-toggle="pill" href="#pills-drinks" role="tab" aria-controls="pills-drinks" aria-selected="false">Drinks</a>
                    </li>
                    <li class="nav-item">
                       <a class="nav-link" id="pills-sweets-tab" data-toggle="pill" href="#pills-sweets" role="tab" aria-controls="pills-sweets" aria-selected="false">Sweets</a>
                    </li>
                    <li class="nav-item">
                    </li>
                </ul>

                
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-meals" role="tabpanel" aria-labelledby="pills-meals-tab">
                        <table border="1" class="sidenav" style="text-align:center;">
                            <colgroup><col><col width=100%><col width=100%></colgroup>
                            <tr style="background-color:#c5cbe3;">
                                <td ><h6><b>Product</b></h6></td>
                                <td ><h6><b>Allowed</b></h6></td>
                            </tr>
                            <?php
                                $sql = "SELECT products.product_id, products.product_name, students_products.allow FROM products 
                                INNER JOIN students_products 
                                ON students_products.product_id = products.product_id AND students_products.card_barcode = :c
                                WHERE  products.category_id = '0'";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute(array(
                                    ':c' => $_SESSION['cardcode'],
                                ));
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    echo '<tr style="background-color:white;"><td><h6>'; echo ($row['product_name']); echo '</h6></td>';
                                    echo '<td><input name="products[]" onclick="enablesave()" value="'; echo ($row['product_id']); echo '"';
                                    echo 'type="checkbox"'; echo ($row['allow']==1 ? 'checked' : ''); echo "></td></tr>";
                                }
                            ?>    
                        </table>  
                    </div>

                    <div class="tab-pane fade" id="pills-drinks" role="tabpanel" aria-labelledby="pills-drinks-tab">
                        <table border="1" class="sidenav" style="inline-block; text-align:center;">
                            <colgroup><col><col width=100%><col width=100%></colgroup>
                            <tr style="background-color:#c5cbe3;">
                                <td ><h6><b>Product</b></h6></td>
                                <td ><h6><b>Allowed</b></h6></td>
                            </tr>
                            <?php
                                $sql = "SELECT products.product_id, products.product_name, students_products.allow FROM products 
                                INNER JOIN students_products 
                                ON students_products.product_id = products.product_id AND students_products.card_barcode = :c
                                WHERE  products.category_id = '2'";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute(array(
                                    ':c' => $_SESSION['cardcode'],
                                ));
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    echo '<tr style="background-color:white;"><td><h6>'; echo ($row['product_name']); echo '</h6></td>';
                                    echo '<td><input name="products[]" onclick="enablesave()" value="'; echo ($row['product_id']); echo '"';
                                    echo 'type="checkbox"'; echo ($row['allow']==1 ? 'checked' : ''); echo "></td></tr>";
                                }
                            ?>    
                        </table>  
                    </div>

                    <div class="tab-pane fade" id="pills-sweets" role="tabpanel" aria-labelledby="pills-sweets-tab">
                        <table border="1" class="sidenav" style="inline-block; text-align:center;">
                            <colgroup><col><col width=100%><col width=100%></colgroup>
                            <tr style="background-color:#c5cbe3;">
                                <td ><h6><b>Product</b></h6></td>
                                <td ><h6><b>Allowed</b></h6></td>
                            </tr>
                            <?php
                                $sql = "SELECT products.product_id, products.product_name, students_products.allow FROM products 
                                INNER JOIN students_products 
                                ON students_products.product_id = products.product_id AND students_products.card_barcode = :c
                                WHERE  products.category_id = '1'";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute(array(
                                    ':c' => $_SESSION['cardcode'],
                                ));
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    echo '<tr style="background-color:white;"><td><h6>'; echo ($row['product_name']); echo '</h6></td>';
                                    echo '<td><input name="products[]" onclick="enablesave()" value="'; echo ($row['product_id']); echo '"';
                                    echo 'type="checkbox"'; echo ($row['allow']==1 ? 'checked' : ''); echo "></td></tr>";
                                }
                            ?>    
                        </table>  
                    </div>
                </div>
            </form>
        </div>

        <footer class=" text-white text-center text-lg-start" style="background-color:#24305e;">
            <!-- Grid container -->
            <div class="container p-4">
                <!--Grid row-->
                <div class="row">
                <!--Grid column-->
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <img style="border-radius:50%; margin-bottom:2%;" height="100" width="100" src="logo.jpg">

                    <h5 class="text-uppercase" style="color:white"><b>Skooola's Services</b></h5>
                    <p style="color:white">
                    For more information please visit our website.
                    </p>

                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase" style="color:white;"><b>Sites</b></h5>

                    <ul class="list-unstyled mb-0">
                    <li>
                        <a href="www.skooola.com" class="text-white">skooola.com</a>
                    </li>
                    <li>
                        <a href="#!" class="text-white">school's link</a>
                    </li>
                    </ul>
                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-0" style="color:white;"><b>Social Media</b></h5>

                    <ul class="list-unstyled">
                        <li>                        
                            <a href="#!" class="fa fa-facebook " style="font-size:20px;"></a>
                        </li>
                    </ul>
                </div>
                <!--Grid column-->
                </div>
                <!--Grid row-->
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
                Copyrights © 2021. All rights reserved.
            </div>
            <!-- Copyright -->
        </footer>
    </body>
</html>