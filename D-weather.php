<?php
/******************************************************************************
#Common URL  (w/Tempe lat & lon)
http://forecast.weather.gov/MapClick.php?w0=t&w1=td&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w12=fog&w13u=0&w15u=1&w16u=1&AheadHour=0&Submit=Submit&FcstType=digital&textField1=33.4148&textField2=-111.9093&site=all&unit=0&dd=&bw=

#URL Breakdown:
http://forecast.weather.gov/MapClick.php
?w0=t					temp
&w1=td					dew point (temp)
&w3=sfcwind				surface wind
&w3u=1					
&w4=sky					Sky Coverage (clouds)
&w5=pop					Probability of Precipitation (%)
&w6=rh					Relative Humidity
&w7=rain				Rain
&w12=fog				Fog
&w13u=0
&w15u=1
&w16u=1
&AheadHour=0
&Submit=Submit
&FcstType=digital
&textField1=33.4148			textField1 may also be named lat
&textField2=-111.9093		textField2 may also be named lon
&site=all
&unit=0
&dd=
&bw=

/*******************************************************************************
# The following based on options in the URL above. 
# The table consists of 26 rows (<tr>'s) and 25 columns (<td>'s)
# Column 0: labels. Columns 1 thru 24: each column contains one hour of data,
# starting with the current hour, unless a later hour is selected.
Rows:
 0: <td>colspan=25><hr></td>
 1: Date
 2: Hour
 3: Temp
 4: Dewpoint
 5: Wind speed
 6: Wind direction
 7: Gust
 8: Cloud cover
 9: Rain %
10: Humidity %
11: Rain amount
12: Fog
13: ...
 |: ... rows 13-25 rows repeat rows 0-12...
25: ...

http://forecast.weather.gov/MapClick.php?w0=t&w1=td&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w9=fog&w10u=0&w12u=1&w13u=1&AheadHour=0&Submit=Submit&FcstType=digital&textField1=33.4148&textField2=-111.9093&site=all&unit=0&dd=&bw=
/******************************************************************************/



#Weather url's
$BASE_URL_OPTIONS = "http://forecast.weather.gov/MapClick.php?w0=t&w1=td&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w12=fog&w13u=0&w15u=1&w16u=1&AheadHour=0&Submit=Submit&FcstType=digital&site=all&unit=0&dd=&bw=&textField1=";

$x = 0;
$DATA_URLS[$x] = $BASE_URL_OPTIONS."33.4148&textField2=-111.9093"	; $LOCATION_NAMES[$x++] = "Tempe";
$DATA_URLS[$x] = $BASE_URL_OPTIONS."33.4150&textField2=-111.5496"	; $LOCATION_NAMES[$x++] = "Apache Junction";








define(LOCATIONS, $x);
define(DEFAULT_LOCATION, 1);


#May add check for this sometime so it can be ignored.
#Maybe. Possibly. Sometime. But no promises.
#MD5 hash of the "Radar data are unavailable" image that is sometimes served.
#define(RADAR_UNAVAILABLE_MD5,  "b7578f7110b249e61a3635d5b1226d87");


define(RAW_HTML_SAMPLE, "D:/www/Weather/weather.gov/samples/weather.gov.html");


//Radar image URL's:  http://radar.weather.gov/lite/N0R/IWA_?.png    ? = 0 thru 7
//Used in Radar_Loop_scripts()
define(RADAR_URL_BASE, "http://radar.weather.gov/lite/N0R/IWA_");
define(RADAR_URL_EXT, ".png");

define(RADAR_URL_BASE_SAMPLE, "/Weather/weather.gov/samples/IWA_");
define(RADAR_URL_EXT_SAMPLE, "_sample.png");


#The min, max, & default hours to display
define(HOURS_MIN,  6);
define(HOURS_MAX, 48);
define(HOURS_DEF, 30);


#For the "Dispaly [x] hours" drop list option
define(HOURS_OPT_MIN, 12);
define(HOURS_OPT_MAX, 48);
define(HOURS_OPT_INC,  6); //INCrement from _MIN to _MAX


#Time between radar images (1000 = 1 second).
define(FRAME_RATE_MIN,  100);
define(FRAME_RATE_MAX, 1000);
define(FRAME_RATE_DEF,  200);
define(FRAME_RATE_INC,  100);


#Time to pause between radar loops (1000 = 1 second).
define(ROTATE_PAUSE_MIN,  500);
define(ROTATE_PAUSE_MAX, 4000);
define(ROTATE_PAUSE_DEF, 1000);
define(ROTATE_PAUSE_INC,  500);


#Number of times to loop, then stop.
define(ROTATE_LOOPS_MIN,  1);
define(ROTATE_LOOPS_MAX, 99);
define(ROTATE_LOOPS_DEF, 10);
define(ROTATE_LOOPS_INC,  1);


#Make sure time zone is correct.
date_default_timezone_set("America/New_York");


#Weather data is the 8th <table> in the source html (as of 2015-03-29).
#(8th counting from 1, but computer programmers like to count from 0...)
define("WEATHER_TABLE", 7);


# Rows (<tr>) of data in source html table.
# The following rows are determined by options in the URL.
# weather.gov displays 48 hours at once, starting with current hour,
# in two 24 hour sets.
# The w1_ or w2_ prefix is for which set of rows of 24 hour data. 
# The (lower-case) "w" prefix just means "weather".
define("w1_DATE",      1);
define("w1_HOUR",      2);
define("w1_TEMP",      3);
define("w1_DEWPOINT",  4);
define("w1_WIND",      5);
define("w1_WIND_DIR",  6);
define("w1_GUST",      7);
define("w1_CLOUDS",    8);
define("w1_RAIN",      9);
define("w1_HUMIDITY", 10);
define("w1_RAIN_amt", 11);
define("w1_FOG",      12);

define("w2_DATE",     14);
define("w2_HOUR",     15);
define("w2_TEMP",     16);
define("w2_DEWPOINT", 17);
define("w2_WIND",     18);
define("w2_WIND_DIR", 19);
define("w2_GUST",     20);
define("w2_CLOUDS",   21);
define("w2_RAIN",     22);
define("w2_HUMIDITY", 23);
define("w2_RAIN_amt", 24);
define("w2_FOG",      25);


#Desired rows of data from source html table. currently ignoring DEWPOINT, GUST, & RAIN_amt rows.
$DESIRED = array(
	w1_DATE, w1_HOUR, w1_TEMP, w1_WIND, w1_WIND_DIR, w1_CLOUDS, w1_RAIN, w1_HUMIDITY, w1_FOG, 
	w2_DATE, w2_HOUR, w2_TEMP, w2_WIND, w2_WIND_DIR, w2_CLOUDS, w2_RAIN, w2_HUMIDITY, w2_FOG, 
);//

#Order to subsequently re-display data (not neccessarily same order as in source)
#If order is changed - make corresponding changes to $DATA[n][0] values below!
$DISPLAY_ORDER = array(w1_DATE, w1_HOUR, w1_TEMP, w1_WIND, w1_WIND_DIR, w1_RAIN, w1_CLOUDS, w1_HUMIDITY, w1_FOG);


//#####
$DEFAULT_ASPECTS = $DISPLAY_ORDER;
sort($DEFAULT_ASPECTS);


#Number of weather rows displayed (TIME, FORCAST, TEMP, etc...)
define("WASPECTS" , count($DISPLAY_ORDER));


#For the extracted Data. $DATA[n][0] values are headers/labels, and should correlate to $DISPLAY_ORDER
#The two - 24 hour - data sets/rows will be concatenated to a single 48 hour data set.
$x=0;
$DATA = array();
$DATA[$DISPLAY_ORDER[$x]] = array(); $DATA[$DISPLAY_ORDER[$x++]][0] = "Date";
$DATA[$DISPLAY_ORDER[$x]] = array(); $DATA[$DISPLAY_ORDER[$x++]][0] = "Hour";
$DATA[$DISPLAY_ORDER[$x]] = array(); $DATA[$DISPLAY_ORDER[$x++]][0] = "Temp °f";
$DATA[$DISPLAY_ORDER[$x]] = array(); $DATA[$DISPLAY_ORDER[$x++]][0] = "Wind mph";
$DATA[$DISPLAY_ORDER[$x]] = array(); $DATA[$DISPLAY_ORDER[$x++]][0] = "Wind dir";
$DATA[$DISPLAY_ORDER[$x]] = array(); $DATA[$DISPLAY_ORDER[$x++]][0] = "Rain %";
$DATA[$DISPLAY_ORDER[$x]] = array(); $DATA[$DISPLAY_ORDER[$x++]][0] = "Clouds %";
$DATA[$DISPLAY_ORDER[$x]] = array(); $DATA[$DISPLAY_ORDER[$x++]][0] = "Humid %";
$DATA[$DISPLAY_ORDER[$x]] = array(); $DATA[$DISPLAY_ORDER[$x++]][0] = "Fog";





function hsc($input) {//********************************************************
	$enc = mb_detect_encoding($input); //It should always be UTF-8 (or ASCII), but, just in case...
	if ($enc == 'ASCII') {$enc = 'UTF-8';} //htmlspecialchars() doesn't recognize "ASCII"
	return htmlspecialchars($input, ENT_QUOTES, $enc);
}//end hsc() //*****************************************************************





function curl_get_contents($url) { //******************************************/
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);


	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
    curl_setopt($ch, CURLOPT_URL, $url);

    $html = curl_exec($ch);
    curl_close($ch);

    return $html;
} //end curl_get_contents() //*************************************************/





function Get_GET() { //********************************************************/
	#Get & validate URL parameters
	global  $HOURS_TO_SHOW, $SHOW_LOCATIONS, $LOCATION_NAMES, $DISPLAY_ORDER, $RAIN_THRESHOLD, 
			$DISPLAY_H, $SELECTED_ASPECTS, $DEFAULT_ASPECTS, $SHOW_RADAR, $WRAP_MAP, $DONT_WRAP_MAP,
			$FRAME_RATE, $ROTATE_PAUSE, $ROTATE_LOOPS,  $TEST_MODE;


	#"SHOW_LOCATIONS" ***********************
	if (!isset($_GET["SHOW_LOCATIONS"])) { $SHOW_LOCATIONS[0] = DEFAULT_LOCATION; }
	else 								 { $SHOW_LOCATIONS 	  = $_GET["SHOW_LOCATIONS"]; }
	#make sure in valid range
	foreach($SHOW_LOCATIONS as $key => $location) {
		if     (!is_numeric($key) || !is_numeric($location))	   { unset($SHOW_LOCATIONS[$key]); }
		elseif (($location < 0) || ($location > (LOCATIONS - 1)) ) { unset($SHOW_LOCATIONS[$key]); }
		else   /* make 'em int's -----------------------> */	   { $SHOW_LOCATIONS[$key] *= 1;   }
	}
	sort($SHOW_LOCATIONS);
	$SHOW_LOCATIONS = array_values($SHOW_LOCATIONS);

	#Weather "ASPEPCTS" *********************
	if (!isset($_GET["ASPECTS"])) {
		//Defaults
		$SELECTED_ASPECTS = array_slice($DEFAULT_ASPECTS,2); //(ignore Date and Hour)
		$SELECTED_ASPECTS = array_values($SELECTED_ASPECTS);
	} else {
		$SELECTED_ASPECTS = $_GET["ASPECTS"];
	}
	#make sure in valid range
	foreach($SELECTED_ASPECTS as $key => $aspect) {
		if     (!is_numeric($key) || !is_numeric($aspect))	{ unset($SELECTED_ASPECTS[$key]); }
		elseif (!in_array($aspect, $DEFAULT_ASPECTS) )		{ unset($SELECTED_ASPECTS[$key]); }
		else   /* make 'em int's ---------------------> */	{ $SELECTED_ASPECTS[$key] *= 1;   }
	}
	sort($SELECTED_ASPECTS);
	$SELECTED_ASPECTS = array_values($SELECTED_ASPECTS);


	#"HOURS_to_SHOW" ************************
	#Needed before Styles() & User_Options() are called
	if (!isset($_GET["HOURS_to_SHOW"])) {
		//default if not selected
		$HOURS_TO_SHOW = HOURS_DEF;
	} else {
		$HOURS_TO_SHOW  = intval(trim($_GET['HOURS_to_SHOW']));
		if 	   ($HOURS_TO_SHOW < HOURS_MIN) { $HOURS_TO_SHOW = HOURS_MIN; }
		elseif ($HOURS_TO_SHOW > HOURS_MAX) { $HOURS_TO_SHOW = HOURS_MAX; }
	}


	#VH *************************************
	$DISPLAY_H = FALSE;
	if (isset($_GET["VH"]) && ($_GET["VH"] == "H")) { $DISPLAY_H = TRUE; }


	#SHOW_RADAR *****************************
	if     (empty($_GET))				{ $SHOW_RADAR = TRUE;  } #Default
	elseif (isset($_GET["SHOW_RADAR"])) { $SHOW_RADAR = TRUE;  }
	else								{ $SHOW_RADAR = FALSE; }
	

	#"RAIN_THRESHOLD" Hightlight rain values when over this amount (%).
	if (isset($_GET["RAIN_THRESHOLD"])) {$RT = trim($_GET["RAIN_THRESHOLD"]);} else {$RT = 25;}
	if (!is_numeric($RT) || ($RT < 0) || ($RT > 99)) {$RT = 25;}
	$RAIN_THRESHOLD = $RT;


	#"DONT_WRAP_MAP" ************************
	if (isset($_GET['DONT_WRAP_MAP'])) {$DONT_WRAP_MAP = TRUE;} else {$DONT_WRAP_MAP = FALSE;}


	#"FRAME_RATE" ***************************
	if (isset($_GET["FRAME_RATE"])) {$FRAME_RATE = $_GET["FRAME_RATE"];} else {$FRAME_RATE = FRAME_RATE_DEF;}
	if (!is_numeric($FRAME_RATE) || ($FRAME_RATE < FRAME_RATE_MIN) || ($FRAME_RATE > FRAME_RATE_MAX)) {$FRAME_RATE = FRAME_RATE_DEF;}


	#"ROTATE_PAUSE" *************************
	if (isset($_GET["ROTATE_PAUSE"])) {$ROTATE_PAUSE = $_GET['ROTATE_PAUSE'];} else {$ROTATE_PAUSE = ROTATE_PAUSE_DEF;}
	if (!is_numeric($ROTATE_PAUSE) || ($ROTATE_PAUSE < ROTATE_PAUSE_MIN) || ($ROTATE_PAUSE > ROTATE_PAUSE_MAX)) {$ROTATE_PAUSE = ROTATE_PAUSE_DEF;}


	#"ROTATE_LOOPS" *************************
	if (isset($_GET["ROTATE_LOOPS"])) {$ROTATE_LOOPS = $_GET['ROTATE_LOOPS'];} else {$ROTATE_LOOPS = ROTATE_LOOPS_DEF;}
	if (!is_numeric($ROTATE_LOOPS) || ($ROTATE_LOOPS < ROTATE_LOOPS_MIN) || ($ROTATE_LOOPS > ROTATE_LOOPS_MAX)) {$ROTATE_LOOPS = ROTATE_LOOPS_DEF;}


	#TEST_MODE alias' (accept as URL param even if <form> option is missing)
	if (isset($_GET["test"]) || isset($_GET["TEST"]) || isset($_GET["TEST_MODE"]))
		{$TEST_MODE = true;}
	else
		{$TEST_MODE = false;}

}//end Get_GET() //************************************************************/





function Get_Weather_Data($location){ //***************************************/
	#get raw html page with weather data
	global $RAW_HTML, $DATA_URLS, $LOCATION_NAMES, $TESTING_MSG, $TEST_MODE;
	
	if ($TEST_MODE) {
		$TESTING_MSG = "<span class=TESTING_MSG>SAMPLE DATA</span>\n";
		$data_url = RAW_HTML_SAMPLE;
		$RAW_HTML = file_get_contents($data_url);
	} else { #/*** LIVE DATA ****/
		$TESTING_MSG = "";
		$data_url = $DATA_URLS[$location];
		$RAW_HTML = curl_get_contents($data_url);
		#$RAW_HTML = file_get_contents($data_url);  //stopped working sometime 2015-03-31
	}
	
	if ($RAW_HTML === false) {
		//#####echo "<pre>".hsc(print_r($http_response_header,true))."</pre>"; 
		echo "<hr>No data returned for $LOCATION_NAMES[$location]:<br>".$data_url."<br>";
	}

}//end  Get_Weather_Data() //**************************************************/





function Extract_Weather_Data() { //*******************************************/
	//Extract desired data from table and save in $DATA array().
	global $RAW_HTML, $DATA, $DESIRED;
	
	$DOM = new DOMDocument;		#$DOM -> preserveWhiteSpace = false;
	$DOM -> loadHTML($RAW_HTML);
	$WEATHER_TABLE = $DOM   -> getElementsByTagName('table') -> item(WEATHER_TABLE);

	$ROWS  = $WEATHER_TABLE	-> getElementsByTagName('tr');

	$nextset = 0; //For first 24 hour data set.
	for ($rowset=0; $rowset < 14; $rowset += 13) { //$rowset should only = 0 or 13
		
		$first_row =  1 + $rowset;
		$last_row  = 12 + $rowset;
		for ($row = $first_row; $row <= $last_row; $row++) {
			
			//only extract desired rows
			if (!in_array($row, $DESIRED)) {continue;}
			
			//get data cells
			$cells   = $ROWS->item($row) -> getElementsByTagName('td');
			
			$first_hour =  1 + $nextset;
			$last_hour  = 24 + $nextset;
			
			#Get data from cells
			for ($hour = $first_hour; $hour <= $last_hour; $hour++) {
				$DATA[$row-$rowset][$hour] = trim($cells->item($hour-$nextset)->textContent).$deg;
			}
		}// end for($row)
		
		$nextset = 24; //append next 24 hour data set to end of first set.
	}//end for ($rowset)
	
}//end Extract_Weather_Data() //***********************************************/





function Display_Weather_V($location) { //*************************************/
	//Display data in new Vertical table, each row one hour.
	global  $DATA, $LOCATION_NAMES, $TESTING_MSG, $RAIN_THRESHOLD, $HOURS_TO_SHOW, $DISPLAY_ORDER, $SELECTED_ASPECTS;

	echo "<table class=data>\n";
			  # WASPECTS is max num of columns. but it's ok if there are fewer.
		echo "<tr>\n<td colspan=".WASPECTS.">";
		echo "<h2>".$LOCATION_NAMES[$location]."<br>".$TESTING_MSG."</h2>";
		echo "</td>\n</tr>\n";
		
		for ($hour = 0; $hour <= $HOURS_TO_SHOW; $hour++) { 
			
			#Highlight Header Row (labels)
			if ($hour == 0) {$hdr = "hdr";} else {$hdr = "";}
			
			#Show day of week after new date.  (skip $hour 0 now as it is only a header)
			$day = "";
			if (($hour > 1) && ($DATA[w1_DATE][$hour - 1] != "") && ($DATA[w1_DATE][$hour] == "")) {
				   $day = date('D', strtotime(date("Y")."/".$DATA[w1_DATE][$hour - 1]));
			}
			
			#Display row of weather data for the current $hour
			echo "<tr>\n";
				echo "<th>".hsc($DATA[w1_DATE][$hour])."$day</th>\n";
				echo "<th>".hsc($DATA[w1_HOUR][$hour])."</th>\n";
				
				#Show data...
				
				for ($aspect=2; $aspect < count($DISPLAY_ORDER); $aspect++) {
					
					#...but skip un-selected weather aspects (Temp, rain, wind, etc).
					if (!in_array($DISPLAY_ORDER[$aspect], $SELECTED_ASPECTS) ) { continue; }
						
					#Highlight rain% value if >= specified value.
					if (($hour > 0) && ($DISPLAY_ORDER[$aspect] == w1_RAIN) && ($DATA[w1_RAIN][$hour] >= $RAIN_THRESHOLD))
						 { $rain = " rain"; }
					else { $rain = ""; }
					
					#Adjust css for wind
					if ( ($hour > 0) && ($DISPLAY_ORDER[$aspect] == w1_WIND_DIR)) { $wind= " wind_dir";} else { $wind = ""; }
					
					echo "<td class='$hdr$rain$wind'>".hsc($DATA[$DISPLAY_ORDER[$aspect]][$hour])."</td>\n";
				}
			echo "</tr>\n";
			
		}//end for($hour)
	echo "</table>\n";	
}//end Display_Weather_V() //**************************************************/





function Display_Weather_H($location) { //*************************************/
	//Display data in new Horizontal table, each column one hour.
	global  $DATA, $LOCATION_NAMES, $TESTING_MSG, $RAIN_THRESHOLD, $DISPLAY_ORDER, $HOURS_TO_SHOW, $SELECTED_ASPECTS;
		
	echo "<table class=data>\n";
		
		$colspan = $HOURS_TO_SHOW + 1;
		echo "<tr><td colspan=$colspan>\n";
		echo "<h2 class='location_name'>".$LOCATION_NAMES[$location]." &nbsp; ".$TESTING_MSG."</h2>\n";
		echo "</td></tr>\n";
		
		for ($tr = 0; $tr < WASPECTS; $tr++) {
			
			#Only show (Date, Time), & selected aspects
			if (!(($tr < 2) || in_array($DISPLAY_ORDER[$tr], $SELECTED_ASPECTS))) {continue;}
			
			echo "<tr>\n";
			for ($hour = 0; $hour <= $HOURS_TO_SHOW; $hour++) {
				
				#Add day of week after new date (if there's room).
				$day = "";
				if (($tr == 0) && ($hour > 1) && ($DATA[w1_DATE][$hour - 1] != "") && ($DATA[w1_DATE][$hour] == "")) {
					$day = date('D', strtotime(date("Y")."/".$DATA[w1_DATE][$hour - 1]));
				}
				
				#Highlight header/date & time rows. ***********************/
				if (($tr == 0) || ($tr == 1)) {$hdr = "hdr";} else {$hdr = "";}
				
				#Highlight rain% value if over specified value.
				$rain = "";
				if (($DISPLAY_ORDER[$tr] == w1_RAIN) && ($DATA[w1_RAIN][$hour] >= $RAIN_THRESHOLD)) { $rain = "rain"; }
				
				#Finally, ouput the weather info. hour 0 is the header/label.
				if ($hour == 0) 
					{ echo "<th>".($DATA[$DISPLAY_ORDER[$tr]][$hour])."</th>\n"; }
				else
					{ echo "<td class='".$hdr." ".$rain."'>".hsc($DATA[$DISPLAY_ORDER[$tr]][$hour])."$day $HEHE</td>\n"; }
			}//end for($hour)
			echo "</tr>\n";
		}//end for($tr)
		
	echo "</table><br>\n";
}//end Display_Weather_H() //**************************************************/





function Header_crap() {//*****************************************************/
	header('Content-type: text/html; charset=UTF-8');
	echo "<!DOCTYPE html>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'."\n";
	echo '<link rel="shortcut icon" href="http://d/D.ico"/>';
	echo "<title>Weather</title>\n";
}//end Header() {//************************************************************/




function User_Options() {//****************************************************/
	global	$LOCATION_NAMES, $RAIN_THRESHOLD, $HOURS_TO_SHOW, $SHOW_LOCATIONS, $TEST_MODE,
			$DISPLAY_H, $SELECTED_ASPECTS, $SHOW_RADAR, $DATA, $DISPLAY_ORDER, $WRAP_MAP, $DONT_WRAP_MAP;

	#First row: Locations **********************************
	echo "\n<p class=options_group>\n";
	
	echo "Show weather for: &nbsp;\n";
	$checked_location = 0;
	for ($LOCATION = 0; $LOCATION < LOCATIONS; $LOCATION++) {
		$checked = "";
		if ($SHOW_LOCATIONS[$checked_location] == $LOCATION){
			$checked = " checked";
			$checked_location++;
		}
		echo "<label class=option_label><input type=checkbox name=SHOW_LOCATIONS[$LOCATION] value=$LOCATION tabindex=1$checked>";
		echo $LOCATION_NAMES[$LOCATION]."</label>\n";
	}
	#SUBMIT button
	#echo "<button class=options autofocus>Submit</button>\n";



	#Second row: Weather aspects - Temp, Wind, etc *********
	echo "\n<p class=options_group>\n";

	echo "Show: &nbsp;\n";
	for ($aspect=2; $aspect < WASPECTS; $aspect++) {
		$checked = "";
		if (in_array($DISPLAY_ORDER[$aspect], $SELECTED_ASPECTS) )	{ $checked = " checked"; }
		echo "<label class=option_label><input type=checkbox name=ASPECTS[$DISPLAY_ORDER[$aspect]] value=$DISPLAY_ORDER[$aspect] tabindex=2$checked>";
		echo $DATA[$DISPLAY_ORDER[$aspect]][0]."</label>\n";
	}
	

	//#####SUBMIT button
	//#####echo "<button class=options>Submit</button>\n";



	#Third row: display options ****************************
	echo "\n<p class=options_group>\n";

	#Hours to display (12, 24, 36, 48)
	echo "<span class=options>Display &nbsp;<select name=HOURS_to_SHOW tabindex=3>\n";
	for ($option = HOURS_OPT_MIN; $option <= HOURS_OPT_MAX; $option += HOURS_OPT_INC) {
		if ($HOURS_TO_SHOW == $option){ $selected = " selected"; } else {$selected = "";}
		echo "<option value=$option$selected>".$option."</option>\n";
	}//end for($options)
	echo"</select> hours</span>\n";


	#Select data display mode: Vertical or Horizontal
	$selected = "";
	if ($DISPLAY_H) { $selected = " selected"; }
	echo "\n<select name=VH id=VH class=options tabindex=3>\n";
		echo "<option value=V>Vertically</option>\n";  //default selection
		echo "<option value=H$selected>Horizontally</option>\n";
	echo"</select>\n";


	#Rain Threshold: highlight rain values at this point
	echo "\n<span  class=options>Highlight rain at ";
	echo "<input type=text id=rain_threshold name=RAIN_THRESHOLD maxlength=2 value=".$RAIN_THRESHOLD." tabindex=3>";
	echo "%</span>\n";


	#Show Radar option
	$checked = "";
	if ($SHOW_RADAR) { $checked = " checked"; }
	echo "<label id=show_radar_label class=option_label>";
	echo "<input type=checkbox name=SHOW_RADAR value=true  tabindex=2$checked>";
	echo "Radar Map</label>";


	#Don't wrap map even if normally it would due to browser window width
	$checked = "";
	if ($DONT_WRAP_MAP === true) { $checked = " checked"; }
	echo "<label id=dont_wrap_map class=option_label>";
	echo "<input type=checkbox name=DONT_WRAP_MAP value=true  tabindex=3$checked>";
	echo "Don't wrap map";
	echo "</label>";
	
	
	/*#TEST MODE option  *****/
	$checked = "";
	if ($TEST_MODE) {$checked = " checked";}
	echo "\n<span id=test_mode><label class=option_label>";
	echo "<input type=checkbox name=TEST_MODE value=true tabindex=3$checked>";
	echo"Test Mode</label></span>\n";
	/*************************/

}//end User_Options() //*******************************************************/




function Styles() {//**********************************************************/
	global $HOURS_TO_SHOW, $DONT_WRAP_MAP;
?>
<style>
	*			{ font-family: arial; }
	h2 			{ font-size: 1.5em; margin: 0; }
	table.data	{ border-collapse: collapse; display: inline-block; margin: 0 .5em .5em 0; }
	td, th		{ font-size: 9pt; text-align:center; vertical-align: top;
				border: 1px solid rgb(63,131,245); padding: 0 .3em 0 .3em; }
	th			{ }
	td 			{ min-width: 2.5em; max-width: 3em; white-space: normal; } /*Default for V display.*/
	label		{ white-space: nowrap; }
	img			{ vertical-align: top; }
 
	.hdr		{ font-weight: bold; }
	.time		 { }
	.temp		 { }
	.wind_mph	 { }
	.wind_dir	 { text-align: left; padding: 0 0 0 .55em; }
	.rain		 { color: blue; font-weight: bold }
	.clouds		 { }
	.humidity	 { }

	.w_container { display: inline-block; vertical-align: top; }  /* white-space: nowrap;*/

	#submit		 { float: right; width: 9em; margin-right: 1.5em; }
	#options_form{ display: inline-block; border: 0px solid gray; padding: 0 0 0 0; }
	#default_ops { float: right; }
	#test_mode	 { float: right; }
	#timestamp	 { margin: .2em 0 .2em 0; padding: 0 .3em 1px .2em; display: inline-block; border: 1px solid teal; }
	#VH			 { }
	#rain_threshold   { width:1.2em; padding: 1px 0 0 2px; }
	#show_radar_label { margin-left: 3em; }
	#wrap_map	 	  { white-space: nowrap; }
	#dont_wrap_map	  { margin-left: 1em; }
	#STARTSTOP		  { border: 1px solid #333; border-radius: 4px; width: 10em;}
	#misc_submit_row  { margin-bottom: .3em; }
	#radar_map		  { font-size: .8em; }

	.options		 { margin: 0 1.5em 0 0; }
	.options_group	 { border: 1px solid rgb(63,131,245); padding-left: .4em; line-height: 1.35em; margin: .3em 0 .4em 0; }

	label			 { padding: 1px .3em 2px .1em; margin-right: .6em; border-left: 1px solid transparent; border-right: 1px solid transparent;}
	label:hover		 { background-color: #ddd; border-left: 1px solid rgb(63,131,245); border-right: 1px solid rgb(63,131,245);}

	.location_name	 { text-align: left; margin-left: 4em; }
	.fine_print  	 { font-size: 9pt; color: #555; }
	.TESTING_MSG 	 { color: red; }

</style>
<?php

	if ($DONT_WRAP_MAP) {echo "<style>.w_container {white-space: nowrap;}</style>\n";}

	#Adjust <td> widths for Horizontal display.
	if ($DISPLAY_H) { echo "<style>td { min-width: 2.8em; max-width: 2.8em; }</style>\n"; }

	#Adjust left margin for location name if hours < HOURS_MIN, so it's not out of box (if it's a long name).
	if ($HOURS_TO_SHOW < HOURS_MIN) { echo "<style>.location_name {margin-left: .2em;}</style>\n";}

}//end Styles() ***************************************************************/




function Time_Stamp_js() {//***************************************************/
?>
<script>
function Time_Stamp(write_return){ //********************************************/
	//returns Day, yyyy-mm-dd, hh:mm:ss am/pm

	//older, simple timestamp:  m/d/yyy h:m:ssAM/PM
	//now = new Date(); return now.toLocaleString(); 

	//var DAYS = ["Sunday", "Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
	var DAYS = ["Sun", "Mon","Tues","Wed","Thur","Fri","Sat"];

	//pad single digits. ie: 1 becomes 01
	function pad(num){ if ( num < 10 ){ num = "0" + num; }; return num; }

	var RAW_TIME  = new Date();
	var YEAR  = RAW_TIME.getFullYear();
	var	MONTH = pad(RAW_TIME.getMonth() + 1);
	var DATE  = pad(RAW_TIME.getDate());
	var DAY   = DAYS[RAW_TIME.getDay()];
	var HOURS = RAW_TIME.getHours();
	var MINS  = pad(RAW_TIME.getMinutes());
	var SECS  = pad(RAW_TIME.getSeconds());

	if (HOURS < 12) {AMPM = "am";} else {AMPM = "pm";}
	if (HOURS > 12) {HOURS = HOURS - 12;}
	
	HOURS = pad(HOURS);

	var FULLDATE = YEAR + "-" + MONTH + "-" + DATE;
	var FULLTIME = HOURS + ":" + MINS + ":" + SECS + " " + AMPM;
	
	var TIMESTAMP = DAY + ", " + FULLDATE + ", " + FULLTIME;

	if (write_return == "write") {
		document.write(TIMESTAMP);
	}else{
		return TIMESTAMP;
	}
}//end Time_Stamp() //***********************************************/
</script>

<?php
}//end Time_Stamp_js() //******************************************************/




function Show_Radar() { //*****************************************************/
	global $SELECTED_ASPECTS, $SHOW_LOCATIONS, $DISPLAY_H, $HOURS_TO_SHOW,
		   $WRAP_MAP, $FRAME_RATE, $ROTATE_PAUSE, $ROTATE_LOOPS;

	#Approximates width of total weather displayed (excluding radar)
	if ($DISPLAY_H) { $weather_width = $HOURS_TO_SHOW ; }
	else			{ $weather_width = (count($SELECTED_ASPECTS) + 2) * count($SHOW_LOCATIONS); }

//	if ($weather_width > $WRAP_MAP) {echo "<br>";}
?>
	<div id=radar_map class=w_container>
		<img src="" name="Rotating" id="RotatingPic" onclick="Start_Stop();"><br>
		
		<button type=button id=STARTSTOP  class="button options" onclick='Start_Stop()'></button>
		
		<span class=options>Frame Rate
		<select name=FRAME_RATE id=FRAME_RATE Xtabindex=4>
			<?php for ($x=FRAME_RATE_MIN; $x <= FRAME_RATE_MAX; $x+=FRAME_RATE_INC) {
				if ($x == $FRAME_RATE) { $selected = "selected"; }else{ $selected =""; }
				echo "<option value=$x $selected>$x</option>\n";
			} ?>
		</select> ms</span>
		
		<span class=options>Loop Pause
		<select name=ROTATE_PAUSE id=ROTATE_PAUSE Xtabindex=4>
			<?php for ($x=ROTATE_PAUSE_MIN; $x <= ROTATE_PAUSE_MAX; $x+=ROTATE_PAUSE_INC) {
				if ($x == $ROTATE_PAUSE) { $selected = "selected"; }else{ $selected =""; }
				echo "<option value=$x $selected>$x</option>\n";
			} ?>
		</select> ms</span>
		
		<span>Loops: </span>
		<select name=ROTATE_LOOPS id=ROTATE_LOOPS Xtabindex=4>
			<?php for ($x=ROTATE_LOOPS_MIN; $x <= ROTATE_LOOPS_MAX; $x+=ROTATE_LOOPS_INC) {
				if ($x == $ROTATE_LOOPS) { $selected = "selected"; }else{ $selected =""; }
				echo "<option value=$x $selected>$x</option>\n";
			} ?>
		</select>
		
		<div>
			<span class=fine_print style="display: block; float: right; margin-right: 1.95em;">(<span id=CURRENT_LOOP class=fine_print> </span>)</span>
			<span id=img_url class=fine_print></span><br>
		</div>
	</div>
<?php
	Radar_Loop_scripts();

}//end Show_Radar() //*********************************************************/




function Radar_Loop_scripts() { //*********************************************/
	global $TEST_MODE;
?>
<script>
function Start_Stop() { //********************************************
	var Start_Stop_button = document.getElementById('STARTSTOP');

	if (RUNNING) {
		RUNNING = false;
		Start_Stop_button.innerHTML = "Start Radar Loop";
		Start_Stop_button.style.backgroundColor = "transparent";
		clearInterval(loop_timer); //Not actually running on init...
	} else {
		RUNNING = true;
		Start_Stop_button.innerHTML = "Stop Radar Loop"; 
		Start_Stop_button.style.backgroundColor = "#FFd0d0"; //light red
		Rotate_Pic(0); //0 skips the PAUSE if (re)starting rotation on pic 0.
	}
} //end Start_Stop() //***********************************************



function Rotate_Pic(pause){ //****************************************
	var rotate_delay = Frame_Rate_Options[Frame_Rate_Options.selectedIndex].value;
	var rotate_pause = Rotate_Pause_Options[Rotate_Pause_Options.selectedIndex].value;
	var rotate_loops = ROTATE_LOOPS_Options[ROTATE_LOOPS_Options.selectedIndex].value;
	var MAX_ROTATIONS = (rotate_loops * pic_list.length) -1 ; //actually, the number of images to cycle thru.

	if (CURRENT_PIC == (pic_list.length - 1)) {document.getElementById('CURRENT_LOOP').innerHTML = CURRENT_LOOP++ ;}

	pause = typeof pause !== 'undefined' ? pause : rotate_pause;

	//Display new image url.
	Img_URL.innerHTML = "(" + pic_list[CURRENT_PIC] + ")";

	if (CURRENT_PIC == 0) {delay = pause;} else {delay = rotate_delay;}

	//Rotate / show new image.
	document.getElementById('RotatingPic').src = pic_list[CURRENT_PIC--];

	if(CURRENT_PIC < 0) {CURRENT_PIC = (pic_list.length - 1);}

	loop_timer = setTimeout('Rotate_Pic(' + rotate_pause  + ')',delay);
	if (FRAME_COUNT++ > MAX_ROTATIONS) {FRAME_COUNT = 0; CURRENT_PIC = 0;  CURRENT_LOOP = 1; Start_Stop();}
}//end Rotate_Pic() //************************************************



//Load radar image URL's
//(Last 8 radar images available from weather.gov (IWA is central AZ))
var pic_list = new Array();
<?php if ($TEST_MODE) { ?>
		for (var x = 0; x < 8; x++) { pic_list[x] = '<?php echo RADAR_URL_BASE_SAMPLE ?>' + x + '<?php echo RADAR_URL_EXT_SAMPLE ?>'; }
<?php } else { ?>
		for (var x = 0; x < 8; x++) { pic_list[x] = '<?php echo RADAR_URL_BASE ?>'		  + x + '<?php echo RADAR_URL_EXT ?>'; }
<?php } ?>


//Show initial image url. Img_URL also used in Rotate_Pic()
var Img_URL = document.getElementById('img_url');
Img_URL.innerHTML = "(" + pic_list[0] + ")";


//FRAME_RATE = ROTATE_DELAY = pause between each image,
//set in Start_Stop() from the following user option (FRAME_RATE).
var Frame_Rate_Options	 = USER_OPTIONS.FRAME_RATE;

//ROTATE_PAUSE = pause between loops.
//Set in Start_Stop() from the following opton (ROTATE_PAUSE).
var Rotate_Pause_Options = USER_OPTIONS.ROTATE_PAUSE;

//ROTATE_LOOPS = number of times to cycle thru radar images, then stop.
// Used to determine MAX_ROTATIONS in Rotate_Pic().
var ROTATE_LOOPS_Options = USER_OPTIONS.ROTATE_LOOPS;
var CURRENT_LOOP = 1;


//load initial radar image, then init the Start_Stop <button> as "Start...
document.getElementById('RotatingPic').src = pic_list[0];
var FRAME_COUNT  = 0; //Total pics rotated...
var CURRENT_PIC = 0;  // index for pic_list[CURRENT_PIC]
var RUNNING = true;	  //Will be flipped to false by initial call to  Start_Stop() below.
var loop_timer   = setTimeout(";", 1); //just init for first call to Start_Stop().
Start_Stop();
</script>

<?php
}//end Radar_Loop_scripts() //*************************************************/




# "Main" //********************************************************************/
#

Get_GET(); //needed before Styles() & User_Options();

Header_crap();
Styles();
Time_Stamp_js();


#</form> is after Show_Radar(), to include radar options
echo "\n<form name=USER_OPTIONS method=get id=options_form>\n"; 

User_Options();



echo '<div id=misc_submit_row>';

	#Reload with default options
	echo "\n<button type=button id=default_ops class=button onclick='parent.location=location.pathname' tabindex=1000 autofocus>Default Options</button>\n";

	#SUBMIT button
	#if ($SHOW_RADAR) {$autofocus = "";} else {$autofocus = "autofocus";}
	echo "\n<button class=button id=submit tabindex=999>Submit</button>\n";

	#Time Stamp
	echo "<span id=timestamp><script>Time_Stamp('write');</script></span>";

	#Data Source
	echo " <span class=fine_print>(Weather data source: ";
		if ($TEST_MODE) {echo "<span class=TESTING_MSG>".RAW_HTML_SAMPLE."</span>";}
		else			{echo "www.weather.gov";}
	echo ")</span><br>\n";

echo "</div>\n";


#Get & display weather for selected locations
echo "\n<div class=w_container>\n";

	echo "\n<div class=w_container>\n";
	for ($SELECTED = 0; $SELECTED < COUNT($SHOW_LOCATIONS); $SELECTED++) {
		
		Get_Weather_Data($SHOW_LOCATIONS[$SELECTED]);
		
		Extract_Weather_Data();
		
		if ($DISPLAY_H) { Display_Weather_H($SHOW_LOCATIONS[$SELECTED]); }
		else			{ Display_Weather_V($SHOW_LOCATIONS[$SELECTED]); }
	}//end for($SELECTED)
	echo "</div>\n"; #end w_container

	if ($SHOW_RADAR) {Show_Radar();}

echo "</div>\n"; #end w_container


echo "</form>\n\n";
#
# end "Main" //****************************************************************/








################################################################################
if ($TEST_MODE) {
	echo "\n<style> body {background-color: #eee}</style>\n";
	echo '<hr><pre style="clear: both;">$_GET: '		    .hsc(print_r($_GET, true))."</pre>\n";
	echo '<hr><pre style="clear: both;">$SHOW_LOCATIONS: '  .hsc(print_r($SHOW_LOCATIONS, true))."</pre>\n";
	echo '<hr><pre style="clear: both;">$DEFAULT_ASPECTS: ' .hsc(print_r($DEFAULT_ASPECTS, true))."</pre>\n";
	echo '<hr><pre style="clear: both;">$SELECTED_ASPECTS: '.hsc(print_r($SELECTED_ASPECTS, true))."</pre>\n";
	echo '<hr><pre style="clear: both;">$DATA: '		    .hsc(print_r($DATA, true))."</pre>\n";
	#echo '<hr><pre style="clear: both; font-family: courier">$RAW_HTML: <br>'.hsc($RAW_HTML)."</pre>";  //#####
}
################################################################################


echo "<div style='clear: both; border: 2px outset gray; height: .5em; '>&nbsp;</div>";