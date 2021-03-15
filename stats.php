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

            <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
 
            <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
            <link rel="icon" href="logooo.png">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	        <link rel="stylesheet" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.css">
            <title>Purchases</title>
    </head>

    <style>
        body {background-color:white}

        h1,h2,h3,h4,h5,h6{color:#747474; font-family:Verdana, Geneva, Tahoma, sans-serif;}
        p{color: #747474; font-size:20px;}

        input[type=checkbox] {transform: scale(2); }

        input[type=submit] {background-color: #14a76c; color:white; font-size:20px; width:100px; }
        input[type=submit]:hover{color:white;}

        input[type=text] {background-color: white; font-size:15px; text-align:center;}


        a{color:white; font-family:Verdana, Geneva, Tahoma, sans-serif; font-size:18px;}
        a:hover{color:#d79922;}
        table{ margin-left:30%; margin-right:30%;margin-bottom:3%;}
        .nav-pills {background-color:white; font-size:10px;}
        .nav {font-size:10px;}
        #pills-tab{background-color:#24305e; font-size:smaller;}
        td {padding: 15px;}
        .center{text-align:center; margin:auto;}
    </style>


    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');
            data.addRows([
                <?php
                    $sql = "SELECT COUNT(products.product_name), products.product_name, SUM(purchases.howmany) as s FROM purchases 
                    INNER JOIN products 
                    ON products.product_id = purchases.product_id
                    AND purchases.card_barcode = :c
                    GROUP BY products.product_name";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                        ':c' => $_SESSION['cardcode'],
                    ));                                
                    while ($i = $stmt->fetch(PDO::FETCH_ASSOC)){

                    echo "['";  echo ($i['product_name']); echo "'"; echo ", "; echo ( $i['s']); echo "],"; 

                    }
                ?>
            ]);
            var options = {'title':'Top purchases',
                        'width':"50%",
                        'height':300};
            var chart = new google.visualization.PieChart(document.getElementById('chart_div1'));
            chart.draw(data, options);

            var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
            chart.draw(data, options);

            var chart = new google.visualization.PieChart(document.getElementById('chart_div3'));
            chart.draw(data, options);


        }


    </script>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color:white;">
            <?php
            echo '<img style="margin-left:5%;" height="100" width="100" src="'; echo ($_SESSION['logo']); echo '.png"> <h4 style="margin-left:1%;"> School Name</h4>' ;
            ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav" style="margin-right:5%;">
                <ul class="navbar-nav ml-auto" style="font-size:20px; margin-right:10px;">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Card</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="stats.php">Purchases</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color:#f13c20;" href="logout.php"><b>Exit</b></a>
                </li>
            </div>
        </nav>
        
        

        <div class="" style="background-color:white;"> 
            <h2 style="text-align:center; margin:2%; color:#d79922"><b>Recent Purchases</b></h2>

            <ul class="nav justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item">
                <a class="nav-link active" id="pills-today-tab" data-toggle="pill" href="#pills-today" role="tab" aria-controls="pills-today" aria-selected="true">Today</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" id="pills-lweek-tab" data-toggle="pill" href="#pills-lweek" role="tab" aria-controls="pills-lweek" aria-selected="false">Last Week</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" id="pills-lmonth-tab" data-toggle="pill" href="#pills-lmonth" role="tab" aria-controls="pills-lmonth" aria-selected="false">Last Month</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-today" role="tabpanel" aria-labelledby="pills-today-tab">
                    <div class="center" id="chart_div1" style="margin-top:2%;  width:400px"></div>
                    <table border="1" style="text-align:center;">
                        <colgroup><col width=100%><col width=100%></colgroup>
                        <tr style="background-color:#c5cbe3;">
                        <td ><h6><b>Product</b></h6></td>
                        <td ><h6><b>Quantity</b></h6></td>
                        </tr>
                        <?php
                            $sql = "SELECT COUNT(products.product_name), products.product_name, purchases.p_date, SUM(purchases.howmany) as s  FROM purchases 
                            INNER JOIN products 
                            ON products.product_id = purchases.product_id
                            AND purchases.card_barcode = :c
                            GROUP BY products.product_name
                            ORDER BY purchases.p_date";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(array(
                                ':c' => $_SESSION['cardcode'],
                            ));                                
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                echo '<tr style="background-color:white;">';
                                echo '<td> <h6>'; echo ($row['product_name']); echo '</h6></td>';
                                echo '<td> <h6>'; echo ($row['s']); echo '</h6></td>';
                                echo '</tr>';
                            }
                        ?>
                    </table>  
                </div>

                <div class="tab-pane fade " id="pills-lweek" role="tabpanel" aria-labelledby="pills-lweek-tab">
                    <div class="center" id="chart_div2" style="margin-top:2%; width:400px"></div>
                    <table border="1" style="text-align:center;">
                        <colgroup><col width=100%><col width=100%></colgroup>
                        <tr style="background-color:#c5cbe3;">
                        <td ><h6><b>Product</b></h6></td>
                        <td ><h6><b>Quantity</b></h6></td>
                        </tr>
                        <?php
                            $sql = "SELECT COUNT(products.product_name), products.product_name, purchases.p_date, SUM(purchases.howmany) as s  FROM purchases 
                            INNER JOIN products 
                            ON products.product_id = purchases.product_id
                            AND purchases.card_barcode = :c
                            GROUP BY products.product_name
                            ORDER BY purchases.p_date";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(array(
                                ':c' => $_SESSION['cardcode'],
                            ));                                
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                echo '<tr style="background-color:white;">';
                                echo '<td> <h6>'; echo ($row['product_name']); echo '</h6></td>';
                                echo '<td> <h6>'; echo ($row['s']); echo '</h6></td>';
                                echo '</tr>';
                            }
                        ?>
                    </table> 
                </div>

                <div class="tab-pane fade " id="pills-lmonth" role="tabpanel" aria-labelledby="pills-lmonth-tab">
                    <div class="center" id="chart_div3" style="margin-top:2%; width:400px"></div>
                    <table border="1" style="text-align:center;">
                        <colgroup><col width=100%><col width=100%></colgroup>
                        <tr style="background-color:#c5cbe3;">
                        <td ><h6><b>Product</b></h6></td>
                        <td ><h6><b>Quantity</b></h6></td>
                        </tr>
                        <?php
                            $sql = "SELECT COUNT(products.product_name), products.product_name, purchases.p_date, SUM(purchases.howmany) as s  FROM purchases 
                            INNER JOIN products 
                            ON products.product_id = purchases.product_id
                            AND purchases.card_barcode = :c
                            GROUP BY products.product_name
                            ORDER BY purchases.p_date";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(array(
                                ':c' => $_SESSION['cardcode'],
                            ));                                
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                echo '<tr style="background-color:white;">';
                                echo '<td> <h6>'; echo ($row['product_name']); echo '</h6></td>';
                                echo '<td> <h6>'; echo ($row['s']); echo '</h6></td>';
                                echo '</tr>';
                            }
                        ?>
                    </table> 
                </div>

            </div>

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
                    <h5 class="text-uppercase " style="color:white;"><b>Sites</b></h5>

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