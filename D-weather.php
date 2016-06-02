<?php
error_reporting(E_ALL & ~E_STRICT);     //(E_ALL &~ E_STRICT) for everything, 0 for none.
ini_set('display_errors', 'on');
ini_set('log_errors'    , 'off');
ini_set('error_log'     , $_SERVER['SCRIPT_FILENAME'].'.ERROR.log');

/*******************************************************************************
#Common URL  (w/Tempe lat & lon)
http://forecast.weather.gov/MapClick.php?lat=33.4148&lon=-111.9093&unit=0&lg=english&FcstType=digital
http://forecast.weather.gov/MapClick.php?w0=t&w1=td&w2=wc&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w8=thunder&w9=snow&w10=fzg&w11=sleet        &w13u=0&w15u=1&w16u=1&AheadHour=0&Submit=Submit&FcstType=digital&textField1=33.4148&textField2=-111.9093&site=all&unit=0&dd=&bw=
http://forecast.weather.gov/MapClick.php?w0=t&w1=td&w2=wc&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w8=thunder&w9=snow&w10=fzg&w11=sleet&w12=fog&w13u=0&w15u=1&w16u=1&AheadHour=0&Submit=Submit&FcstType=digital&textField1=33.4148&textField2=-111.9093&site=all&unit=0&dd=&bw=
http://forecast.weather.gov/MapClick.php?w0=t&w1=td&w2=wc&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w8=thunder&w9=snow&w10=fzg&w11=sleet        &w13u=0&w15u=1&w16u=1&AheadHour=48             &FcstType=digital&textField1=33.4148&textField2=-111.9093&site=all&unit=0&dd=&bw=&AheadDay.x=76&AheadDay.y=8
http://forecast.weather.gov/MapClick.php?lat=33.4148&lon=-111.9093&unit=0&lg=english&FcstType=digital&w0=t&w1=td&w2=wc&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w8=thunder&w9=snow&w10=fzg&w11=sleet&w12=fog&w13u=0&w15u=1&w16u=1&FcstType=digital&site=all&unit=0&dd=&bw=



//URL Breakdown:
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
# The following describes the weather <table> returned from options in the URL above. 
# The <table> consists of 36 rows (<tr>'s) and 25 columns (<td>'s)
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






function Set_Theme($theme=0) { //**********************************************/
	global $COLOR;

	//The number of theme sets, order & names must coorespond to $THEME_COUNT & $THEME_LIST near end of Init();

	//--- DEFAULTS -----------------------------------
	$COLOR['body-bg']			= "#FFF";
	$COLOR['body']				= "#000";
	$COLOR['hover-bg']			= "#Fdd";
	$COLOR['hover-border']		= "";
	$COLOR['hover-font']		= "";
	$COLOR['focus-bg']			= "#Fcc";
	$COLOR['focus-border']		= "";
	$COLOR['focus-font']		= "inherit";
	$COLOR['active-bg']			= "#Fbb";
	$COLOR['active-border']		= "#444";
	$COLOR['active-font']		= "";
	$COLOR['imgbar']			= "#444";
	$COLOR['imgbar-font']		= "#777";
	$COLOR['imgbar-slot']		= "#ddd";
	$COLOR['fine-print']		= "#555";
	$COLOR['input-bg']			= "white";
	$COLOR['input-font']		= "black";
	$COLOR['input-border']		= "rgb(127,157,187)";
	$COLOR['data-font-size']	= ".90em";			//16px = 12pt = 1em
	$COLOR['data-grid']			= "rgb(100,160,250)";
	$COLOR['options-border']	= "rgb(63,131,245)";
	$COLOR['borders']			= "rgb(10,80,200)";
	$COLOR['data-border'] 		= $COLOR['borders'];
	$COLOR['msgs-border']		= $COLOR['borders'];
	$COLOR['msgs-h-border']		= $COLOR['borders'];
	$COLOR['not-found-border']	= $COLOR['borders'];
	$COLOR['rain']				= "blue";
	$COLOR['button-bg']			= "";
	$COLOR['button-border']		= "";
	$COLOR['button-font']		= "inherit";
	$COLOR['svg-btn']			= "#555";
	//End DEFAULTS -----------------------------------


	if ($theme == 1) {
		//--- Dark ---------------------------------------
		$COLOR['body-bg']			= "black";
		$COLOR['body']				= "#0F0";
		$COLOR['hover-bg']			= "red";
		$COLOR['hover-border']		= "yellow";
		$COLOR['hover-font']		= "";
		$COLOR['focus-bg']			= "red";
		$COLOR['focus-border']		= "cyan";
		$COLOR['focus-font']		= "yellow";
		$COLOR['active-bg']			= "red";
		$COLOR['active-border']		= "#444";
		$COLOR['active-font']		= "";
		$COLOR['imgbar']			= "#444";
		$COLOR['imgbar-font']		= "#777";
		$COLOR['imgbar-slot']		= "#444";
		$COLOR['fine-print']		= "#555";
		$COLOR['input-bg']			= "black";
		$COLOR['input-font']		= "#0F0";
		$COLOR['input-border']		= "#0F0";
		$COLOR['data-grid']			= "rgb(100,160,250)";
		$COLOR['data-font-size']	= ".90em";
		$COLOR['options-border']	= "rgb(63,131,245)";
		$COLOR['borders']			= "rgb(10,80,200)";
		$COLOR['data-border'] 		= $COLOR['borders'];
		$COLOR['msgs-border']		= $COLOR['borders'];
		$COLOR['msgs-h-border']		= $COLOR['borders'];
		$COLOR['not-found-border']	= $COLOR['borders'];
		$COLOR['rain']				= "cyan";
		$COLOR['button-bg']			= "blue";
		$COLOR['button-border']		= "yellow";
		$COLOR['button-font']		= "inherit";
		$COLOR['svg-btn']			= "#000";
		//End Dark ---------------------------------------
	} 
	if ($theme == 2) {
		//--- Dark 2 -------------------------------------
		$COLOR['body-bg']			= "black";
		$COLOR['body']				= "#FFbf00";
		$COLOR['hover-bg']			= "#333";
		$COLOR['hover-border']		= "yellow";
		$COLOR['hover-font']		= "";
		$COLOR['focus-bg']			= "#333";
		$COLOR['focus-border']		= "yellow";
		$COLOR['focus-font']		= "yellow";
		$COLOR['active-bg']			= "#FFbf00";
		$COLOR['active-border']		= "yellow";
		$COLOR['active-font']		= "black";
		$COLOR['imgbar']			= "#444";
		$COLOR['imgbar-font']		= "#777";
		$COLOR['imgbar-slot']		= "#444";
		$COLOR['fine-print']		= "#555";
		$COLOR['input-bg']			= "black";
		$COLOR['input-font']		= "#0F0";
		$COLOR['input-border']		= "#0F0";
		$COLOR['data-font-size']	= ".90em";
		$COLOR['data-grid']			= "rgb(100,160,250)";
		$COLOR['options-border']	= "rgb(63,131,245)";
		$COLOR['borders']			= "#FFbf00"; //rgb(10,80,200)
		$COLOR['data-border'] 		= $COLOR['borders'];
		$COLOR['msgs-border']		= $COLOR['borders'];
		$COLOR['msgs-h-border']		= $COLOR['borders'];
		$COLOR['not-found-border']	= $COLOR['borders'];
		$COLOR['rain']				= "cyan";
		$COLOR['button-bg']			= "black";
		$COLOR['button-border']		= "#FFbf00";
		$COLOR['button-font']		= "#FFbf00";
		$COLOR['svg-btn']			= "#FFbf00";
		//End Dark 2 -------------------------------------
	} 
	if ($theme == 3) {
		//--- Dark 3 -------------------------------------
		$COLOR['body-bg']			= "black";
		$COLOR['body']				= "#F00";
		$COLOR['hover-bg']			= "#333";
		$COLOR['hover-border']		= "yellow";
		$COLOR['hover-font']		= "";
		$COLOR['focus-bg']			= "#333";
		$COLOR['focus-border']		= "yellow";
		$COLOR['focus-font']		= "yellow";
		$COLOR['active-bg']			= "#F00";
		$COLOR['active-border']		= "yellow";
		$COLOR['active-font']		= "black";
		$COLOR['imgbar']			= "#444";
		$COLOR['imgbar-font']		= "#777";
		$COLOR['imgbar-slot']		= "#444";
		$COLOR['fine-print']		= "#555";
		$COLOR['input-bg']			= "black";
		$COLOR['input-font']		= "#0FF";
		$COLOR['input-border']		= "#0FF";
		$COLOR['data-font-size']	= ".90em";
		$COLOR['data-grid']			= "#FFbf00"; //rgb(100,160,250)
		$COLOR['options-border']	= "rgb(63,131,245)";
		$COLOR['borders']			= "#F00"; //rgb(10,80,200)
		$COLOR['data-border'] 		= "#FFbf00";
		$COLOR['msgs-border']		= $COLOR['borders'];
		$COLOR['msgs-h-border']		= $COLOR['borders'];
		$COLOR['not-found-border']	= $COLOR['borders'];
		$COLOR['rain']				= "cyan";
		$COLOR['button-bg']			= "black";
		$COLOR['button-border']		= "#F00";
		$COLOR['button-font']		= "#F00";
		$COLOR['svg-btn']			= "#F00";
		//End Dark 3 -------------------------------------
	} 
}//end Set_Theme() { //********************************************************/





function Init() { //***********************************************************/
	global $URL_BASE, $URL_OPTIONS, $URL_MOST, $DATA_URLS, $LOCATION_NAMES, 
		   $DESIRED, $DISPLAY_ORDER, $DATA, $DEFAULT_ASPECTS, $SAMPLE_SET, 
		   $RAW_HTML_SAMPLES, $RADAR_URL_BASE_SAMPLE, $RAIN_THRESHOLD, $THEME_LIST, $THEME_COUNT;


	//Make sure time zone is correct.
	date_default_timezone_set("America/New_York");


	//Weather url's ******************************************************
	$URL_BASE    = "http://forecast.weather.gov/";
	$URL_BASE_1  = $URL_BASE."MapClick.php?";
	$URL_OPTIONS = "&w0=t&w1=td&w2=wc&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w8=thunder&w9=snow&w10=fzg&w11=sleet&w12=fog&w13u=0&w15u=1&w16u=1&FcstType=digital&site=all&unit=0&dd=&bw=";
	$URL_MOST    = $URL_BASE_1.$URL_OPTIONS; //$URL_MOST only needs textField1 & textField2 (lat & lon) query options to complete the URL.


	//A few pre-defined locations...
	$x = 0;
	//The first, zero, location is a placeholder for the "Search For" location option.
	$DATA_URLS[$x] = $URL_MOST."&textField1=33.4148&textField2=-111.9093"	; $LOCATION_NAMES[$x++] = "Tempe, AZ";
	
	$DATA_URLS[$x] = $URL_MOST."&textField1=33.4148&textField2=-111.9093"	; $LOCATION_NAMES[$x++] = "Tempe";
	$DATA_URLS[$x] = $URL_MOST."&textField1=33.4150&textField2=-111.5496"	; $LOCATION_NAMES[$x++] = "Apache Junction";


	define('LOCATIONS', $x);
	define('DEFAULT_LOCATION', 1);

	//Used to find user provided location
	define('BASE_SEARCH_URL', "http://forecast.weather.gov/zipcity.php?inputstring=");



	//Weather data is the 8th <table> in the source html (as of 2015-03-29).
	//(8th counting from 1, but computer programmers like to count from 0...)
	define('WEATHER_TABLE', 7);


	// Rows (<tr>) of data in source html table.
	// The following rows are determined by options in the URL. (all weather options selected)
	// weather.gov displays 48 hours at once, starting with current hour,
	// in two 24 hour sets.
	// The d1_ or d2_ prefix denotes which set of rows of 24 hour data. 
	// The "d" prefix just means "day". I don't know- you got a better idea?

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



	//Desired rows of data to extract from source html table. Currently all rows.
	$DESIRED = array(
		d1_DATE, d1_HOUR, d1_TEMP, d1_DEWPOINT, d1_WIND_CHILL, d1_WIND, d1_WIND_DIR, d1_GUST, d1_CLOUDS, d1_RAIN, d1_HUMIDITY, d1_FOG, d1_RAIN_CHNC, d1_THUNDER, d1_FRZ_RAIN, d1_SLEET, d1_SNOW, 
		d2_DATE, d2_HOUR, d2_TEMP, d2_DEWPOINT, d2_WIND_CHILL, d2_WIND, d2_WIND_DIR, d2_GUST, d2_CLOUDS, d2_RAIN, d2_HUMIDITY, d2_FOG, d2_RAIN_CHNC, d2_THUNDER, d2_FRZ_RAIN, d2_SLEET, d2_SNOW, 
	);//


	//Order to subsequently re-display data (not neccessarily all of, or same order as, the source)
	//$DISPLAY_ORDER is also used in Get_GET() to validate values in $_GET["ASPECTS"] (selected aspects).
	$DISPLAY_ORDER = array(d1_DATE, d1_HOUR, d1_TEMP, d1_DEWPOINT , d1_WIND_CHILL, d1_WIND, d1_WIND_DIR, d1_GUST, d1_RAIN, d1_CLOUDS, d1_HUMIDITY, d1_FOG, d1_RAIN_CHNC, d1_THUNDER, d1_FRZ_RAIN, d1_SLEET, d1_SNOW);


	//Number of weather aspects displayed (TIME, FORCAST, TEMP, etc...)
	define('WASPECTS', count($DISPLAY_ORDER));



	//Default aspects to display
	//Leave aspect out of $DEFAULT_ASPECTS if you don't want it displayed *by default*.
	//The option box will be available, but unchecked.
	//$DEFAULT_ASPECTS = $DISPLAY_ORDER; //All aspects
	//$DEFAULT_ASPECTS = array(d1_DATE, d1_HOUR, d1_TEMP, d1_WIND, d1_WIND_DIR, d1_GUST, d1_RAIN, d1_CLOUDS, d1_HUMIDITY, d1_FOG, d1_RAIN_CHNC, d1_THUNDER, d1_FRZ_RAIN, d1_SLEET, d1_SNOW);
	$DEFAULT_ASPECTS = array(d1_DATE, d1_HOUR, d1_TEMP, d1_WIND, d1_WIND_DIR, d1_GUST, d1_RAIN, d1_CLOUDS, d1_HUMIDITY, d1_FOG);



	//For the extracted Data. $DATA[0][n] values are headers/labels, and should correlate to $DISPLAY_ORDER above.
	//The two set of rows (from original source html <table>), of 24 hour data columns, will be concatenated to a single 48 hour data set.
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
	$DATA[0][d1_RAIN_CHNC]	= "Rain";			//Rain, Thunder, Snow, Freezing Rain, Sleet, & Fog
	$DATA[0][d1_THUNDER]	= "Thndr";			//values are "Lkly", "Schc", etc...
	$DATA[0][d1_SNOW]		= "Snow";
	$DATA[0][d1_FRZ_RAIN]	= "Frzng Rain";
	$DATA[0][d1_SLEET]		= "Sleet";
	$DATA[0][d1_FOG]		= "Fog";

	ksort($DATA[0]); //key sort (maintains key=>value paring) (only matters on array dumps or if use foreach)




	//Occasionally, a "Radar data are unavailable" image is served.
	//May add check for this at some point so it can be ignored.
	//Maybe. Possibly. Sometime. But no promises.
	//MD5 hash of the "...unavailable" image.
	//define('RADAR_UNAVAILABLE_MD5',  "b7578f7110b249e61a3635d5b1226d87");


	//1 for actual sample radar images, 2 for simple graphics.
	//If change either $..._SAMPLE below, change cooresponding value in Get_GET();
	$SAMPLE_SET = 1;
	$RAW_HTML_SAMPLES[1] = "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.1.html";
	$RAW_HTML_SAMPLES[2] = "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.2.html";
	$RAW_HTML_SAMPLES[3] = "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.3.html";
	$RAW_HTML_SAMPLES[4] = "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.4.html";
	$RADAR_URL_BASE_SAMPLE =     "/Weather/weather.gov/samples/";

	//##### ##################################################################################################
	//##### For trouble-shooting...
	//##### X (uppercase) can = a, b, c, d, or e.
	//#####
	if (isset($_GET['X']) && ($_GET['X'] >= 'a') && ($_GET['X'] < 'e')) {
		$RAW_HTML_SAMPLES[1] = "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.1".$_GET['X'].".html";
	}
	//##### ##################################################################################################




	//Radar image URL's:  http://radar.weather.gov/lite/N0R/IWA_?.png    ? = 0 thru 7
	//Used in Radar_Loop_js_functions()
	define('RADAR_SITE_DEF', "IWA"); //Default radar site (Central AZ)
	define('RADAR_RANGE_STD', "N0R/"); // N<ZERO>R is base range.     "Views out to 124 nmi" (~143 miles).
	define('RADAR_RANGE_EXT', "N0Z/"); // N<ZERO>Z is extended range. "Views out to 248 nmi" (~286 miles).
	define('RADAR_RANGE_DEF', RADAR_RANGE_STD);
	
	define('RADAR_URL_BASE', "http://radar.weather.gov/lite/");
	
	define('RADAR_URL_BASE_DEF', RADAR_URL_BASE.RADAR_RANGE_DEF.RADAR_SITE_DEF."_");
	define('RADAR_IMG_EXT', ".png");

	//##### http://radar.weather.gov/ridge/Conus/RadarImg/Conus_20160325_0048_N0Ronly.gif
	define('RADAR_URL_SE', "http://radar.weather.gov/ridge/Conus/RadarImg/southeast.gif");
	define('RADAR_URL_US',"http://radar.weather.gov/ridge/Conus/RadarImg/latest.gif");
	define('RADAR_URL_US_SMALL',"http://radar.weather.gov/ridge/Conus/RadarImg/latest_Small.gif");


	//Values, in *hours*, for the "Dispaly [x] days" drop list option. Converted to number of days for listing.
	define('HOURS_MIN',  12);
	define('HOURS_MAX', 155); //Max available from weather.gov
	define('HOURS_DEF',  24);
	define('HOURS_INC',  12); //INCrement from _MIN to _MAX


	//Time between radar images (1000 = 1 second).
	define('FRAME_RATE_MIN',  100);
	define('FRAME_RATE_MAX', 1000);
	define('FRAME_RATE_DEF',  300);
	define('FRAME_RATE_INC',  100);


	//Time to pause between radar loops (1000 = 1 second).
	define('ROTATE_PAUSE_MIN',  200);
	define('ROTATE_PAUSE_MAX', 2000);
	define('ROTATE_PAUSE_DEF',  800);
	define('ROTATE_PAUSE_INC',  200);


	//Number of times to loop, then stop.
	define('ROTATE_LOOPS_MIN',  1);
	define('ROTATE_LOOPS_MAX', 99);
	define('ROTATE_LOOPS_DEF', 10);
	define('ROTATE_LOOPS_INC',  1);
	
	//Default value to hightlight rain when chance of >= this amount.
	$RAIN_THRESHOLD = 25;
	

	//These must coorespond to the number & order of "if/then"'s in Set_Themes().
 	$THEME_LIST = ["Default", "Dark", "Dark 2", "Dark 3"];
	$THEME_COUNT = count($THEME_LIST);

}//end Init() { //*************************************************************/




function hsc($input) {//*******************************************************/
	$enc = mb_detect_encoding($input); //It should always be UTF-8 (or ASCII), but, just in case...
	if ($enc == 'ASCII') {$enc = 'UTF-8';} //htmlspecialchars() doesn't recognize "ASCII"
	return htmlspecialchars($input, ENT_QUOTES, $enc);
}//end hsc() //****************************************************************/





//******************************************************************************
//A custom "var_dump()"
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
	//Default parameters ($url, false, false) returns body only, no headers.

    $ch = curl_init();

	//May work faster with this when only need the headers.
	if ($headers && $nobody) { curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');	}

	//curl_setopt($ch, CURLOPT_VERBOSE, true); //for trouble-shooting only
	//curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, $headers);
	curl_setopt($ch, CURLOPT_NOBODY, $nobody);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	//##### curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
    curl_setopt($ch, CURLOPT_URL, $url);

    $html = curl_exec($ch);
    curl_close($ch);

    return $html;
} //end curl_get_contents() //*************************************************/





function Get_GET() {//*********************************************************/
	//Get & validate URL parameters
	global  $SHOW_LOCATIONS, $LOCATION_NAMES, $SELECTED_ASPECTS, $DEFAULT_ASPECTS, $DISPLAY_ORDER,
			$HOURS_TO_SHOW, $DISPLAY_H,  $RAIN_THRESHOLD,
			$SAMPLE_SET, $RAW_HTML_SAMPLES, $THEME, $THEME_LIST, $THEME_COUNT, $TEST_MODE,
			$SHOW_RADAR, $WRAP_MAP, $DONT_WRAP_MAP, $RADAR_VIEW,
			$SHOW_RADAR_OPTIONS, $FRAME_RATE, $ROTATE_PAUSE, $ROTATE_LOOPS,
			$SHOW_LOCATION_OPTIONS, $SHOW_WEATHER_OPTIONS, $SHOW_DISPLAY_OPTIONS;

	$_GET = array_change_key_case($_GET, CASE_UPPER);


	//TEST_MODE aliases
	//LEAVE CHECK FOR TEST MODE HERE! It's result is used below.
	if (isset($_GET["TEST"]) || isset($_GET["TEST_MODE"])) {$TEST_MODE = true; }
	else 												   {$TEST_MODE = false;}


	//Which sample set (1 or 2) of radar images to use
	//if (isset($_GET["SS"]))			{ $SAMPLE_SET = $_GET["SS"]; }
	//if (isset($_GET["SAMPLE_SET"])) { $SAMPLE_SET = $_GET["SAMPLE_SET"]; }
	//$RAW_HTML_SAMPLES = "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.p1.html";


	//"LOCATION_SEARCH" **********************
	if (isset($_GET["LOCATION_SEARCH"])) { $LOCATION_NAMES[0] = $_GET["LOCATION_SEARCH"]; }
	//Only keep ascii printable char's
	$LOCATION_NAMES[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', trim($LOCATION_NAMES[0]));
	//Get rid of some unlikely chars for location names that could cause problems
	$bad_chars = explode(' ', '! @ # $ % ^ & * ( ) _ < > ` ~  = + [ ] { } \\ | ; : \' " ? /');
	$LOCATION_NAMES[0] = str_replace($bad_chars ,'' ,$LOCATION_NAMES[0]); 


	//"SHOW_LOCATIONS" ***********************
	if (!isset($_GET["SHOW_LOCATIONS"])) { $SHOW_LOCATIONS[DEFAULT_LOCATION] = DEFAULT_LOCATION; }
	else 								 { $SHOW_LOCATIONS = $_GET["SHOW_LOCATIONS"]; }
	//make sure in valid range
	foreach($SHOW_LOCATIONS as $key => $location) {
		if     (!is_numeric($key) || !is_numeric($location))	   { unset($SHOW_LOCATIONS[$key]); }
		elseif (($location < 0) || ($location > (LOCATIONS - 1)) ) { unset($SHOW_LOCATIONS[$key]); }
		else													   { $SHOW_LOCATIONS[$key] *= 1;   } //make 'em integers
	}
	asort($SHOW_LOCATIONS); //Just in case they're not sent in order...  (asort - doesn't re-index the keys)


	//Weather "ASPEPCTS" *********************
	if (!isset($_GET["ASPECTS"])) { $SELECTED_ASPECTS = $DEFAULT_ASPECTS; } 			 //[1] is not sent, but always used.
	else 						  { $SELECTED_ASPECTS = $_GET["ASPECTS"]; $SELECTED_ASPECTS[1] = 1; } 

	//make sure in valid range
	foreach($SELECTED_ASPECTS as $key => $aspect) {
		if     (!is_numeric($key) || !is_numeric($aspect))	{ unset($SELECTED_ASPECTS[$key]); }
		elseif (!in_array($aspect, $DISPLAY_ORDER) )		{ unset($SELECTED_ASPECTS[$key]); }
		else   /* make 'em int's ---------------------> */	{ $SELECTED_ASPECTS[$key] *= 1;   }
	}
	sort($SELECTED_ASPECTS);
	$SELECTED_ASPECTS = array_values($SELECTED_ASPECTS);


	//"HOURS_TO_SHOW" ************************
	//Needed before Styles() or User_Options() are called
	if (!isset($_GET["HOURS_TO_SHOW"])) {
		//default if not selected
		$HOURS_TO_SHOW = HOURS_DEF;
	} else {
		$HOURS_TO_SHOW  = intval(trim($_GET['HOURS_TO_SHOW']));
		if 	   ($HOURS_TO_SHOW < HOURS_MIN) { $HOURS_TO_SHOW = HOURS_MIN; }
		elseif ($HOURS_TO_SHOW > HOURS_MAX) { $HOURS_TO_SHOW = HOURS_MAX; }
	}


	//VH *************************************
	$DISPLAY_H = FALSE;
	if (isset($_GET["VH"]) && ($_GET["VH"] == "H")) { $DISPLAY_H = TRUE; }


	//SHOW_RADAR *****************************
	if 	   (empty($_GET)) 					   { $SHOW_RADAR = TRUE; } //Default
	elseif (isset($_GET["SHOW_RADAR"])) 	   { $SHOW_RADAR = TRUE; }
	elseif ($TEST_MODE && (count($_GET) == 1)) { $SHOW_RADAR = TRUE; } //Default also in "manual"* test mode.
	else									   { $SHOW_RADAR = FALSE;}
	//*("manual" test mode: ?TEST_MODE added to URL, even if checkbox option not available)


	//"RAIN_THRESHOLD" Hightlight rain values when over this amount (%). The default value for $RAIN_THRESHOLD set in Init().
	if (isset($_GET["RAIN_THRESHOLD"])) {$RT = (int)trim($_GET["RAIN_THRESHOLD"]);} else {$RT = $RAIN_THRESHOLD;}
	if (($RT < 1) || ($RT > 99)) {$RT = $RAIN_THRESHOLD;}
	$RAIN_THRESHOLD = $RT;


	//"DONT_WRAP_MAP" ************************
	if (isset($_GET['DONT_WRAP_MAP'])) {$DONT_WRAP_MAP = TRUE;} else {$DONT_WRAP_MAP = FALSE;}


	//"RADAR_VIEW" / Zoom Level***************
	if (isset($_GET['RADAR_VIEW']) && ($_GET['RADAR_VIEW'] == "N0Z")) //N<ZERO>Z
		{$RADAR_VIEW = "N0Z";} //N<ZERO>Z  Extended range. "Views out to 248 nmi" (~286 miles).
	else
		{$RADAR_VIEW = "N0R";} //N<ZERO>R  Normal range.   "Views out to 124 nmi" (~143 miles).


	//"THEME" ********************************
	if (isset($_GET["THEME"])) {$THEME = (int)$_GET['THEME'];}
	if (($THEME < 0) || ($THEME > $THEME_COUNT)) {$THEME = 1;}


	//Show Location Options/Aspects? *********
	if (isset($_GET["SHOW_LOCATION_OPTIONS"]) && ($_GET["SHOW_LOCATION_OPTIONS"] == "true"))
		 {$SHOW_LOCATION_OPTIONS = "true";}
	else {$SHOW_LOCATION_OPTIONS = "false";}  //Default to value set in style sheet for #LOCATION_OPTIONS


	//Show Weather Options/Aspects? **********
	if (isset($_GET["SHOW_WEATHER_OPTIONS"]) && ($_GET["SHOW_WEATHER_OPTIONS"] == "true"))
		 {$SHOW_WEATHER_OPTIONS = "true";}
	else {$SHOW_WEATHER_OPTIONS = "false";}  //Default to value set in style sheet for #WEATHER_OPTIONS


	//Show Display  Options? *****************
	if (isset($_GET["SHOW_DISPLAY_OPTIONS"]) && ($_GET["SHOW_DISPLAY_OPTIONS"] == "true"))
		 {$SHOW_DISPLAY_OPTIONS = "true";}
	else {$SHOW_DISPLAY_OPTIONS = "false";}  //Default to value set in style sheet for #DISPLAY_OPTIONS


	//Radar options: $i=1 for AZ radar, $i=2 for user selected/custom location.
	for ($i = 1; $i < 3; $i++) {
		//Show Radar Options? ********************
 		if (isset($_GET["SHOW_RADAR_OPTIONS"][$i]) && ($_GET["SHOW_RADAR_OPTIONS"][$i] == "true"))
			 {$SHOW_RADAR_OPTIONS[$i] = "true";}
		else {$SHOW_RADAR_OPTIONS[$i] = "false";}
		
		//"FRAME_RATE" ***************************
		if (isset($_GET["FRAME_RATE"][$i]))   {$FRAME_RATE[$i]   = $_GET["FRAME_RATE"][$i];} else   {$FRAME_RATE[$i] = FRAME_RATE_DEF;}
		if (!is_numeric($FRAME_RATE[$i])   || ($FRAME_RATE[$i]   < FRAME_RATE_MIN)   || ($FRAME_RATE[$i]   > FRAME_RATE_MAX))   {$FRAME_RATE[$i]   = FRAME_RATE_DEF;}
		
		//"ROTATE_PAUSE" *************************
		if (isset($_GET["ROTATE_PAUSE"][$i])) {$ROTATE_PAUSE[$i] = $_GET["ROTATE_PAUSE"][$i];} else {$ROTATE_PAUSE[$i] = ROTATE_PAUSE_DEF;}
		if (!is_numeric($ROTATE_PAUSE[$i]) || ($ROTATE_PAUSE[$i] < ROTATE_PAUSE_MIN) || ($ROTATE_PAUSE[$i] > ROTATE_PAUSE_MAX)) {$ROTATE_PAUSE[$i] = ROTATE_PAUSE_DEF;}
		
		//"ROTATE_LOOPS" *************************
		if (isset($_GET["ROTATE_LOOPS"][$i])) {$ROTATE_LOOPS[$i] = $_GET["ROTATE_LOOPS"][$i];} else {$ROTATE_LOOPS[$i] = ROTATE_LOOPS_DEF;}
		if (!is_numeric($ROTATE_LOOPS[$i]) || ($ROTATE_LOOPS[$i] < ROTATE_LOOPS_MIN) || ($ROTATE_LOOPS[$i] > ROTATE_LOOPS_MAX)) {$ROTATE_LOOPS[$i] = ROTATE_LOOPS_DEF;}
	}

}//end Get_GET() //************************************************************/





function Search_for_custom_location() {//**************************************/
	global $URL_BASE, $URL_OPTIONS, $DATA_URLS, $LOCATION_NAMES, $CUSTOM_RADAR_SITE;

	$Search_URL = BASE_SEARCH_URL.rawurlencode($LOCATION_NAMES[0]);
	$HEADERS = trim(curl_get_contents($Search_URL, 1, 1))."\n"; //The 1, 1 parameters returns headers only.
	$HEADERS = str_replace("\r\n", "\n", $HEADERS); //Normalize EOL
	$HEADERS = str_replace("\r"  , "\n", $HEADERS); //Normalize EOL

	//$found =  "Location: ..." line from the $HEADERS  (should only find 1). Contains URL for the 302 redirect.
	$search_for = '/Location: .*$/m';
	$location_found = preg_match($search_for, $HEADERS ,$found); //$found is always an array, even if only has one value.

	//Searches for some locations, such as in Alaska, do not return URLs/Headers with
	//lat & lon (or textField1 & 2) ?query values.
	//However, like with the radar id (rid), a link (<a href>) on the returned page does.
	if ($location_found) {
		
		//Get intermediary forecast page.
		$HEADERS_Location = substr($found[0], 10); //Drop the "Location: " prefix
		$forecast_html = trim(curl_get_contents($HEADERS_Location))."\n";
		
		//Get links (<a>'s), to search for final url (to Tabular Forecast) & rid
		$DOM = new DOMDocument;
		@$DOM -> loadHTML($forecast_html);
		$links = $DOM -> getElementsByTagName('a'); //get links 
		
		$tab_forecast_found = false;
		$rid_found			= false;
		$x = 0;
		
		foreach ($links as $node) {
			
			//Search for final url...
			if (($node->nodeValue) === "Tabular Forecast") {
				//there is only one such link, and it starts after the domain (http:. . .weather.gov/)
				$DATA_URLS[0] = $URL_BASE.$node->getAttribute('href').$URL_OPTIONS;
				$tab_forecast_found = true;
			}
			//search for rid=??? & extract ???.  There is only one "rid=" in the page.
			else if (($node->nodeValue) === "") { //Only check those <a>'s with no text, as it's around an <img>
				$query_string = parse_url(trim($node->getAttribute('href')), PHP_URL_QUERY);
				parse_str($query_string, $query_values);
				
				if (isset($query_values["rid"])) {
					$CUSTOM_RADAR_SITE = strtoupper($query_values["rid"]); //needs to be upper case for the img URL's
					$rid_found = true;
				}
			}
			
			if ($tab_forecast_found && $rid_found) {break;}
			$x++;
		}//end  foreach($links)
	}//end if($location_found)

	return $location_found;  //true, false, or 0 (zero).
}//end Search_for_custom_location() //*****************************************/





function Get_Weather_Pages($location){//***************************************/
	//get raw html page with weather data
	global $DATA_URLS, $LOCATION_NAMES, $TESTING_MSG, $TEST_MODE, $RAW_HTML_SAMPLES, $HOURS_TO_SHOW, $MESSAGES;

	// AheadHour can be 0 thru 107.    If > 107, weather.gov assumes 0.
	// Since each page has 48 hours of data, use AheadHour = 0, 48, 96, or 107.
	// On the "4th" page, with AheadHour=107, only the last 11 hours are new,
	// relative to "3rd" page (AheadHour=96).
	$AheadHour[1] = 0; $AheadHour[2] = 48; $AheadHour[3] = 96; $AheadHour[4] = 107;
	
	// $pages_to_get = 1 to 4, depending on...
	if 		($HOURS_TO_SHOW > 144) {$pages_to_get = 4;}
	else if	($HOURS_TO_SHOW >  96) {$pages_to_get = 3;}
	else if ($HOURS_TO_SHOW >  48) {$pages_to_get = 2;}
	else    /*----------------->*/ {$pages_to_get = 1;}

	$raw_html = array(1=>"");
	
	for ($page = 1; $page <= $pages_to_get; $page++) {
		if ($TEST_MODE) {
			$TESTING_MSG = "<span class=TESTING_MSG>SAMPLE DATA</span>\n";
			$raw_html[$page] = file_get_contents($RAW_HTML_SAMPLES[$page]);
			$data_url = $RAW_HTML_SAMPLES[$page];
		}
		else { //*** LIVE DATA ****/
			$TESTING_MSG = "";
			
			$data_url = $DATA_URLS[$location]."&AheadHour=".$AheadHour[$page];
			$raw_html[$page] = curl_get_contents($data_url);
			//$raw_html[$page] = file_get_contents($data_url);  //stopped working sometime 2015-03-31
		}
		
		$MESSAGES[$location] = ""; //used for error (etc) messages
		$location_error = false;
		
		//As of 2016-03-28 (at least), for some locations (such as in AK), a request for the Tabular Forecast
		//page returns the page with no data <table>, and the msg: "An error occurred while processing your request."
		if (strpos($raw_html[$page],"An error occurred") !== false) {
			$location_error = true;
			$MESSAGES[$location] .= 'Location found: <b>'.hsc($LOCATION_NAMES[$location]).'</b><br>'.
								    'However, <b>"An error occurred while processing your request."</b><br><br>'.
								    'from the Tabular Forecast page:<br><br>'.hsc($data_url);
			$raw_html[$page] = false;
		}
		else if (($raw_html[$page] === false) || (strlen($raw_html[$page]) == 0)) { 
			$MESSAGES[$location] .= 'Nothing returned for: <b>"'.hsc($LOCATION_NAMES[$location]).'":</b> '.hsc($data_url)."<br>";
		} else {
			// For trouble shooting only
			// $MESSAGES[$location] .= "<hr>Data recieved from (".hsc($LOCATION_NAMES[$location])."): ".hsc($data_url)."<br>";
		}
	}

	return $raw_html;
}//end  Get_Weather_Pages() //*************************************************/





function Extract_Weather_Data($raw_html) {//***********************************/
	//Extract desired data from table(s) and save in $DATA array().
	global $DESIRED, $DATA, $HOURS_TO_SHOW;
	
	$DOM = new DOMDocument;		//$DOM -> preserveWhiteSpace = false;

	// 2016-02-17
	// Notes for "@$DOM -> loadHTML($raw_html[$page]);" line, in first for loop below.
	// The @ suppresses errors that loadHTML() returns while parsing the $raw_html file(s).
	// The errors don't matter here, and only clog up Apache's error.log file.

 	$pages = count($raw_html); 

	$hour_offset = 0; //from the current hour
	
	//cycle thru the WEATHER_TABLE in each html page.
	for ($page = 1; $page <= $pages; $page++) {
		
		$pageset = ($page - 1) * 48;
		
		//Get <tr>'s of data from the weather <table>
		@$DOM -> loadHTML($raw_html[$page]);
		$WEATHER_TABLE = $DOM -> getElementsByTagName('table') -> item(WEATHER_TABLE);
		$ROWS  = $WEATHER_TABLE	-> getElementsByTagName('tr');
		
		//cycle thru each set of rows (2 sets of ASPECTS_TOTAL rows)
		//On 4th page, only last 11 hours of data in the 2nd rowset are new/a continuation of page 3.
		if ($page < 4) {$rs = 0;} else {$rs = d2_OFFSET;}
		for ($rowset = $rs; $rowset <= d2_OFFSET; $rowset += d2_OFFSET) {
			
			//cycling thru each row in the rowset
			$first_row = FIRST_ROW	   + $rowset;
			$last_row  = ASPECTS_TOTAL + $rowset;
			for ($row = $first_row; $row <= $last_row; $row++) {
				
				if (!in_array($row, $DESIRED)) {continue;}
				
				//get row of data: date, hour, temp, etc...
				$cells = $ROWS -> item($row) -> getElementsByTagName('td');
				
				//On 4th page, only last 11 hours of data are new/a continuation of page 3.
				if ($page < 4) {$h = 1;} else {$h = 14;}
				
				//Get $DATA from $cells
				for ($hour = $h; $hour <= 24; $hour++) {
					$DATA[$hour + $hour_offset][$row - $rowset] = trim($cells -> item($hour) -> textContent);
				}
			}// end for($row)
			
			$hour_offset += 24; //24 hours in each $rowset (except for page 4...).
		}//end for ($rowset)
	}

	$DATA = array_values($DATA); //re-index...

	//After the first date of a new day, remove redundant dates.
	for ($data_index = 2; $data_index <= $HOURS_TO_SHOW; $data_index++) {
		if ( ($DATA[$data_index][d1_DATE] != "") && ($DATA[$data_index][d1_HOUR] > 0) ) {
			$DATA[$data_index][d1_DATE] = "";
		}
	}
}//end Extract_Weather_Data() //***********************************************/





function Display_Weather_V($location) {//**************************************/
	//Display data in new Vertical table, each row one hour.
	global $COLOR,  $DATA, $LOCATION_NAMES, $TESTING_MSG, $RAIN_THRESHOLD, $HOURS_TO_SHOW, $DISPLAY_ORDER, $SELECTED_ASPECTS, $MESSAGES;

	$columns = count($SELECTED_ASPECTS) - 1;
	
	echo "<table class=data>\n";
		
		echo "<tr>\n<td colspan=".$columns.">"; 
		echo "<h2>".hsc($LOCATION_NAMES[$location])."<br>".$TESTING_MSG."</h2>";
		echo "</td>\n</tr>\n";
		
		//$data_index is from 0 (current/first hour of data) to $HOURS_TO_SHOW
		for ($data_index = 0; $data_index <= $HOURS_TO_SHOW; $data_index++) {
			
			//Highlight Header Row (labels)
			if ($data_index == 0) {$hdr = "hdr";} else {$hdr = "";}
			
			//If a (new day) or (start of data), add row with day, date...
			if (($DATA[$data_index][d1_HOUR] === "00") || ($data_index == 1)) {
				$day_of_week = date('D', strtotime(date("Y")."/".$DATA[$data_index][d1_DATE]));
				echo "<tr class=newday><th colspan=".$columns.">$day_of_week, ";
				echo  hsc($DATA[$data_index][$DISPLAY_ORDER[0]])."</th></tr>\n";
			}
			
			//get total number of weather aspects 	//##### selected to display
			$aspects = count($DISPLAY_ORDER);
			
			//Show selected data...
			echo "<tr>";
			for ($aspect=1; $aspect < $aspects; $aspect++) {
				if (!in_array($DISPLAY_ORDER[$aspect], $SELECTED_ASPECTS) ) { continue; }
				
				if ($aspect < 2) {$td = "th";} else {$td = "td";} //header or data?
				
				$aspect_class = ""; //used to adjust css for specific columns and/or weather data
				
				//Highlight rain% value if >= specified value.
				if (($DISPLAY_ORDER[$aspect] == d1_RAIN) && ($data_index > 0) && ($DATA[$data_index][d1_RAIN] >= $RAIN_THRESHOLD)) {
					$aspect_class = "rain";
				}
				
				//Adjust css for a couple of columns
				if (($DISPLAY_ORDER[$aspect] == d1_WIND_DIR) && ($data_index >  0)) { $aspect_class = "wind_dir";}
				if (($DISPLAY_ORDER[$aspect] == d1_FOG)) {$aspect_class = " fog";}
				
				//If both are blank, don't bother adding class='' to output.
				$classes = trim("$hdr $aspect_class"); //Trimming whitespace...
				if (($hdr) || ($aspect_class)) {$classes = " class='$classes'";}
				
				//At 12 noon, change "12" to "noon"
				if (($DISPLAY_ORDER[$aspect] == d1_HOUR) && ($DATA[$data_index][$DISPLAY_ORDER[$aspect]] == "12")) {
					$DATA[$data_index][$DISPLAY_ORDER[$aspect]] = "Noon";
				}
				
				echo "<$td$classes>".hsc($DATA[$data_index][$DISPLAY_ORDER[$aspect]])."</$td>";
			}//end for($aspect)
			echo "</tr>\n";
		}//end for($data_index)
	echo "</table>\n";
}//end Display_Weather_V() //**************************************************/





function Display_Weather_H($location) {//**************************************/
	//Display data in new Horizontal table, each column one hour.
	global $COLOR, $DATA, $LOCATION_NAMES, $TESTING_MSG, $RAIN_THRESHOLD, $HOURS_TO_SHOW, $DISPLAY_ORDER, $SELECTED_ASPECTS, $MESSAGES;
		
	echo "<table class=data>\n";
		
		$colspan = $HOURS_TO_SHOW + 1;
		echo "<tr><td colspan=$colspan>\n";
		echo "<h2 class='location_name'>".hsc($LOCATION_NAMES[$location])." &nbsp; ".$TESTING_MSG."</h2>\n";
		echo "</td></tr>\n";
		
		for ($tr = 0; $tr < WASPECTS; $tr++) {
			
			//Highlight date & time rows. (First two rows.)
			if ($tr < 2) {$hdr = "hdr";} else {$hdr = "";}
			
			//Only show (Date, Time), & selected aspects
			if (!(($tr < 2) || in_array($DISPLAY_ORDER[$tr], $SELECTED_ASPECTS))) {continue;}
			
			echo "<tr>\n";
			for ($data_index = 0; $data_index <= $HOURS_TO_SHOW; $data_index++) {
				
				//Add day of week just past date, unless, at beginning, with two dates next to each other.
				$day_of_week = "";
				if ( ($tr === 0) && ($data_index > 1) ) { 
					if (($DATA[$data_index-1][d1_DATE] != "") && ($DATA[$data_index][d1_HOUR] > 0) ) {
						$day_of_week = date('D', strtotime(date("Y")."/".$DATA[$data_index-1][d1_DATE]));
					}
				}
				
				//Header/data labels? (Date, Hour, Temp °f...
				if ($data_index == 0) {$td = "th";} else {$td = "td";}
				
				//Highlight rain% value if over specified value.
				$rain = "";
				if ( ($DATA[$data_index][d1_RAIN] >= $RAIN_THRESHOLD) && 
					 ($DISPLAY_ORDER[$tr]		  == d1_RAIN) && 
					 ($data_index				  > 0) )
				{ $rain = "rain"; }
				
				//Highlight start of a new day... (bolds line between day columns)
				$newday = "";
				if (($DATA[$data_index][d1_HOUR] === "00") || ($data_index == 1)) { $newday = "newday";}
				
				//If all are blank, don't bother adding class='' to output.
				$classes = trim("$hdr $rain $newday"); //Trimming whitespace...
				if ($hdr || $rain || $newday) {$classes = " class='$classes'";}
				
				//Finally, ouput the weather info.
				echo "<$td${classes}>".hsc($DATA[$data_index][$DISPLAY_ORDER[$tr]])."$day_of_week</$td>\n";
			}//end for($data_index)
			echo "</tr>\n";
		}//end for($tr)
		
	echo "</table><br>\n";
}//end Display_Weather_H() //**************************************************/





function User_Options() {//****************************************************/
	global	$COLOR, $DATA, $LOCATION_NAMES, $RAIN_THRESHOLD, $HOURS_TO_SHOW, $SHOW_LOCATIONS, $TEST_MODE,
			$DISPLAY_H, $SELECTED_ASPECTS, $SHOW_RADAR, $DISPLAY_ORDER, $WRAP_MAP, $DONT_WRAP_MAP, $RADAR_VIEW,
			$SHOW_LOCATION_OPTIONS, $SHOW_WEATHER_OPTIONS;

	//First row: Locations **********************************
?>
		<div id=LOCATION_OPTIONS class=options_group>
			<span class=indent>Show weather for:</span>
<?php
	foreach ($LOCATION_NAMES as $key => $location_name) {
		$checked = "";
		if (isset($SHOW_LOCATIONS[$key])) { $checked = " checked"; }
		
		if ($key > 0) {
			//Defined choices: Tempe, AJ, etc...
			echo "<label>";
			echo "	<input type=checkbox name=SHOW_LOCATIONS[".hsc($key)."] value=".hsc($key)." $checked>";
			echo hsc($location_name)."";
			echo "</label>\n";
		}
		else if ($key == 0) {
			echo "<label id=location_search_ckbox_label>\n";
			echo "<input type=checkbox  name=SHOW_LOCATIONS[0] value=0 $checked>\n";
			echo "</label>";
			echo "<label id=location_search_label>";
			echo "<input type=text id=location_search name=LOCATION_SEARCH value='".hsc($location_name)."'>\n";
			echo "</label>\n";
		}
		else {
			echo "Line: ".__LINE__." AAAACCCKKK!!!";
		}
	}//end foreach $LOCATION_NAMES

	echo "</div>\n"; //end location_options



	//Second row: Weather aspects - Temp, Wind, etc *********
	echo "\n<div id=WEATHER_OPTIONS class=options_group>\n";

	echo "<span class=indent>Show:</span>\n";
	for ($aspect=1; $aspect < WASPECTS; $aspect++) {
		$checked = "";
		if (in_array($DISPLAY_ORDER[$aspect], $SELECTED_ASPECTS) )	{ $checked = " checked"; }
		echo "<label><input type=checkbox name=ASPECTS[$DISPLAY_ORDER[$aspect]] ";
		echo "value=".hsc($DISPLAY_ORDER[$aspect])."$checked>";
		echo "<span class=label>".hsc($DATA[0][$DISPLAY_ORDER[$aspect]])."</span>";
		echo "</label>\n";
	}
	echo "</div>";



	//Third row: display options ****************************
	echo "\n<div id=DISPLAY_OPTIONS class=options_group>\n";


	//Hours to display (12, 24, 36, 48, etc...)
	$hours_options = "";
	for ($option = HOURS_MIN; $option <= HOURS_MAX; $option += HOURS_INC) {
		if ($HOURS_TO_SHOW == $option){ $selected = " selected"; } else {$selected = "";}
		$hours_options .= "<option value=$option$selected>".round($option/24,1)."</option>\n";
	}
	//in case HOURS_MIN + HOURS_INC... is not an even multiple of HOURS_MAX
	$option -= HOURS_INC; // revert to last good value
	if ($option < HOURS_MAX) {
		if ($HOURS_TO_SHOW == HOURS_MAX){ $selected = " selected"; } else {$selected = "";}
		$hours_options .= "<option value=".HOURS_MAX."$selected>".round(HOURS_MAX/24,1)."</option>\n";
	}
	echo "<label>Display &nbsp;<select name=HOURS_TO_SHOW>\n";
		echo $hours_options;
	echo"</select> days</label>\n";



	//Display mode: Vertical or Horizontal
	$selected = "";
	if ($DISPLAY_H) { $selected = " selected"; }
	echo "\n<label> &nbsp; <select name=VH id=VH>\n";
		echo "<option value=V>Vertically</option>\n";  //default selection
		echo "<option value=H$selected>Horizontally</option>\n";
	echo"</select> &nbsp;</label>\n";



	//Rain Threshold: highlight rain values at this point
	echo "\n<label>Highlight rain at "; 
	echo "<input type=text id=rain_threshold name=RAIN_THRESHOLD maxlength=2 value=".$RAIN_THRESHOLD.">";
	echo "%</label>\n";



	//Show Radar option
	$checked = "";
	if ($SHOW_RADAR) { $checked = " checked"; }
	echo "<label id=show_radar_label><input type=checkbox name=SHOW_RADAR value=true$checked>Radar Map</label>\n\n";


	if ($SHOW_RADAR) {
		//Don't wrap map, even if normally it would due to browser window width
		$checked = "";
		if ($DONT_WRAP_MAP) { $checked = " checked"; }
		echo "<label><input type=checkbox name=DONT_WRAP_MAP value=true$checked>Don't wrap map</label>\n\n";
		
		
		//Radar "zoom" level: central AZ or state view.
		$N0R_checked = ""; //N<ZERO>R   default  "Views out to 124 nmi" (~143 miles).
		$N0Z_checked = ""; //N<ZERO>Z            "Views out to 248 nmi" (~286 miles).
		if ($RADAR_VIEW == "N0Z") {$N0Z_checked = " checked"; } else {$N0R_checked = " checked";}
		echo "<span id=radar_view>Radar Range (radius):";
		echo 	"<label><input type=radio name=RADAR_VIEW value=N0R $N0R_checked>143 miles</label>\n";
		echo 	"<label><input type=radio name=RADAR_VIEW value=N0Z $N0Z_checked>286 miles</label>\n";
		echo "</span>\n";
	}


	/*** TEST MODE option ****./
	$checked = "";
	if ($TEST_MODE) {$checked = " checked";}
	echo "\n<label id=test_mode>";
	echo "<input type=checkbox name=TEST_MODE value=true$checked>Test Mode</label>\n";
	/*************************/


	echo "</div>"; //End Third Row
}//end User_Options() //*******************************************************/





function Show_Radar($i=1) { //***************************************************/
	// $i is 1 or 2, the "instance" of which radar to show. 1 is the default radar, 2 is custom site.
	global $COLOR, $SELECTED_ASPECTS, $SHOW_LOCATIONS, $DISPLAY_H, $HOURS_TO_SHOW, $RADAR_VIEW, $WRAP_MAP, $IMG_CNT,
	       $FRAME_RATE, $ROTATE_PAUSE, $ROTATE_LOOPS, $CUSTOM_RADAR_SITE, $SHOW_RADAR_OPTIONS, $LOCATION_FOUND;


	if ($i == 2) {$default_img = RADAR_URL_BASE.$RADAR_VIEW."/".$CUSTOM_RADAR_SITE."_0".RADAR_IMG_EXT;}
	else 		 {$default_img = RADAR_URL_BASE.$RADAR_VIEW."/".RADAR_SITE_DEF."_0".RADAR_IMG_EXT;}

	$frame_rate_options = "";
	 for ($x=FRAME_RATE_MIN; $x <= FRAME_RATE_MAX; $x+=FRAME_RATE_INC) {
		if ($x == $FRAME_RATE[$i]) {$selected = " selected";} else {$selected ="";}
		$frame_rate_options .= "<option value=$x$selected>$x</option>\n";
	}

	$rotate_pause_options = "";
	for ($x=ROTATE_PAUSE_MIN; $x <= ROTATE_PAUSE_MAX; $x+=ROTATE_PAUSE_INC) {
		if ($x == $ROTATE_PAUSE[$i]) { $selected = " selected"; }else{ $selected =""; }
		$rotate_pause_options .= "<option value=$x$selected>$x</option>\n";
	}

	$rotate_loops_options = "";
	for ($x=ROTATE_LOOPS_MIN; $x <= ROTATE_LOOPS_MAX; $x+=ROTATE_LOOPS_INC) {
		if ($x == $ROTATE_LOOPS[$i]) { $selected = " selected"; }else{ $selected =""; }
		$rotate_loops_options .= "<option value=$x$selected>$x</option>\n";
	}

	$imgbar_slots = "\n";
	$tabindex = -1;
	for($x = ($IMG_CNT - 1); $x >= 0; $x--) {
		$imgbar_slots .= "<td id=slot_{$x}_{$i}>$x</td>\n";
	}


	//show default radar & controls
?>
	<div id=radar<?= $i ?> class="radar_div">
		
		<img src="<?= $default_img ?>" id="ROTATING_PIC_<?= $i ?>" alt="Radar Image - Forecast Area"><br>
		
		<div class=radar_controls>
			<button type=button id=START_STOP_<?= $i ?> class=start_stop></button>&nbsp;
			
			<table id=IMGBAR_<?= $i ?> class=imgbar><tr><?= $imgbar_slots ?></tr></table>
			
			<button type=button id=SHOW_RADAR_OPTS_<?= $i ?> class=radar_opts_btn>Opt</button>
			<input type=hidden id=SHOW_RADAR_OPTIONS_<?= $i ?> name=SHOW_RADAR_OPTIONS[<?= $i ?>] value=<?= $SHOW_RADAR_OPTIONS[$i] ?>>
		</div>
		
		<div id=RADAR_OPTIONS_<?= $i ?> class=radar_opts_div>
			
			<label>Frame Rate
				<select name=FRAME_RATE[<?= $i ?>] id=FRAME_RATE_<?= $i ?> class=radar_option_values>
					<?= $frame_rate_options ?>
				</select>ms
			</label>
			
			<label>Loop Pause
				<select name=ROTATE_PAUSE[<?= $i ?>] id=ROTATE_PAUSE_<?= $i ?> class=radar_option_values>
					<?= $rotate_pause_options ?>
				</select>ms
			</label>
			
			<label class=rotate_loops_label>Loops:
				<select name=ROTATE_LOOPS[<?= $i ?>] id=ROTATE_LOOPS_<?= $i ?> class=radar_option_values>
					<?= $rotate_loops_options ?>
				</select>
			</label>
			<span id=LOOP_<?= $i ?> class="fine_print loops">( )</span>
		</div>
	</div>

	<script>Radar[<?=$i ?>] = new Init_Radar(PIC_LIST[<?=$i ?>], "<?=$i ?>");</script>
<?php
}//end Show_Radar() //*********************************************************/





function Radar_Loop_js_functions() { //****************************************/
	global $COLOR;
?>

<script>
function Supports_SVG() { //**********************************************
	if (typeof SVGRect != "undefined") {return true}
	else 							   {return false}
}//end Supports_SVG() //**************************************************




function Imgbar_Click(Pics, newpic) { //**********************************

	//Stop rotation if running...
	if (Pics.running === true) {Start_Stop(Pics);}

	//Initial condition first imgbar click...
	if (Pics.current_loop == 0) {Pics.current_loop = 1;}

	//...and Change_Pic() to clicked/selected value (newpic).
	var prior_pic = Pics.current_pic;
	Pics.current_pic = newpic;
	Change_Pic(Pics, prior_pic, 1);

 	Pics.start_stop.focus();

}//end Imgbar_Click() //**************************************************




function Imgbar_Control(event, Pics) { //*********************************

	if (!event) {var event = window.event;} //for IE

	//Normalize the which/key/charcode...
	var event_code = event.which || event.keyCode || event.charCode;

	var tab = 9, enter = 13; space = 32, arrow_left = 37, arrow_right = 39;

	if (event_code == arrow_left)  {
		Rotate_Pics(Pics, 1, "reverse");
		event.preventDefault ? event.preventDefault() : (event.returnValue = false);  //prevent horizontal screen scrolling...
	}

	else if (event_code == arrow_right) {
		Rotate_Pics(Pics, 1);
		event.preventDefault ? event.preventDefault() : (event.returnValue = false);  //prevent horizontal screen scrolling...
	}
}//end Imgbar_Control() //************************************************




function Start_Stop(Pics) { //********************************************

	Pics.running = !Pics.running;

	if (!Pics.running) {  /** Stop **/
		clearInterval(Pics.loop_timer);
		Pics.start_stop.innerHTML = PLAY_BTN;
	}
	else {				  /** Start **/
		clearInterval(Pics.loop_timer); //Make sure timer not already running.
		
		//If started while on last pic of last loop, start new set of rotations...
		if ((Pics.current_pic == 0) && (Pics.current_loop >= Pics.loops.value)) {
			Pics.current_loop = 0;
		}
		
		Pics.start_stop.innerHTML = PAUS_BTN; //STOP_BTN;
		Rotate_Pics(Pics);
		
		document.getElementById('START_STOP_' + Pics.instance).focus();
	}
} //end Start_Stop() //***************************************************




function Rotate_Pics(Pics, once, direction){ //***************************

	//Rotate from oldest to newest. So, rotation order is: 7 6 5 4 3 2 1 0
	//Pic 7 (.length - 1) is the oldest pic.  Pic 0 is the newest pic.

	var prior_pic = Pics.current_pic;
	var oldest = Pics.pic_list.length - 1;

	//Determine next image/Pics.current_pic.
	if (direction === "reverse") {
		//First, check if current (soon to be prior) pic was oldest pic.
		if (Pics.current_pic >= oldest) {Pics.current_pic = 0;}
		else 							{Pics.current_pic++;}
	} else {
		//First, check if current (soon to be prior) pic was newest pic.
		if (Pics.current_pic <= 0) {Pics.current_pic = oldest;}
		else 					   {Pics.current_pic--;}
	}

	Change_Pic(Pics, prior_pic, once);
	
	//Update/Increment .current_loop...
	if (Pics.current_loop == 0) {Pics.current_loop = 1;} //First img change after page load.
	else if ((Pics.current_pic == (Pics.pic_list.length - 1)) && (prior_pic === 0)) {Pics.current_loop++;} //##### Pics.running &&
	Pics.loop_displayed.innerHTML = "(" + Pics.current_loop + ")";

	if (once) {if (Pics.running) {Start_Stop(Pics)} return}

	//delay for setTimeout (time to next pic)
	if ((Pics.current_pic == 0) && (Pics.current_loop >= Pics.loops.value)) {
		//Stop if on last pic of last loop (remember, pic zero is the last pic in each rotation)...
		Start_Stop(Pics);
		return;
	}
	else if (Pics.current_pic == 0) { var delay = Pics.rotate_pause.value; }
	else 							{ var delay = Pics.frame_rate.value; }

	Pics.loop_timer = setTimeout(function (){Rotate_Pics(Pics)},delay);

}//end Rotate_Pics() //***************************************************




function Change_Pic(Pics, prior_pic, once){ //****************************

	//Display new image.
	Pics.rotating_pic.src = Pics.pic_list[Pics.current_pic];
	
	//Update imgbar.
	Pics.imgbar_slots[prior_pic].style.backgroundColor 		  = "";      //clear     imgbar spot of prior_pic.
	Pics.imgbar_slots[Pics.current_pic].style.backgroundColor = "<?= $COLOR['imgbar-slot'] ?>";  //highlight imgbar spot for current pic.

	Pics.loop_displayed.innerHTML = "(" + Pics.current_loop + ")";

}//end Change_Pic() //****************************************************




function Init_Radar(pic_list, instance) { //******************************
	//Various element id's are suffixed with _<instance>.

	var Pix 			= {};
	Pix.instance 		= instance;
	Pix.pic_list 		= [];
	Pix.pic_list 		= pic_list.slice(); //Make a copy of, not a reference to...
	Pix.current_pic 	= 0;    //Index for .pic_list[current pic].
	Pix.current_loop 	= 0;
	Pix.loop_timer 		= setTimeout(";", 1);
	Pix.running 		= false;
	Pix.rotating_pic 	= document.getElementById('ROTATING_PIC_' 	   + instance); //<img>
	Pix.loop_displayed 	= document.getElementById('LOOP_' 			   + instance); //<span>
	Pix.frame_rate 		= document.getElementById('FRAME_RATE_' 	   + instance); //Normal pause between each img.
	Pix.rotate_pause 	= document.getElementById('ROTATE_PAUSE_' 	   + instance); //A longer pause on pic 0 (normally).
	Pix.loops			= document.getElementById('ROTATE_LOOPS_' 	   + instance); //Number of times to loop, then stop.
	Pix.start_stop 		= document.getElementById('START_STOP_' 	   + instance); //<button>
	Pix.top_pic  		= document.getElementById('ROTATING_PIC_' 	   + instance); //Currently, same as .rotating_pic
	Pix.show_radar_opts = document.getElementById('SHOW_RADAR_OPTS_'   + instance); //<button> to show/hide radar options
	Pix.radar_options 	= document.getElementById('RADAR_OPTIONS_' 	   + instance); //<div> container for radar options
	Pix.show_radar_options	= document.getElementById('SHOW_RADAR_OPTIONS_'+ instance); //<input hidden> status of radar optons.  true/false
	Pix.imgbar			= document.getElementById('IMGBAR_' 		   + instance); //Not currently used, but maybe someday...

	Pix.rotating_pic.src 		  = Pix.pic_list[0];  //Load initial image
	Pix.start_stop.innerHTML      = PLAY_BTN;
	Pix.show_radar_opts.innerHTML = CTRL_ICO;

	/******** Radar imgbar control ********/
	Pix.top_pic.onclick 		= function(     ){Start_Stop(Pix); document.getElementById('START_STOP_' + instance).focus();}
	Pix.start_stop.onclick 		= function(     ){Start_Stop(Pix); Stop_Propagation(event)}
	Pix.start_stop.onkeydown 	= function(event){Imgbar_Control(event, Pix); Stop_Propagation(event)}
	Pix.show_radar_opts.onclick = function(     ){Show_Hide_Options(Pix.radar_options, Pix.show_radar_options, "visibility", "visible", "hidden")}

	Pix.imgbar_slots = []; //<td>'s
	for (var x=0; x < Pix.pic_list.length; x++) {Pix.imgbar_slots[x] = document.getElementById("slot_" + x  + "_" + instance);}
	Pix.imgbar_slots[Pix.current_pic].style.backgroundColor = "<?= $COLOR['imgbar-slot'] ?>"; //Inital condition.

	//Add events for imgbar click control
	for (x=0; x < Pix.pic_list.length; x++) {
		//Must use a self-invoking anonymous function (SIAF): it is invoked with the "(x)" at the end of it's line. 
		//The "x" from our for-loop is passed via the "(x)" argument to the SIAF's "xvalue" argument, which is then
		//used as an arg for Imgbar_Click(). If it is not done this way, only the final value of x from the for-loop 
		//get's passed to the event handler functions, not the value of x as the loop runs.
		//Because Javascript, that's why.
		
		( function(xvalue) { 
			Pix.imgbar_slots[xvalue].onclick = 
				function(event) {
					Imgbar_Click(Pix, xvalue);
					Stop_Propagation(event);
				}
			}
		)(x);
	}
	/****** End Radar imgbar control ******/

	return Pix;
}//end Init_Radar() //****************************************************
</script>
<?php
}//end Radar_Loop_js_functions() //********************************************/





function Init_Radar_URLs_etc_js() { //*****************************************/
	global $COLOR, $TEST_MODE, $RADAR_URL_BASE_SAMPLE, $RADAR_VIEW, $CUSTOM_RADAR_SITE, $IMG_CNT;
?>
<script>
//************************************************************************/
var PLAY_BTN  = '<svg class="play_btn" width="50px" height="21px" fill="<?= $COLOR['svg-btn'] ?>">';
	PLAY_BTN += '<polygon points="0,0  12.6,7.46  0,15.75" transform="translate(22,3)" /></svg>\n';


var PAUS_BTN  = '<svg class="paus_btn" width="50px" height="21px" fill="<?= $COLOR['svg-btn'] ?>">\n';
	PAUS_BTN += '	<g transform="translate(18,3.5)">\n';
    PAUS_BTN += '		<rect width="6" height="14" x="0"    y="0" rx="2" ry="2" />\n';
	PAUS_BTN += '		<rect width="6" height="14" x="9.25" y="0" rx="2" ry="2" />\n';
	PAUS_BTN += '	<g>';
	PAUS_BTN += '</svg>';


var STOP_BTN  = '<svg>\n';
    STOP_BTN += '	<rect width="14" height="14" x="19" y="3.5" rx="2" ry="2" fill="<?= $COLOR['svg-btn'] ?>" />\n';
	STOP_BTN += '</svg>\n';


var CTRL_ICO  = '<svg class=options_icon width="22px" height="20px" fill="<?= $COLOR['svg-btn'] ?>">\n';
	CTRL_ICO += '	<g transform="translate(1.5,2)">\n';
	CTRL_ICO += '		<rect width="2.5" height="18" x="0.5"  y="0" rx="1" ry="1" />\n';
	CTRL_ICO += '		<rect width="2.5" height="18" x="8.5"  y="0" rx="1" ry="1" />\n';
	CTRL_ICO += '		<rect width="2.5" height="18" x="16.5" y="0" rx="1" ry="1" />\n';
	CTRL_ICO += '		<circle cx="1.75"  cy="15.5" r="3" stroke="transparent"/>\n';
	CTRL_ICO += '		<circle cx="9.75"  cy="2.75" r="3" stroke="transparent"/>\n';
	CTRL_ICO += '		<circle cx="17.75" cy="11"   r="3" stroke="transparent"/>\n';
	//CTRL_ICO += '		<rect width="36" height="22" x="0" y="0" rx="3" ry="3" ';
	//CTRL_ICO += '		fill="transparent" fill-opacity="0.25" stroke="#444" class="icon_bg"/>';
	CTRL_ICO += '	</g>\n';
	CTRL_ICO += '</svg>\n';


//##### if (!Supports_SVG()) {
	PLAY_BTN = "Play &nbsp; &#x25Ba;";  //# >  &#x25B7; &#x25ba;
	PAUS_BTN = "Pause  <b>| |</b>";     //# <b>| |</b> &#9616;&#9616;  &#10073;&#10073; &#9612;&#9612;   &#9015;&#9015;
	STOP_BTN = "STOP";
	CTRL_ICO = "Opt";
//##### }
//************************************************************************/





//************************************************************************/
//URL's for the 8 most recent radar images available from weather.gov
//Load URL's into PIC_LIST[site][x]
//site = radar site (1=default, 2=user supplied),   x = image 0 thru 7
//image 0 is most recent, 7 is the oldest.

<?php
//*****************/
if ($TEST_MODE) { $radar_url_base_1 = $RADAR_URL_BASE_SAMPLE."/1/SAMPLE_";
				  $radar_url_base_2 = $RADAR_URL_BASE_SAMPLE."/2/SAMPLE_";
}
else            { $radar_url_base_1 = RADAR_URL_BASE.$RADAR_VIEW."/".RADAR_SITE_DEF."_";
				  $radar_url_base_2 = RADAR_URL_BASE.$RADAR_VIEW."/".$CUSTOM_RADAR_SITE."_";
}

$IMG_CNT = 8;  //##### this needs to be determined by the number of images loaded.
//*****************/
?>

var PIC_LIST	= [];
	PIC_LIST[1] = []; //Default radar site
	PIC_LIST[2] = []; //Radar site for user requested location.
var IMG_CNT		= <?= $IMG_CNT ?>;

for (var x = 0; x < IMG_CNT; x++) { PIC_LIST[1][x] = '<?= $radar_url_base_1 ?>' + x + '<?= RADAR_IMG_EXT ?>'; }

if ('<?= $CUSTOM_RADAR_SITE ?>' != "" ) {
	for (var x = 0; x < IMG_CNT; x++) { PIC_LIST[2][x] = '<?= $radar_url_base_2 ?>' + x + '<?= RADAR_IMG_EXT ?>';	}
}
//************************************************************************/




//Radar objects instantiated at end of Show_Radar().
//Radar[1] will be default (AZ), [2] will be user entered/custom site (if present).
var Radar = [];

</script>

<?php
}//end Init_Radar_URLs_etc_js() //*********************************************/





function Prevent_Some_Keys_js() { //*******************************************/
?>

<script>
//Called via onkeydown(). onkeypress returns different keyCode's, particularlly 39 for right arrow & quotes keys.
function Prevent_Some_Keys(event) {
	var key_code  = event.keyCode;

	/*********************./
	//Detect shifted: !=49   @=50 #=51  $=52  %=53  ^=54  &=55  *=56  (=57  )=48  _=173 <=188 >=190 
	//Detect either:  `=192  ==61 +=61  [=219 {=219 ]=221 }=221 \=220 |=220 ;=59  :=59  '=222 "=222 ?=191 /=191
	//       numpad: /=111 *=106 +=107 .=110
	/**********************/
	
	if (event.shiftKey && (
		(key_code === 49) || (key_code === 50) || (key_code ===  51) || (key_code ===  52) || 
		(key_code === 53) || (key_code === 54) || (key_code ===  55) || (key_code ===  56) || 
		(key_code === 57) || (key_code === 48) || (key_code === 173) || (key_code === 188) || (key_code === 190) ))
		{
		event.preventDefault ? event.preventDefault() : (event.returnValue = false);
		
	} else if ( /*shifted or unshifted*/
		(key_code === 192) || (key_code ===  61) || (key_code === 219) || (key_code === 220) || 
		(key_code === 221) || (key_code ===  59) || (key_code === 222) || (key_code === 191) ||
		(key_code === 106) || (key_code === 107) || (key_code === 110) || (key_code === 111) )
		{
		event.preventDefault ? event.preventDefault() : (event.returnValue = false);
	}
}//end Prevent_Some_Keys()
</script>
<?php
}//end Prevent_Some_Keys_js() //***********************************************/





function Time_Stamp_js() {//***************************************************/
?>

<script>
function Time_Stamp(write_return){ //************************************/
	//returns Day, yyyy-mm-dd, hh:mm:ss am/pm

	//older, simple timestamp:  m/d/yyy h:m:ssAM/PM
	//now = new Date(); return now.toLocaleString(); 

	//var DAYS = ["Sunday", "Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
	var DAYS = ["Sun", "Mon","Tues","Wed","Thur","Fri","Sat"];

	//pad single digits. ie: 1 becomes 01
	function pad(num){ if ( num < 10 ){ num = "0" + num; }; return num; }

	var RAW_TIME  = new Date();
	var YEAR  = RAW_TIME.getFullYear();
	var MONTH = pad(RAW_TIME.getMonth() + 1);
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

	if (write_return === "write") {
		document.write(TIMESTAMP);
	}else{
		return TIMESTAMP;
	}
}//end Time_Stamp() //****************************************************/
</script>

<?php
}//end Time_Stamp_js() //******************************************************/





function Header_crap() {//*****************************************************/
	header('Content-type: text/html; charset=UTF-8');
	echo "<!DOCTYPE html>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'."\n";
	echo '<link rel="shortcut icon" href="http://d/D.ico"/>'."\n";
	echo "<title>Weather</title>\n";
}//end Header() {//************************************************************/





function Styles() {//**********************************************************/
	global $COLOR, $HOURS_TO_SHOW, $DISPLAY_H, $DONT_WRAP_MAP,
		   $SHOW_RADAR_OPTIONS, $SHOW_LOCATION_OPTIONS, $SHOW_WEATHER_OPTIONS, $SHOW_DISPLAY_OPTIONS; 
?>

<style>
	body { background-color: <?= $COLOR['body-bg'] ?>; color: <?= $COLOR['body'] ?>; }
	
	*	{ font-family: arial; box-sizing: border-box; }
	div {padding: 0; margin: 0}
	pre	{ font-family: courier; margin: 0; }
	h2 	{ font-size: 1.5em; margin: 0; }
	img	{ vertical-align: top; }

	label {	display: inline-block; height: 24px; padding: 2px .5em 0 .5em; margin : 0 .2em 0 0; white-space: nowrap; }

	label:hover { background-color: <?= $COLOR['hover-bg'] ?> }

	[type="checkbox"] { display: inline-block; margin: 0 2px 0 0; }
	[type="text"]	  { height: 20px; position: relative; top: 2; padding: 0 0 0 0; margin: 0 0 0 0; 
						color			: <?= $COLOR['input-font'] ?>;
						border			: 1px solid <?= $COLOR['input-border'] ?>;
						background-color: <?= $COLOR['input-bg'] ?>;
					  }

	select { background-color: <?= $COLOR['input-bg'] ?>; color: <?= $COLOR['input-font'] ?>; border-color: <?= $COLOR['input-border'] ?>; }

	#location_search_ckbox_label { margin: 0; width: 194px; }
	#location_search_label { margin: 0 -160px 0 0; padding-left: 4px; padding-right: .5em; position: relative; left: -172px; }
	#location_search  { width: 160px; }
	#rain_threshold	  { width:1.4em; padding: 1px 0 0 2px; }
	#show_radar_label { margin-left: 1em; }
	#test_mode 		  { float: right; margin-right: 0 }
	.rotate_loops_label {margin-right: 0;}

	button				{ color: <?= $COLOR['button-font'] ?>; background-color: <?= $COLOR['button-bg'] ?>; border: 1px solid <?= $COLOR['button-border'] ?>; border-radius: 4px; height: 24px; margin: 0; }
	button:hover	 	{ color: <?= $COLOR['hover-font'] ?>;   background-color: <?= $COLOR['hover-bg'] ?>;  border-color: <?= $COLOR['hover-border'] ?>;  }
	button:focus	 	{ color: <?= $COLOR['focus-font'] ?>;   background-color: <?= $COLOR['focus-bg'] ?>;  border-color: <?= $COLOR['focus-border'] ?>;  }
	button:active	 	{ color: <?= $COLOR['active-font'] ?>;  background-color: <?= $COLOR['active-bg'] ?>; border-color: <?= $COLOR['active-border'] ?>; }
	button::-moz-focus-inner { border: 0; }

	#top 	  { display: table; border-collapse: collapse; width: 100%; margin-bottom: .4em; border: 1px solid rgb(63,131,245); }
	#top div  { display:table-cell; }
	#top_left { }

	/*class for various, but not all, show/hide buttons*/
	/* //##### .show_hide { display: inline-block; padding: 0; margin: 0; height: 18px; position: relative; top: -4px;}*/
	.show_hide {  padding: 0; margin: 0; height: 18px; font-size: 12px; }

	#SHOW_LOCATION_OPTS { }
	#SHOW_WEATHER_OPTS { XXXXXfloat: left;  } /** <button> **/

	#LOCATION_OPTIONS { display: none; } /** //##### {display: none/block} **/

	#WEATHER_OPTIONS  { display: none; }
	
	#DISPLAY_OPTIONS  { display: none; }

	.w_container { display: inline-block; vertical-align: top; }

	.options_group { border: 1px solid <?= $COLOR['options-border'] ?>; padding: 0; margin: 0 0 .4em 0; }

	.data		{ border: 1px solid <?= $COLOR['data-border'] ?>; border-collapse: collapse; display: inline-table; margin: 0 .5em .5em 0; vertical-align:top; }
	.data th, .data td {  border: 1px inset <?= $COLOR['data-grid'] ?>; font-size: <?= $COLOR['data-font-size'] ?>; text-align: center; vertical-align: top; padding: 0 .2em; }
	.data th	{}
	.data td	{ min-width: 2.5em; max-width: 3.7em; white-space: normal; } /*Default for V display.*/

	.newday		{border-top: 1px solid <?= $COLOR['borders'] ?>} /*rgb(63,131,245)*/

	.hdr		{ font-weight: bold; padding: 0 .3em;}
	.time		{}
	.temp		{}
	.wind_mph	{}
	.wind_dir	{ text-align: left; padding: 0 0 0 0.25em; }
	.rain		{ color: <?= $COLOR['rain'] ?>; font-weight: bold; }
	.clouds		{}
	.humidity	{}
	.fog		{ max-width: 8em; padding: 0 .5em;}

	.not_found	{ border: 2px solid <?= $COLOR['borders'] ?>; font-weight: bold; text-align: center; } /*rgb(63,131,245)*/

	.messages	{ border: 2px solid <?= $COLOR['borders'] ?>; border-collapse: collapse; display: inline-block; margin: 0 .5em .5em 0; width: 20em; }
	.messages_H	{ border: 2px solid <?= $COLOR['borders'] ?>; border-collapse: collapse; display: inline-block; margin: 0 .5em .5em 0; }
	
	.indent		{ margin: 0 .5em }
	
	#wrap_map	 	  { white-space: nowrap; }

	.submit			{ width: 9em; margin: 0 0 0 0; }
	#default_ops 	{ font-size: 70% }
	#reset			{ font-size: 70% }
	
	
	.submit_default { float: right; }

	#radar_view		  { margin-left: 1em; }
	#radar_view input { margin-left: .2em; margin-right: .1em; }

	.radar_div	   	 { position: relative; display: inline-block; border: solid 0px #444; text-align: right; margin-bottom: .5em; white-space: normal;}
	
	.radar_controls	 { padding: 3px 0 3px 0; border: 0px solid #444; margin: 0;}

	.start_stop 	 { width: 70px; padding: 0; margin: 0; float: left;  }
	.radar_opts_btn	 { width: 36px; display: inline-block; float: right; }

	.imgbar			 { display: inline-block; margin: 0 5px 0 0; font-size: 75%;
					   width: 472px; height: 23px; border-collapse: collapse; }
	.imgbar td		 { width: 59px;  height: 23px; border: 1px solid #444; color: #777; text-align: center;  padding: 0; cursor: default}
	.imgbar td:hover { background-color: <?= $COLOR['hover-bg'] ?> }

	.radar_opts_div  { display: inline-block; visibility: hidden; white-space: nowrap; font-size: 92%;}
	.radar_opts_div  { width: 513px;  padding: 0; margin: 0; border: solid 1px #444; text-align: left; }

	.loops	{ margin-right: 0; padding: 0; white-space: nowrap}

	#timestamp	 	{ margin: .2em 0 .2em 0; padding: 1px .3em 0 .2em; display: inline-block; border: 1px solid <?= $COLOR['borders'] ?>; }
	#timestamp_row  { margin-bottom: .3em; }

	.location_name	{ text-align: left; margin-left: 4em; }
	.fine_print  	{ font-size: 90%; color: <?= $COLOR['fine-print'] ?>; }
	.TESTING_MSG 	{ color: red; }

<?php
	if ($SHOW_RADAR_OPTIONS[1] == "true") { echo "#RADAR_OPTIONS_1 {visibility: visible}\n";}
	if ($SHOW_RADAR_OPTIONS[2] == "true") { echo "#RADAR_OPTIONS_2 {visibility: visible}\n";}
	if ($SHOW_LOCATION_OPTIONS == "true") { echo "#LOCATION_OPTIONS {display: block}\n";}
	if ($SHOW_WEATHER_OPTIONS  == "true") { echo "#WEATHER_OPTIONS  {display: block}\n";}
	if ($SHOW_DISPLAY_OPTIONS  == "true") { echo "#DISPLAY_OPTIONS  {display: block}\n";}

	if ($DONT_WRAP_MAP) {echo ".w_container {white-space: nowrap;}\n";}

	if ($DISPLAY_H) {
		echo "	.data th 		{min-width: 5.5em;}\n";
		echo "	.data td.newday	{border: 1px solid rgb(63,131,245); border-left: 2px solid rgb(10,80,200);} \n";
	}

	//Adjust left margin for location name if hours < HOURS_MIN, so it's not out of box (if it's a long name).
	if ($HOURS_TO_SHOW < HOURS_MIN) { echo ".location_name {margin-left: .2em;}\n";}

echo "</style>\n\n";
}//end Styles() ***************************************************************/





// "Main" *********************************************************************/

Init();
Get_GET(); //needed before Set_Theme(), Styles(), User_Options()
Set_Theme($THEME);
Header_crap();
Styles();
Time_Stamp_js();
Prevent_Some_Keys_js();


?>




<?php






if ($TEST_MODE) {echo "<script>document.write(window.innerWidth);</script>";} //##### 

echo "\n<form name=USER_OPTIONS method=get id=options_form>\n";

$theme_options = "";
foreach ($THEME_LIST as $key => $theme_name) {
	if ($THEME == $key) {$selected = " selected";} else {$selected = "";}
	$theme_options .= "<option value={$key}{$selected}>{$theme_name}</option>\n";
}

?>
	<div id=top>
		<div id=top_left>									<!--up &#9650;  down &#9660; right  &#9658;   up/down &#8661;-->
			<button type=button id=SHOW_LOCATION_OPTS class=show_hide>&#9660;&#9650;Locations</button>
			<button type=button id=SHOW_WEATHER_OPTS  class=show_hide>&#9660;&#9650;Weather Options</button>
			<button type=button id=SHOW_DISPLAY_OPTS  class=show_hide>&#9660;&#9650;Display Options</button>
			
			<input type=hidden  id=SHOW_LOCATION_OPTIONS name=SHOW_LOCATION_OPTIONS value=<?= $SHOW_LOCATION_OPTIONS ?>>
			<input type=hidden  id=SHOW_WEATHER_OPTIONS  name=SHOW_WEATHER_OPTIONS  value=<?= $SHOW_WEATHER_OPTIONS ?>>
			<input type=hidden  id=SHOW_DISPLAY_OPTIONS  name=SHOW_DISPLAY_OPTIONS  value=<?= $SHOW_DISPLAY_OPTIONS ?>>
			
			<label>Choose Theme: <select id=THEME name=THEME><?= $theme_options ?></select></label>
			
			<button type=submit id=submit1 class=submit style="float: right">Submit</button>
		</div>
	</div>
<?php


User_Options();


//Time Stamp, Data Source, Theme Options, Default, Reset, & Submit buttons


//##### if ($TEST_MODE) {$data_source =  "<span class=fine_print>(Weather data source:<span class=TESTING_MSG>".hsc($RAW_HTML_SAMPLES[1])."</span>)</span>";}
//##### else			{$data_source =  "";} //##### "<span class=fine_print>(Weather data source:www.weather.gov)</span>";}
$data_source = ""; //##### 

?>
<div id=timestamp_row>
	<span id=timestamp><script>Time_Stamp('write');</script></span>
	<?= $data_source ?>

	<span class=submit_default>
		<button type=button id=default_ops>Defaults Options</button>
		<button type=reset  id=reset      >Reset Current Options</button>
		<button type=submit id=submit2 class=submit>Submit</button>
	</span>
</div><!-- End timestamp_row -->
<?php


//Get & display weather for selected locations
echo "\n<div class=w_container id=weather_div>\n";
	echo "\n<div class=w_container id=locations>\n";

	$LOCATION_FOUND = false;	
	foreach($SHOW_LOCATIONS as $key => $location) { 
		
		if ($location == 0) { //"search for" user provided location (zip or city,ST)
			
			$LOCATION_FOUND = Search_for_custom_location();
			
			if (!$LOCATION_FOUND) {
				echo '<div class="data not_found">Not Found:<br>"'.hsc($LOCATION_NAMES[0]).'"</div>';
				unset($SHOW_LOCATIONS[0]);
				continue;
			}
		}
		
		$RAW_HTML = Get_Weather_Pages($location);
		
		if ($MESSAGES[$location] ==="") {	
			Extract_Weather_Data($RAW_HTML);
			
			if ($DISPLAY_H) { Display_Weather_H($location); }
			else			{ Display_Weather_V($location); }
		}
		else {
			if ($DISPLAY_H) { echo '<div class="messages_H">'.$MESSAGES[$location].'</div>';; }
			else			{ echo '<div class="messages">'.$MESSAGES[$location].'</div>';; }
		}
		
	}
	echo "</div>\n"; //end inner w_container around locations


	if ($SHOW_RADAR) {
		
		//javascript radar functions...
		Radar_Loop_js_functions();
		Init_Radar_URLs_etc_js();
		
		if ($LOCATION_FOUND) {Show_Radar(2);} //custom site...
		
		if ( !$LOCATION_FOUND ||
			( ($CUSTOM_RADAR_SITE != RADAR_SITE_DEF) && (count($SHOW_LOCATIONS) > 1) ) )
		{
			Show_Radar(1);  //default site (AZ)...
		}
		
		//US map...
		if ($TEST_MODE){
			echo '<a href="/weather/weather.gov/ridge/Conus/RadarImg/latest.gif" target=_blank>';
			echo '<img id=radar_us src="/weather/weather.gov/ridge/Conus/RadarImg/latest_Small.gif" alt="Test Mode Radar Image - US"></a>';
		}
		else {
			echo '<hr><a href="'.RADAR_URL_US.'" target=_blank>';
			echo '<img id=radar_us src="'.RADAR_URL_US_SMALL.'" alt="Radar Image - US"></a>';
		}
	}	
echo "</div>\n"; //end w_container
echo "</form>\n\n";



?>
<script>
function Stop_Propagation(event) { //*************************************
	event.cancelBubble = true;
	if (event.stopPropagation) {event.stopPropagation()}
}//end Stop_Propagation(event) { //***************************************




function Show_Hide_Options(element, state, d_or_v, show, hide) {  //******
	//element: element to show/hide
	//state  : <input hidden>  Preserves state via form/URL value 
	//d_or_v : "display:" or "visibility:"   style property.
	//show   : either ""        or "visible"       property value.
	//hide   : either "visible" or "hidden"           "       "

	//##### document.getElementById('TRACE_OUT').innerHTML += "in[" + element.style[d_or_v]  + ", " + state.value + ", " + show + ", " + hide  + "] "; 

	if ((typeof element.currentStyle)  !== 'undefined') { //IE
		var visibility = element.currentStyle[d_or_v];
	} else {
		var visibility = window.getComputedStyle(element).getPropertyValue(d_or_v);
	}

	if (state.value == "false") {
		element.style[d_or_v] = show;     //<div>
		state.value           = "true";	  //<input hidden>
	}
	else {
		element.style[d_or_v] = hide;     //<div>
		state.value 		  = "false";  //<input hidden>
	}

	//##### document.getElementById('TRACE_OUT').innerHTML += "out[" + element.style[d_or_v]  + ", " + state.value + "] :: ";

}//end Show_Hide_Options() //*********************************************




function Focus(event) { //************************************************
	//Simulate "hover" of various inputs when using keyboard/tabbing

	//Only process tab and mouse clicks. (For radio button  options, arrow keys also fire onclick events.)
	if ((event.keyCode != 9) && (event.type != "click")) {return}

	//Normalize a couple things...
	if (!event) 			   {var event = window.event;      } //for IE
	if (event.srcElement)      {var target = event.srcElement; } //for IE
	else 				       {var target = event.target;     }
	if (target.nodeType == 3)  {    target = target.parentNode;} //for Safari

	//Clear label bg for the previous checkbox...
	if ((ACTIVE.tagName == "INPUT") || (ACTIVE.tagName == "SELECT")) {
		ACTIVE.parentElement.style.backgroundColor = "";
	}

	//Set the label bg for the new checkbox...
	if ((target.tagName == "INPUT") || (target.tagName == "SELECT")) {
		target.parentElement.style.backgroundColor = "<?= $COLOR['focus-bg'] ?>";
	}	

	//Save for next tab/click
	ACTIVE = target;

}//end Focus() //*********************************************************




function pad_str(str, len, pad, left) { //********************************
	if (str === undefined) {str = "---";}
	if (pad === undefined) {pad = " ";}
	str = str + "";

	if (left) {for (var x=str.length; x < len; x++) {str = pad + str;}}
	else      {for (var x=str.length; x < len; x++) {str = str + pad;}}
	return str;
}//end pad_str() //*******************************************************


/******************************./
//##### ########################
? >
<style>
#TRACE_HDR {display: inline-block; width: 47em; height: 1.1em; font: 12px courier; border: 1px solid red; white-space: pre}
#TRACE_OUT {display: inline-block; width: 47em; height: 30em; white-space: pre;
			font: 12px courier; border: 1px solid red; }
</style>
<div style="display: inline-block">
	<div id=TRACE_HDR> .type     : event source       : .activeElement       : ACTIVE</div><br>
	<div id=TRACE_OUT></div>
</div>
< ?php
//##### ########################
/******************************/
/****************./
//##### ##########
	TRACE = document.getElementById('TRACE_OUT'); 				//##### TRACE.innerHTML.length
	TRACE = document.getElementById('TRACE_OUT'); 				//#####event.type
	//.type     : event source       : .activeElement       : ACTIVE
	TRACE.innerHTML += pad_str((event.type), 10) 				+ " : "
					+  pad_str("...", 18)  				 		+ " : "
					+  pad_str(document.activeElement.id, 20) 	+ " : "
					+  pad_str(ACTIVE.id, 20) 				  	+ " ;\n";

	xdiff = TRACE.innerHTML.length - 1760; //currently 22 lines at 80 bytes per line...
	if (TRACE.innerHTML.length > 1760) {TRACE.innerHTML = TRACE.innerHTML.substring(xdiff);}
	TRACE.scrollTop = TRACE.scrollHeight;
//##### ##########
/*****************/




document.onkeydown = function(event){Focus(event)}
document.onkeyup   = function(event){Focus(event)}
document.onclick   = function(event){Focus(event)}
document.getElementById('location_search').onkeydown = function(event){Prevent_Some_Keys(event)}
document.getElementById('submit2').focus();
document.getElementById('default_ops').onclick = function(){parent.location = location.pathname};



/********************/ 
Show_Location_Opts 	  		= document.getElementById('SHOW_LOCATION_OPTS');    //<button>
Show_Location_Options 		= document.getElementById('SHOW_LOCATION_OPTIONS'); //<input hidden> = true/false
Location_Options 	  		= document.getElementById('LOCATION_OPTIONS');	    //<div> to show/hide
Show_Location_Opts.onclick  = function(){Show_Hide_Options(Location_Options, Show_Location_Options, "display", "block", "none")}
/********************/


/********************/ 
Show_Weather_Opts 	 	  = document.getElementById('SHOW_WEATHER_OPTS');    //<button>
Show_Weather_Options 	  = document.getElementById('SHOW_WEATHER_OPTIONS'); //<input hidden> = true/false
Weather_Options 	 	  = document.getElementById('WEATHER_OPTIONS');	     //<div> to show/hide
Show_Weather_Opts.onclick = function(){Show_Hide_Options(Weather_Options, Show_Weather_Options, "display", "block", "none")}
/********************/ 



/********************/ 
Show_Display_Opts 	 	  = document.getElementById('SHOW_DISPLAY_OPTS');    //<button>
Show_Display_Options 	  = document.getElementById('SHOW_DISPLAY_OPTIONS'); //<input hidden> = true/false
Display_Options 	 	  = document.getElementById('DISPLAY_OPTIONS');	     //<div> to show/hide
Show_Display_Opts.onclick = function(){Show_Hide_Options(Display_Options, Show_Display_Options, "display", "block", "none")}
/********************/ 



//Element of, but Only if it's an <input> or <select>...
var ACTIVE = document.activeElement;

</script>
<?php
//
// end "Main" *****************************************************************/







################################################################################
if ($TEST_MODE) {
	//dump_array($var, $name="", $ECHO = 1, $PRE=1, $BRDR=1, $DSPLY=1, $VD=0, $LVL=0)
	
	dump_array($_GET			 ,'$_GET'			  ,1,1,1,2);
	dump_array($SHOW_LOCATIONS   ,'$SHOW_LOCATIONS'   ,1,1,1,2);
	dump_array($DEFAULT_ASPECTS  ,'$DEFAULT_$ASPECTS' ,1,1,1,2);
	dump_array($SELECTED_ASPECTS ,'$SELECTED_$ASPECTS',1,1,1,2);
	dump_array($LOCATION_NAMES   ,'$LOCATION_NAMES'   ,1,1,1,2);
	dump_array($DATA			 ,'$DATA'			  ,1,1,1,2);
	
	?>
	<div style="clear: both;"></div>
	<style>
	.XXXXX_body {background-color: #Fee}
	.radar_div   {border: 1px solid red}
	#weather_div {border: 1px solid blue}
	#locations   {border: 1px solid #0C0}
	</style>
	<?php
	//echo '<hr><pre>$RAW_HTML[1]: <br>'.hsc($RAW_HTML[1])."</pre>";  //##### 
	//echo '<hr><pre>$RAW_HTML[2]: <br>'.hsc($RAW_HTML[2])."</pre>";  //##### 
	//echo '<hr><pre>$RAW_HTML[3]: <br>'.hsc($RAW_HTML[3])."</pre>";  //##### 
	//echo '<hr><pre>$RAW_HTML[4]: <br>'.hsc($RAW_HTML[4])."</pre>";  //##### 
}
################################################################################


?>
<div id=preloads></div> <!--  //##### -->
<div style='clear: both; border: 2px outset gray; height: .5em; '>&nbsp;</div>
<?php



################################################################################
/*******************************************************************************
NOTES:

Rain  P.o.P.
--------------
SChc  11 - 23

Chc   25 - 54

Lkly  55 - 78

Ocnl  75 -




/******************************************************************************/