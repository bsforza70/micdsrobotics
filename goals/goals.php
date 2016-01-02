 <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>MICDS Robotics</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Bob Sforza & Charlie Biggs" />
<meta name="author" content="MICDS Robotics" />
<!-- css -->
<link href="../css/bootstrap.min.css" rel="stylesheet" />
<link href="../css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="../css/jcarousel.css" rel="stylesheet" />
<link href="../css/flexslider.css" rel="stylesheet" />
<link href="../css/style.css" rel="stylesheet" />
<link href="../css/goals_css.css" rel="stylesheet" />

<link href="../skins/default.css" rel="stylesheet" />

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
                    <a class="navbar-brand" href="../index.html"><span>MICDS</span> Robotics</a>
                </div>
                <div class="navbar-collapse collapse ">
                    <ul class="nav navbar-nav">
                        <li><a href="../index.html">Home</a></li>
                        <!--<li class="dropdown">
                            <a href="#" class="dropdown-toggle " data-toggle="dropdown" data-hover="dropdown" data-delay="0" data-close-others="false">Features <b class=" icon-angle-down"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="typography.html">Typugraphy</a></li>
                                <li><a href="components.html">Components</a></li>
								<li><a href="pricingbox.html">Pricing box</a></li>
                            </ul>
                        </li>-->
                        <!--<li><a href="portfolio.html">Portfolio</a></li>-->
                        <li><a href="../blog.html">Blog</a></li>
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
        
        define ('URL', 'https://www.micdsrobotics.com/goals/');
        
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
                $sql2 = "UPDATE events SET " . $_POST["event_type"] . "= '<p>" . $_POST["input"] . "</p><p>(" . $_POST["time"] . ")</p>" . "' WHERE id = " . $latest_id . ";";//Updating the values

                try {
                    $events_db -> exec($sql2); 
                } catch (Exception $error){ 
                    echo "could not create goal, details below:"."</br>".$error;
                    exit;
                }
            }
        }//Detects imput for goals events and update the database. 

        if (isset($_POST["row_request"]) and isset($_POST["row_id"])) {
            $latest_id = $_POST["row_id"];
            if (isset($_POST["row_id"])) {
                $deletion_id = $_POST["row_id"];
            } else {
                $deletion_id = $latest_id;
            }
            $new_id = $latest_id + 1; 
            $sql1 = "INSERT INTO events (id) VALUES ('" . $new_id . "');";//Execute to add one row with only an id colum to the database if the add row button is pressed. 
            $sql3 = "DELETE FROM events WHERE id =" . $deletion_id .  ";";//delete row. 
            $sql5 = "UPDATE events SET id=id+1 WHERE id >= " . $new_id . ";";
            $sql6 = "UPDATE events SET id=id-1 WHERE id >= " . $new_id . ";";
            $deletion_confirm = False;
            if (isset($_POST["deletion_confirm"])) {
                if ($_POST["deletion_confirm"]=="Yes") {
                    $deletion_confirm=True;
                }
            }
            if ($_POST["row_request"]=='+') {
                try { 
                    $events_db -> exec($sql5.$sql1); 
                } catch (Exception $error) {
                    echo "could not create row, details below:"."</br>".$error;
                    exit;
                } 
            } elseif (isset($deletion_id) and $deletion_confirm and $_POST["row_request"]=='-') {
                try {
                    $events_db -> exec($sql3.$sql6); 
                } catch (Exception $error){ 
                    echo "could not delete row, details below:"."</br>".$error;
                    exit;
                }
            } elseif ($_POST["row_request"]=='-' and !$deletion_confirm) {
                echo '<section class="deletion_confirm">Are you sure you want to delete the whole row?
                    <form method="POST" action="." style="display:inline-block">
                        <input type="hidden" value="-" name="row_request" style="height:1px; width:1px">
                        <input type="hidden" value="Yes" name="deletion_confirm" style="height:1px; width:1px">
                        <input type="hidden" value="'.$latest_id.'" name="row_id" style="height:1px; width:1px">
                        <input type="submit" value="Yes">
                    </form>
                    <form mothod="POST" action="." style="display:inline-block"> 
                        <input type="submit" value="No">
                    </form>
                </section>';
            }//Add rows to below or delete rows
        }

        if (isset($_POST["delete"])) {
            if ($_POST["delete"] == 'delete'){
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
        echo '<div class="table-reponsive">';
        echo '<table class="table table-striped">';
        foreach ($content as $row_id => $events_array) {
            echo '<tr>';
            foreach ($events_array as $event_type => $event) {
                if ($row_id!=0){
                    echo '<td class="goals_content type_'.$event_type.' row_'.$row_id.'">'.$event;
                    if (!is_null($event) and ($row_id != 0)) {
                        echo '<form method="post", action=".">
                                <input type="hidden" value="delete" name="delete">
                                <input type="hidden" value="' . $row_id . '" name="row_id">
                                <input type="hidden" value="' . $event_type . '" name="event_type">
                                <input type="hidden" value="' . date(DATE_RSS) . '" name="time">
                                <input type="image" value="submit" src="../img/delete_button1.png" border="0" alt="delete" class="delete_button1">
                             </form>';//delete button
                    }
                    if (is_null($event) and ($row_id != 0)) {
                        echo '<form method="post", action=".">
                                <textarea name="input" autocomplete="on" class="textarea" placeholder="+" cols=20 rows=10 minlength=5></textarea>
                                <input type="hidden" value="' . $row_id . '" name="row_id">
                                <input type="hidden" value="' . $event_type . '" name="event_type">
                                <input type="hidden" value="' . date(DATE_RSS) . '" name="time">
                                <input type="image" value="submit" src="../img/submit_button.png" border="0" alt="Submit" class="submit_button">
                             </form>';//textarea and add button
                    }
                    echo '</td>';
                }else{
                    echo '<th class="event_type">'.$event.'</th>';
                }
            }
            echo '<td class="rows_button"><form method="post", action=".">
                    <input type="hidden" value="+" name="row_request">
                    <input type="hidden" value="' . $row_id . '" name="row_id">
                    <input type="image" value="+" src="../img/add_button.png" border="0" alt="Delete row" class="add_button">
                </form>';
            if ($row_id != 0) {
                echo '<form method="post", action=".">
                    <input type="hidden" value="-" name="row_request">
                    <input type="hidden" value="' . $row_id . '" name="row_id">
                    <input type="image" value="-" src="../img/delete_button.png" border="0" alt="Add row" class="delete_button">
                </form>';
            }echo '</td></tr>';
        } echo '</table>';
        echo '</div>';
        ?>
        
    </section>
<footer>
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				<div class="widget">
					<h5 class="widgetheading">Get in touch with us</h5>
					<address>
					<strong>MICDS Robotics</strong><br>
					 101 N Warson Rd<br>
					 St. Louis, MO 63124</address>
					<p>
						<i class="icon-phone"></i> (314) 867-5309 <br>
						<i class="icon-envelope-alt"></i> awilson@micds.org
					</p>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="widget">
					<h5 class="widgetheading">Pages</h5>
					<ul class="link-list">
						<li><a href="#">Press release</a></li>
						<li><a href="#">Terms and conditions</a></li>
						<li><a href="#">Privacy policy</a></li>
						<li><a href="#">Career center</a></li>
						<li><a href="#">Contact us</a></li>
					</ul>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="widget">
					<h5 class="widgetheading">Latest posts</h5>
					<ul class="link-list">
						<li><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></li>
						<li><a href="#">Pellentesque et pulvinar enim. Quisque at tempor ligula</a></li>
						<li><a href="#">Natus error sit voluptatem accusantium doloremque</a></li>
					</ul>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="widget">
					<h5 class="widgetheading">Flickr photostream</h5>
					<div class="flickr_badge">
						<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=8&amp;display=random&amp;size=s&amp;layout=x&amp;source=user&amp;user=34178660@N03"></script>
					</div>
					<div class="clear">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="sub-footer">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="copyright">
						<p>
							<span>&copy; Moderna 2014 All right reserved. By </span><a href="http://bootstraptaste.com" target="_blank">Bootstrap Themes</a>
						</p>
                        <!-- 
                            All links in the footer should remain intact. 
                            Licenseing information is available at: http://bootstraptaste.com/license/
                            You can buy this theme without footer links online at: http://bootstraptaste.com/buy/?theme=Moderna
                        -->
					</div>
				</div>
				<div class="col-lg-6">
					<ul class="social-network">
						<li><a href="#" data-placement="top" title="Facebook"><i class="fa fa-facebook"></i></a></li>
						<li><a href="#" data-placement="top" title="Twitter"><i class="fa fa-twitter"></i></a></li>
						<li><a href="#" data-placement="top" title="Linkedin"><i class="fa fa-linkedin"></i></a></li>
						<li><a href="#" data-placement="top" title="Pinterest"><i class="fa fa-pinterest"></i></a></li>
						<li><a href="#" data-placement="top" title="Google plus"><i class="fa fa-google-plus"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
    <script src="../js/jquery.js" type="text/javascript" charset="utf-8"></script>
    <script src="../js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="../js/goals/goals.js" type="text/javascript" charset="utf-8"></script>
	</footer>