<?php  ob_start(); mb_internal_encoding('utf-8');
//Version 11.4

error_reporting(E_ALL & ~E_STRICT);     //(E_ALL &~ E_STRICT) for everything, 0 for none.
ini_set('display_errors', 'on');
ini_set('log_errors'    , 'off');
ini_set('error_log'     , $_SERVER['SCRIPT_FILENAME'].'.ERROR.log');



/*******************************************************************************
D-Weather

Copyright © 2015     https://github.com/Self-Evident

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to do
so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
/******************************************************************************/




/*******************************************************************************
D-Weather

Copyright © 2015     https://github.com/Self-Evident

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

   1. Redistributions of source code must retain the above copyright notice,
      this list of conditions and the following disclaimer.

   2. Redistributions in binary form must reproduce the above copyright notice,
      this list of conditions and the following disclaimer in the documentation
      and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
/******************************************************************************/








/*******************************************************************************
#Common URL sample
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





function Load_Themes() { //****************************************************/
	global $THEMES;


	$x = 0; 
	$THEMES['name'][$x] = "Default";
	$THEMES['styles'][$x] = <<< END_of_THEME

	/********** {$THEMES['name'][$x]} Theme *******************/
	html 				{ font-size 	  : 100%  }
	body 				{ color			  :}
	body 				{ background-color: white }
	caption				{ border-color    : rgb(10,80,200)  }
	label:hover 		{ background-color: rgb(177,214,230) } /*soft blue 1*/ /*faded red 1 #Fdd */
	.active				{ background-color: rgb(110,179,208) } /*soft blue 3*/ /*faded red 3 #Fbb*/
	input[type='text']  { color 		  : black }
	input[type='text']  { border-color    : rgb(127,157,187) }
	input[type='text']  { background-color: white }
	select 				{ color			  : black }
	select 				{ background-color: white }
	select 				{ border-color 	  : rgb(127,157,187) }
	button				{ color 		  :}
	button 				{ background-color: rgb(225,241,247) }
	button 				{ border-color	  :}
	button:hover	 	{ color 		  :}
	button:hover 		{ background-color: rgb(177,214,230) } /*soft blue 1*/
	button:hover 		{ border-color 	  :}
	button:focus	 	{ color 		  :}
	button:focus 		{ background-color: rgb(140,195,220) } /*soft blue 2*/ /*faded red 2 #Fcc*/
	button:focus 		{ border-color 	  :}
	button:active	 	{ color 		  :}
	button:active 		{ background-color: rgb(110,179,208) }
	button:active 		{ border-color 	  : #444 }
	.options_group 		{ border-color 	  : rgb(63,131,245) }
	.data				{ border-color 	  : rgb(10,80,200)  }
	.data th, .data td  { border-color    : rgb(100,160,250)}
	.data th, .data td  { font-size 	  : .90em }
	.newday				{ border-top-color: rgb(10,80,200) }
	.data td.newday_h 	{border-color	  : rgb(100,160,250) }
	.data td.newday_h 	{border-left	  : 1px solid rgb(10,80,200) }
	.rain				{ color 		  : blue; font-weight: bold }
	.not_found			{ border-color 	  : rgb(10,80,200) } 
	.imgbar td:hover 	{ background-color: rgb(140,195,220) } /*soft blue 2*/ /*faded red 2 #Fcc*/
	.imgbar_current_pic { background-color: #ddd }
	#timestamp 			{ border-color    : rgb(10,80,200) }
	.fine_print 		{ color			  : #555 }
	.messages			{ border-color    : rgb(10,80,200) }
	.messages_H			{ border-color    : rgb(10,80,200) }
	.TESTING_MSG 		{ color 		  : red; }
	/***********************************************************/
END_of_THEME;


	$x++; 
	$THEMES['name'][$x] = "Plain";
	$THEMES['styles'][$x] = <<< END_of_THEME

	/********** {$THEMES['name'][$x]} Theme *******************/
	* {border-color: black}
	body 				{ color			  : black }
	body 				{ background-color: white }
	label:hover 		{ background-color: #CCC  }
	.active				{ background-color: #ccc  }
	input[type='text']  { color 		  : black }
	input[type='text']  { border-color    : black }
	input[type='text']  { background-color: white }
	select 				{ color			  : black }
	select 				{ background-color: white }
	select 				{ border-color 	  : black }
	button				{ color 		  : black }
	button 				{ background-color: #ccc  }
	button 				{ border-color	  : gray  }
	button:hover	 	{ color 		  : }
	button:hover 		{ background-color: #aaa  }
	button:hover 		{ border-color 	  : #444  }
	button:focus	 	{ color 		  : }
	button:focus 		{ background-color: #aaa  }
	button:focus 		{ border-color 	  : black }
	button:active	 	{ color 		  : }
	button:active 		{ background-color: #999 }
	button:active 		{ border-color 	  : #333 }
	.options_group 		{ border-color 	  : black }
	.data				{ border-color 	  : black }
	.data th, .data td  { border-color    : black }
	.data th, .data td  { font-size 	  : .90em }
	.newday				{ border-top-color: silver }
	.data td.newday_h 	{border-color	  : silver }
	.data td.newday_h 	{border-left	  : silver }
	.rain				{ color 		  : blue }
	.not_found			{ border-color 	  : black } 
	.imgbar td:hover 	{ background-color: #ccc }
	.imgbar_current_pic { background-color: #aaa }
	#timestamp 			{ border-color    : black }
	.fine_print 		{ color			  : #555 }
	.messages			{ border-color    : black }
	.messages_H			{ border-color    : black }
	.TESTING_MSG 		{ color 		  : red; }
	/***********************************************************/
END_of_THEME;


	$x++; 
	$THEMES['name'][$x] = "Dark Green 1";
	$THEMES['styles'][$x] = <<< END_of_THEME

	/********** {$THEMES['name'][$x]} Theme *******************/
	body 				{ color			  : #0F0 }
	body 				{ background-color: black }
	label:hover 		{ color			  : black }
	label:hover 		{ background-color: #0F0 }
	.active				{ color			  : black }
	.active				{ background-color: #0F0 }
	input[type='text']  { color 		  : #0F0 }
	input[type='text']  { border-color    : #0F0 }
	input[type='text']  { background-color: black }
	select 				{ color			  : #0F0 }
	select 				{ background-color: black }
	select 				{ border-color 	  : #0F0 }
	button				{ color 		  : #0F0 }
	button 				{ background-color: black }
	button 				{ border-color	  : #0F0 }
	button:hover	 	{ color 		  : black }
	button:hover 		{ background-color: #0F0 }
	button:hover 		{ border-color 	  : green !important}
	button:focus	 	{ color 		  : black }
	button:focus 		{ background-color: #0F0 }
	button:focus 		{ border-color 	  : #0F0 }
	button:active	 	{ color 		  : black }
	button:active 		{ background-color: #0F0 }
	button:active 		{ border-color 	  : #444 }
	.options_group 		{ border-color 	  : rgb( 63, 131, 245) }
	.data				{ border-color 	  : rgb( 10,  80, 200) }
	.data th, .data td  { border-color    : rgb(  0,  70,   0) }
	.data th, .data td  { font-size 	  : .90em }
	.newday				{ border-top-color: rgb( 10,  80, 200) }
	.data td.newday_h 	{ border-color	  : rgb(  0,  70,   0) }
	.data td.newday_h 	{ border-left	  : 1px solid rgb(10,80,200) }
	.rain				{ color 		  : blue }
	.not_found			{ border-color 	  : rgb( 10,  80, 200) } 
	.imgbar td:hover 	{ background-color: #0F0 }
	.imgbar_current_pic { background-color: #444 }
	#timestamp 			{ border-color    : rgb( 10,  80, 200) }
	.fine_print 		{ color			  : #0F0 }
	.messages			{ border-color    : rgb( 10,  80, 200) }
	.messages_H			{ border-color    : rgb( 10,  80, 200) }
	.TESTING_MSG 		{ color 		  : red; }
	/***********************************************************/
END_of_THEME;


	$x++; 
	$THEMES['name'][$x] = "Dark Green 2";
	$THEMES['styles'][$x] = <<< END_of_THEME

	/********** {$THEMES['name'][$x]} Theme *******************/
	body 				{ color			  : #0F0 }
	body 				{ background-color: black }
	label:hover 		{ color			  : yellow }
	label:hover 		{ background-color: blue }
	.active				{ color			  : yellow }
	.active				{ background-color: blue }
	input[type='text']  { color 		  : #0F0 }
	input[type='text']  { border-color    : #0F0 }
	input[type='text']  { background-color: black }
	select 				{ color			  : #0F0 }
	select 				{ background-color: black }
	select 				{ border-color 	  : #0F0 }
	button				{ color 		  : #0F0 }
	button 				{ background-color: black }
	button 				{ border-color	  : #0F0 }
	button:hover	 	{ color 		  : yellow }
	button:hover 		{ background-color: blue }
	button:hover 		{ border-color 	  : yellow }
	button:focus	 	{ color 		  : yellow }
	button:focus 		{ background-color: blue }
	button:focus 		{ border-color 	  : yellow }
	button:active	 	{ color 		  : yellow }
	button:active 		{ background-color: blue }
	button:active 		{ border-color 	  : #444 }
	.options_group 		{ border-color 	  : rgb(63,131,245) }
	.data				{ border-color 	  : rgb(10,80,200)  }
	.data th, .data td  { border-color    : rgb(100,160,250)}
	.data th, .data td  { font-size 	  : .90em }
	.newday				{ border-top-color: rgb(10,80,200)}
	.data td.newday_h 	{ border-color	  : rgb(100,160,250) }
	.data td.newday_h 	{ border-left	  : 1px solid rgb(10,80,200) }
	.rain				{ color 		  : blue }
	.not_found			{ border-color 	  : rgb(10,80,200) } 
	.imgbar td:hover 	{ background-color: #444 }
	.imgbar_current_pic { background-color: #333 }
	#timestamp 			{ border-color    : rgb(10,80,200) }
	.fine_print 		{ color			  : #0F0 }
	.messages			{ border-color    : rgb(10,80,200) }
	.messages_H			{ border-color    : rgb(10,80,200) }
	.TESTING_MSG 		{ color 		  : red; }
	/***********************************************************/
END_of_THEME;


	$x++; 
	$THEMES['name'][$x] = "Dark Amber 1";
	$THEMES['styles'][$x] = <<< END_of_THEME

	/********** {$THEMES['name'][$x]} Theme *******************/
	body 				{ color			  : #FFbf00 }
	body 				{ background-color: black }
	label:hover 		{ color			  : black }
	label:hover 		{ background-color: #FFbf00 }
	.active				{ color			  : black }
	.active				{ background-color: #FFbf00 }
	input[type='text']  { color 		  : #FFbf00 }
	input[type='text']  { border-color    : #FFbf00 }
	input[type='text']  { background-color: black }
	select 				{ color			  : #FFbf00 }
	select 				{ background-color: black }
	select 				{ border-color 	  : #FFbf00 }
	button				{ color 		  : #FFbf00 }
	button 				{ background-color: black }
	button 				{ border-color	  : #FFbf00 }
	button:hover	 	{ color 		  : black }
	button:hover 		{ background-color: #FFbf00 }
	button:hover 		{ border-color 	  : yellow }
	button:focus	 	{ color 		  : black }
	button:focus 		{ background-color: #FFbf00 }
	button:focus 		{ border-color 	  : yellow }
	button:active	 	{ color 		  : black }
	button:active 		{ background-color: #FFbf00 }
	button:active 		{ border-color 	  : #444 }
	.options_group 		{ border-color 	  : rgb(63,131,245) }
	.data				{ border-color 	  : rgb(10,80,200)  }
	.data th, .data td  { border-color    : rgb(110,80,0)}
	.data th, .data td  { font-size 	  : .90em }
	.newday				{ border-top-color: rgb(10,80,200) }
	.data td.newday_h 	{ border-color	  : rgb(110,80,0) }
	.data td.newday_h 	{ border-left	  : 1px solid rgb(10,80,200) }
	.rain				{ color 		  : blue }
	.not_found			{ border-color 	  : rgb(10,80,200) } 
	.imgbar td:hover 	{ background-color: #444 }
	.imgbar_current_pic { background-color: #333 }
	#timestamp 			{ border-color    : rgb(10,80,200) }
	.fine_print 		{ color			  : #FFbf00 }
	.messages			{ border-color    : rgb(10,80,200) }
	.messages_H			{ border-color    : rgb(10,80,200) }
	.TESTING_MSG 		{ color 		  : red; }
	/***********************************************************/
END_of_THEME;

}//end Load_Themes() { //******************************************************/





function Init() { //***********************************************************/
	global $URL_BASE, $URL_OPTIONS, $URL_MOST, $DATA_URLS, $LOCATION_NAMES, 
		   $DESIRED, $DISPLAY_ORDER, $DATA, $DEFAULT_ASPECTS, $SAMPLE_SET, 
		   $RAIN_THRESHOLD, $RAW_HTML_SAMPLES, $RADAR_URL_BASE_SAMPLE, $IMG_CNT;

	//General Note: In this program, the word "aspect" (without regard to usage/capitalization) 
	//is used as a synonym for "element", regarding the various facets and features of weather (temp, wind, rain, etc.).
	//This is to avoid potential conflicts with various reserved words in javascript (like element and elements).
	//(However, that explanation is really just a lame ret-con, as I just didn't even think of weather "element" when 
	//I started, and didn't like the sound of "facet" or "feature".)

	//Make sure time zone is correct.
	date_default_timezone_set("America/New_York");


	//Weather url's ******************************************************
	$URL_BASE    = "http://forecast.weather.gov/";
	$URL_BASE_1  = $URL_BASE."MapClick.php?";
	$URL_OPTIONS = "&w0=t&w1=td&w2=wc&w3=sfcwind&w3u=1&w4=sky&w5=pop&w6=rh&w7=rain&w8=thunder&w9=snow&w10=fzg&w11=sleet&w12=fog&w13u=0&w15u=1&w16u=1&FcstType=digital&site=all&unit=0&dd=&bw=&Submit=Submit";
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

	define('FIRST_ROW',      1); //Currenlty 1, as of 2016-02-26
	define('ASPECTS_TOTAL', 17); //Total number of weather aspects/elements available.
	define('d2_OFFSET',     18); //offset to first row of next set of rows of weather.
								 //Currently there is one blank/divider row between the sets.

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



	//Desired rows of data to extract from source html table. Currently all data rows.
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
	//$DEFAULT_ASPECTS = $DISPLAY_ORDER; //All aspects.
	//				   = array(d1_DATE, d1_HOUR, d1_TEMP, d1_WIND, d1_WIND_DIR, d1_GUST, d1_RAIN, d1_CLOUDS, d1_HUMIDITY, d1_FOG, d1_RAIN_CHNC, d1_THUNDER, d1_FRZ_RAIN, d1_SLEET, d1_SNOW);
	$DEFAULT_ASPECTS = array(d1_DATE, d1_HOUR, d1_TEMP, d1_WIND, d1_WIND_DIR, d1_GUST, d1_RAIN, d1_FOG);



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
	$RAW_HTML_SAMPLES[1] =  "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.1.html";
	$RAW_HTML_SAMPLES[2] =  "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.2.html";
	$RAW_HTML_SAMPLES[3] =  "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.3.html";
	$RAW_HTML_SAMPLES[4] =  "D:/www/Weather/weather.gov/samples/$SAMPLE_SET/weather.gov.sample_all.4.html";
	//##### $RADAR_URL_BASE_SAMPLE =     "/Weather/weather.gov/samples/";
	$RADAR_URL_BASE_SAMPLE ="http://d/Weather/weather.gov/lite/";


	//The number of images to load.  Used to create imgbar in Show_Radar();
	//Someday, this may be dynamically obtained, when I get to loading more than just the 0...7 images.
	$IMG_CNT = 8;

	//Radar image URL's:  http://radar.weather.gov/lite/N0R/XXX_?.png    ? = 0 thru 7
	define('RADAR_SITE_DEF',  "IWA");  //Default radar site (IWA is central AZ)
	define('RADAR_RANGE_STD', "N0R/"); // N<ZERO>R is base range.     "Views out to 124 nmi" (~143 miles).
	define('RADAR_RANGE_EXT', "N0Z/"); // N<ZERO>Z is extended range. "Views out to 248 nmi" (~286 miles).

	define('RADAR_URL_BASE', "http://radar.weather.gov/lite/");
	
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
	global  $THEME, $THEMES, $SHOW_LOCATIONS, $LOCATION_NAMES, $SELECTED_ASPECTS, $DEFAULT_ASPECTS, $DISPLAY_ORDER,
			$HOURS_TO_SHOW, $DISPLAY_VH,  $RAIN_THRESHOLD, $SAMPLE_SET, $RAW_HTML_SAMPLES, $TEST_MODE,
			$SHOW_RADAR, $WRAP_MAP, $DONT_WRAP_MAP, $RADAR_VIEW,
			$SHOW_RADAR_OPTIONS, $FRAME_RATE, $ROTATE_PAUSE, $ROTATE_LOOPS,
			$SHOW_LOCATION_OPTIONS, $SHOW_WEATHER_OPTIONS, $SHOW_DISPLAY_OPTIONS;

	$_GET = array_change_key_case($_GET, CASE_UPPER);


	//Return selected style sheet ************
	if (isset($_GET["CSS"])) {
		header("Content-type: text/css; charset: UTF-8");	
		if (isset($THEMES['styles'][$_GET["CSS"]])) {die($THEMES['styles'][$_GET["CSS"]]);}
		else 										{die($THEMES['styles'][0]);}
	}


	//"THEME" ********************************
	//Get user selected  "THEME" option
	if (isset($_GET["THEME"])) {$THEME = (int)$_GET['THEME'];}
	else 					   {$THEME = 0;}
	if (!isset($THEMES['name'][$THEME])) {$THEME = 0;}


	//TEST_MODE aliases
	//LEAVE CHECK FOR TEST MODE HERE! It's result is used below.
	if (isset($_GET["TEST"]) || isset($_GET["TEST_MODE"])) {$TEST_MODE = true; }
	else 												   {$TEST_MODE = false;}


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
	if (!isset($_GET["ASPECTS"])) { $SELECTED_ASPECTS = $DEFAULT_ASPECTS; }
	else 						  { $SELECTED_ASPECTS = $_GET["ASPECTS"]; }

	//make sure in valid range
	foreach($SELECTED_ASPECTS as $key => $aspect) {
		if     (!is_numeric($key) || !is_numeric($aspect))	{ unset($SELECTED_ASPECTS[$key]); }
		elseif (!in_array($aspect, $DISPLAY_ORDER) )		{ unset($SELECTED_ASPECTS[$key]); }
		else   /* make 'em int's ---------------------> */	{ $SELECTED_ASPECTS[$key] *= 1;   }
	}
	sort($SELECTED_ASPECTS);
	$SELECTED_ASPECTS = array_values($SELECTED_ASPECTS);


	//"HOURS_TO_SHOW" ************************
	//Needed before Style_Sheet() or User_Options() are called
	if (!isset($_GET["HOURS_TO_SHOW"])) {
		//default if not selected
		$HOURS_TO_SHOW = HOURS_DEF;
	} else {
		$HOURS_TO_SHOW  = intval(trim($_GET['HOURS_TO_SHOW']));
		if 	   ($HOURS_TO_SHOW < HOURS_MIN) { $HOURS_TO_SHOW = HOURS_MIN; }
		elseif ($HOURS_TO_SHOW > HOURS_MAX) { $HOURS_TO_SHOW = HOURS_MAX; }
	}



	//VH *************************************
	if (isset($_GET["VH"])) {
		if 		($_GET["VH"] == "H") { $DISPLAY_VH = "H"; }
		else if ($_GET["VH"] == "N") { $DISPLAY_VH = "N"; }
		else 						 { $DISPLAY_VH = "V"; } //default
	}


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
	if (isset($_GET['DONT_WRAP_MAP'])) {$DONT_WRAP_MAP = "checked";} else {$DONT_WRAP_MAP = "";}


	//Show Location Options? *****************
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


	//Radar options: $i=1 for the default radar, $i=2 for user selected/custom location.
	for ($i = 1; $i < 3; $i++) {
		
		//"RADAR_VIEW" / Zoom Level **************
		if (isset($_GET['RADAR_VIEW'][$i]) && ($_GET['RADAR_VIEW'][$i] == "N0Z")) //N<ZERO>Z
			 {$RADAR_VIEW[$i] = "N0Z";} //N<ZERO>Z  Extended range. "Views out to 248 nmi" (~286 miles).
		else {$RADAR_VIEW[$i] = "N0R";} //N<ZERO>R  Normal range.   "Views out to 124 nmi" (~143 miles).
		
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
		}
		else {
			// For trouble shooting only
			//##### $MESSAGES[$location] .= "<hr>Data recieved from (".hsc($LOCATION_NAMES[$location])."): ".hsc($data_url)."<br>";
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
			
			$hour_offset += 24; //24 hours in each $rowset.
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
	global $DATA, $LOCATION_NAMES, $TESTING_MSG, $RAIN_THRESHOLD, $HOURS_TO_SHOW, $DISPLAY_ORDER, $SELECTED_ASPECTS, $MESSAGES;

	$show_date = in_array(d1_DATE, $SELECTED_ASPECTS);

	$columns = ASPECTS_TOTAL - 1;


	echo "<table class='data data_V'>\n";
	echo "<caption>".hsc($LOCATION_NAMES[$location])."<br>$TESTING_MSG</caption>\n";
		
		//$data_index is from 0 (current/first hour of data) to $HOURS_TO_SHOW
		for ($data_index = 0; $data_index <= $HOURS_TO_SHOW; $data_index++) {
			
			//Highlight Header Row (labels)
			if ($data_index == 0) {$hdr = "hdr";} else {$hdr = "";}
			
			//If (start of data) or a (new day), add row with day, date...
			if (($data_index == 1) || ($DATA[$data_index][d1_HOUR] === "00")) {
				$day_of_week = date('D', strtotime(date("Y")."/".$DATA[$data_index][d1_DATE]));
				echo "<tr class=newday><th colspan=".$columns.">";
				echo "<span class=a_1>$day_of_week, ".hsc($DATA[$data_index][$DISPLAY_ORDER[0]]);
				echo "</span></th></tr>\n";
			}
			
			//Show selected data...
			echo "<tr>";
			
			foreach ($DISPLAY_ORDER as $aspect) {
				
				if ($aspect == d1_DATE ) { continue; } //Skip date column, it's shown as a header row. (above)
				
				if ($aspect < 3) {$td = "th";} else {$td = "td";} //header (Date/Hour) or data (Temp, etc)?
				
				$classes = "a_$aspect"; //used for show/hide option
				
				//Highlight rain% value if >= specified value.
				if (($aspect == d1_RAIN) || ($aspect == d1_RAIN_CHNC)) {
					if ($DATA[$data_index][d1_RAIN] >= $RAIN_THRESHOLD){ $classes .= " rain"; }
				}
				else if (($aspect == d1_WIND_DIR) && ($data_index > 0)) { $classes .= " wind_dir"; }
				else if (($aspect == d1_FOG)) 							{ $classes .= " fog"; }
				
				$classes = "class='$classes'";
				
				//At 12 noon, change "12" to "noon"
				if (($aspect == d1_HOUR) && ($DATA[$data_index][$aspect] == "12")) {$DATA[$data_index][$aspect] = "Noon";}
				
				echo "<$td $classes>".hsc($DATA[$data_index][$aspect])."</$td>";
			}//end foreach ($aspect)
			echo "</tr>\n";
		}//end for($data_index)
	echo "</table>\n";
}//end Display_Weather_V() //**************************************************/





function Display_Weather_H($location) {//**************************************/
	//Display data in new Horizontal table, each column one hour.
	global $DATA, $LOCATION_NAMES, $TESTING_MSG, $RAIN_THRESHOLD, $HOURS_TO_SHOW, $DISPLAY_ORDER, $SELECTED_ASPECTS, $MESSAGES;
		
	echo "<table class='data data_H'>\n";
		
		$colspan = $HOURS_TO_SHOW + 1;
		echo "<tr><td colspan=$colspan>\n";
		echo "<h2 class='location_name'>".hsc($LOCATION_NAMES[$location])." &nbsp; ".$TESTING_MSG."</h2>\n";
		echo "</td></tr>\n";
		
		foreach ($DISPLAY_ORDER as $aspect) { //$aspect
			
			//Highlight date & time rows. (First two rows.)
			if ($aspect < 2) {$hdr = "hdr";} else {$hdr = "";}
			
			echo "<tr>";
			for ($data_index = 0; $data_index <= $HOURS_TO_SHOW; $data_index++) {
				
				//Add day of week just past date, unless, at beginning, with two dates next to each other.
				$day_of_week = "";
				if ( ($aspect === 0) && ($data_index > 1) ) { 
					if (($DATA[$data_index-1][d1_DATE] != "") && ($DATA[$data_index][d1_HOUR] > 0) ) {
						$day_of_week = date('D', strtotime(date("Y")."/".$DATA[$data_index-1][d1_DATE]));
					}
				}
				
				//Header/data labels? (Date, Hour, Temp °f...
				if ($data_index == 0) {$td = "th";} else {$td = "td";}
				
				//Highlight rain% value if over specified value.
				$rain = "";
				if ( ($DATA[$data_index][d1_RAIN] >= $RAIN_THRESHOLD) && (($aspect == d1_RAIN) || ($aspect == d1_RAIN_CHNC)) )
				{ $rain = "rain"; }
				
				//Highlight start of a new day... (bolds line between day columns)
				$newday = "";
				if (($DATA[$data_index][d1_HOUR] === "00") || ($data_index == 1)) { $newday = "newday_h";}
				
				$classes = trim("a_$aspect $hdr $rain $newday"); //Trimming whitespace...
				$classes = " class='$classes'";
				
				//Finally, ouput the weather info.
				echo "<$td ${classes}>".hsc($DATA[$data_index][$aspect])."$day_of_week</$td>";
			}//end for($data_index)
			echo "</tr>\n";
		}//end for($aspect)
		
	echo "</table>\n";
}//end Display_Weather_H() //**************************************************/





function User_Options() {//****************************************************/
	global	$DATA, $LOCATION_NAMES, $RAIN_THRESHOLD, $HOURS_TO_SHOW, $SHOW_LOCATIONS, $TEST_MODE,
			$DISPLAY_VH, $SELECTED_ASPECTS, $SHOW_RADAR, $DISPLAY_ORDER, $WRAP_MAP, $DONT_WRAP_MAP,
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
			//Pre-defined locations - listed at top of Init();
			//checkbox names need the indexes to match the values. ie: SHOW_LOCATIONS[1]=1
			echo "<label>";
			echo 	"<input type=checkbox name=SHOW_LOCATIONS[".hsc($key)."] value=".hsc($key)." $checked>";
			echo hsc($location_name)."";
			echo "</label>\n";
		}
		else if ($key == 0) {
			echo "<label id=location_search_ckbox_label>\n";
			echo 	"<input type=checkbox  name=SHOW_LOCATIONS[0] value=0 $checked>\n";
			echo "</label>";
			echo "<label id=location_search_label>";
			echo 	"<input type=text id=location_search name=LOCATION_SEARCH value='".hsc($location_name)."'>\n";
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

	for ($aspect=0; $aspect < WASPECTS; $aspect++) {
		$checked = "";
		if (in_array($DISPLAY_ORDER[$aspect], $SELECTED_ASPECTS) )	{ $checked = " checked"; }
		echo "<label>";
		echo 	"<input type=checkbox class=aspect_options  name=ASPECTS[] ";  //##### use name=ASPECTS[$DISPLAY_ORDER[$aspect]] ?
		echo 	"value=".hsc($DISPLAY_ORDER[$aspect])."$checked>".hsc($DATA[0][$DISPLAY_ORDER[$aspect]]);
		echo "</label>\n";
	}
	echo "</div>";




	//Third row: display options ****************************

	//Hours to display (12, 24, 36, 48, etc...). Displayed in days: .5, 1, 1.5 2, etc...
	$hours_options = "";
	for ($option = HOURS_MIN; $option <= HOURS_MAX; $option += HOURS_INC) {
		if ($HOURS_TO_SHOW == $option){ $selected = " selected"; } else {$selected = "";}
		$hours_options .= "<option value=$option$selected>".round($option/24,1)."</option>\n";
	}
	//...in case HOURS_MIN + HOURS_INC... is not an even multiple of HOURS_MAX
	$option -= HOURS_INC; // revert to last good value
	if ($option < HOURS_MAX) {
		if ($HOURS_TO_SHOW == HOURS_MAX){ $selected = " selected"; } else {$selected = "";}
		$hours_options .= "<option value=".HOURS_MAX."$selected>".round(HOURS_MAX/24,1)."</option>\n";
	}



	//Display mode: Vertical or Horizontal
	$selected_v = $selected_h = $selected_n = "";
	if 		($DISPLAY_VH == "H") { $selected_h = "selected"; }
	else if ($DISPLAY_VH == "N") { $selected_n = "selected"; }
	else						 { $selected_v = "selected"; }



	//Show Radar & associated  Options.
	$show_radar_checked = "";
	if ($SHOW_RADAR) { $show_radar_checked = "checked"; }


	//Output third row...
	echo "\n<div id=DISPLAY_OPTIONS class=options_group>\n";

	//Hours to display (12, 24, 36, 48, etc...). Displayed in days: .5, 1, 1.5 2, etc...
	echo "<label>Display &nbsp;<select id=HOURS_TO_SHOW name=HOURS_TO_SHOW>\n";
		echo $hours_options;
	echo "</select> days</label>\n";


	//Display mode: Vertical or Horizontal
	echo "\n<label> &nbsp; <select id=VH name=VH>\n";
		echo "<option value=V $selected_v>Vertically</option>\n";  //default selection
		echo "<option value=H $selected_h>Horizontally</option>\n";
		echo "<option value=N $selected_n>None</option>\n";
	echo"</select> &nbsp;</label>\n";


	//Rain Threshold: highlight rain values at this point
	echo "\n<label>Highlight rain at "; 
		echo "<input type=text id=rain_threshold name=RAIN_THRESHOLD maxlength=2 value=".$RAIN_THRESHOLD.">";
	echo "%</label>\n";


	//Show Radar & associated  Options.
	echo "<label id=show_radar_label><input id=SHOW_RADAR type=checkbox name=SHOW_RADAR $show_radar_checked>Radar Map</label>\n\n";

	echo "<div id=MAP_OPTIONS class=radar_div>";
		echo "<label><input id=dont_wrap_map type=checkbox name=DONT_WRAP_MAP value=true $DONT_WRAP_MAP>Don't wrap map</label>\n\n";
	echo "</div>\n";


	/*** TEST MODE option ****./
	$test_mode_checked = "";
	if ($TEST_MODE) {$test_mode_checked = " checked";}
	echo "\n<label id=test_mode>";
	echo "<input type=checkbox name=TEST_MODE value=true$test_mode_checked>Test Mode</label>\n";
	/*************************/


	echo "</div>"; //End Third Row
}//end User_Options() //*******************************************************/





function Show_Radar($i=1) { //***************************************************/
	// $i is 1 or 2, the "instance" of which radar to show. 1 is the default radar, 2 is custom site.
	global $SELECTED_ASPECTS, $SHOW_LOCATIONS, $HOURS_TO_SHOW, $RADAR_VIEW, $WRAP_MAP, $IMG_CNT, $INIT_RADARS,
	       $FRAME_RATE, $ROTATE_PAUSE, $ROTATE_LOOPS, $CUSTOM_RADAR_SITE, $SHOW_RADAR_OPTIONS, $LOCATION_FOUND, $RID;


	//used in window.onload to, well, init the radar objects.
	if (!isset($INIT_RADARS)) {$INIT_RADARS = "";} //First or second call to Show_Radar()?
	if ($RADAR_VIEW[$i] == "N0Z") { $rz = 2; } else { $rz = 1; }
	$INIT_RADARS .= "Radar[$i] = Init_Radar(PIC_LIST[$i][$rz], '$i');\n";

	if ($i == 2) {$default_img = RADAR_URL_BASE.$RADAR_VIEW[$i]."/".$CUSTOM_RADAR_SITE."_0".RADAR_IMG_EXT;}
	else 		 {$default_img = RADAR_URL_BASE.$RADAR_VIEW[$i]."/".RADAR_SITE_DEF."_0".RADAR_IMG_EXT;}

	$imgbar_slots = "\n";
	$tabindex = -1;
	for($x = ($IMG_CNT - 1); $x >= 0; $x--) { $imgbar_slots .= "<td id=slot_{$x}_{$i}>$x</td>\n"; }

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

	//Radar "zoom" level
	$N0R_checked = ""; //N<ZERO>R   default  "Views out to 124 nmi" (~143 miles).
	$N0Z_checked = ""; //N<ZERO>Z            "Views out to 248 nmi" (~286 miles).
	if ($RADAR_VIEW[$i] == "N0Z") {$N0Z_checked = " checked"; } else {$N0R_checked = " checked";}


	//show default radar & controls
?>
	<div id=radar<?= $i ?> class=radar_div>
		
		<img src="<?= $default_img ?>" id=ROTATING_PIC_<?= $i ?> width=600 height=550 alt="Radar Image - Forecast Area"><br>
		
		<div class=radar_controls>
			<button type=button id=START_STOP_<?= $i ?> class=start_stop></button>&nbsp;
			
			<table id=IMGBAR_<?= $i ?> class=imgbar><tr><?= $imgbar_slots ?></tr></table>
			
			<button type=button id=SHOW_RADAR_OPTS_<?= $i ?> class=radar_opts_btn>&#9660;&#9650;</button>
			<input type=hidden id=SHOW_RADAR_OPTIONS_<?= $i ?> name=SHOW_RADAR_OPTIONS[<?= $i ?>] value="<?= $SHOW_RADAR_OPTIONS[$i] ?>">
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
		
		<div class=radar_view>Radar Range (radius):
			<label><input id=RANGE_R_<?= $i ?> type=radio name=RADAR_VIEW[<?= $i ?>] value=N0R <?= $N0R_checked ?>>143 miles</label>
			<label><input id=RANGE_Z_<?= $i ?> type=radio name=RADAR_VIEW[<?= $i ?>] value=N0Z <?= $N0Z_checked ?>>286 miles</label>
		</div>
		
	</div>
<?php
}//end Show_Radar() //*********************************************************/





function js_Radar_Loop_functions() { //****************************************/
?>

<script>
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
		//First img change after page load.
	if (Pics.current_loop == 0) {Pics.current_loop = 1;}
		//Add "Pics.running &&" to change counter only while running.
	else if ((Pics.current_pic == (Pics.pic_list.length - 1)) && (prior_pic === 0)) {Pics.current_loop++;}
	Pics.loop_displayed.innerHTML = "(" + Pics.current_loop + ")";

	if (once) {if (Pics.running) {Start_Stop(Pics);} return;}

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
	Pics.imgbar_slots[prior_pic       ].classList.remove("imgbar_current_pic");
	Pics.imgbar_slots[Pics.current_pic].classList.add("imgbar_current_pic");

	Pics.loop_displayed.innerHTML = "(" + Pics.current_loop + ")";

}//end Change_Pic() //****************************************************




function Change_Radar_Range2(Pics, i, new_range){ //**********************

	//new_range: 1 for N0R (143 mile range), 2 for N0Z (286 mile range).

	rotating_pic = document.getElementById('ROTATING_PIC_' + i);
	
	//Set the new list
	Pics.pic_list = PIC_LIST[i][new_range];

	//Display the new pic (from the new range).
	rotating_pic.src = PIC_LIST[i][new_range][Pics.current_pic];

}//end Change_Radar_Range2() //********************************************




function Init_Radar(pic_list, instance) { //******************************
	//Various id's are suffixed with _<instance>.

	var Pix 			= {};
	Pix.instance 		= instance;
	Pix.pic_list 		= [];
	Pix.pic_list 		= pic_list;		//##### .slice(); //Make a copy of, not a reference to?...
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
	Pix.show_radar_options	= document.getElementById('SHOW_RADAR_OPTIONS_'+ instance); //<input> value = "checked" or ""
	Pix.imgbar			= document.getElementById('IMGBAR_' 		   + instance); //Not currently used, but maybe someday...

	Pix.rotating_pic.src 		  = Pix.pic_list[0];  //Load initial image
	Pix.start_stop.innerHTML      = PLAY_BTN;
	Pix.show_radar_opts.innerHTML = CTRL_ICO;


	/***** Radar Range Option events ******/
	document.getElementById('RANGE_R_' + instance).onclick = function (){Change_Radar_Range2(Pix, instance, 1)} //1 == N0R/143 mile range
	document.getElementById('RANGE_Z_' + instance).onclick = function (){Change_Radar_Range2(Pix, instance, 2)} //2 == N0Z/286 mile range


	/******** Radar imgbar control ********/
	Pix.top_pic.onclick 		= function(     ){Start_Stop(Pix); document.getElementById('START_STOP_' + instance).focus();}
	Pix.start_stop.onclick 		= function(     ){Start_Stop(Pix); Stop_Propagation(event)}
	Pix.start_stop.onkeydown 	= function(event){Imgbar_Control(event, Pix); Stop_Propagation(event)}
	Pix.show_radar_opts.onclick = function(     ){
		Show_Hide_Opts(Pix.radar_options.id, Pix.show_radar_options.id, "display", "inline-block", "none")
	}

	Pix.imgbar_slots = []; //<td>'s
	for (var x=0; x < Pix.pic_list.length; x++) {Pix.imgbar_slots[x] = document.getElementById("slot_" + x  + "_" + instance);}

	Pix.imgbar_slots[Pix.current_pic].classList.add("imgbar_current_pic");

	//Add events for imgbar click control
	for (x=0; x < Pix.pic_list.length; x++) {
		//Must use a self-invoking anonymous function (SIAF): it is invoked with the "(x)" at the end of it's {block}. 
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
}//end js_Radar_Loop_functions() //********************************************/





function js_Init_Radar_URLs_etc() { //*****************************************/
	global $TEST_MODE, $RADAR_URL_BASE_SAMPLE, $CUSTOM_RADAR_SITE, $IMG_CNT;


	//Build js arrays of radar url's. Inserted in <script> below...
	if ($TEST_MODE) { $radar_url_base_1R = $RADAR_URL_BASE_SAMPLE."N0R/IWA_";
					  $radar_url_base_1Z = $RADAR_URL_BASE_SAMPLE."N0Z/IWA_";
					  $radar_url_base_2R = $RADAR_URL_BASE_SAMPLE."N0R/AMA_";
					  $radar_url_base_2Z = $RADAR_URL_BASE_SAMPLE."N0Z/AMA_";
	}
	else            { $radar_url_base_1R = RADAR_URL_BASE."N0R/".RADAR_SITE_DEF."_";
					  $radar_url_base_1Z = RADAR_URL_BASE."N0Z/".RADAR_SITE_DEF."_";
					  $radar_url_base_2R = RADAR_URL_BASE."N0R/".$CUSTOM_RADAR_SITE."_";
					  $radar_url_base_2Z = RADAR_URL_BASE."N0Z/".$CUSTOM_RADAR_SITE."_";
	}

	$pic_list_1R = $pic_list_1Z = $pic_list_2R = $pic_list_2Z = "";

	for ($x=0; $x < $IMG_CNT; $x++){
		$pic_list_1R .= "PIC_LIST[1][1][$x] = '{$radar_url_base_1R}{$x}".RADAR_IMG_EXT."';\n";
		$pic_list_1Z .= "PIC_LIST[1][2][$x] = '{$radar_url_base_1Z}{$x}".RADAR_IMG_EXT."';\n";
		
		if ($CUSTOM_RADAR_SITE != "" ) {
			$pic_list_2R .= "PIC_LIST[2][1][$x] = '{$radar_url_base_2R}{$x}".RADAR_IMG_EXT."';\n";
			$pic_list_2Z .= "PIC_LIST[2][2][$x] = '{$radar_url_base_2Z}{$x}".RADAR_IMG_EXT."';\n";
		}
	}
?>



<script>
//************************************************************************
//URL's for the 8 most recent radar images available from weather.gov
//Load URL's into PIC_LIST[site][range][x]
//site = radar site (1=default, 2=user supplied),   x = image 0 thru 7
//image 0 is most recent, 7 is the oldest.

var PIC_LIST	= [];
	PIC_LIST[1] = []; //Default radar site
	PIC_LIST[1][1] = []; //N0Z 124m range
	PIC_LIST[1][2] = []; //N0R 248m range
	
	PIC_LIST[2] = []; //Radar site for user requested location, if present.
	PIC_LIST[2][1] = []; //N0Z 124m range
	PIC_LIST[2][2] = []; //N0R 248m range

//Default site, "short" range, 143 miles: N0R  (N<zero>R)
<?= $pic_list_1R; ?>

//Default site, "long"  range, 286 miles: N0Z  (N<zero>Z)
<?= $pic_list_1Z; ?>

//Custom site, "long"  range, 286 miles: N0Z  (N<zero>Z)
<?= $pic_list_2R; ?>

//Custom site, "long"  range, 286 miles: N0Z  (N<zero>Z)
<?= $pic_list_2Z; ?>
//************************************************************************
</script>

<?php
}//end js_Init_Radar_URLs_etc() //*********************************************/





function js_Prevent_Some_Keys() { //*******************************************/
?>

<script>
//Called via onkeydown(). onkeypress returns different keyCode's, particularlly 39 for right arrow & quotes keys.
function Prevent_Some_Keys(event) { //************************************
	var key_code  = event.keyCode;

	/*********************./
	//Detect shifted: !=49   @=50 #=51  $=52  %=53  ^=54  &=55  *=56  (=57  )=48  _=173 <=188 >=190 
	//Detect either:  `=192  ==61 +=61  [=219 {=219 ]=221 }=221 \=220 |=220 ;=59  :=59  '=222 "=222 ?=191 /=191
	//       numpad:  /=111 *=106 +=107 .=110
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
}//end Prevent_Some_Keys() //********************************************
</script>

<?php
}//end js_Prevent_Some_Keys() //***********************************************/





function js_Time_Stamp() {//***************************************************/
?>

<script>
function Time_Stamp(return_method, target_id){ //*************************
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

	if (return_method === "write") 		 { document.write(TIMESTAMP); }
	else if( return_method === "insert") { document.getElementById(target_id).innerHTML = TIMESTAMP; }
	else 								 { return TIMESTAMP; }
}//end Time_Stamp() //****************************************************
</script>

<?php
}//end js_Time_Stamp() //******************************************************/





function js_Main() {//*********************************************************/
	global $INIT_RADARS;
?>

<script>
//************************************************************************/
PLAY_BTN = "Play &nbsp; &#x25Ba;";  //# >  &#x25B7; &#x25ba;
PAUS_BTN = "Pause  <b>| |</b>";     //# <b>| |</b> &#9616;&#9616;  &#10073;&#10073; &#9612;&#9612;   &#9015;&#9015;
STOP_BTN = "STOP";
CTRL_ICO = "&#9660;&#9650;"; //&#9660;&#9650;  Opt
//************************************************************************/




function Stop_Propagation(event) { //*************************************
	event.cancelBubble = true;
	if (event.stopPropagation) {event.stopPropagation()}
}//end Stop_Propagation(event) { //***************************************




function Show_Hide_Maps(class_to_hide, ckbox_id) { //*********************

	var maps 	  = document.getElementsByClassName(class_to_hide); //<elements> to show/hide
	var show_maps = document.getElementById(ckbox_id).checked;

	for (var x = 0; x < maps.length; x++){
		if (show_maps) { maps[x].style["display"] = "inline-block"; }
		else 		   { maps[x].style["display"] = "none"; }
	}
}//end Show_Hide_Maps() //************************************************




//************************************************************************
function Show_Hide_Opts(id_show_hide, id_state, d_or_v, show, hide, init) {

	var ele	  = document.getElementById(id_show_hide); 	//<element> to show/hide
	var state = document.getElementById(id_state); 		//<input> value = "checked" or ""

	//ele    : element to show/hide
	//state  : <input hidden> element.  Preserves state via form/URL value 
	//d_or_v : "display" or "visibility"
	//show   : "block", or "inline-block, or "table-cell", or "visible" etc...
	//hide   : "none"   or     "none"     or    "none"     or  "hidden"
	//init	 : only used at end of window.onload to set selected initial state. (do not toggle)


	//If init, flip state.value, so as to actually change to the initial selected state.
	if ((typeof init !== "undefined") && (init == "init")) {
		if (state.value != "false") {state.value = "false";}
		else 						{state.value = "true";}
	}


	if (state.value == "false") {
		ele.style[d_or_v] = show; 		//<div>
		state.value 	  = "true"; 	//<input>
	}
	else {
		ele.style[d_or_v] = hide; 		//<div>
		state.value 	  = "false"; 	//<input>
	}

}//end Show_Hide_Opts() //************************************************




function Show_Hide_Aspect(ckbox) { //*************************************

	var weather_aspects = document.querySelectorAll(".a_" + ckbox.value);

	for (var i = 0; i < weather_aspects.length; i++)
	{
		if  (ckbox.checked && (weather_aspects[i].tagName == "SPAN"))   //for .newday header in Display_V()
				{weather_aspects[i].style["display"]  = "inline";}
		else if (ckbox.checked)											//for other td & th data cells
				{weather_aspects[i].style["display"]  = "table-cell";}
		else 	
				{weather_aspects[i].style["display"]  = "none";}
	}
}//end Show_Hide_Aspect() //**********************************************




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
		ACTIVE.parentElement.classList.remove("active");
	}

	//Set the label bg for the new checkbox...
	if ((target.tagName == "INPUT") || (target.tagName == "SELECT")) {
		target.parentElement.classList.add("active");
	}	

	//Save for next tab/click
	ACTIVE = target;

}//end Focus() //*********************************************************




function assign_events_to_aspect_checkboxes() { //************************
	var weather_aspect = document.querySelectorAll(".aspect_options");

	for (var x = 0; x < weather_aspect.length; x++){
		
		( function(xvalue) { weather_aspect[xvalue].onclick = function() {
			Show_Hide_Aspect(weather_aspect[xvalue]);
		} } )(x);
	}
}//end assign_events_to_aspect_checkboxes() //****************************




function Change_Radar_Range(radar, new_range){ //*************************

	//radar = radar object: images (pic list), options, etc...
	//new_range: 1 for N0R (143 mile range), 2 for N0Z (286 mile range).

	var rotating_pic = "";

	for (var i = 1; i <=2; i++) { //1 is default radar, 2 is custom/user supplied location.
		
		rotating_pic = document.getElementById('ROTATING_PIC_' + i);
		
		if (rotating_pic) {
			
			//Set the new list
			radar[i].pic_list = PIC_LIST[i][new_range];
			
			//Set the new displayed radar pic.
			rotating_pic.src = PIC_LIST[i][new_range][radar[i].current_pic];
		}
	}
}//end Change_Radar_Range() //********************************************




function  Display_VH(){ //************************************************

	var VH_option  = document.getElementById('VH');
	var V_data 	   = document.getElementsByClassName('data_V');
	var H_data 	   = document.getElementsByClassName('data_H');

	for (var location = 0; location < H_data.length; location++ )
	{
		if (VH_option.value == "H") {
			V_data[location].style['display'] = "none";
			H_data[location].style['display'] = "inline-table";
		}else
		if (VH_option.value == "N") {
			V_data[location].style['display'] = "none";
			H_data[location].style['display'] = "none";
		} else {
			V_data[location].style['display'] = "inline-table";
			H_data[location].style['display'] = "none";
		}
	}
}//end Display_VH() //****************************************************




window.onload = function(){ //********************************************

	//Used in function Focus(). Do not declare with var.  I don't know why, that's why.
	ACTIVE = document.activeElement;


	//Radar[1] will be the default, [2] will be user entered/custom site (if present).
	var Radar = [];
	<?= $INIT_RADARS ?>


	assign_events_to_aspect_checkboxes();


	document.onkeydown = function(event){Focus(event)}
	document.onkeyup   = function(event){Focus(event)}
	document.onclick   = function(event){Focus(event)}


	document.getElementById('location_search').onkeydown = function(event){Prevent_Some_Keys(event)}
	document.getElementById('default_ops').onclick = function(){parent.location = location.pathname};
	document.getElementById('reset_btn').onclick   = function(){parent.location = location.href};
	document.getElementById('submit2').focus();


	Theme_Select 		  = document.getElementById('THEME')
	Theme_Select.onchange = function() {document.getElementById('stylesheet').setAttribute('href',  "?CSS=" + this.value);}


	VH_Select 		   = document.getElementById('VH');
	VH_Select.onchange = function (){ Display_VH(); }


	Show_Radar 		   = document.getElementById('SHOW_RADAR'); //<checkbox>
	Show_Radar.onclick = function() {Show_Hide_Maps("radar_div", "SHOW_RADAR");}


	Show_Location_Opts 	 	   = document.getElementById("SHOW_LOCATION_OPTS"); //<button>
	Show_Location_Opts.onclick = function() {Show_Hide_Opts("LOCATION_OPTIONS", "SHOW_LOCATION_OPTIONS", "display", "block", "none")}


	Show_Weather_Opts 		  = document.getElementById("SHOW_WEATHER_OPTS"); 	//<button>
	Show_Weather_Opts.onclick = function() {Show_Hide_Opts("WEATHER_OPTIONS", "SHOW_WEATHER_OPTIONS", "display", "block", "none")}


	Show_Display_Opts 	 	  = document.getElementById("SHOW_DISPLAY_OPTS");   //<button>
	Show_Display_Opts.onclick = function() {Show_Hide_Opts("DISPLAY_OPTIONS", "SHOW_DISPLAY_OPTIONS", "display", "block", "none")}



	//If page is reloaded, makes sure form options state match the page contents. Mostly...
	document.getElementById('options_form').reset();

	

	//Show or hide various page elements based on cooresponding <form> values (<input>, <select>, etc).

	Show_Hide_Opts("LOCATION_OPTIONS", "SHOW_LOCATION_OPTIONS", "display", "block", "none", "init");
	Show_Hide_Opts("WEATHER_OPTIONS",  "SHOW_WEATHER_OPTIONS",  "display", "block", "none", "init");
	Show_Hide_Opts("DISPLAY_OPTIONS",  "SHOW_DISPLAY_OPTIONS",  "display", "block", "none", "init");

	if (document.getElementById("RADAR_OPTIONS_1"))
		{ Show_Hide_Opts("RADAR_OPTIONS_1",  "SHOW_RADAR_OPTIONS_1",  "display", "inline-block", "none", "init"); }

	if (document.getElementById("RADAR_OPTIONS_2"))
		{ Show_Hide_Opts("RADAR_OPTIONS_2",  "SHOW_RADAR_OPTIONS_2",  "display", "inline-block", "none", "init"); }

	//Show only selected weather aspects (temp, wind, etc.).
	var aspect_options = document.querySelectorAll(".aspect_options");
	for (var aspect=0; aspect < aspect_options.length; aspect++) {Show_Hide_Aspect(aspect_options[aspect]);}

	//Display weather info in the selected format (vertical or horizontal).
	Display_VH();

	Show_Hide_Maps("radar_div", "SHOW_RADAR");

	//What time is it?
	Time_Stamp('insert', 'timestamp');

}//end window.onload //***************************************************
</script>

<?php
}//end js_Main() //************************************************************/





function Header_crap() {//*****************************************************/
	ob_get_clean(); //Should be empty. Should be...

	header('Content-type: text/html; charset=UTF-8');
	echo "<!DOCTYPE html>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'."\n";
	echo '<link rel="shortcut icon" href="http://d/D.ico"/>'."\n";
	echo "<title>Weather</title>\n";
}//end Header_crap() {//*******************************************************/





function Style_Sheet() {//**********************************************************/
	global $HOURS_TO_SHOW, $DONT_WRAP_MAP, $SHOW_RADAR_OPTIONS,
		   $SHOW_LOCATION_OPTIONS, $SHOW_WEATHER_OPTIONS, $SHOW_DISPLAY_OPTIONS; 
?>

<style>
	/** @viewport{ zoom: 1.0; width: extend-to-zoom; }**/

	/* //##### html *  ???? //##### */
	html * { font-family: arial; box-sizing: border-box; }

	html { overflow-y: scroll; }

	div { padding: 0; margin: 0 }
	pre	{ font-family: courier; margin: 0; }
	h2 	{ font-size: 1.5em; margin: 0; }
	img	{ vertical-align: top; }

	caption	{ font-size: 1.5em; margin: 0; font-weight: bold; border: 1px solid black; border-bottom: 0; }

	label {	display: inline-block; height: 24px; padding: 2px .5em 0 .5em; margin : 0 .2em 0 0; white-space: nowrap; }

	input[type="checkbox"] { display: inline-block; margin: 0 2px 0 0; }
	input[type="text"]	   { height: 20px; position: relative; padding: 0 0 0 0; margin: 0 0 0 0; border: 1px solid }

	select { display: inline-block; border: 1px solid;}

	#location_search_ckbox_label { margin: 0; width: 194px; }
	#location_search_label { margin: 0 -160px 0 0; padding-left: 4px; padding-right: .5em; position: relative; left: -172px; }
	#location_search  { width: 160px; }
	#rain_threshold	  { width:1.5em; padding: 1px 0 0 2px; }
	#show_radar_label { margin-left: 1em }
	#test_mode 		  { float: right; margin-right: 0 }
	.rotate_loops_label { margin-right: 0; }

	button { border: 1px solid ; border-radius: 4px; height: 24px; margin: 0; }
	button::-moz-focus-inner { border: 0; }

	#top 	   { display: table; width: 100%; border: 0; padding: 2px }
	#top div   { display: table-cell; }
	#top_left  {}
	#top_right { text-align: right; }

	/*class for a couple, but not all, show/hide buttons*/
	.show_hide_buttons {  padding: 0 .2em 0 0; margin: 0; }

	#MAP_OPTIONS	  { display: inline }

	#theme_label  {margin-left: 1em;}

	.w_container { display: inline-block; vertical-align: top; }

	.options_group { border: 1px solid; padding: 0; margin: 0 0 .4em 0; }

	/* display for .data will be changed to inline-table for selected mode (vertical or horizontal) in window.onload */
	/* It can be a couple seconds faster to start out none, plus it is less visually jarring. */
	
	.data		{ border: 1px solid black; border-collapse: collapse; display: none; margin: 0 .5em .5em 0; vertical-align:top; }
	.data th, .data td { border: 1px inset gray; text-align: center; vertical-align: top; padding: 0 .2em; }
	.data th	{}
	.data td	{ min-width: 2.5em; max-width: 3.7em; white-space: normal; } /*Default for V display.*/

	.data_H th 	 { min-width: 6em; } /*So the left most "header" column of the "horizontal" display doesn't wrap when table is wider than the page.*/
	
	.newday	{ border-top: 1px solid } /*rgb(63,131,245)*/

	.hdr		{ font-weight: bold; padding: 0 .3em;}
	.time		{}
	.temp		{}
	.wind_mph	{}
	.wind_dir	{ text-align: left; padding: 0 0 0 0.25em; }
	.rain		{}
	.clouds		{}
	.humidity	{}
	.fog		{ max-width: 8em; padding: 0 .5em;}
	.thndr		{}
	.frz_rain	{}
	.sleet		{}
	.snow		{}

	.not_found	{ border: 2px solid; font-weight: bold; text-align: center; } /*rgb(63,131,245)*/

	.messages	{ border: 2px solid; display: inline-block; margin: 0 .5em .5em 0; width: 20em; }
	.messages_H	{ border: 2px solid; display: inline-block; margin: 0 .5em .5em 0; }

	.indent		{ margin: 0 .5em }

	#wrap_map	{ white-space: nowrap; }

	.submit_default { display: inline-block; float: right; border: solid 0px red;}

	.submit			{ width: 9em; margin: 0 0 0 0; }

	#submit1 {}
	#submit2 { margin-right: 3em }

	#reset_btn		{ }
	#default_ops 	{ }

	#MAP_OPTIONS.radar_div { margin: 0; border: 0; }

	.radar_div 		 { position: relative; border: solid 0px #444; text-align: right; margin-bottom: .5em; white-space: normal; }
	.radar_div 		 { vertical-align: top; display: none; } /*If selected, will be changed to inline-block at end of window.onload*/

	.radar_view		  { border: 1px solid #444; text-align: center; margin: .20em 0 0 0; font-size: 85%; }
	.radar_view input { margin-left: .0em; margin-right: .0em; }
	.radar_view label { }

	.radar_controls	 { padding: 0px 0 0px 0; border: 0px solid #444; margin: .3em 0 0 0;}

	.start_stop 	 { width: 70px; padding: 0; margin: 0; float: left;  }
	.radar_opts_btn	 { width: 36px; display: inline-block; float: right; font-size: .75em; }

	.imgbar			 { display: inline-block; margin: 0 5px 0 0; font-size: 75%;
					   width: 472px; height: 23px; border-collapse: collapse; }
	.imgbar td		 { width: 59px;  height: 23px; border: 1px solid #444; color: #777; text-align: center;  padding: 0; cursor: default}

	.radar_opts_div  { white-space: nowrap; font-size: 92%;}

	.radar_opts_div  { width: 513px;  padding: 0; margin: .1em 0 .1em 0; border: solid 1px #444; text-align: left; }
	
	.loops	{ margin-right: 0; padding: 0; white-space: nowrap}

	#timestamp	 	{ margin: .2em 0 .2em 0; padding: 1px .3em 0 .2em; display: inline-block; border: 1px solid; }
	#timestamp_row  { margin-bottom: .3em; }

	.location_name	{ text-align: left; margin-left: 4em; }
	.fine_print  	{ font-size: 90%; }
	.TESTING_MSG 	{ color: red; }

<?php
	// See Load_Themes() for the rest of the default styles (mostly colors).


	if ($DONT_WRAP_MAP == "checked") {echo "	.w_container {white-space: nowrap;}\n";}

	//Adjust left margin for location name if hours < HOURS_MIN, so it's not out of box (if it's a long name).
	if ($HOURS_TO_SHOW < HOURS_MIN) { echo ".location_name {margin-left: .2em;}\n";}

echo "</style>\n\n";
}//end Style_Sheet() //*************************************************************/





// "Main" *********************************************************************/

Init();

Load_Themes(); //Needed before Get_GET().

Get_GET(); //needed before Style_Sheet(), User_Options()

Header_crap();

Style_Sheet(); //Defaults - place before any other stylesheets.

?>
<link id=stylesheet rel='stylesheet' property='stylesheet' href='?CSS=<?= $THEME ?>'>
<meta name="viewport" content="initial-scale=1">

<form id=options_form name=USER_OPTIONS method=get>
<?php



//Top row options ********************************************
$theme_options = "";
foreach ($THEMES['name'] as $key => $theme_name) {
	if ($THEME == $key) {$selected = " selected";} else {$selected = "";}
	$theme_options .= "\n<option value={$key}{$selected}>$key: {$theme_name}</option>";
}
?>
	<div class=options_group>
		<div id=top>
			<div id=top_left>
				<button type=button id=SHOW_LOCATION_OPTS class=show_hide_buttons>&#9660;&#9650;Locations</button>
				<button type=button id=SHOW_WEATHER_OPTS  class=show_hide_buttons>&#9660;&#9650;Weather Options</button>
				<button type=button id=SHOW_DISPLAY_OPTS  class=show_hide_buttons>&#9660;&#9650;Display Options</button>
				
				<input type=hidden  id=SHOW_LOCATION_OPTIONS name=SHOW_LOCATION_OPTIONS value="<?= $SHOW_LOCATION_OPTIONS ?>">
				<input type=hidden  id=SHOW_WEATHER_OPTIONS  name=SHOW_WEATHER_OPTIONS  value="<?= $SHOW_WEATHER_OPTIONS ?>">
				<input type=hidden  id=SHOW_DISPLAY_OPTIONS  name=SHOW_DISPLAY_OPTIONS  value="<?= $SHOW_DISPLAY_OPTIONS ?>">
				
				<label id=theme_label>Color Themes: <select id=THEME name=THEME><?= $theme_options ?></select></label>
			</div>
			<div id=top_right>
				<button type=submit id=submit1 class=submit>Submit</button>
			</div>
		</div>
	</div>
<?php




//Locations, Weather aspects, Hours to display, Radar, etc...
User_Options();




//Time Stamp, Data Source, Theme Options, Default, Reset, & Submit buttons
?>
<div id=timestamp_row>
	<span id=timestamp></span>
	(To "save" the current options, press Submit, then bookmark the page.)

	<span class=submit_default>
		<button type=submit id=submit2 class=submit>Submit</button>
		<button type=button id=reset_btn>Reload Current Options</button>
		<button type=button id=default_ops>Load Default Options</button>
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
			
			Display_Weather_H($location);
			Display_Weather_V($location);
		}
		else {
			if ($DISPLAY_VH) { echo '<div class=messages_H>'.$MESSAGES[$location].'</div>';; }
			else			 { echo '<div class=messages>'.$MESSAGES[$location].'</div>';; }
		}
	}
	echo "</div>\n"; //end locations



	//Show Radars...
	if ($LOCATION_FOUND) {
		Show_Radar(2); //custom site...
	} 
	
	if ( !$LOCATION_FOUND ||
		( ($CUSTOM_RADAR_SITE != RADAR_SITE_DEF) && (count($SHOW_LOCATIONS) > 1) ) )
	{
		Show_Radar(1); //default site...
	}
	
	//US map...
	echo "<hr><div id=us_map class=radar_div>";
	if ($TEST_MODE){
		echo '<a href="/weather/weather.gov/ridge/Conus/RadarImg/latest.gif" target=_blank>';
		echo '<img id=radar_us src="/weather/weather.gov/ridge/Conus/RadarImg/latest_Small.gif" alt="Test Mode Radar Image - US"></a>';
	}
	else {
		echo '<a href="'.RADAR_URL_US.'" target=_blank>';
		echo '<img id=radar_us src="'.RADAR_URL_US_SMALL.'" alt="Radar Image - US"></a>';
	}
	echo "</div>";


echo "</div>\n"; //end weather_div
echo "</form>\n\n";


?>
<div style='clear: both; border: 2px outset gray; height: .5em; '>&nbsp;</div>
<?php



//Load & start the javascripts...

js_Init_Radar_URLs_etc();

js_Radar_Loop_functions();

js_Prevent_Some_Keys();

js_Time_Stamp();

js_Main();
//
// end "Main" *****************************************************************/







################################################################################
if ($TEST_MODE) {
	//dump_array($var, $name="", $ECHO = 1, $PRE=1, $BRDR=1, $DSPLY=1, $VD=0, $LVL=0)

	dump_array($_GET			 ,'$_GET'			  ,1,1,1,2);
	dump_array($SHOW_LOCATIONS   ,'$SHOW_LOCATIONS'   ,1,1,1,2);
	dump_array($DEFAULT_ASPECTS  ,'$DEFAULT_$ASPECTS' ,1,1,1,2);
	dump_array($SELECTED_ASPECTS ,'$SELECTED_$ASPECTS',1,1,1,2);
	dump_array($DISPLAY_ORDER    ,'$DISPLAY_ORDER'    ,1,1,1,2);
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