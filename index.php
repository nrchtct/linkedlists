<?php

$app_path = "linkedlists/";
include $app_path.'config.php';

$app_url = $website_url.$app_path;

if ($data_url=="") $data = $app_path."data.csv";
else $data = $data_url;

// load data
$rows   = array_map('str_getcsv', file($data));
$header = array_shift($rows);
$items  = array();
foreach($rows as $row) $items[] = @array_combine($header, $row);

// get keywords & collaborators from entries
$left_items = array();
$right_items = array();
$middle_items = array();

foreach ($items as $i => $item) {
	if ($items[$i][$facet_left]!="") {
		$items[$i][$facet_left] = explode(";", $items[$i][$facet_left]);
		$left_items = array_unique (array_merge ($left_items, $items[$i][$facet_left]));	
	}
	if ($items[$i][$facet_right]!="") {
		$items[$i][$facet_right] = explode(";", $items[$i][$facet_right]);
		$right_items = array_unique (array_merge ($right_items, $items[$i][$facet_right]));	
	}
	if ($facet_middle!="" && $items[$i][$facet_middle]!="") {
		$items[$i][$facet_middle] = explode(";", $items[$i][$facet_middle]);
		$middle_items = array_unique (array_merge ($middle_items, $items[$i][$facet_middle]));		
	}
}
asort($left_items);
asort($right_items);

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $website_title ?></title>

<meta name="title" content="<?php echo $website_title ?>">
<meta name="description" content="<?php echo $website_description ?>">

<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo $website_baseurl ?>/">
<meta property="og:title" content="<?php echo $website_title ?>">
<meta property="og:description" content="<?php echo $website_description ?>">
<meta property="og:image" content="<?php echo $app_path ?>img/boxes.png">

<meta name="twitter:card" content="summary_large_image">

<link rel="icon" type="image/png" href="<?php echo $app_path ?>img/favicon.png">
<link rel="icon" type="image/png" href="<?php echo $app_path ?>favicon-192.png" sizes="192x192">

<style>

@font-face {
    font-family: hkgrotesk;
    src: url("<?php echo $app_path ?>fonts/hkgrotesk-bold.woff") format("woff");
    font-weight: bold;
}

@font-face {
    font-family: hkgrotesk;
    src: url("<?php echo $app_path ?>fonts/hkgrotesk-regular.woff") format("woff");
    font-weight: normal;
}

@font-face {
    font-family: hkgrotesk;
    src: url("<?php echo $app_path ?>fonts/hkgrotesk-light.woff") format("woff");
    font-weight: 100;
}

* {
  transition-duration: .5s;
  transition-property: background-color;	
}

/* default colors */
:root {
  --light_background: hsl(0,0%,98%);
  --light_text: hsl(0,0%,0%);
	--light_border: hsl(0,0%,75%);
	--light_border_hover: hsl(0,0%,50%);
	--light_link: hsl(209,100%,45%);
	--light_filter: hsl(330,100%,40%);
	--dark_background: hsl(0,0%,8%);
	--dark_text: hsl(0,0%,75%);
	--dark_border: hsl(0,0%,25%);
	--dark_border_hover: hsl(0,0%,50%);
	--dark_link: hsl(209,100%,40%);
	--dark_filter: hsl(330,100%,40%);
}


/* light and default */
body { color: var(--light_text); background-color: var(--light_background);} 
h2 { color: var(--light_text); }
a:link, a:visited { color: var(--light_link); }
#l .active span, #r .active span , #m .active { color: var(--light_filter); }
#m li { color: var(--light_text);}
#m h2 a { color: var(--light_text);}
::selection {   background-color: var(--light_link); }	
#m li img:not(.dark) { opacity: 1; }	
#m li img {border-color: var(--light_border);}
#m li:hover img {border-color: var(--light_border_hover);}


/* dark mode */
@media (prefers-color-scheme: dark) {
	body { color: var(--dark_text); background-color: var(--dark_background);} 
	h2 { color: var(--dark_text); }
	a:link, a:visited { color: var(--dark_link); }
	#l .active span, #r .active span , #m .active { color: var(--dark_filter); }
	#m li { color: var(--dark_text); }
	#m h2 a { color: var(--dark_text);}
	::selection { background-color: var(--dark_link); }
	#m li img:not(.dark) { opacity: .66; transition: opacity .5s; }
	#m li:hover img:not(.dark) { opacity: 1; }	
	#m li img {border-color: var(--dark_border);}	
	#m li:hover img {border-color: var(--dark_border_hover);}
		
}


canvas, #l, #m, #r {
	opacity: 1; 
  transition-duration: .2s;
  transition-property: opacity;	
}


body {
	-webkit-text-size-adjust: none;
	margin: 0; padding: 0;
	font-family: hkgrotesk, sans-serif;
/*	font-size: 1.25vw;*/
	font-size: 10px;
	font-weight: 100;
	line-height: 1.3em;
	margin: auto;
	overflow-y: scroll;
	min-height: 100vh;	
}

::selection {
	color: white;
}

h1 {
	font-size: 2em;	
	font-weight: 100;
	line-height: 1.4em;
	margin-top: .7em;
	margin-bottom: .6em;
	margin-left: -.075em;
	white-space: nowrap;
}

h1:hover {
	font-weight: 100;
	text-decoration: underline;
	cursor: pointer;
}

h1.passive:hover {
	text-decoration: none;
	cursor: default;
}

h2 {
	font-size: 1em;
	font-weight: bold;
	margin-top: 1em;
	margin-bottom: 1.5em;
	transform: translate3d(0,0,0); 
	text-transform: uppercase;
	letter-spacing: .05em;
}


div { 	margin: auto; }

a:link, a:visited { text-decoration: none; }
a:hover { text-decoration: underline;}

#l .active span, #r .active span { font-weight: bold; } 

#l li.active:hover, #r li.active:hover {text-decoration: none !important; }

ul {
	list-style-type: none;
	padding: 0;
	margin: 0;
}

li { 	margin: 0; padding: 0; }

#l ul, #r ul { 	user-select: none; width: 80%; }

#l, #r { 
	position: fixed; 
	top: 1.5em;
	max-height: 100%;
	bottom: 2vw;
}

#l li span:hover, #r li span:hover { text-decoration: underline;}
#l p img {height: 1em; }
#l li span, #r li span { cursor: pointer; }

#l li, #r li {
  transition-duration: .4s, .4s, .4s;
  transition-property: font-size, opacity, line-height;
}


/* layout */

#l, #m, #r {
	background-color: xred;
}

h1 {width: 22.5vw; white-space: normal;}
#l {width: 20vw; margin: 0 3vw;}
#m {width: 45vw; margin-top: 1.5em; padding-left: 4vw; }
#r {width: 20vw; padding-top: 4.325em; right: 3vw; }

/*  search  */

#m h2 { float: left; } 

#ll_search {
	padding: 0;
	margin: 2em 0 2.5em 0;
	border: 0px;
	width: 15vw;
	display: block;
	border: 1px solid #999;
}



/* areas */

#l { 	transform: translate3d(0,0,0); }
#l h2 { margin-top: 1.5em; margin-bottom: 0.75em;}
#l li {padding: 0; margin: .2em 0; line-height: 1.2em; font-weight: 100; }
#l p {transform: translate3d(0,0,0); }

/* types */
#m img { width: 44.4vw; height: 11.1vw; margin-bottom: 0em; }

#m li img {
	border-width: .08em;
	border-style: solid;
}

#m li { margin-bottom: 1.5em; clear: both; 	overflow: hidden;  }

#m ul { padding-bottom: 2em; padding-top: 0.1em; }
#m h2 a.passive:hover { text-decoration: none; cursor: default;}

#m.init li { transition-duration: 0s; }
#m li {
	max-height: 30vw;
  transition-duration: .3s, .3s, .3s;
  transition-property: opacity, max-height, margin-bottom;
}

#m li.hidden {
	opacity: 0; max-height: 0; margin-bottom: 0;
}

#l p a, #m li a { font-weight: normal; }

#m li { cursor: default;}
#m li span { cursor: help;}
#m li em { font-style: normal; cursor: pointer; }
#m li em:hover {text-decoration: underline;}

/* authors */
#r ul { margin-top: .5em; float: right;}
#r li { padding: 0; margin: .2em 0; line-height: 1.2em; font-weight: 100; }
#r h2 { margin-bottom: 1.1em; text-align: right; direction: rtl; hyphens: manual; }

#r { text-align: right; }

#canvas {
	top: 0; left: 0;
	width: 100vw;
	height: 100vh;
	position: fixed;
	transition: opacity .3s ease-in;
}

#canvas {	z-index: -1; }

#l, #m, #r {	z-index: 1; }


#email em {
	white-space: nowrap;
	word-spacing: -.3em;
	font-style: normal;
}

#imprint {
	font-size: .75em;
	line-height: 1.25em;
}

</style>
</head>
<body>
<div>

<div id="l">
<h1><span><?php echo $website_title ?></span></h1>
<p><?php echo $about_section ?></p>

<p id="imprint">
<?php echo $contact_details ?>
</p>

<h2><?php echo $facet_left ?></h2>
<ul>
<?php
foreach ($left_items as $topic) {
	$slug = preg_replace("/[^A-Za-z0-9]/", '', $topic);
	echo "<li id='$slug'><span>".htmlspecialchars($topic)."</span></li>\n";
}
?>	
</ul>

</div>

<div id="r">
<h2><?php echo $facet_right ?></h2>
<ul>
<?php
foreach ($right_items as $name) {
	$slug = preg_replace("/[^A-Za-z0-9]/", '', $name);
	echo "<li id='$slug'><span>$name</span></li>\n";
}
?>	
</ul>
</div>

<!-- <div id="q">
</div> -->

<div id="m" class="init">


	
<h2>
<input type="search" placeholder="Search" id="ll_search" name="q">
	<?php
	
	if (count($types)==1) echo $types[0];
	else {
		foreach ($types as $i=>$t) {
			if (count($types)==$i+1) echo " &amp; ";
			else if ($i>0) echo ", ";
			echo "<a href='#$t'>$t</a>";
		}		
	}
	
?></h2>
<ul>

<?php

foreach ($items as $item) {
	
	$class_slug = $item["type"];
	
	if (is_array($item[$facet_left])) foreach ($item[$facet_left] as $topic) {
		$class_slug = $class_slug." ".preg_replace("/[^A-Za-z0-9]/", '', $topic);
	}
	
	if (is_array($item[$facet_right])) foreach ($item[$facet_right] as $name) {
		$class_slug = $class_slug." ".preg_replace("/[^A-Za-z0-9]/", '', $name);
	}
	
	echo "<li class='$class_slug'>";
	echo "<a href='{$item["link"]}'";
	if ($item["hover"]!="") echo " title='{$item["hover"]}' ";
	echo	">";
		
	if ($item["img"]!="") echo "<img alt='{$item["title"]}' src='{$app_path}banners/{$item["img"]}'>";

	$regexp = "/(".implode($middle_items, "|").")/i";
  $item["info"] = preg_replace($regexp, "<em>$1</em>", $item["info"]); 

	echo "{$item["title"]}</a> {$item["info"]}</li>\n";
}

?>
	
</ul>

</div>

<canvas id="canvas"></canvas>


<script>

// polyfill
if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = function (callback, thisArg) {
        thisArg = thisArg || window;
        for (var i = 0; i < this.length; i++) {
            callback.call(thisArg, this[i], i, this);
        }
    };
}


var website_title = "<?php echo $website_title ?>";
var view = 0; // 1: left, 2: types, 3: right, 4: search
var vin = -1; // index of keyword or person
var hash = '';
var l = []; var m = []; var r = [];
var lm = []; // left → middle, i.e., from areas to items
var rm = []; // right → middle, i.e., from authors to items
var ml = []; // middle → left, i.e., from items to areas
var mr = []; // middle → right, i.e., from items to authors
var m_iv = []; // items in view
var m_bb = []; // items bounding boxes
var vw = document.documentElement.clientWidth/100;
var vh = document.documentElement.clientHeight/100;
var can = document.getElementById('canvas');
var can_bb = null;
var ctx = false;
var hover = null;
var redraw_timeouts = [];
function x(s) { return document.querySelector(s); }
function X(s) { return document.querySelectorAll(s); }
var strokeColor = '50,50,50';
var gap = 200;
var types = ['<?php echo implode("','", $types) ?>'];
var search_box = x("#ll_search");

// remove ems from titles
X("#m li span").forEach(function(e){
	var title = e.getAttribute("title");
	title = title.replace(/<em>/g, "").replace(/<\/em>/g, "");
	e.setAttribute("title", title);
 });

function items() {

	// set hidden classes
	
	// no filter
	if (view==0) X("#m li.hidden").forEach(function(e){e.classList.remove("hidden")});
	else {
		X("#m li").forEach(function(e){e.classList.add("hidden")});
		
		// left
		if (view==1) {
			for (var i = 0; i < lm[vin].length; i++) lm[vin][i].classList.remove("hidden");
		}
		// type
		else if (view==2) {
			X("#m li").forEach(function(e){
				if (e.classList.contains(types[vin])) e.classList.remove("hidden");
			})
		}
		// right
		else if (view==3) {	
			for (var i = 0; i < rm[vin].length; i++) rm[vin][i].classList.remove("hidden");
		}
		else if (view==4) {
			
			var query = decodeURIComponent(hash.substring(2)).toLowerCase();
			
			X("#m li").forEach(function(e){				
				if (e.outerHTML.toLowerCase().includes(query)) e.classList.remove("hidden")			
				else e.classList.add("hidden")			
			});
			
		}
	}
}


	
function links() {
	if (typeof window.matchMedia !== "undefined" &&
		window.matchMedia("(prefers-color-scheme: dark)").matches) strokeColor = '200,200,200';
	else strokeColor = '100,100,100';		
	
	// clear canvas
	var w = document.documentElement.clientWidth;
	var h = document.documentElement.clientHeight;
	ctx.clearRect(0, 0, w, h+gap);
	
	// get current position of canvas element
	can_bb = can.getBoundingClientRect();
	
	// areas
	for (var li = 0; li < lm.length; li++) {
		var le = l[li];
		var aid = le.id;
		if (aid==hash) continue;		
		for (var i = 0; i < lm[li].length; i++) {
			var me = lm[li][i];
			var mi = getIndex(me);
			if (me.classList.contains("hidden") || !m_iv[mi]) continue;			
			if (hover==le || hover==me) link_lm(li, mi, .7);
			else link_lm(li, mi, .1);			
		}
	}
	
	// authors
	for (var ri = 0; ri < rm.length; ri++) {
		var re = r[ri];
		var pname = re.textContent;
		if (re.id==hash) continue;
		for (var i = 0; i < rm[ri].length; i++) {
			var me = rm[ri][i];
			var mi = getIndex(me);
			if (me.classList.contains("hidden") || !m_iv[mi]) continue;			
			if (hover==re || hover==me) link_mr(mi, ri, .7);
			else link_mr(mi, ri, .1);
		}
	}	
}

function link_lm(a, b, o) {
	var bb1 = x("#l li:nth-of-type("+(a+1)+") span").getBoundingClientRect();
	var bb2 = m_bb[b];
	
	var h = bb2.height/2;
	
	var dx = -can_bb.left;
	var dy = -can_bb.top;				
	
	var start = { x: dx+bb1.left+bb1.width+vw/4, 							y: dy+bb1.top+.55*bb1.height  };
	var cp1 =   { x: dx+bb1.left+bb1.width+(20*vw-bb1.width), 	y: dy+bb1.top+.55*bb1.height  };
	var cp2 =   { x: dx+bb2.left-vw*4,  				 								y: dy+bb2.top+h };
	var end =   { x: dx+bb2.left-vw/2,													y: dy+bb2.top+h };

	ctx.lineWidth = .075*vw;
	ctx.strokeStyle = 'rgba('+strokeColor+','+o+')';
	ctx.beginPath();
	ctx.moveTo(start.x, start.y);
	ctx.bezierCurveTo(cp1.x, cp1.y, cp2.x, cp2.y, end.x, end.y);
	ctx.stroke();
}

function link_mr(a, b, o) {
	var bb1 = m_bb[a];
	var bb2 = x("#r li:nth-of-type("+(b+1)+") span").getBoundingClientRect();

	var h = bb1.height/2;
	
	var dx = -can_bb.left;
	var dy = -can_bb.top;	
	
	var start = { x: dx+bb1.left+bb1.width, 		y: dy+bb1.top+h  };
	var cp1 =   { x: dx+bb1.left+bb1.width+vw*4,  		y: dy+bb1.top+h  };
	var cp2 =   { x: dx+bb2.left-(20*vw-bb2.width),  y: dy+bb2.top+.55*bb2.height };
	var end =   { x: dx+bb2.left-vw/4,								y: dy+bb2.top+.55*bb2.height };

	ctx.lineWidth = .075*vw;
	ctx.strokeStyle = 'rgba('+strokeColor+','+o+')';
	ctx.beginPath();
	ctx.moveTo(start.x, start.y);
	ctx.bezierCurveTo(cp1.x, cp1.y, cp2.x, cp2.y, end.x, end.y);
	ctx.stroke();
}

function items_bb() {	
	// get which items are shown and get their position
	X("#m li").forEach(function(e) {		
		if (e.classList.contains('hidden')) return;
		var i = getIndex(e);
		m_bb[i] = e.getBoundingClientRect();
		if (m_bb[i].bottom >= vw && m_bb[i].top <= window.innerHeight-vw) m_iv[i]=true;
		else m_iv[i]=false;
	});
}

function areas() {

	var areas = [];
	for (var li = 0; li < lm.length; li++) {
		areas[li] = 0;
		for (var i = 0; i < lm[li].length; i++) {
			var m_li = lm[li][i];
			if (!m_li.classList.contains('hidden') && m_iv[getIndex(m_li)]) areas[li]++;			
		}
	}
	
	var sum = 0;
	for (var i = 0; i < areas.length; i++) sum=sum+areas[i];
	var shown = 0;
	for (var i = 0; i < areas.length; i++) if (areas[i]>0) shown++;

	for (var i = 0; i < areas.length; i++) {
		var el = x("#l li:nth-of-type("+(i+1)+")");
		if (areas[i]==0) var scale = 0;
		else var scale = interval(areas[i], 0, 4, .33, 1.33, true, true);
		if (areas[i]<1) el.style.opacity = areas[i];
		else el.style.opacity = areas[i];
		el.style["font-size"] = scale+"em";
	}
}

function getIndex(el) {
    var i = 0;
    while ( (el = el.previousElementSibling) ) i++;    
    return i;
}

function authors() {

	var people = [];
	for (var ri = 0; ri < rm.length; ri++) {
		people[ri] = 0;
		for (var i = 0; i < rm[ri].length; i++) {
			var m_li = rm[ri][i];
			if (!m_li.classList.contains('hidden') && m_iv[getIndex(m_li)]) people[ri]++;			
		}
	}
	
	var sum = 0;
	for (var i = 0; i < people.length; i++) sum=sum+people[i];
	
	var shown = 0;
	for (var i = 0; i < people.length; i++) if (people[i]>0) shown++;

	for (var i = 0; i < people.length; i++) {
		var el = x("#r li:nth-of-type("+(i+1)+")");
		if (people[i]==0) var scale = 0;
		else var scale = interval(people[i], 0, 3, .33, 1.33, true, true);
		if (people[i]<1) el.style.opacity = people[i];
		else el.style.opacity = people[i];
		el.style["font-size"] = scale+"em";
	}
	
}

function canvas() {
  var dpr = window.devicePixelRatio || 1;
  var rect = can.getBoundingClientRect();	
	var w = document.documentElement.clientWidth;
	var h = document.documentElement.clientHeight;	
	vw = w/100;
	vh = h/100;
  can.width = w * dpr;
  can.height = (h+gap)* dpr;
  ctx.scale(dpr, dpr);
	can.style.height = (h+gap)+"px";
	can.style.width = w+"px";
	ctx.clearRect(0, 0, w, h+gap);
}

function check_view() {
	view = 0;
	vin = -1;

	X(".active").forEach(function(e) {e.classList.remove("active")});
	X(".passive").forEach(function(e) {e.classList.remove("passive")});
	
	vin = types.indexOf(hash);

	// selected item type
	if (vin>-1) {
		view = 2;	
		
		for (var i = 0; i < types.length; i++) {
			
			var t = x("#m h2 a:nth-of-type("+(i+1)+")");
			
			if (vin==i) {
				t.classList.add("active");
				t.setAttribute("href", "#");
			}
			else {
				t.classList.remove("active");
				t.setAttribute("href", "#"+types[i]);				
			}
		}

		document.title = website_title+" · "+types[vin];
		search_box.value="";
	}
	// active search
	else if (hash.startsWith("q=")) {
		view = 4;
		document.title = website_title+" · "+decodeURIComponent(hash.substring(2));
		search_box.value = decodeURIComponent(hash.substring(2));
	}
	// active facet
	else {
		search_box.value="";
				
		if (types.length>1) for (var i = 0; i < types.length; i++) {
			x("#m h2 a:nth-of-type("+(i+1)+")").setAttribute("href", "#"+types[i])
		}

		X("#l li").forEach(function(e){
			var id = e.id;
			if (id!="" && hash==id) {
				view=1;
				vin=getIndex(e);
				e.classList.add("active");
				document.title = website_title+" · "+e.textContent;
			}			
		});
		
		X("#r li").forEach(function(e){
			var id = e.id;
			if (id!="" && hash==id) {
				view=3;
				vin=getIndex(e);
				e.classList.add("active");
				document.title = website_title+" · "+e.textContent;
			}			
		});
	}
	
	if (view==0) {
		x("h1").classList.add('passive');

		if (window.location.hash!="") {			
			history.pushState("", document.title, window.location.pathname+window.location.search);
		}
		document.title = website_title;			
	}
	else x("h1").classList.remove('passive');
}


function redraw() {

	items_bb();	
	areas();
	authors();	
	links();
	
	for (var i = 0; i < redraw_timeouts.length; i++) clearTimeout(redraw_timeouts[i]);
	
	for (var i = 0; i < 5; i++) {
		redraw_timeouts[i] = setTimeout(function() {
			items_bb();
			links();
		}, i*100);
	}
	
}

function redraw_staged() {
	check_view();		
	items(); 
	
	for (var i = 0; i < redraw_timeouts.length; i++) clearTimeout(redraw_timeouts[i]);
	
	redraw_timeouts[0] = setTimeout(function() {
		// canvas();
		// items_bb();
		areas();
		authors();
		// links();
	}, 500);

	for (var i = 1; i < 10; i++) {
		redraw_timeouts[i] = setTimeout(function() {
			items_bb();
			links();
		}, i*100);
	}
	
}

function interval(x, xmin, xmax, ymin, ymax, bound, log) {
	// make sure return value is withiin ymin and ymax
	if (typeof bound === "undefined") bound = false;
	if (typeof log === "undefined") log = false;

	if (xmin == xmax) return ymax;

	var y, m, n;

	if (log) {
		var logxmax = Math.log(xmax+1);
		var logxmin = Math.log(xmin+1);
		m           = ( ymax / logxmax - ymin) / (1 - logxmin );
		n           = ymin - m*logxmin;
		y           = m * Math.log(x+1) + n;
	}
	else {
		m           = (ymax - ymin) / (xmax - xmin);
		n           = -xmin * m + ymin;
		y           = x * m + n;
	}

	if (bound) {
		if (ymin<ymax) {
			y          = Math.min(ymax, y);
			y          = Math.max(ymin, y);
		}
		else {
			y          = Math.max(ymax, y);
			y          = Math.min(ymin, y);
		}
	}

	return y;
}

window.onresize = function(){
	vw = document.documentElement.clientWidth/100;
	vh = document.documentElement.clientHeight/100;	
	x("body").style.fontSize = 5+.5*vw+.5*vh+"px";	
	canvas();
	redraw();
}

setTimeout(function(){
	var ticking = false;
	window.onscroll = function(e) {
		
		if (view==0 && window.scrollY==0) x("h1").classList.add('passive');		
		else x("h1").classList.remove('passive');
		
	  if (!ticking) {
	    window.requestAnimationFrame(function() {
	      redraw();
	      ticking = false;
	    });
	    ticking = true;
	  }
	};
}, 1000)

document.onreadystatechange = function () {
	
	x("body").style.fontSize = 5+.5*vw+.5*vh+"px";
	
  if (document.readyState === 'interactive') {

		// associations between items, authors and areas
		X("#m li").forEach(function(e){m.push(e); ml[getIndex(e)] = []; mr[getIndex(e)] = []; });
		X("#l li").forEach(function(e){ l.push(e); lm[getIndex(e)] = []; });
		X("#r li").forEach(function(e){ r.push(e); rm[getIndex(e)] = []; });
		
		X("#m li").forEach(function(me){
			var mi = getIndex(me);
			var html = me.innerHTML;
			X("#l li").forEach(function(le){
				var li = getIndex(le);
				var aid = le.id;
				if (me.classList.contains(aid)) {
					lm[li].push(me);
					ml[mi].push(le);
				}
			});
			X("#r li").forEach(function(re){
				var ri = getIndex(re);
				var pid = re.id;
				if (me.classList.contains(pid)) {
					rm[ri].push(me);
					mr[mi].push(re);
				}
			});
			
		});
	
		// canvas
		if (can.getContext) ctx = can.getContext('2d');
		else ctx = false;
		canvas();
	
		hash = window.location.hash.substring(1);

		redraw_staged();
		setTimeout(function(){ redraw(); }, 1000);
		
		x("#m.init").classList.remove("init");
	
		setTimeout(function(){
			
			X("li span").forEach(function(el) {
				el.onmouseenter = function(e) {
					hover = e.target.parentNode;
					links();	
				}
			});
			
			X("#m li").forEach(function(el) {
				el.onmouseenter = function(e) {
					hover = e.target;
					links();	
				}
			});
			
			X("#r li span, #l li span, #m li").forEach(function(el) {
				el.onmouseleave = function(e) {
				hover = null;
				links();
				}
			});
			
		}, 1000);
	
  }
}


function hashchange(e) {
	hash = window.location.hash.substring(1);	
	redraw_staged();
}

window.onhashchange = hashchange;

X("#l li span, #r li span").forEach(function(el) {
	el.onclick = function(e){
		var id = e.target.parentNode.id;
		if (hash == id) {
			history.pushState("", document.title, window.location.pathname);
			hash="";
			redraw_staged();
		}
		else window.location.hash = id;
		e.preventDefault();
	}
});

X("#m li em").forEach(function(el) {
	el.onclick = function(e){

		var text = e.target.textContent;
		search_box.value=text;
		searchchange();
		e.preventDefault();
	}
});

function searchchange(){
	var text = search_box.value;
	
	if (text=="") {
		window.location.hash="";
		redraw_staged();
	}
	else window.location.hash = "q="+text;
	
};

search_box.onchange = searchchange;

// cancel search
search_box.oninput = function(e){
	if (e.target.value=="") window.location.hash="";
}

x("h1").onclick = function(e){

	setTimeout(function(){
		window.scrollTo({ top: 0, left: 0, behavior: 'smooth' });		
	}, 500);
	
	if (hash=="") return;
	hash="";
	redraw_staged();
	e.preventDefault();	
};

document.onkeyup = function(e) {
   if (e.key === "Escape") {
		 window.scrollTo({ top: 0, left: 0, behavior: 'smooth' });
		 search_box.blur();
		 hash="";
		 redraw_staged();
	 }
}



</script>
</div>
</body>
</html>