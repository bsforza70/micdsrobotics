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
            $events_db = new PDO("mysql:host=45.56.70.141;dbname=robotics_goals", "jackcai_client", "991206");
            $events_db -> setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $events_db -> exec("SET NAMES 'utf8'");
        } catch (Exception $error) {
            echo "could not connect to the database, details below:"."</br>".$error;
            exit;
        }

        if (isset($_POST["event_type"]) and isset($_POST["input"])) {
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

            if (!empty(trim($_POST["input"])) and $switch) {

                $latest_id = $_POST["row_id"];
                $new_id = $latest_id + 1; 
                $sql2 = "UPDATE events SET " . $_POST["event_type"] . "= '" . $_POST["input"] . "(" . $_POST["time"] . ")" . "' WHERE id = " . $latest_id . ";";//Updating the values

                try {
                    $events_db -> exec($sql2); 
                } catch (Exception $error){ 
                    echo "could not create goal, details below:"."</br>".$error;
                    exit;
                }
            }
        }//Detects imput for goals events and update the database. 

        if (isset($_POST["row_request"])) {

            $latest_id = $_POST["row_id"];
            $new_id = $latest_id + 1; 
            $sql1 = "INSERT INTO events (id) VALUES ('" . $new_id . "');";//Execute to add one row with only an id colum to the database if the add row button is pressed. 
            $sql3 = "DELETE FROM events WHERE id =" . $latest_id .  ";";
            $sql5 = "UPDATE events SET id=id+1 WHERE id >= " . $new_id . ";";
            $sql6 = "UPDATE events SET id=id-1 WHERE id >= " . $new_id . ";";

            if ($_POST["row_request"]=='+') {
                try { 
                    $events_db -> exec($sql5.$sql1); 
                } catch (Exception $error) {
                    echo "could not create row, details below:"."</br>".$error;
                    exit;
                } 
            } elseif ($_POST["row_request"]=='-') {
                try {
                    $events_db -> exec($sql6.$sql3); 
                } catch (Exception $error){ 
                    echo "could not delete row, details below:"."</br>".$error;
                    exit;
                }
            }//Add rows to below or delete rows
        }

        if (isset($_POST["delete"])) {
            if ($_POST["delete"] == delete){
                $latest_id = $_POST["row_id"];
                $sql4 = "UPDATE events SET " . $_POST["event_type"] . "=null WHERE id = " . $latest_id . ";";//removing data
                try {
                        $events_db -> exec($sql4); 
                    } catch (Exception $error){ 
                        echo "could not delete goal, details below:"."</br>".$error;
                        exit;
                    }
            }//Detects deletion requests and update the database. 
        }

        try {
            $results2 = $events_db -> prepare("SELECT Notebook, Construction, Programming, Outreach, 3D_Printing FROM events ORDER BY id ASC");
            $results2 -> execute();
        } catch (Exception $error) {
            echo "could not retrieve events data, details below:"."</br>".$error;
            exit;
        }
        
        $content = $results2->fetchAll(PDO::FETCH_ASSOC);

        //Now the content variable is a two dimentional array that stores all the events. 
        $event_db=null;
        //displaying the content
        echo '<div class="content">';
        foreach ($content as $row_id => $events_array) {
            var_dump($row_id);
            echo '<div class="row">';
            echo '<div class=col-lg-1></div>';
            foreach ($events_array as $event_type => $event) {
                echo '<div class=col-lg-2><p class="goals_content">'.$event.'</p>';
                if (!is_null($event) and ($row_id != 0)) {
                    echo '<form method="post", action="goals.php">
                            <input type="hidden" value="delete" name="delete">
                            <input type="hidden" value="' . $row_id . '" name="row_id">
                            <input type="hidden" value="' . $event_type . '" name="event_type">
                            <input type="hidden" value="' . date(DATE_RSS) . '" name="time">
                            <input type="submit" value="delete">
                         </form>';//delete button
                }
                if (is_null($event) and ($row_id != 0)) {
                    echo '<form method="post", action="goals.php">
                            <label for="planner_input">Add:</label>
                            <input type="textarea" id="planner_input" name="input" autocomplete="on">
                            <input type="hidden" value="' . $row_id . '" name="row_id">
                            <input type="hidden" value="' . $event_type . '" name="event_type">
                            <input type="hidden" value="' . date(DATE_RSS) . '" name="time">
                            <input type="submit" value="submit">
                         </form>';//add button
                }
                echo '</div>';
            }
            echo '</div>';
            echo '<form method="post", action="goals.php">
                    <input type="hidden" value="+" name="row_request">
                    <input type="hidden" value="' . $row_id . '" name="row_id">
                    <input type="submit" value="+">
                </form>';
            if ($row_id != 0) {
                echo '<form method="post", action="goals.php">
                    <input type="hidden" value="-" name="row_request">
                    <input type="hidden" value="' . $row_id . '" name="row_id">
                    <input type="submit" value="-">
                </form>';
            }
        }
        ?>
        
    </section>