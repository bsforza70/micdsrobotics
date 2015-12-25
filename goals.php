 <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>MICDS Robotics</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Bob Sforza & Charlie Biggs" />
<meta name="author" content="MICDS Robotics" />
<!-- css -->
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="css/jcarousel.css" rel="stylesheet" />
<link href="css/flexslider.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />

<link href="skins/default.css" rel="stylesheet" />

</head>
<body>
<div id="wrapper">
	<!-- start header -->
	<header>
        <div class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html"><span>MICDS</span> Robotics</a>
                </div>
                <div class="navbar-collapse collapse ">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.html">Home</a></li>
                        <!--<li class="dropdown">
                            <a href="#" class="dropdown-toggle " data-toggle="dropdown" data-hover="dropdown" data-delay="0" data-close-others="false">Features <b class=" icon-angle-down"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="typography.html">Typugraphy</a></li>
                                <li><a href="components.html">Components</a></li>
								<li><a href="pricingbox.html">Pricing box</a></li>
                            </ul>
                        </li>-->
                        <!--<li><a href="portfolio.html">Portfolio</a></li>-->
                        <li><a href="blog.html">Blog</a></li>
                        <!--<li><a href="contact.html">Contact</a></li>-->
                    </ul>
                </div>
            </div>
        </div>
	</header>
    
    <section id='planner_table'>
        <h1 id="inner-headline">Goals</h1>
        
        <?php 
        try {
            $events = new PDO("mysql:host=45.56.70.141:3306;dbname=micdsrobotics", "jackcai_client","991206");
        } catch (Exception $error) {
            echo "could not connect to the database, details below: "."</br>".$error;
            exit;
        }
        
        var_dump($db);
        
        
        $events = array (
            array('Notebook'),
            array('Construction'),
            array('Programming'),
            array('Outreach'),
            array('3D Printing'),
        );
        
        echo '<div class="content">';
        for ($i=0; $i<=100; $i++) {
            echo '<div class="row">';
            foreach ($events as $event){
                if (!empty($event[$i])) {
                    echo '<div class="col-lg-2">'.$event[$i].'</div>';
                }else{
                    echo '</div>';
                    break 2;
                }
            }
            echo '<div>';
        }
        echo '</div>';
        
        ?>
        <form method="post" action="goals-processing.php">
            <label for="planner_input">what are you going to add?</label>
            <input type="text" id="planner_input" name="input">
            <input type="submit" value="submit"><br/>
            <input type="radio" value="-1" name="event_type" checked>Please select an event type
            <input type="radio" value="0" name="event_type">Notebook
            <input type="radio" value="1" name="event_type">Construction
            <input type="radio" value="2" name="event_type">Programming
            <input type="radio" value="3" name="event_type">Outreach
            <input type="radio" value="4" name="event_type">3D Printing
        </form>
        
    </section>