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
$BASE_URL_OPTIONS = "http://forecast.weather.gov/MapClick.php?w0=t&w1=td&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w12=fog&w13u=0&w15u=1&w16u=1&AheadHour=0&Submit=Submit&FcstType=digital&site=all&unit=0&dd=&bw=";

$x = 0;
$DATA_URLS[$x] = $BASE_URL_OPTIONS."&textField1=33.4148&textField2=-111.9093"	; $LOCATION_NAMES[$x++] = "Tempe";
$DATA_URLS[$x] = $BASE_URL_OPTIONS."&textField1=33.4150&textField2=-111.5496"	; $LOCATION_NAMES[$x++] = "Apache Junction";








define(LOCATIONS, count($DATA_URLS));
define(DEFAULT_LOCATION, 1);




define(RAW_HTML_SAMPLE, "samples\weather.gov.html");


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


#Desired rows of data from source html table
$DESIRED	   = array(
	w1_DATE, w1_HOUR, w1_TEMP, w1_WIND, w1_WIND_DIR, w1_CLOUDS, w1_RAIN, w1_HUMIDITY, w1_FOG, 
	w2_DATE, w2_HOUR, w2_TEMP, w2_WIND, w2_WIND_DIR, w2_CLOUDS, w2_RAIN, w2_HUMIDITY, w2_FOG, 
);//


#Order to subsequently re-display data (not neccessarily same order as in source)
$DISPLAY_ORDER = array(w1_DATE, w1_HOUR, w1_TEMP, w1_WIND, w1_WIND_DIR, w1_RAIN, w1_CLOUDS, w1_HUMIDITY, w1_FOG);







#Number of weather rows displayed (TIME, FORCAST, TEMP, etc...)
define("WASPECTS" , count($DISPLAY_ORDER));


#For extracted Data (currently ignoring DEWPOINT, GUST, & RAIN_amt rows)
#Both 24 hour data sets rows will be concatenated to a 48 hour data set.
$DATA				= array();
$DATA[w1_DATE]		= array();	$DATA[w1_DATE][0]	  = "Date";
$DATA[w1_HOUR]		= array();	$DATA[w1_HOUR][0]	  = "Hour";
$DATA[w1_TEMP]		= array();	$DATA[w1_TEMP][0]	  = "Temp °";
$DATA[w1_WIND]		= array();	$DATA[w1_WIND][0]	  = "Wind mph";
$DATA[w1_WIND_DIR]	= array();	$DATA[w1_WIND_DIR][0] = "Wind dir";
$DATA[w1_RAIN]		= array();	$DATA[w1_RAIN][0]	  = "Rain %";
$DATA[w1_CLOUDS]	= array();	$DATA[w1_CLOUDS][0]	  = "Clouds %";
$DATA[w1_HUMIDITY]	= array();	$DATA[w1_HUMIDITY][0] = "Humid %";
$DATA[w1_FOG]		= array();	$DATA[w1_FOG][0]	  = "Fog";




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





function Get_Weather_Data($location){ //***************************************/
	#get raw html page with weather data
	global $RAW_HTML, $DATA_URLS, $TESTING_MSG;


	
	if (isset($_GET["TEST_MODE"])){
		$TESTING_MSG = "<span class=TESTING_MSG>SAMPLE DATA</span>\n";
		$RAW_HTML = file_get_contents(RAW_HTML_SAMPLE);

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

	global $RAW_HTML, $DATA, $DESIRED;

	$DOM = new DOMDocument;		#$DOM -> preserveWhiteSpace = false;
	$DOM -> loadHTML($RAW_HTML);
	$WEATHER_TABLE = $DOM   -> getElementsByTagName('table') -> item(WEATHER_TABLE);

	$ROWS  = $WEATHER_TABLE	-> getElementsByTagName('tr');

	//Extract desired data from table and save in $DATA array().
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
	//Display data in new table, each row one hour.
	global  $DATA, $LOCATION_NAMES, $TESTING_MSG, $RAIN_THRESHOLD, $HOURS_to_SHOW;

	echo "<table class=data>\n";
		echo "<tr>\n<td colspan=".WASPECTS.">";
		echo "<h2>".$LOCATION_NAMES[$location].$TESTING_MSG."</h2>";
		echo "</td>\n</tr>\n";
		
		for ($hour = 0; $hour <= $HOURS_to_SHOW; $hour++) { 
			
			$hdr = "";
			
			#Highlight Header Row (labels)
			if ($hour == 0) { $hdr = "hdr"; }
				
			#Highlight rain% value if over specified value.
			if ($DATA[w1_RAIN][$hour] > $RAIN_THRESHOLD) { $rain = "rain"; } else { $rain = ""; }
			






			#Display row of data.
			echo "<tr>\n";
				echo "<td class='hdr'>"				.hsc($DATA[w1_DATE    ][$hour])."</td>\n";
				echo "<td class='hdr'>" 			.hsc($DATA[w1_HOUR    ][$hour])."</td>\n";
				echo "<td class='$hdr'>"			.hsc($DATA[w1_TEMP    ][$hour])."</td>\n";
				echo "<td class='$hdr'>"			.hsc($DATA[w1_WIND    ][$hour])."</td>\n";
				echo "<td class='$hdr wind_dir'>"	.hsc($DATA[w1_WIND_DIR][$hour])."</td>\n";
				echo "<td class='$hdr ".$rain."'>"	.hsc($DATA[w1_RAIN    ][$hour])."</td>\n";
				echo "<td class='$hdr'>"			.hsc($DATA[w1_CLOUDS  ][$hour])."</td>\n";
				echo "<td class='$hdr'>"			.hsc($DATA[w1_HUMIDITY][$hour])."</td>\n";
				echo "<td class='$hdr'>"			.hsc($DATA[w1_FOG     ][$hour])."</td>\n";
			echo "</tr>\n";
		}//end for($hour)
	echo "</table>\n";	
}//end Display_Weather_V() //**************************************************/





function Display_Weather_H($location) { //*************************************/
	//Display data in new table, each column one hour.
	global  $DATA, $LOCATION_NAMES, $TESTING_MSG, $RAIN_THRESHOLD, $DISPLAY_ORDER, $HOURS_to_SHOW;
		
	echo "<table class=data>\n";
		
		$colspan = $HOURS_to_SHOW + 1;
		echo "<tr><td colspan=$colspan>\n";
		echo "<h2 class='location_name'>".$LOCATION_NAMES[$location].$TESTING_MSG."</h2>\n";
		echo "</td></tr>\n";
		
		for ($tr = 0; $tr < WASPECTS; $tr++) {




			echo "<tr>\n";
			for ($hour = 0; $hour <= $HOURS_to_SHOW; $hour++) {
				
				$hdr = "";
				




				#Highlight header/date & time rows. ***********************/
				if (($tr == 0) || ($tr == 1)) {$hdr = "hdr";}
				
				#Highlight rain% value if over specified value.
				$rain = "";
				if (($DISPLAY_ORDER[$tr] == w1_RAIN) && ($DATA[w1_RAIN][$hour] > $RAIN_THRESHOLD)) { $rain = "rain"; }
				
				#Finally, ouput the weather info. hour 0 is the header/label.
				if ($hour == 0) 
					{ echo "<th>".($DATA[$DISPLAY_ORDER[$tr]][$hour])."</th>\n"; }
				else
					{ echo "<td class='".$hdr." ".$rain."'>".hsc($DATA[$DISPLAY_ORDER[$tr]][$hour])."</td>\n"; }
			}//end for($hour)
			echo "</tr>\n";
		}//end for($tr)
		
	echo "</table><br>\n";
}//end Display_Weather_H() //**************************************************/





function Header_crap() {//*****************************************************/
header('Content-type: text/html; charset=UTF-8');
echo "<!DOCTYPE html>\n";
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'."\n";
echo "<title>Weather</title>";
}//end Header() {//************************************************************/





function Styles() {//**********************************************************/
	global $HOURS_to_SHOW;
?>
<style>
*			 { font-family: arial; }
h2 			 { font-size: 1.5em; margin: 0; }
table.data	 { border-collapse: collapse; display: inline-block; margin: 0 .5em .5em 0;}
td, th		 { font-size: 9pt; text-align:center; vertical-align: top;
				border: 1px solid rgb(63,131,245); padding: 0 .3em 0 .3em; }
th			 { }
td 			 { min-width: 2.5em; max-width: 3em; white-space: normal; } /*Default for V display.*/
.hdr		 { font-weight: bold; }
.time		 { }
.temp		 { }
.forecast	 { }
.wind		 { }
.wind_dir	 { padding: 0 0 0 .5em; text-align: left; }
.rain		 { color: blue; font-weight: bold }
.clouds		 { }
.humidity	 { }
#container	 { white-space: nowrap; }
#test_mode	 { border: 1px solid transparent; margin-right: 2em; }
#timestamp	 { margin: .2em 0 .2em 0; padding: 0 .3em 0 .2em; display: inline-block; border: 1px solid teal; }
#data_source { font-size: 9pt; color: #555; }
.options	 { margin: 0 2em 0 0; }
.location_option { border: 1px solid rgb(63,131,245); padding: .2em .4em .2em .2em; margin-right: .5em;}
.location_name	 { text-align: left; margin-left: 4em; }

.TESTING_MSG 	 { color: red; padding-left: 1em; }

label:hover { background-color: #eee; border: 1px solid rgb(63,131,245); }
</style>

<?php

#Adjust <td> widths for Horizontal display.
if ($_GET["V_or_H"] == "H") { echo "<style>td { min-width: 2.8em; max-width: 2.8em; }</style>\n"; }

#Adjust margin L for location name if hours < 5, otherwise it's out of box.
if ($HOURS_to_SHOW < 5) { echo "<style>.location_name {margin-left: .2em;}</style>\n";}


}//end Styles() ***************************************************************/






function User_Options() {//****************************************************/
	global $LOCATION_NAMES, $RAIN_THRESHOLD, $HOURS_to_SHOW, $SHOW_LOCATIONS;
	
echo "<form method=get action=''>Show weather for: \n";



#First row: List location options ****
$checked_location = 0;
for ($LOCATION = 0; $LOCATION < LOCATIONS; $LOCATION++) {
	$checked = "";
	if ($SHOW_LOCATIONS[$checked_location] == $LOCATION){
		$checked = "checked";
		$checked_location++;
	}
	//echo "<span class=location_option>";
	echo "<label class=location_option><input type=checkbox name=SHOW_LOCATIONS[] value=$LOCATION $checked>";
	echo $LOCATION_NAMES[$LOCATION]."</label>\n";
	//echo "</span>\n";
}


#Second row of options ****************
echo "<p style='margin: .5em .5em .5em 0;'>";


#SUBMIT button
echo "<button class=options autofocus>Submit</button>\n";



#Select data display mode: Vertical or Horizontal
$selected = "";
if ($_GET["V_or_H"] == "H") { $selected = " selected"; }
echo "Display <select name=V_or_H class=options>\n";
	echo "<option value=V>Vertical</option>\n";  //default selection
	echo "<option value=H$selected>Horizontal</option>\n";
echo"</select>\n";



#Options of hours to get & display (12, 24, 36, 48)
echo "<span  class=options>Display <select name=HOURS_to_SHOW>\n";
for ($option = 12; $option <= 48; $option += 12) {
	if ($HOURS_to_SHOW == $option){ $selected = " selected"; } else {$selected = "";}
	echo "<option value=$option$selected>".$option."</option>\n";
}//end for($options)
echo"</select> hours</span>\n";



#Rain Threshold
if (!isset($_GET["RAIN_THRESHOLD"])) {$_GET["RAIN_THRESHOLD"] = 25;}
else  {$_GET["RAIN_THRESHOLD"] = trim($_GET["RAIN_THRESHOLD"]);}
if (!is_numeric($_GET["RAIN_THRESHOLD"])) {$_GET["RAIN_THRESHOLD"] = 25;}
if (($_GET["RAIN_THRESHOLD"]) < 1 || ($_GET["RAIN_THRESHOLD"]) > 99) {$_GET["RAIN_THRESHOLD"] = 25;}
echo "<span  class=options>Highlight rain at <input type=text name=RAIN_THRESHOLD style='width:1.2em' maxlength=2 value=".$_GET["RAIN_THRESHOLD"].">";
echo "%</span>\n";



#TEST MODE option
$checked = "";
if (isset($_GET["TEST_MODE"])) {$checked = " checked";}
echo "<label id=test_mode><input type=checkbox name=TEST_MODE value=true $checked>Test Mode</label>\n";



#SUBMIT button
echo "<button class=button>Submit</button>\n";


echo "</form>\n\n";

#Hightlight rain values when over this amount (%).
$RAIN_THRESHOLD = $_GET["RAIN_THRESHOLD"] - 1; //

}//end User_Options() //*******************************************************/




function Javascripts() {//*****************************************************/
?>
<script>
function Time_Stamp(){ //**********************************************/
	//returns Day, yyyy-mm-dd hh:mm:ss am/pm

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

	return DAY + ", " + FULLDATE + " &nbsp;" + FULLTIME;
}//end Time_Stamp() //*************************************************/
</script>
<?php
}//end Javascripts() //********************************************************/




function Get_GET() { //********************************************************/
	global $HOURS_to_SHOW, $SHOW_LOCATIONS, $LOCATION_NAMES;

	#Validate $_GET["HOURS_to_SHOW"]

	#Needed before Styles() & User_Options()
	if (!isset($_GET["HOURS_to_SHOW"])) {
		//only show 24 hours by default
		$HOURS_to_SHOW = 24;
	} else {
		$HOURS_to_SHOW  = intval($_GET['HOURS_to_SHOW']);
		if 	   ($HOURS_to_SHOW < 1)  { $HOURS_to_SHOW =  1; }
		else if($HOURS_to_SHOW > 48) { $HOURS_to_SHOW = 48; }
	}


	#Validate $_GET[SHOW_LOCATIONS]

	if (!isset($_GET["SHOW_LOCATIONS"])) { $SHOW_LOCATIONS[0] = DEFAULT_LOCATION; }
	else 								 { $SHOW_LOCATIONS 	  = $_GET["SHOW_LOCATIONS"]; }

	#make 'em all int's
	foreach($SHOW_LOCATIONS as $key => $location) { $SHOW_LOCATIONS[$key] = $SHOW_LOCATIONS[$key] * 1; }

	#make sure in valid range
	foreach($SHOW_LOCATIONS as $key => $location) {
		if (($location < 0) || ($location > (LOCATIONS - 1)) ) { unset($SHOW_LOCATIONS[$key]); }
	}

	sort($SHOW_LOCATIONS);
	$SHOW_LOCATIONS = array_values($SHOW_LOCATIONS);



}//end Get_GET() //************************************************************/



	
# "Main" //********************************************************************/
#
Header_crap();
Javascripts();
Get_GET(); //needed before Styles() & User_Options();
Styles();
User_Options();


#Time Stamp
echo "<div id=timestamp><script>document.write(Time_Stamp() );</script></div>";


#Data Source
echo " <span id=data_source>(Weather data source: ";
	if (isset($_GET["TEST_MODE"]))  {echo "<span class=TESTING_MSG>".getcwd()."\\".RAW_HTML_SAMPLE."</span>";}
	else							{echo "www.weather.gov";}
echo ")</span>\n";



#Get & display weather for selected locations
echo "<div id=container>\n";
for ($SELECTED = 0; $SELECTED < COUNT($SHOW_LOCATIONS); $SELECTED++) {

	Get_Weather_Data($SHOW_LOCATIONS[$SELECTED]);

	Extract_Weather_Data();

	if ($_GET["V_or_H"] == "H") { Display_Weather_H($SHOW_LOCATIONS[$SELECTED]); }
	else 						{ Display_Weather_V($SHOW_LOCATIONS[$SELECTED]); }
}//end for $SELECTED
echo "</div>";


#echo '<hr><pre style="clear: both; font-family: courier">$RAW_HTML: <br>'.hsc($RAW_HTML)."</pre>";  //#####
if (isset($_GET["TEST_MODE"])) {
	echo '<hr><pre style="clear: both;">$SHOW_LOCATIONS: '.hsc(print_r($SHOW_LOCATIONS, true))."</pre>";
	echo '<hr><pre style="clear: both;">$_GET: '.hsc(print_r($_GET, true))."</pre>";
	echo '<hr><pre style="clear: both;">$DATA: '.hsc(print_r($DATA, true))."</pre>";
}

echo "<div style='clear: both; border: 2px outset gray; height: .5em; '>&nbsp;</div>";
################################################################################