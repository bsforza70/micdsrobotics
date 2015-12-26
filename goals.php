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
                        <li><a href="index.html">Home</a></li>
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
                        <li class="active"><a href="goals.php">Goals</a></li>
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
            $events_db = new PDO("mysql:host=localhost;dbname=micdsrobotics", "root");
            $events_db -> setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $events_db -> exec("SET NAMES 'utf8'");
        } catch (Exception $error) {
            echo "could not connect to the database, details below:"."</br>".$error;
            exit;
        }


        try {
            $results2 = $events_db -> prepare("SELECT Notebook, Construction, Programming, Outreach, 3D_Printing FROM events");
            $results2 -> execute();
        } catch (Exception $error) {
            echo "could not retrieve events data, details below:"."</br>".$error;
            exit;
        }

        $test1 = $results2->fetchAll(PDO::FETCH_ASSOC);
        $switch = True;
        foreach ($test1 as $test_row) {
            foreach ($test_row as $event_type => $event) {
                if ($event_type === $_POST["event_type"] and $event === $_POST["input"]) {
                    echo '<pre>';
                    echo 'You have entered repeated data at ' . $event_type . ', content: ' . $event;
                    echo '</pre>';
                    $switch = False;
                }
            }
        }
        $test1 = null;

        try {
            $results1 = $events_db -> query("SELECT * FROM events ORDER BY id DESC LIMIT 1");
        } catch (Exception $error) {
            echo "could not retrieve input data, details below:"."</br>".$error;
            exit;
        }

        $test2 = $results1->fetch(PDO::FETCH_ASSOC);
        foreach ($test2 as $event_type => $event) {
            if ($event_type === $_POST["event_type"] and isset($event)) {
                echo '<pre>';
                echo 'You have attempted to enter more than one goals at ' . $event_type . ', content: ' . $event;
                echo '</pre>';
                $switch = False;
            }
        }

        if ($_POST["event_type"] != -1 and !empty(trim($_POST["input"])) and $switch) {
            try {
                $results1 = $events_db -> query("SELECT * FROM events ORDER BY id DESC LIMIT 1");
            } catch (Exception $error) {
                echo "could not retrieve input data, details below:"."</br>".$error;
                exit;
            }

            $content = $results1->fetch(PDO::FETCH_BOTH); //Select & Get the most recent row. 
            $current_id = $content[0];
            $new_id = $current_id + 1;

            $sql1 = "INSERT INTO events (" . $_POST["event_type"] . ", id) VALUES ('" . $_POST["input"] . "', '" . $new_id . "');";//Execute this if the most recent colum is filled. (Update contents inside the most recent row.)
            $sql2 = "UPDATE events SET " . $_POST["event_type"] . "= '" . $_POST["input"] . "' WHERE id = " . $current_id . ";";//Execute this if the most recent colum contains null values. (Insert a new row and that row would become the most recent one.)
            $empty_row = False;                                                                                            #/#
                                                                                                                          #/#
            for ($i=0; $i<=5; $i++) {                                                                                    #/#
                $empty_row = ($empty_row OR is_null($content[$i]));                                                     #/#
            }//Checks to see if all the colums in the most recent row is populated.                  #                 #/#
                                                                                                  #/#                 #/#
            if (!$empty_row) {                                                                  #/#                  #/#
                try {                                                                         #/#                   #/#
                    $events_db -> exec($sql1);                                              #/#                    #/#
                } catch (Exception $error) {                                              #/#                     #/#
                    echo "could not retrieve input data, details below:"."</br>".$error;############################
                    exit;                                                                 #\#
                }                                                                           #\#
            } else {                                                                          #\#
                try {                                                                           #\#
                    $events_db -> exec($sql2);                                                    #\#
                } catch (Exception $error){                                                          #
                    echo "could not retrieve input data, details below:"."</br>".$error;
                    exit;
                }
            }
        }//Detects imput for goals events and update the database. 

        //if ($_POST["delete"] == delete){

        //}//Detects deletion requests and update the database. 

        try {
            $results2 = $events_db -> prepare("SELECT Notebook, Construction, Programming, Outreach, 3D_Printing FROM events");
            $results2 -> execute();
        } catch (Exception $error) {
            echo "could not retrieve events data, details below:"."</br>".$error;
            exit;
        }
        
        $content = $results2->fetchAll(PDO::FETCH_NUM);

        //Now the content variable is a two dimentional array that stores all the events. 
        $event_db=null;
        //displaying the content
        echo '<div class="content">';
        foreach ($content as $row_id => $events_array) {
            echo '<div class="row">';
            echo '<div class=col-lg-1></div>';
            foreach ($events_array as $event) {
                echo '<div class=col-lg-2><p>'.$event.'</p>';
                if (!is_null($event) and ($row_id != 0)) {
                    echo '<form method="post", action="goals.php">
                             <input type="button" value="delete" name="delete">
                         </form>';//delete button
                }
                echo '</div>';
            }
            echo '</div>';
        }
        ?>
        <form method="post" action="goals.php">
            <label for="planner_input">What are you going to add?</label>
            <input type="textarea" id="planner_input" name="input" autocomplete="on"></br>
            <input type="radio" value="-1" name="event_type" checked>Please select an event type:
            <input type="radio" value="Notebook" name="event_type">Notebook
            <input type="radio" value="Construction" name="event_type">Construction
            <input type="radio" value="Programming" name="event_type">Programming
            <input type="radio" value="Outreach" name="event_type">Outreach
            <input type="radio" value="3D_Printing" name="event_type">3D Printing
            <input type="submit" value="submit">
        </form>
        
    </section>