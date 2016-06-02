<?php
/*******************************************************************************
#Common URL  (w/Tempe lat & lon)

&textField1=33.4148&textField2=-111.9093


http://forecast.weather.gov/MapClick.php?lat=33.4148&lon=-111.9093&unit=0&lg=english&FcstType=digital
http://forecast.weather.gov/MapClick.php?w0=t&w1=td&w2=wc&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w8=thunder&w9=snow&w10=fzg&w11=sleet        &w13u=0&w15u=1&w16u=1&AheadHour=0&Submit=Submit&FcstType=digital&textField1=33.4148&textField2=-111.9093&site=all&unit=0&dd=&bw=
http://forecast.weather.gov/MapClick.php?w0=t&w1=td&w2=wc&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w8=thunder&w9=snow&w10=fzg&w11=sleet&w12=fog&w13u=0&w15u=1&w16u=1&AheadHour=0&Submit=Submit&FcstType=digital&textField1=33.4148&textField2=-111.9093&site=all&unit=0&dd=&bw=
http://forecast.weather.gov/MapClick.php?w0=t&w1=td&w2=wc&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w8=thunder&w9=snow&w10=fzg&w11=sleet        &w13u=0&w15u=1&w16u=1&AheadHour=48             &FcstType=digital&textField1=33.4148&textField2=-111.9093&site=all&unit=0&dd=&bw=&AheadDay.x=76&AheadDay.y=8

#URL Breakdown:
http://forecast.weather.gov/MapClick.php
?w0=t					Temperature
&w1=td					(Temp) Dep Point
&w2=wc					Wind Chill
&w3=sfcwind				Surface Wind Speed
&w3u=1					?
&w4=sky					Sky Coverage %
&w5=pop					Precipitation Potential %
&w6=rh					Relative Humidity %
&w7=rain				Rain
&w8=thunder				Thunder
&w9=snow				Snow
&w10=fzg				Freezing Rain
&w11=sleet				Sleet
&w12=fog				Fog
&w13u=0					?
&w15u=1					?
&w16u=1					?
&AheadHour=0			Offset from current hour to first hour.
&Submit=Submit
&FcstType=digital
&textField1=33.4148		Latitude
&textField2=-111.9093	Longitude
&site=all
&unit=0
&dd=
&bw=



/******************************************************************************.
# The following describes the weather table returned from options in the URL above. 
# The table consists of 36 rows (<tr>'s) and 25 columns (<td>'s)
# Column 0: labels. Columns 1 thru 24: each column contains one hour of data,
# starting with the current hour, unless a later hour (&AheadHour) is selected.
Rows:
 0: <td>colspan=25><hr></td>
 1: Date
 2: Hour
 3: Temp
 4: Dewpoint
 5: Wind chill
 6: Surface Wind Speed
 7: Wind direction
 8: Gust
 9: Sky/Cloud cover %
10: Precipitation %
11: Humidity %
12: Rain chance
13: Thunder
14: Snow
15: Freezing Rain
16: Sleet
17: Fog
18: <td>colspan=25><hr></td>
 |: ... rows 18-35 rows repeat rows 0-17...
35: ...

/******************************************************************************/





function Init() { //***********************************************************/
	global $BASE_URL, $BASE_OPTIONS, $BASE_URL_OPTIONS, $DATA_URLS, $LOCATION_NAMES, 
		   $DESIRED, $DISPLAY_ORDER, $DATA, $DEFAULT_ASPECTS, 
		   $SAMPLE_SET, $RAW_HTML_SAMPLES, $RADAR_URL_BASE_SAMPLE; 

	#Make sure time zone is correct.
	date_default_timezone_set("America/New_York");



	#Weather url's *****************************************************************
	$BASE_URL = "http://forecast.weather.gov/MapClick.php?";
	$BASE_OPTIONS = "&w0=t&w1=td&w2=wc&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w8=thunder&w9=snow&w10=fzg&w11=sleet&w12=fog&w13u=0&w15u=1&w16u=1&FcstType=digital&site=all&unit=0&dd=&bw=";
	$BASE_URL_OPTIONS = $BASE_URL.$BASE_OPTIONS;


	#A few pre-defined locations...
	$x = 0;
	#The first, zero, location is a placeholder for the "Search For" location option.
	$DATA_URLS[$x] = $BASE_URL_OPTIONS."&textField1=33.4148&textField2=-111.9093"	; $LOCATION_NAMES[$x++] = "Tempe, AZ";
	
	$DATA_URLS[$x] = $BASE_URL_OPTIONS."&textField1=33.4148&textField2=-111.9093"	; $LOCATION_NAMES[$x++] = "Tempe";
	$DATA_URLS[$x] = $BASE_URL_OPTIONS."&textField1=33.4150&textField2=-111.5496"	; $LOCATION_NAMES[$x++] = "Apache Junction";







	define('LOCATIONS', $x);
	define('DEFAULT_LOCATION', 2);

	#Used to find lat & lon of user provided location
	define('BASE_SEARCH_URL', "http://forecast.weather.gov/zipcity.php?inputstring=");



	#Weather data is the 8th <table> in the source html (as of 2015-03-29).
	#(8th counting from 1, but computer programmers like to count from 0...)
	define('WEATHER_TABLE', 7);


	# Rows (<tr>) of data in source html table.
	# The following rows are determined by options in the URL. (all weather options selected)
	# weather.gov displays 48 hours at once, starting with current hour,
	# in two 24 hour sets.
	# The d1_ or d2_ prefix denotes which set of rows of 24 hour data. 
	# The "d" prefix just means "day". I don't know, you got a better idea?

	define('d1_DATE',       1);
	define('d1_HOUR',       2);
	define('d1_TEMP',       3);
	define('d1_DEWPOINT',   4);
	define('d1_WIND_CHILL', 5);
	define('d1_WIND',       6);
	define('d1_WIND_DIR',   7);
	define('d1_GUST',       8);
	define('d1_CLOUDS',     9);
	define('d1_RAIN',      10);
	define('d1_HUMIDITY',  11);
	define('d1_RAIN_CHNC', 12);
	define('d1_THUNDER',   13);
	define('d1_SNOW',      14);
	define('d1_FRZ_RAIN',  15);
	define('d1_SLEET',     16);
	define('d1_FOG',       17);

	define('ASPECTS_TOTAL', 17); //Total number of weather aspects available.
	define('d2_OFFSET',     18); //offset to first row of next set of rows of weather.
								 //Currently there is one blank/divider row between the sets.
	define('FIRST_ROW', d2_OFFSET - ASPECTS_TOTAL);  //Currenlty 1, as of 2016-02-26

	define('d2_DATE',      19);
	define('d2_HOUR',      20);
	define('d2_TEMP',      21);
	define('d2_DEWPOINT',  22);
	define('d2_WIND_CHILL',23);
	define('d2_WIND',      24);
	define('d2_WIND_DIR',  25);
	define('d2_GUST',      26);
	define('d2_CLOUDS',    27);
	define('d2_RAIN',      28);
	define('d2_HUMIDITY',  29);
	define('d2_RAIN_CHNC', 30);
	define('d2_THUNDER',   31);
	define('d2_SNOW',      32);
	define('d2_FRZ_RAIN',  33);
	define('d2_SLEET',     34);
	define('d2_FOG',       35);



	#Desired rows of data to extract from source html table. Currently all rows.
	$DESIRED = array(
		d1_DATE, d1_HOUR, d1_TEMP, d1_DEWPOINT, d1_WIND_CHILL, d1_WIND, d1_WIND_DIR, d1_GUST, d1_CLOUDS, d1_RAIN, d1_HUMIDITY, d1_FOG, d1_RAIN_CHNC, d1_THUNDER, d1_FRZ_RAIN, d1_SLEET, d1_SNOW, 
		d2_DATE, d2_HOUR, d2_TEMP, d2_DEWPOINT, d2_WIND_CHILL, d2_WIND, d2_WIND_DIR, d2_GUST, d2_CLOUDS, d2_RAIN, d2_HUMIDITY, d2_FOG, d2_RAIN_CHNC, d2_THUNDER, d2_FRZ_RAIN, d2_SLEET, d2_SNOW, 
	);//


	#Order to subsequently re-display data (not neccessarily all of, or same order as, the source)
	#$DISPLAY_ORDER is also used in Get_GET() to validate values in $_GET["ASPECTS"] (selected aspects).
	$DISPLAY_ORDER = array(d1_DATE, d1_HOUR, d1_TEMP, d1_DEWPOINT , d1_WIND_CHILL, d1_WIND, d1_WIND_DIR, d1_GUST, d1_RAIN, d1_CLOUDS, d1_HUMIDITY, d1_FOG, d1_RAIN_CHNC, d1_THUNDER, d1_FRZ_RAIN, d1_SLEET, d1_SNOW);


	#Number of weather aspects displayed (TIME, FORCAST, TEMP, etc...)
	define('WASPECTS', count($DISPLAY_ORDER));



	#Default aspects to display
	#Leave aspect out of $DEFAULT_ASPECTS if you don't want it displayed *by default*.
	#The option box will be available, but unchecked.
	#$DEFAULT_ASPECTS = $DISPLAY_ORDER; #All aspects
	#$DEFAULT_ASPECTS = array(d1_DATE, d1_HOUR, d1_TEMP, d1_WIND, d1_WIND_DIR, d1_GUST, d1_RAIN, d1_CLOUDS, d1_HUMIDITY, d1_FOG, d1_RAIN_CHNC, d1_THUNDER, d1_FRZ_RAIN, d1_SLEET, d1_SNOW);
	$DEFAULT_ASPECTS = array(d1_DATE, d1_HOUR, d1_TEMP, d1_WIND, d1_WIND_DIR, d1_GUST, d1_RAIN, d1_CLOUDS, d1_HUMIDITY, d1_FOG);



	#For the extracted Data. $DATA[0][n] values are headers/labels, and should correlate to $DISPLAY_ORDER above.
	#The two set of rows (from original source html), of 24 hour data columns, will be concatenated to a single 48 hour data set.
	$x = 0;
	$DATA    = array();
	$DATA[0] = array();

	$DATA[0][d1_DATE]		= "Date";
	$DATA[0][d1_HOUR]		= "Hour";
	$DATA[0][d1_TEMP]		= "Temp °f";
	$DATA[0][d1_DEWPOINT]	= "Dew Point";
	$DATA[0][d1_WIND_CHILL]	= 'Wind Chill';
	$DATA[0][d1_WIND]		= "Wind mph";
	$DATA[0][d1_WIND_DIR]	= "Wind dir";
	$DATA[0][d1_GUST]		= "Gusts mph";
	$DATA[0][d1_CLOUDS]		= "Clouds %";
	$DATA[0][d1_RAIN]		= "Prcip %";
	$DATA[0][d1_HUMIDITY]	= "Humid %";
	$DATA[0][d1_RAIN_CHNC]	= "Rain";			#Rain, Thunder, Snow, Freezing Rain, Sleet, & Fog
	$DATA[0][d1_THUNDER]	= "Thndr";			#values are "Lkly", "Schc", etc...
	$DATA[0][d1_SNOW]		= "Snow";
	$DATA[0][d1_FRZ_RAIN]	= "Frzng Rain";
	$DATA[0][d1_SLEET]		= "Sleet";
	$DATA[0][d1_FOG]		= "Fog";

	ksort($DATA[0]); //key sort (maintains key=>value paring) (only matters on array dumps or if use foreach)
	#dump_array($DATA); die(); //##### 




	#Occasionally, a "Radar data are unavailable" image is served.
	#May add check for this at some point so it can be ignored.
	#Maybe. Possibly. Sometime. But no promises.
	#MD5 hash of the "...unavailable" image.
	#define('RADAR_UNAVAILABLE_MD5',  "b7578f7110b249e61a3635d5b1226d87");


	#1 for actual sample radar images, 2 for simple graphics.
	# If change either $..._SAMPLE below, change cooresponding value in Get_GET();
	$SAMPLE_SET = 1;
	$RAW_HTML_SAMPLES[1] = "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.1.html";
	$RAW_HTML_SAMPLES[2] = "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.2.html";
	$RAW_HTML_SAMPLES[3] = "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.3.html";
	$RAW_HTML_SAMPLES[4] = "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.4.html";
	$RADAR_URL_BASE_SAMPLE = "/Weather/weather.gov/samples/$SAMPLE_SET/SAMPLE_";

	//##### ##################################################################################################
	//##### i1 can = a, b, c, d, or e.
	//##### 
	$RAW_HTML_SAMPLES[1] = "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.1".$_GET['i1'].".html";
	//##### ##################################################################################################


	#Radar image URL's:  http://radar.weather.gov/lite/N0R/IWA_?.png    ? = 0 thru 7
	#Used in Radar_Loop_scripts()
	define('RADAR_SITE_DEF', "IWA_"); //Default radar site (Central AZ)
	define('RADAR_URL_BASE', "http://radar.weather.gov/lite/N0R/");
	define('RADAR_URL_BASE_DEF', RADAR_URL_BASE.RADAR_SITE_DEF);
	define('RADAR_IMG_EXT', ".png");


	#For the "Dispaly [x] hours" drop list option
	define('HOURS_MIN',  12);
	define('HOURS_MAX', 155);
	define('HOURS_DEF',  24);
	define('HOURS_INC',  12); //INCrement from _MIN to _MAX


	#Time between radar images (1000 = 1 second).
	define('FRAME_RATE_MIN',  100);
	define('FRAME_RATE_MAX', 1000);
	define('FRAME_RATE_DEF',  200);
	define('FRAME_RATE_INC',  100);


	#Time to pause between radar loops (1000 = 1 second).
	define('ROTATE_PAUSE_MIN',  500);
	define('ROTATE_PAUSE_MAX', 4000);
	define('ROTATE_PAUSE_DEF', 1000);
	define('ROTATE_PAUSE_INC',  500);


	#Number of times to loop, then stop.
	define('ROTATE_LOOPS_MIN',  1);
	define('ROTATE_LOOPS_MAX', 99);
	define('ROTATE_LOOPS_DEF', 10);
	define('ROTATE_LOOPS_INC',  1);
}//end Init() { //*************************************************************/




function hsc($input) {//*******************************************************/
	$enc = mb_detect_encoding($input); //It should always be UTF-8 (or ASCII), but, just in case...
	if ($enc == 'ASCII') {$enc = 'UTF-8';} //htmlspecialchars() doesn't recognize "ASCII"
	return htmlspecialchars($input, ENT_QUOTES, $enc);
}//end hsc() //****************************************************************/





//******************************************************************************
function dump_array($var, $name="", $ECHO = 1, $PRE=1, $BRDR=1, $DSPLY=1, $VD=0, $LVL=0) { //**
	//If $VD == 1 (or true), use var_dump on each non-array value.
	$dump   = "";
	$pad    = '';
	$indent = '   ';

	if (($LVL == 0) && $PRE) { //used only on outer most level
		if     ($DSPLY == 1) {$DSPLY='display: inline-block; ';} //default
		elseif ($DSPLY == 2) {$DSPLY='float: left;'; }
		elseif ($DSPLY == 3) {$DSPLY='float: right;';}
		else   {$DSPLY='';}
		$dump .= '<pre style="'.$DSPLY.' font-family: monospace; border: '.$BRDR.'px solid gray; margin:0; padding: 2px 4px 2px 2px">';
	}

	if (!is_array($var)) {
		if ( $name != "" ) {$dump .= $name." = ";}
		if ($VD) {ob_start(); var_dump($var); $dump .= str_replace("\n",'',ob_get_clean());}
		else     {$dump .= $var;}
	}
	else {
		for ($x=0; $x < $LVL; $x++) {$pad .= $indent;}
		
		if ( $name != "" ) {
			if ($VD) {$desc=" => Array(".count($var).")";} else {$desc = '';}
			$dump .= $pad.$name.$desc."\n";
		}
		
		foreach ($var as $key => $value) {
			if (is_array($value)) {
				$dump .= dump_array($value,'['.$key.']',0,0,0,0,$VD,$LVL+1);
			}
			else if ($VD) {
				$dump .= $pad.$indent.'['.hsc($key).'] => ';
				ob_start(); var_dump(hsc($value)); $dump .= ob_get_clean();
			}
			else {$dump .= $pad.$indent.'['.hsc($key).'] = '.hsc($value)."\n";}
		}
	}

	if (($LVL == 0) && $PRE ) {$dump .= "</pre>";}
	if ($ECHO) {echo $dump;} else {return $dump;}
}//dump_array() //**************************************************************





function curl_get_contents($url, $headers = false, $nobody = false) {//********/
	#Default parameters ($url, false, false) returns body only.

    $ch = curl_init();

	//May work faster with this when only need the headers.
	if ($headers && $nobody) { curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');	}

	#curl_setopt($ch, CURLOPT_VERBOSE, true); //for trouble-shooting only
    #curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    #curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, $headers);
	curl_setopt($ch, CURLOPT_NOBODY, $nobody);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
    curl_setopt($ch, CURLOPT_URL, $url);

    $html = curl_exec($ch);
    curl_close($ch);

    return $html;
} //end curl_get_contents() //*************************************************/





function Get_GET() {//*********************************************************/
	#Get & validate URL parameters
	global  $HOURS_TO_SHOW, $SHOW_LOCATIONS, $LOCATION_NAMES, $DISPLAY_ORDER, $RAIN_THRESHOLD, 
			$DISPLAY_H, $SELECTED_ASPECTS, $DISPLAY_ORDER, $SHOW_RADAR, $WRAP_MAP, $DONT_WRAP_MAP,
			$FRAME_RATE, $ROTATE_PAUSE, $ROTATE_LOOPS, $DEFAULT_ASPECTS, 
			$TEST_MODE, $SAMPLE_SET, $RAW_HTML_SAMPLES, $RADAR_URL_BASE_SAMPLE;

	$_GET = array_change_key_case($_GET, CASE_UPPER);


	#TEST_MODE alias' (accept as URL param even if <form> option is missing)
	#LEAVE CHECK FOR TEST MODE HERE! It's result is used below.
	if (isset($_GET["TEST"]) || isset($_GET["TEST_MODE"])) {$TEST_MODE = true; }
	else 												   {$TEST_MODE = false;}


	#Which sample set (1 or 2) of radar images to use
	#if (isset($_GET["SS"]))			{ $SAMPLE_SET = $_GET["SS"]; }
	#if (isset($_GET["SAMPLE_SET"])) { $SAMPLE_SET = $_GET["SAMPLE_SET"]; }
	#$RAW_HTML_SAMPLES = "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.p1.html";
	#$RADAR_URL_BASE_SAMPLE = "/Weather/weather.gov/samples/$SAMPLE_SET/SAMPLE_";


	#"SEARCH_FOR_LOCATION" ******************
	if (isset($_GET["SEARCH_FOR_LOCATION"])) { $LOCATION_NAMES[0] = $_GET["SEARCH_FOR_LOCATION"]; }


	#"SHOW_LOCATIONS" ***********************
	if (!isset($_GET["SHOW_LOCATIONS"])) { $SHOW_LOCATIONS[DEFAULT_LOCATION] = DEFAULT_LOCATION; }
	else 								 { $SHOW_LOCATIONS = $_GET["SHOW_LOCATIONS"]; }
	#make sure in valid range
	foreach($SHOW_LOCATIONS as $key => $location) {
		if     (!is_numeric($key) || !is_numeric($location))	   { unset($SHOW_LOCATIONS[$key]); }
		elseif (($location < 0) || ($location > (LOCATIONS - 1)) ) { unset($SHOW_LOCATIONS[$key]); }
		else													   { $SHOW_LOCATIONS[$key] *= 1;   } //make 'em integers
	}
	asort($SHOW_LOCATIONS); //Just in case they're not sent in order...  (asort - doesn't re-index the keys)


	#Weather "ASPEPCTS" *********************
	if (!isset($_GET["ASPECTS"])) { $SELECTED_ASPECTS = $DEFAULT_ASPECTS; }
	else 						  { $SELECTED_ASPECTS = $_GET["ASPECTS"]; }
	
	#make sure in valid range
	foreach($SELECTED_ASPECTS as $key => $aspect) {
		if     (!is_numeric($key) || !is_numeric($aspect))	{ unset($SELECTED_ASPECTS[$key]); }
		elseif (!in_array($aspect, $DISPLAY_ORDER) )		{ unset($SELECTED_ASPECTS[$key]); }
		else   /* make 'em int's ---------------------> */	{ $SELECTED_ASPECTS[$key] *= 1;   }
	}
	sort($SELECTED_ASPECTS);
	$SELECTED_ASPECTS = array_values($SELECTED_ASPECTS);


	#"HOURS_TO_SHOW" ************************
	#Needed before Styles() or User_Options() are called
	if (!isset($_GET["HOURS_TO_SHOW"])) {
		//default if not selected
		$HOURS_TO_SHOW = HOURS_DEF;
	} else {
		$HOURS_TO_SHOW  = intval(trim($_GET['HOURS_TO_SHOW']));
		if 	   ($HOURS_TO_SHOW < HOURS_MIN) { $HOURS_TO_SHOW = HOURS_MIN; }
		elseif ($HOURS_TO_SHOW > HOURS_MAX) { $HOURS_TO_SHOW = HOURS_MAX; }
	}


	#VH *************************************
	$DISPLAY_H = FALSE;
	if (isset($_GET["VH"]) && ($_GET["VH"] == "H")) { $DISPLAY_H = TRUE; }


	#SHOW_RADAR *****************************
	if     (empty($_GET))					 { $SHOW_RADAR = TRUE;  } #Default
	elseif ($TEST_MODE && count($_GET == 1)) { $SHOW_RADAR = TRUE;  } #Default also in TEST_MODE
	elseif (isset($_GET["SHOW_RADAR"]))		 { $SHOW_RADAR = TRUE;  }
	else									 { $SHOW_RADAR = FALSE; }
	

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

}//end Get_GET() //************************************************************/





function Search_for_custom_location() {//**************************************/
	global $BASE_URL_OPTIONS, $DATA_URLS, $LOCATION_NAMES, $TEST_MODE;


	#####################################################################
	/********************************************************************
	Searches for some locations, such as in Alaska, do not return URLs/Headers with
	lat & lon (or textField1 & 2) ?query values.
	However, like with the radar id (rid), a link on the returned page does.

	Also, Check if custom rid == IWA (central AZ) and don't display custom radar img if so.
	/********************************************************************/
	#####################################################################


	$Search_URL  = BASE_SEARCH_URL.rawurlencode($_GET["SEARCH_FOR_LOCATION"]);
	$HEADERS = trim(curl_get_contents($Search_URL, 1, 1))."\n"; //The 1, 1 parameters returns headers only
	$HEADERS = str_replace("\r\n", "\n", $HEADERS); //Normalize EOL
	$HEADERS = str_replace("\r"  , "\n", $HEADERS); //Normalize EOL

	#$found =  "Location: ..." line from the $HEADERS  (should only find 1)
	#Location: http://forecast.weather.gov/MapClick.php?CityName=Tempe&state=AZ&site=IWA&lat=33.4148&lon=-111.9093
	$search_for = '/Location: .*$/m';
	$LOCATION_found = preg_match($search_for, $HEADERS ,$found); //$found is always an array.

	if ($LOCATION_found) {
		$HEADERS_Location = substr($found[0], 10); //Drop the "Location: " prefix
		$HEADERS_Location_query = parse_url(trim($HEADERS_Location), PHP_URL_QUERY);
		parse_str($HEADERS_Location_query, $Query_values);
		
		if (isset($Query_values["lat"])) { $lat = $Query_values["lat"];		   $lon = $Query_values["lon"]; }
		else 							 { $lat = $Query_values["textField1"]; $lon = $Query_values["textField2"]; }
		
		$DATA_URLS[0] = $BASE_URL_OPTIONS."&textField1=".$lat."&textField2=".$lon;
		
		#Some searches only return lat & lon, no city, state, etc...
		if (isset($Query_values["CityName"]) && isset($Query_values["state"])) {
			$LOCATION_NAMES[0] = $Query_values["CityName"].", ".$Query_values["state"]; //." (".$Query_values["site"].")";
		}
		else {
			$LOCATION_NAMES[0] = $_GET["SEARCH_FOR_LOCATION"]." (Lat: ".$lat.", Lon: ".$lon.")";
		}
		
	}#end if($LOCATION_found)

	if ($TEST_MODE) {
		echo "<pre>".hsc($HEADERS)."</pre>";
		if($LOCATION_found) { echo '<pre>"Location: " header $Query_values: '.print_r($Query_values,true)."</pre>"; }
	}

	return $LOCATION_found;  //true, false, or 0 (zero).

}//end Search_for_custom_location() //*****************************************/




function Get_Weather_Pages($location){//***************************************/
	#get raw html page with weather data
	global $DATA_URLS, $LOCATION_NAMES, $TESTING_MSG, $TEST_MODE, $CUSTOM_RADAR_SITE, $RAW_HTML_SAMPLES, $HOURS_TO_SHOW;

	# AheadHour can be 0 thru 107.    If > 107, weather.gov assumes 0.
	# Since each page has 48 hours of data, use AheadHour = 0, 48, 96, or 107.
	# The "4th" page can start no later than AheadHour 107, as only the last 11 hours are new,
	# relative to "3rd" page (&AheadHour=96). If AheadHour is > 107, data returned starts 
	# same as AheadHour = 0.
	$AheadHour[1] =   0;
	$AheadHour[2] =  48;
	$AheadHour[3] =  96;
	$AheadHour[4] = 107;
	
	# $pages_to_get = 1 to 4, depending on...
	if 		($HOURS_TO_SHOW > 144)	{$pages_to_get = 4;}
	else if	($HOURS_TO_SHOW >  96)	{$pages_to_get = 3;}
	else if ($HOURS_TO_SHOW >  48)	{$pages_to_get = 2;}
	else    						{$pages_to_get = 1;}

	$raw_html = array(1=>"");
	
	for ($page = 1; $page <= $pages_to_get; $page++) {
		if ($TEST_MODE) {
			$TESTING_MSG = "<span class=TESTING_MSG>SAMPLE DATA</span>\n";
			$raw_html[$page] = file_get_contents($RAW_HTML_SAMPLES[$page]);
		}
		else { #*** LIVE DATA ****#
			$TESTING_MSG = "";
			
			if ($location == 0) { $headers_too = true; }
			else 				{ $headers_too = false; }
			
			$data_url = $DATA_URLS[$location]."&AheadHour=".$AheadHour[$page];
			$raw_html[$page] = curl_get_contents($data_url, $headers_too);
			#$raw_html = file_get_contents($data_url);  //stopped working sometime 2015-03-31
			
			# Find the radar id for user provided $location
			# (as ?rid=abc in the query portion of the href of an <a> in the $raw_html)
			# preg_match returns an array, but there should be only one...
			if ($location == 0) {
				$rid_found = preg_match("/rid=.../", $raw_html[$page], $RIDS); 
				$CUSTOM_RADAR_SITE = strtoupper(substr($RIDS[0],4)); //drop the "rid=" prefix
				//##### echo " *".$CUSTOM_RADAR_SITE."* <pre>".($raw_html)."</pre>"; //#####
			}
		}
		
		if ($raw_html[$page] === false) { 
			echo "<hr>No data returned from (".hsc($LOCATION_NAMES[$location])."): ".hsc($data_url)."<br>";
		} else {
			//echo "<hr>Data recieved from (".hsc($LOCATION_NAMES[$location])."): ".hsc($data_url)."<br>";
		}
	}
	
	return $raw_html;  //##### 
}//end  Get_Weather_Pages() //*************************************************/





function Extract_Weather_Data($raw_html) {//***********************************/
	//Extract desired data from table(s) and save in $DATA array().
	global $DESIRED, $DATA, $HOURS_TO_SHOW;
	
	$DOM = new DOMDocument;		#$DOM -> preserveWhiteSpace = false;

	# 2016-02-17
	# Notes for @$DOM -> loadHTML($raw_html[$page]); line, in loop below.
	# The @ suppresses errors that loadHTML() returns while parsing the $raw_html file(s).
	# The errors don't seem to matter, and only clog up Apache's error.log file.
	# Alternate to using the @ error suppression:
	# libxml_use_internal_errors(true); $DOM -> loadHTML($raw_html); $DOM_errors = libxml_get_errors(); libxml_clear_errors();

 	$pages = count($raw_html); 

	$hour_offset = 0; //from the current hour
	
	#cycle thru the WEATHER_TABLE in each html page.
	for ($page = 1; $page <= $pages; $page++) {
		
		$pageset = ($page - 1) * 48;
		
		#Get <tr>'s of data from the weather <table>
		@$DOM -> loadHTML($raw_html[$page]);
		$WEATHER_TABLE = $DOM -> getElementsByTagName('table') -> item(WEATHER_TABLE);
		$ROWS  = $WEATHER_TABLE	-> getElementsByTagName('tr');
		
		#cycle thru each set of rows (2 sets of ASPECTS_TOTAL rows)
		#On 4th page, only last 11 hours of data in the 2nd rowset are new/a continuation of page 3.
		if ($page < 4) {$rs = 0;} else {$rs = d2_OFFSET;}
		for ($rowset = $rs; $rowset <= d2_OFFSET; $rowset += d2_OFFSET) {
			
			#cycling thru each row in the rowset
			$first_row = FIRST_ROW	   + $rowset;
			$last_row  = ASPECTS_TOTAL + $rowset;
			for ($row = $first_row; $row <= $last_row; $row++) {
				
				//only extract desired rows
				if (!in_array($row, $DESIRED)) {continue;}
				
				//get row of data[hour][aspect]: date, hour, temp, etc...
				$cells = $ROWS -> item($row) -> getElementsByTagName('td');
				
				#On 4th page, only last 11 hours of data are new/a continuation of page 3.
				if ($page < 4) {$h = 1;} else {$h = 14;}
				
				#Get $DATA from $cells
				for ($hour = $h; $hour <= 24; $hour++) {
					$DATA[$hour + $hour_offset][$row - $rowset] = trim($cells -> item($hour) -> textContent);
				}
			}// end for($row)
			
			$hour_offset += 24; #24 hours in each $rowset (except for page 4...).
		}//end for ($rowset)
	}

	$DATA = array_values($DATA);
}//end Extract_Weather_Data() //***********************************************/





function Display_Weather_V($location) {//***************************************/
	//Display data in new Vertical table, each row one hour.
	global  $DATA, $LOCATION_NAMES, $TESTING_MSG, $RAIN_THRESHOLD, $HOURS_TO_SHOW, $DISPLAY_ORDER, $SELECTED_ASPECTS;

	echo "<table class=data>\n";
		# WASPECTS is max num of columns. but it's ok if there are fewer.
		echo "<tr>\n<td colspan=".WASPECTS.">";
		echo "<h2>".hsc($LOCATION_NAMES[$location])."<br>".$TESTING_MSG."</h2>";
		echo "</td>\n</tr>\n";
		
		#$data_index is from 0 (current/first hour of data) to $HOURS_TO_SHOW
		for ($data_index = 0; $data_index <= $HOURS_TO_SHOW; $data_index++) {
			
			#Highlight Header Row (labels)
			if ($data_index == 0) {$hdr = "hdr";} else {$hdr = "";}
			
			#After the first date, remove redundant dates when the hour of the current $data_index is not 0 (midnight).
			if ( ($DATA[$data_index][d1_DATE] != "") && ($DATA[$data_index][d1_HOUR] > 0) && ($data_index > 1) ) {
				$DATA[$data_index][d1_DATE] = "";
			}
			
			#Show day of week in date column, if prior row's date column was a new date.
			#Considerations:
			#	$data_index = 0: Ignore - this is the column header. 
			#	$data_index = 1: Ignore - this must always have the date (not the day of week). 
			#	$data_index = 2: Ignore if it is also a new day (leave the date).
			$day = "";
			if ($data_index > 1) {
				if ( (($data_index == 2) && ($DATA[2][d1_HOUR] >  0)) || (($data_index >  2) && ($DATA[$data_index][d1_HOUR] == 1)) )
				{ 
					$day = date('D', strtotime(date("Y")."/".$DATA[$data_index - 1][d1_DATE]));
				}
			}
			
			#get number of weather aspects selected to display
			$aspects = count($DISPLAY_ORDER);
			
			#Highlight midnight of a new day...
			$newday = "";
			if (($DATA[$data_index][d1_HOUR] == 23) || ($data_index == 0)) { $newday = "newday";}
			#if (($DATA[$data_index][d1_HOUR] == 0) || ($data_index == 1)) { $newday = "newday";}
			
			#Show selected data...
			echo "<tr class='$newday'>\n";
			for ($aspect=0; $aspect < $aspects; $aspect++) {
				if (!in_array($DISPLAY_ORDER[$aspect], $SELECTED_ASPECTS) ) { continue; }
					
				if ($aspect < 2) {$td = "th";} else {$td = "td";} #header or data?
					
				$aspect_class = ""; #used to adjust css for specific columns and/or weather data
				#Highlight rain% value if >= specified value.
				if (($data_index > 0) && ($DISPLAY_ORDER[$aspect] == d1_RAIN) && ($DATA[$data_index][d1_RAIN] >= $RAIN_THRESHOLD)) {
					$aspect_class = " rain";
				}
					
				#Adjust css for a couple of columns
				if (($data_index >  0) && ($DISPLAY_ORDER[$aspect] == d1_WIND_DIR)) { $aspect_class = " wind_dir";}
				if (($DISPLAY_ORDER[$aspect] == d1_FOG)) {$aspect_class = " fog";}
					
				echo "<$td class='$hdr$aspect_class'>".$day.hsc($DATA[$data_index][$DISPLAY_ORDER[$aspect]])."</$td>\n";
				$day = ""; #only needed for first column (at most).
			}//end for($aspect)
			echo "</tr>\n";
		}//end for($data_index)
	echo "</table>\n";	
	echo $FINISH." | ".$START."<hr>"; //##### 
}//end Display_Weather_V() //**************************************************/





function Display_Weather_H($location) {//***************************************/
	//Display data in new Horizontal table, each column one hour.
	global  $DATA, $LOCATION_NAMES, $TESTING_MSG, $RAIN_THRESHOLD, $HOURS_TO_SHOW, $DISPLAY_ORDER, $SELECTED_ASPECTS;
		
	echo "<table class=data>\n";
		
		$colspan = $HOURS_TO_SHOW + 1;
		echo "<tr><td colspan=$colspan>\n";
		echo "<h2 class='location_name'>".hsc($LOCATION_NAMES[$location])." &nbsp; ".$TESTING_MSG."</h2>\n";
		echo "</td></tr>\n";
		
		for ($tr = 0; $tr < WASPECTS; $tr++) {
			
			#Only show (Date, Time), & selected aspects
			if (!(($tr < 2) || in_array($DISPLAY_ORDER[$tr], $SELECTED_ASPECTS))) {continue;}
			
			echo "<tr>\n";
			for ($data_index = 0; $data_index <= $HOURS_TO_SHOW; $data_index++) {
				
				#Add day of week after new date (if there's room).
				$day = "";
				if (($tr == 0) && ($data_index > 1) && ($DATA[$data_index - 1][d1_DATE] != "") && ($DATA[$data_index][d1_DATE] == "")) {
					$day = date('D', strtotime(date("Y")."/".$DATA[$data_index - 1][d1_DATE]));
				}
				
				#Highlight header/date & time rows. ***********************/
				if (($tr == 0) || ($tr == 1)) {$hdr = "hdr";} else {$hdr = "";}
				
				#Highlight rain% value if over specified value.
				$rain = "";
				if (($DISPLAY_ORDER[$tr] == d1_RAIN) && ($DATA[$data_index][d1_RAIN] >= $RAIN_THRESHOLD)) { $rain = "rain"; }
				
				#Finally, ouput the weather info. hour 0 is the header/label.
				if ($data_index == 0) 
					{ echo "<th>".($DATA[$data_index][$DISPLAY_ORDER[$tr]])."</th>\n"; }
				else
					{ echo "<td class='".$hdr." ".$rain."'>".hsc($DATA[$data_index][$DISPLAY_ORDER[$tr]])."$day</td>\n"; }
			}//end for($data_index)
			echo "</tr>\n";
		}//end for($tr)
		
	echo "</table><br>\n";
}//end Display_Weather_H() //**************************************************/





function Header_crap() {//*****************************************************/
	header('Content-type: text/html; charset=UTF-8');
	echo "<!DOCTYPE html>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'."\n";
	echo '<link rel="shortcut icon" href="http://d/D.ico"/>'."\n";
	echo "<title>Weather</title>\n";
}//end Header() {//************************************************************/




function User_Options() {//****************************************************/
	global	$LOCATION_NAMES, $RAIN_THRESHOLD, $HOURS_TO_SHOW, $SHOW_LOCATIONS, $TEST_MODE,
			$DISPLAY_H, $SELECTED_ASPECTS, $SHOW_RADAR, $DATA, $DISPLAY_ORDER, $WRAP_MAP, $DONT_WRAP_MAP;

	#First row: Locations **********************************
	echo "\n<div class=options_group>\n";
	echo "Show weather for: &nbsp;\n";

	foreach ($LOCATION_NAMES as $key => $location_name) {
		$checked = "";
		if (isset($SHOW_LOCATIONS[$key])) { $checked = " checked"; }
		
		if ($key > 0) {
			#Defined choices: Tempe, AJ, etc...
			echo "<label class='option_label location_label'>";
			echo "<input type=checkbox name=SHOW_LOCATIONS[".hsc($key)."] value=".hsc($key)." tabindex=1$checked>";
			echo hsc($location_name)."</label>\n";
		} else {
			#Search box
			echo "<span class=location_search_option>\n";
			echo "<input type=checkbox name=SHOW_LOCATIONS[0]    value=0    tabindex=1$checked>\n"; 
			echo "<input type=text id=search_for_location name=SEARCH_FOR_LOCATION tabindex=1 value='".hsc($location_name)."'>\n";
			echo "</span>";
			echo "<span class=fine_print>Currently, weather retrieval may fail for some (otherwise valid) locations, such as in Alaska. &nbsp;I'm working on it...</span>";
			echo "<br>\n";
		}
		
	}//end for $LOCATION
	
	echo "</div>";



	#Second row: Weather aspects - Temp, Wind, etc *********
	echo "\n<p class=options_group>\n";

	echo "Show: &nbsp;\n";
	for ($aspect=0; $aspect < WASPECTS; $aspect++) {
		$checked = "";
		if (in_array($DISPLAY_ORDER[$aspect], $SELECTED_ASPECTS) )	{ $checked = " checked"; }
		echo "<label class=option_label><input type=checkbox name=ASPECTS[$DISPLAY_ORDER[$aspect]] ";
		echo "value=".hsc($DISPLAY_ORDER[$aspect])." tabindex=2$checked>";
		echo hsc($DATA[0][$DISPLAY_ORDER[$aspect]])."</label>\n";
	}
	



	#Third row: display options ****************************
	echo "\n<p class=options_group>\n";
	
	#Hours to display options (12, 24, 36, 48, etc...)
	echo "<span class=options>Display &nbsp;<select name=HOURS_TO_SHOW tabindex=3>\n";
	
	for ($option = HOURS_MIN; $option <= HOURS_MAX; $option += HOURS_INC) {
		if ($HOURS_TO_SHOW == $option){ $selected = " selected"; } else {$selected = "";}
		echo "<option value=$option$selected>".$option."</option>\n";
	}//end for($options)

	#in case HOURS_MIN + HOURS_INC... is not an even multiple of HOURS_MAX
	$option -= HOURS_INC; // revert to last good value
	if ($option < HOURS_MAX) {
		if ($HOURS_TO_SHOW == HOURS_MAX){ $selected = " selected"; } else {$selected = "";}
		echo "<option value=".HOURS_MAX."$selected>".HOURS_MAX."</option>\n";
	}
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
	echo "<input type=text id=rain_threshold name=RAIN_THRESHOLD width=2 maxlength=2 value=".$RAIN_THRESHOLD." tabindex=3>";
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
	global $HOURS_TO_SHOW, $DONT_WRAP_MAP, $DISPLAY_H;
?>
<style>
	*			{ font-family: arial; }
	pre			{ font-family: courier; margin: 0; }
	
	h2 			{ font-size: 1.5em; margin: 0; }

	td, th		{ font-size: 9pt; text-align:center; vertical-align: top; border: 1px solid rgb(63,131,245); padding: 0 .3em; }
	th			{ }
	td			{ min-width: 2.5em; max-width: 2.9em; white-space: normal; } /*Default for V display.*/

	label		{ white-space: nowrap; display: inline-block; }
	img			{ vertical-align: top; }

	.data		{ border-collapse: collapse; display: inline-block; margin: 0 .5em .5em 0; vertical-align:top; }
 
	.hdr		{ font-weight: bold; padding: 0 .3em;}
	.time		{ }
	.temp		{ }
	.wind_mph	{ }
	.wind_dir	{ text-align: left; padding: 0 0 0 0.25em; }
	.rain		{ color: blue; font-weight: bold; }
	.clouds		{ }
	.humidity	{ }
	.fog		{ max-width: 8em; padding: 0 .5em;}

	.not_found	{ border: 1px solid rgb(63,131,245); font-weight: bold; text-align: center; }
	
	.w_container { display: inline-block; vertical-align: top; }  /* white-space: nowrap;*/
	
	.location_search_option	 { display: inline-block; margin-right: 1em} /*span around search_for_location*/
	
	.newday		{background-color: #EEE;}

	#submit			{ float: right; width: 9em; margin-right: 1.5em; }
	#options_form	{ display: inline-block; border: 0px solid gray; padding: 0 0 0 0; }
	#default_ops 	{ float: right; }
	#test_mode	 	{ float: right; }
	#VH			 	{ }
	#rain_threshold	{ width:1.4em; padding: 1px 0 0 2px; }

	#show_radar_label { margin-left: 3em; }
	#wrap_map	 	  { white-space: nowrap; }
	#dont_wrap_map	  { margin-left: 1em; }
	#STARTSTOP		  { border: 1px solid #333; border-radius: 4px; width: 10em;}
	#radar_map		  { font-size: .8em; }

	#timestamp	 	{ margin: .2em 0 .2em 0; padding: 1px .3em 0 .2em; display: inline-block; border: 1px solid teal; }
	#timestamp_row  { margin-bottom: .3em; }

	.options		  { margin: 0 1.5em 0 0; }
	.options_group	  { border: 1px solid rgb(63,131,245); padding-left: .4em; line-height: 1.35em; margin: .3em 0 .4em 0; }

	.option_label	     { padding: 1px .3em 2px .1em; margin-right: .6em; border-left: 1px solid transparent; border-right: 1px solid transparent;}
	.option_label:hover  { background-color: #ddd; border-left: 1px solid rgb(63,131,245); border-right: 1px solid rgb(63,131,245);}
	
	.location_label	     { border-top: 1px solid transparent;}
	.location_label:hover{ border-top: 1px solid rgb(63,131,245);}

	.location_name	 { text-align: left; margin-left: 4em; }
	.fine_print  	 { font-size: 9pt; color: #555; }
	.TESTING_MSG 	 { color: red; }
</style>
<?php
	if ($DONT_WRAP_MAP) {echo "<style>.w_container {white-space: nowrap;}</style>\n";}

	#Adjust <td> widths for Horizontal display.
	if ($DISPLAY_H) { echo "<style>th { max-width: 6em; }</style>\n\n"; }

	#Adjust left margin for location name if hours < HOURS_MIN, so it's not out of box (if it's a long name).
	if ($HOURS_TO_SHOW < HOURS_MIN) { echo "<style>.location_name {margin-left: .2em;}</style>\n";}

}//end Styles() ***************************************************************/




function Time_Stamp_js() {//***************************************************/
?>
<script>
function Time_Stamp(write_return){ //*********************************************/
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




function Show_Radar() { //******************************************************/
	global $SELECTED_ASPECTS, $SHOW_LOCATIONS, $DISPLAY_H, $HOURS_TO_SHOW,
		   $WRAP_MAP, $FRAME_RATE, $ROTATE_PAUSE, $ROTATE_LOOPS, $CUSTOM_RADAR_SITE, $location_found;

	#Approximates width of total weather displayed (excluding radar)
	if ($DISPLAY_H) { $weather_width = $HOURS_TO_SHOW ; }
	else			{ $weather_width = (count($SELECTED_ASPECTS) + 2) * count($SHOW_LOCATIONS); }

	if ($location_found) { echo '<img src="'.RADAR_URL_BASE.$CUSTOM_RADAR_SITE.'_0.png">'; }
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
			<span id=IMG_URL class=fine_print></span><br>
		</div>
	</div>
<?php
	Radar_Loop_scripts();

}//end Show_Radar() //*********************************************************/




function Radar_Loop_scripts() { //**********************************************/
	global $TEST_MODE, $RADAR_URL_BASE_SAMPLE;
?>
<script>
function Start_Stop() { //*********************************************
	var Start_Stop_button = document.getElementById('STARTSTOP');

	if (RUNNING) {
		RUNNING = false;
		Start_Stop_button.innerHTML = "Start Radar Loop";
		Start_Stop_button.style.backgroundColor = "transparent";
		clearInterval(LOOP_TIMER); //Not actually running on init...
	} else {
		RUNNING = true;
		Start_Stop_button.innerHTML = "Stop Radar Loop"; 
		Start_Stop_button.style.backgroundColor = "#FFd0d0"; //light red
		Rotate_Pic();
	}
} //end Start_Stop() //***********************************************



function Rotate_Pic(){ //*********************************************
	var frame_rate   = Frame_Rate_Options[Frame_Rate_Options.selectedIndex].value;
	var rotate_pause = Rotate_Pause_Options[Rotate_Pause_Options.selectedIndex].value;
	var rotate_loops = ROTATE_LOOPS_Options[ROTATE_LOOPS_Options.selectedIndex].value;
	var max_rotations = (rotate_loops * PIC_LIST[0].length) - 1 ; //actually, the number of images to cycle thru.

	//Determine new image/CURRENT_PIC.
	//Pic 0 is the "last"(latest) pic. Pic 7 (.length - 1) is the "first"(oldest) pic.
	//So, to rotate from oldest to newest, rotation order is: 7 6 5 4 3 2 1 0
	// First check if prior pic was last pic in list.
	if(CURRENT_PIC <= 0) {CURRENT_PIC = (PIC_LIST[0].length - 1);} else {CURRENT_PIC--;}

	//Display new image & image URL.
	ROTATING_PIC.src  = PIC_LIST[0][CURRENT_PIC];
	IMG_URL.innerHTML = "(" + PIC_LIST[0][CURRENT_PIC] + ")";

	//Display/update current loop when on first(oldest) pic in list.
	if (CURRENT_PIC == (PIC_LIST[0].length - 1)) {
		document.getElementById('CURRENT_LOOP').innerHTML = CURRENT_LOOP;
		CURRENT_LOOP++;
	}

	//delay for setTimeout (time to next pic)
	if (CURRENT_PIC == 0)	{ delay = rotate_pause; }  //A slightly longer pause on pic 0 (normally).
	else					{ delay = frame_rate; }    //Normal pause between each img.

	if (FRAME_COUNT < max_rotations) {
		FRAME_COUNT++;
		LOOP_TIMER = setTimeout('Rotate_Pic()',delay);
	} else {
		FRAME_COUNT  = 0;
		CURRENT_PIC  = 0;
		CURRENT_LOOP = 1;
		Start_Stop();
	}
	
}//end Rotate_Pic() //************************************************




//Load radar image URL's into PIC_LIST[site][x]
//site = radar site (0=default, 1=user supplied),   x = image 0 thru 7
//image 0 is most recent, 7 is the oldest. //(Last 8 radar images available from weather.gov)
var PIC_LIST	= [];
	PIC_LIST[0] = []; //Default radar site
	PIC_LIST[1] = []; //Radar site for user requested location.
<?php
if ($TEST_MODE) { $radar_url_base = $RADAR_URL_BASE_SAMPLE; }
else            { $radar_url_base = RADAR_URL_BASE_DEF; }
 ?>
for (var x = 0; x < 8; x++) {
	PIC_LIST[0][x] = '<?php echo $radar_url_base ?>' + x + '<?php echo RADAR_IMG_EXT ?>';
}


//FRAME_RATE = pause between each image,
//set in rotate_pic() from the following user option (FRAME_RATE).
var Frame_Rate_Options	 = USER_OPTIONS.FRAME_RATE;

//ROTATE_PAUSE = pause between loops.
//Set in Start_Stop() from the following opton (ROTATE_PAUSE).
var Rotate_Pause_Options = USER_OPTIONS.ROTATE_PAUSE;

//ROTATE_LOOPS = number of times to cycle thru radar image list, then stop.
// Used to determine max_rotations in Rotate_Pic().
var ROTATE_LOOPS_Options = USER_OPTIONS.ROTATE_LOOPS;
var CURRENT_LOOP = 1;

var FRAME_COUNT = 0;  //Current count of total pics rotated...
var CURRENT_PIC = 0;  //index for PIC_LIST[0][CURRENT_PIC]

//Initialize the Start_Stop <button> as "Start...
var RUNNING = true;	  //Will be flipped to false by initial call to Start_Stop().
var LOOP_TIMER   = setTimeout(";", 1); //Needed for initial call to Start_Stop().
Start_Stop();


//Show initial image url. Changed with pic in Rotate_Pic()
var IMG_URL			  = document.getElementById('IMG_URL');
    IMG_URL.innerHTML = "(" + PIC_LIST[0][0] + ")";

//Load initial/latest radar image
var ROTATING_PIC     = document.getElementById('RotatingPic');
    ROTATING_PIC.src = PIC_LIST[0][0];
</script>

<?php
}//end Radar_Loop_scripts() //*************************************************/




# "Main" //********************************************************************/
#


Init();


Get_GET(); //needed before Styles() & User_Options();

Header_crap();
Styles();
Time_Stamp_js();


#</form> is at end of "Main", after Show_Radar(), to include radar options
echo "\n<form name=USER_OPTIONS method=get id=options_form>\n"; 

User_Options();



echo '<div id=timestamp_row>';

	#Reload with default options
	echo "\n<button type=button id=default_ops class=button onclick='parent.location=location.pathname' tabindex=1000>Default Options</button>\n";

	#SUBMIT button
	#if ($SHOW_RADAR) {$autofocus = "";} else {$autofocus = "autofocus";}
	echo "\n<button class=button id=submit tabindex=999 autofocus>Submit</button>\n";

	#Time Stamp
	echo "<span id=timestamp><script>Time_Stamp('write');</script></span>";

	#Data Source
	echo " <span class=fine_print>(Weather data source: ";
		if ($TEST_MODE) {echo "<span class=TESTING_MSG>".hsc($RAW_HTML_SAMPLES[1])."</span>";}
		else			{echo "www.weather.gov";}
	echo ")</span><br>\n";

echo "</div>\n";


#Get & display weather for selected locations
echo "\n<div class=w_container>\n";

	echo "\n<div class=w_container>\n";
	foreach($SHOW_LOCATIONS as $key => $location) { 
		
		if ($location == 0) { //"search for" user provided location (zip or city,ST)
			
			$location_found = Search_for_custom_location();
			
			if (!$location_found) {
				echo '<div class="data not_found">Not Found:<br>"'.hsc($_GET['SEARCH_FOR_LOCATION']).'"</div>';
				continue;
			}
			
		}//#end if ($location==0)
		
		$RAW_HTML = Get_Weather_Pages($location);
		
		Extract_Weather_Data($RAW_HTML);
		
		if ($DISPLAY_H) { Display_Weather_H($location); }
		else			{ Display_Weather_V($location); }
	}
	echo "</div>\n"; #end w_container

	if ($SHOW_RADAR) {Show_Radar();}

echo "</div>\n"; #end w_container


echo "</form>\n\n";
#
# end "Main" //****************************************************************/







################################################################################
if ($TEST_MODE) {
	#dump_array($var, $name="", $ECHO = 1, $PRE=1, $BRDR=1, $DSPLY=1, $VD=0, $LVL=0)
	
	dump_array($_GET			 ,'$_GET'			  ,1,1,1,2);
	dump_array($SHOW_LOCATIONS   ,'$SHOW_LOCATIONS'   ,1,1,1,2);
	dump_array($DEFAULT_ASPECTS  ,'$DEFAULT_$ASPECTS' ,1,1,1,2);
	dump_array($SELECTED_ASPECTS ,'$SELECTED_$ASPECTS',1,1,1,2);
	dump_array($LOCATION_NAMES   ,'$LOCATION_NAMES'   ,1,1,1,2);
	dump_array($DATA			 ,'$DATA'			  ,1,1,1,2);
	
	echo '<div style="clear: both;"></div>';
	echo "\n<style> body {background-color: #ccf}</style>\n";
	#echo '<hr><pre style="clear: both;">$_GET: '		    .hsc(print_r($_GET, true))."</pre>\n";
	#echo '<hr><pre style="clear: both;">$SHOW_LOCATIONS: '  .hsc(print_r($SHOW_LOCATIONS, true))."</pre>\n";
	#echo '<hr><pre style="clear: both;">$DEFAULT_ASPECTS: ' .hsc(print_r($DEFAULT_ASPECTS, true))."</pre>\n";
	#echo '<hr><pre style="clear: both;">$SELECTED_ASPECTS: '.hsc(print_r($SELECTED_ASPECTS, true))."</pre>\n";
	#echo '<hr><pre style="clear: both;">$LOCATION_NAMES: '  .hsc(print_r($LOCATION_NAMES, true))."</pre>\n";
	#echo '<hr><pre style="clear: both;">$DATA: '			 .hsc(print_r($DATA, true))."</pre>\n";

	#echo '<hr><pre>$RAW_HTML[1]: <br>'.hsc($RAW_HTML[1])."</pre>";  //#####
	#echo '<hr><pre>$RAW_HTML[2]: <br>'.hsc($RAW_HTML[2])."</pre>";  //#####
	#echo '<hr><pre>$RAW_HTML[3]: <br>'.hsc($RAW_HTML[3])."</pre>";  //#####
	#echo '<hr><pre>$RAW_HTML[4]: <br>'.hsc($RAW_HTML[4])."</pre>";  //#####
}
################################################################################



echo "<div style='clear: both; border: 2px outset gray; height: .5em; '>&nbsp;</div>";

