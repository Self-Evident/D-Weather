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
# Columns (td's): 0: Label,  1: ... 24: 24 hour columns of data,
# starting with current hour, unless later hour selected.
Rows:
 0: <td>colspan=25> <hr>
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



$RADAR_IMG    = '<img src="http://radar.weather.gov/lite/N0R/IWA_0.png">';
$RADAR_SAMPLE = '<img src="samples/IWA_SAMPLE.png">';

define(RAW_HTML_SAMPLE, "samples/weather.gov.html");


#The min & max displayed columns option at which to $WRAP_MAP
define(WRAP_MIN,  5);
define(WRAP_MAX, 18);


#Make sure time zone is correct.
date_default_timezone_set("America/New_York");


#Weather data is the 8th <table> in the source html (as of 2015-03-29).
#(8th counting from 1, but computers like to count from 0...)
define("WEATHER_TABLE", 7);


# Rows (<tr>) of data in source html table.
# The following rows are determined by options in the URL.
# weather.gov displays 48 hours at once, starting with current hour,
# in two 24 hour sets.
# The w1_ or w2_ prefix is for which set of rows of 24 hour data. 
# The (lower-case) "w" prefix means "weather"
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


#Desired rows of data from source html table #(currently ignoring DEWPOINT, GUST, & RAIN_amt rows).
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
#The two - 24 hour - data sets/rows will be concatenated to a 48 hour data set.
$DATA					 = array();
$DATA[$DISPLAY_ORDER[0]] = array(); $DATA[$DISPLAY_ORDER[0]][0] = "Date";
$DATA[$DISPLAY_ORDER[1]] = array(); $DATA[$DISPLAY_ORDER[1]][0] = "Hour";
$DATA[$DISPLAY_ORDER[2]] = array(); $DATA[$DISPLAY_ORDER[2]][0] = "Temp °f";
$DATA[$DISPLAY_ORDER[3]] = array(); $DATA[$DISPLAY_ORDER[3]][0] = "Wind mph";
$DATA[$DISPLAY_ORDER[4]] = array(); $DATA[$DISPLAY_ORDER[4]][0] = "Wind dir";
$DATA[$DISPLAY_ORDER[5]] = array(); $DATA[$DISPLAY_ORDER[5]][0] = "Rain %";
$DATA[$DISPLAY_ORDER[6]] = array(); $DATA[$DISPLAY_ORDER[6]][0] = "Clouds %";
$DATA[$DISPLAY_ORDER[7]] = array(); $DATA[$DISPLAY_ORDER[7]][0] = "Humid %";
$DATA[$DISPLAY_ORDER[8]] = array(); $DATA[$DISPLAY_ORDER[8]][0] = "Fog";




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
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
    curl_setopt($ch, CURLOPT_URL, $url);

    $html = curl_exec($ch);
    curl_close($ch);

    return $html;
} //end curl_get_contents() //*************************************************/





function Get_GET() { //********************************************************/
	#Get & validate URL parameters
	global  $HOURS_to_SHOW, $SHOW_LOCATIONS, $LOCATION_NAMES, $DISPLAY_ORDER, $RAIN_THRESHOLD, 
			$SELECTED_ASPECTS, $DEFAULT_ASPECTS, $SHOW_RADAR, $WRAP_MAP, $TEST_MODE;


	#"HOURS_to_SHOW" ************************
	#Needed before Styles() & User_Options() are called
	if (!isset($_GET["HOURS_to_SHOW"])) {
		//only show 24 hours by default
		$HOURS_to_SHOW = 24;
	} else {
		$HOURS_to_SHOW  = intval($_GET['HOURS_to_SHOW']);
		if 	   ($HOURS_to_SHOW < 1)  { $HOURS_to_SHOW =  1; }
		else if($HOURS_to_SHOW > 48) { $HOURS_to_SHOW = 48; }
	}


	#"SHOW_LOCATIONS" ***********************
	if (!isset($_GET["SHOW_LOCATIONS"])) { $SHOW_LOCATIONS[0] = DEFAULT_LOCATION; }
	else 								 { $SHOW_LOCATIONS 	  = $_GET["SHOW_LOCATIONS"]; }
	#make sure in valid range
	foreach($SHOW_LOCATIONS as $key => $location) {
		if (!is_numeric($key) || !is_numeric($location)) 			{ unset($SHOW_LOCATIONS[$key]); }
		else if (($location < 0) || ($location > (LOCATIONS - 1)) ) { unset($SHOW_LOCATIONS[$key]); }
		else /*make 'em int's*/										{ $SHOW_LOCATIONS[$key] *= 1;   }
	}
	sort($SHOW_LOCATIONS);
	$SHOW_LOCATIONS = array_values($SHOW_LOCATIONS);


	#Weather "ASPEPCTS" *********************
	if (!isset($_GET["ASPECTS"])) {
		$SELECTED_ASPECTS = array_slice($DEFAULT_ASPECTS,2); //(ignore Date and Hour)
		$SELECTED_ASPECTS = array_values($SELECTED_ASPECTS);
	} else {
		$SELECTED_ASPECTS = $_GET["ASPECTS"];
	}
	#make sure in valid range
	foreach($SELECTED_ASPECTS as $key => $aspect) {
		if (!is_numeric($key) || !is_numeric($aspect))	{ unset($SELECTED_ASPECTS[$key]); }
		else if (!in_array($aspect, $DEFAULT_ASPECTS) )	{ unset($SELECTED_ASPECTS[$key]); }
		else {$SELECTED_ASPECTS[$key] *= 1;} //make 'em int's
	}
	sort($SELECTED_ASPECTS);
	$SELECTED_ASPECTS = array_values($SELECTED_ASPECTS);


	#SHOW_RADAR *****************************
	$SHOW_RADAR = "true";
	if (isset($_GET["SHOW_RADAR"]) && ($_GET["SHOW_RADAR"] == "false")) { $SHOW_RADAR = false; }


	#"WRAP_MAP" *****************************
	if (isset($_GET['WRAP_MAP'])) { $WRAP_MAP = $_GET['WRAP_MAP']; } else {$WRAP_MAP = WRAP_MAX;}
	if (!is_numeric($WRAP_MAP) || ($WRAP_MAP < WRAP_MIN) || ($WRAP_MAP > WRAP_MAX)) {$WRAP_MAP = WRAP_MAX;}


	#"RAIN_THRESHOLD" Hightlight rain values when over this amount (%).
	if (!isset($_GET["RAIN_THRESHOLD"])) {$_GET["RAIN_THRESHOLD"] = 25;}
	$RT = trim($_GET["RAIN_THRESHOLD"]);
	if (!is_numeric($RT) || ($RT < 0) || ($RT > 99)) {$RT = 25;}
	$RAIN_THRESHOLD = $RT;


	#TEST_MODE alias' (accepts as URL param even if <form> option is missing)
	if (isset($_GET["test"]) || isset($_GET["TEST"]) || isset($_GET["TEST_MODE"]))
		{$TEST_MODE = true;}
	else
		{$TEST_MODE = false;}

}//end Get_GET() //************************************************************/





function Get_Weather_Data($location){ //***************************************/
	#get raw html page with weather data
	global $RAW_HTML, $DATA_URLS, $TESTING_MSG, $RADAR_IMG, $RADAR_SAMPLE, $TEST_MODE;



	if ($TEST_MODE) {
		$TESTING_MSG = "<span class=TESTING_MSG>SAMPLE DATA</span>\n";
		$RAW_HTML = file_get_contents(RAW_HTML_SAMPLE);
		$RADAR_IMG = $RADAR_SAMPLE;
	} else { #/*** LIVE DATA ****/
		$TESTING_MSG = "";
		$RAW_HTML = curl_get_contents($DATA_URLS[$location]);
		#$RAW_HTML = file_get_contents($DATA_URLS[$location]);  //stopped working sometime 2015-03-31
	}
	
	if ($RAW_HTML === false) {
		#echo "<pre>".hsc(print_r($http_response_header,true))."</pre>"; 
		echo "<hr>No data returned from:<br>".$DATA_URLS[$location];
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
	global  $DATA, $LOCATION_NAMES, $TESTING_MSG, $RAIN_THRESHOLD, $HOURS_to_SHOW, $DISPLAY_ORDER, $SELECTED_ASPECTS;

	echo "<table class=data>\n";
			  # WASPECTS is max num of columns. but it's ok if there are fewer.
		echo "<tr>\n<td colspan=".WASPECTS.">";
		echo "<h2>".$LOCATION_NAMES[$location]."<br>".$TESTING_MSG."</h2>";
		echo "</td>\n</tr>\n";
		
		for ($hour = 0; $hour <= $HOURS_to_SHOW; $hour++) { 
			
			#Highlight Header Row (labels)
			if ($hour == 0) {$hdr = "hdr";} else {$hdr = "";}
			
			#Show day of week after new date.  (skip $hour 0 as it is only a header)
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
					
					#...but only for selected weather aspects (Temp, rain, wind, etc).
					if (!in_array($DISPLAY_ORDER[$aspect], $SELECTED_ASPECTS) ) { continue; }
						
					#Highlight rain% value if > or = specified value.
					if (($hour > 0) && ($DISPLAY_ORDER[$aspect] == w1_RAIN) && ($DATA[w1_RAIN][$hour] >= $RAIN_THRESHOLD))
						 { $rain = " rain"; }
					else { $rain = ""; }
					
					#Adjust css for wind
					if ( ($hour > 0) && ($DISPLAY_ORDER[$aspect] == w1_WIND_DIR)) { $wind= " wind";} else { $wind = ""; }
					
					echo "<td class='$hdr$rain$wind'>".hsc($DATA[$DISPLAY_ORDER[$aspect]][$hour])."</td>\n";
				}
			echo "</tr>\n";
			
		}//end for($hour)
	echo "</table>\n";	
}//end Display_Weather_V() //**************************************************/





function Display_Weather_H($location) { //*************************************/
	//Display data in new Horizontal table, each column one hour.
	global  $DATA, $LOCATION_NAMES, $TESTING_MSG, $RAIN_THRESHOLD, $DISPLAY_ORDER, $HOURS_to_SHOW, $SELECTED_ASPECTS;
		
	echo "<table class=data>\n";
		
		$colspan = $HOURS_to_SHOW + 1;
		echo "<tr><td colspan=$colspan>\n";
		echo "<h2 class='location_name'>".$LOCATION_NAMES[$location]." &nbsp; ".$TESTING_MSG."</h2>\n";
		echo "</td></tr>\n";
		
		for ($tr = 0; $tr < WASPECTS; $tr++) {
			
			#Only show (Date, Time), & selected aspects
			if (!(($tr < 2) || in_array($DISPLAY_ORDER[$tr], $SELECTED_ASPECTS))) {continue;}
			
			echo "<tr>\n";
			for ($hour = 0; $hour <= $HOURS_to_SHOW; $hour++) {
				
				#Add day of week after new date.
				$day = "";
				if (($tr == 0) && ($hour > 1) && ($DATA[w1_DATE][$hour - 1] != "") && ($DATA[w1_DATE][$hour] == "")) {
					$day = date('D', strtotime(date("Y")."/".$DATA[w1_DATE][$hour - 1]));
				}
				
				#Highlight header/date & time rows. ***********************/
				if (($tr == 0) || ($tr == 1)) {$hdr = "hdr";} else {$hdr = "";}
				
				#Highlight rain% value if over specified value.
				$rain = "";
				if (($DISPLAY_ORDER[$tr] == w1_RAIN) && ($DATA[w1_RAIN][$hour] > $RAIN_THRESHOLD)) { $rain = "rain"; }
				
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
echo "<title>Weather</title>\n";
}//end Header() {//************************************************************/





function User_Options() {//****************************************************/
	global	$LOCATION_NAMES, $RAIN_THRESHOLD, $HOURS_to_SHOW, $SHOW_LOCATIONS, $TEST_MODE,
			$SELECTED_ASPECTS, $SHOW_RADAR, $DATA, $DISPLAY_ORDER, $WRAP_MAP;


	echo "\n<form method=get action='' id=options_form>\n";



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
		echo "<label class=location_option><input type=checkbox name=SHOW_LOCATIONS[$LOCATION] value=$LOCATION tabindex=1$checked>";
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
		echo "<label class=location_option><input type=checkbox name=ASPECTS[$DISPLAY_ORDER[$aspect]] value=$DISPLAY_ORDER[$aspect] tabindex=2$checked>";
		echo $DATA[$DISPLAY_ORDER[$aspect]][0]."</label>\n";
	}
	
	#Show Radar option
	$checked_true = $checked_false = "";
	if ($SHOW_RADAR) { $checked_true = " checked"; } else { $checked_false = " checked"; }
	?><span id=show_radar class=location_option>Radar image:
	<label class=show_radar_option><input type=radio name=SHOW_RADAR value=true  tabindex=2<?php  echo $checked_true ?>>Yes</label>
	<label class=show_radar_option><input type=radio name=SHOW_RADAR value=false tabindex=2<?php echo $checked_false?>>No</label>
	</span>
	<?php
	//######echo "<label id=show_radar class=location_option><input type=checkbox name=SHOW_RADAR value=true tabindex=2$checked>";
	//#####echo "Radar Image Map</label>\n";

	/*#TEST MODE option  *****/
	$checked = "";
	if ($TEST_MODE) {$checked = " checked";}
	echo "\n<label id=test_mode><input type=checkbox name=TEST_MODE value=true tabindex=2$checked>Test Mode</label>\n";
	/*************************/

	#SUBMIT button
	#echo "<button class=options>Submit</button>\n";



	#Third row: display options ****************************
	echo "\n<p class=options_group>\n";

	#Hours to display (12, 24, 36, 48)
	echo "<span class=options>Display &nbsp;<select name=HOURS_to_SHOW tabindex=3>\n";
	for ($option = 12; $option <= 48; $option += 12) {
		if ($HOURS_to_SHOW == $option){ $selected = " selected"; } else {$selected = "";}
		echo "<option value=$option$selected>".$option."</option>\n";
	}//end for($options)
	echo"</select> hours</span>\n";

	#Select data display mode: Vertical or Horizontal
	$selected = "";
	if ($_GET["V_or_H"] == "H") { $selected = " selected"; }
	echo "\n<select name=V_or_H id=V_of_H class=options tabindex=3>\n";
		echo "<option value=V>Vertically</option>\n";  //default selection
		echo "<option value=H$selected>Horizontally</option>\n";
	echo"</select>\n";

	#Rain Threshold: highlight rain values at this point
	echo "\n<span  class=options>Highlight rain at ";
	echo "<input type=text name=RAIN_THRESHOLD style='width:1.2em' maxlength=2 value=".$RAIN_THRESHOLD." tabindex=3>";
	echo "%</span>\n";

	#Wrap map at this many columns: (number of locations) x (number of weather columns)
	if ($_GET['V_or_H'] != "H") {
		echo "\n<span  class=options>Wrap map if over <select name=WRAP_MAP tabindex=3>\n";
		for ($option = WRAP_MIN; $option <= WRAP_MAX; $option ++) {
			if ($WRAP_MAP == ($option)) { $selected = " selected"; }
			else 						{ $selected = ""; }
			echo "<option value=$option$selected>".$option."</option>\n";
		}//end for($options)
		echo"</select> columns. <span class=fine_print>(Date, Hour, Temp, etc...)</span></span>\n";
	}//end if

	#Reload with default options
	echo "\n<button type=button id=default_ops class=button onclick='parent.location=location.pathname' tabindex=1000>Default Options</button>\n";

	#SUBMIT button
	echo "\n<button class=button id=submit autofocus tabindex=999>Submit</button>\n";

	echo "</form>\n\n";

}//end User_Options() //*******************************************************/




function Styles() {//**********************************************************/
	global $HOURS_to_SHOW;
?>
<style>

p { Xborder: 1px solid red; line-height: 1.35em; margin: .3em 0 .4em 0;   } /* //##### .5em .5em .5em 0; */

*			{ font-family: arial; }
h2 			{ font-size: 1.5em; margin: 0; }
table.data	{ border-collapse: collapse; display: inline-block; margin: 0 .5em .5em 0; vertical-align: top;}
td, th		{ font-size: 9pt; text-align:center; vertical-align: top;
			  border: 1px solid rgb(63,131,245); padding: 0 .3em 0 .3em; }
th			{ }
td 			{ min-width: 2.5em; max-width: 3em; white-space: normal; } /*Default for V display.*/
label		{ white-space:nowrap; }
.hdr		 { font-weight: bold; }

.time		 { }
.temp		 { }
.wind		 { text-align: left; padding: 0 0 0 .5em; }
.wind_dir	 { padding: 0 0 0 .5em; text-align: left; }
.rain		 { color: blue; font-weight: bold }
.clouds		 { }
.humidity	 { }

#w_container { white-space: nowrap; }
#submit		 { float: right; width: 9em; margin-right: 2em; }
#options_form{ display: inline-block; border: 0px solid gray; padding: 0 0 0 0; }
#default_ops { float: right; }
#show_radar	 { margin-left: 2em; }
.show_radar_option	 { border: 1px solid transparent; }
#test_mode	 { border: 1px solid transparent; margin-right: 1em; float: right; }
#timestamp	 { margin: .2em 0 .2em 0; padding: 0 .3em 0 .2em; display: inline-block; border: 1px solid teal; }
#V_or_H		 { padding: 2em 0 0 0; }


.fine_print  { font-size: 9pt; color: #555; }
.options	 { margin: 0 1.5em 0 0; }
.options_group {border: 1px solid rgb(63,131,245); padding-left: .4em; }
.location_option { border: 1px solid transparent; padding-right: .4em; margin-right: .5em;} /*padding: .1em .4em .1em .1em; rgb(63,131,245)*/
.location_name	 { text-align: left; margin-left: 4em; }
.TESTING_MSG 	 { color: red; }

label:hover { background-color: #eee; border: 1px solid rgb(63,131,245); }
</style>
<?php

#Adjust <td> widths for Horizontal display.
if ($_GET["V_or_H"] == "H") { echo "<style>td { min-width: 2.8em; max-width: 2.8em; }</style>\n"; }

#Adjust left margin for location name if hours < 5, so it's not out of box.
#if ($HOURS_to_SHOW < 5) { echo "<style>.location_name {margin-left: .2em;}</style>\n";}

}//end Styles() ***************************************************************/




function Javascripts() {//*****************************************************/
?>
<script>
function Time_Stamp(){ //**********************************************/
	//returns Day, yyyy-mm-dd, hh:mm:ss am/pm

	//older, simple timestamp:  m/d/yyy h:m:ssAM/PM
	//now = new Date(); return now.toLocaleString(); 

	//var DAYS = ["Sunday", "Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
	var DAYS = ["Sun", "Mon","Tues","Wed","Thur","Fri","Sat"];

	//pad single digits. ie: 1 becomes 01
	function pad(num){ if ( num < 10 ){ num = "0" + num; }; return num; }

	var TIMESTAMP  = new Date();
	var YEAR  = TIMESTAMP.getFullYear();
	var	MONTH = pad(TIMESTAMP.getMonth() + 1);
	var DATE  = pad(TIMESTAMP.getDate());
	var DAY   = DAYS[TIMESTAMP.getDay()];
	var HOURS = TIMESTAMP.getHours();
	var MINS  = pad(TIMESTAMP.getMinutes());
	var SECS  = pad(TIMESTAMP.getSeconds());

	if (HOURS < 12) {AMPM = "am";} else {AMPM = "pm";}
	if (HOURS > 12) {HOURS = HOURS - 12;}
	
	HOURS = pad(HOURS);

	var FULLDATE = YEAR + "-" + MONTH + "-" + DATE;
	var FULLTIME = HOURS + ":" + MINS + ":" + SECS + " " + AMPM;

	return DAY + ", " + FULLDATE + ", " + FULLTIME;
}//end Time_Stamp() //*************************************************/
</script>

<?php
}//end Javascripts() //********************************************************/




function Show_Radar($RB) { //**************************************************/
	#$RB can = "right" or "below"
	global $RADAR_IMG, $SELECTED_ASPECTS, $SHOW_LOCATIONS, $WRAP_MAP, $SHOW_RADAR;
	
	if (!$SHOW_RADAR) { return; }

	#$V_COLS approximates width of total weather displayed
	$V_COLS = (count($SELECTED_ASPECTS) + 2) * count($SHOW_LOCATIONS);

	if (($RB === "right") &&  ($_GET['V_or_H'] != 'H') && ($V_COLS <= $WRAP_MAP))  {echo $RADAR_IMG;}
	if (($RB === "below") && (($_GET['V_or_H'] == 'H') || ($V_COLS >  $WRAP_MAP))) {echo $RADAR_IMG;}

}//end Show_Radar() //*********************************************************/




# "Main" //********************************************************************/
#
Header_crap();
Get_GET(); //needed before Styles() & User_Options();
Javascripts();
Styles();
User_Options();


#Time Stamp
echo "<div id=timestamp><script>document.write(Time_Stamp());</script></div>";


#Data Source
echo " <span class=fine_print>(Weather data source: ";
	if ($TEST_MODE) {echo "<span class=TESTING_MSG>".getcwd().DIRECTORY_SEPARATOR.dirname(RAW_HTML_SAMPLE)."</span>";}
	else			{echo "www.weather.gov";}
echo ")</span>\n";



#Get & display weather for selected locations
echo "\n<div id=w_container>\n";
	for ($SELECTED = 0; $SELECTED < COUNT($SHOW_LOCATIONS); $SELECTED++) {
		
		Get_Weather_Data($SHOW_LOCATIONS[$SELECTED]);
		
		Extract_Weather_Data();
		
		if ($_GET["V_or_H"] == "H") { Display_Weather_H($SHOW_LOCATIONS[$SELECTED]); }
		else 						{ Display_Weather_V($SHOW_LOCATIONS[$SELECTED]); }
	}//end for($SELECTED)

	Show_Radar("right");
	
echo "</div>\n"; //end w_container

	Show_Radar("below");
#
# end "Main" //****************************************************************/





################################################################################
if ($TEST_MODE) {
	echo "<style> body {background-color: #eee}</style>\n";
	echo '<hr><pre style="clear: both;">$SHOW_LOCATIONS: '.hsc(print_r($SHOW_LOCATIONS, true))."</pre>";
	echo '<hr><pre style="clear: both;">$DEFAULT_ASPECTS: '.hsc(print_r($DEFAULT_ASPECTS, true))."</pre>";
	echo '<hr><pre style="clear: both;">$SELECTED_ASPECTS: '		  .hsc(print_r($SELECTED_ASPECTS, true))."</pre>";
	echo '<hr><pre style="clear: both;">$_GET: '		  .hsc(print_r($_GET, true))."</pre>";
	echo '<hr><pre style="clear: both;">$DATA: '		  .hsc(print_r($DATA, true))."</pre>";
	#echo '<hr><pre style="clear: both; font-family: courier">$RAW_HTML: <br>'.hsc($RAW_HTML)."</pre>";  //#####
}
echo "<div style='clear: both; border: 2px outset gray; height: .5em; '>&nbsp;</div>";
################################################################################