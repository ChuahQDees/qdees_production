
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>FormWizard_v1</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="colorlib.com">

<link rel="stylesheet" href="fonts/material-design-iconic-font/css/material-design-iconic-font.css">

<link rel="stylesheet" href="css/style.css">
<meta name="robots" content="noindex, follow">
<script nonce="ea0610b1-eebe-488a-bcfe-2b1a1e76a7b1">try{(function(w,d){!function(bu,bv,bw,bx){bu[bw]=bu[bw]||{};bu[bw].executed=[];bu.zaraz={deferred:[],listeners:[]};bu.zaraz.q=[];bu.zaraz._f=function(by){return async function(){var bz=Array.prototype.slice.call(arguments);bu.zaraz.q.push({m:by,a:bz})}};for(const bA of["track","set","debug"])bu.zaraz[bA]=bu.zaraz._f(bA);bu.zaraz.init=()=>{var bB=bv.getElementsByTagName(bx)[0],bC=bv.createElement(bx),bD=bv.getElementsByTagName("title")[0];bD&&(bu[bw].t=bv.getElementsByTagName("title")[0].text);bu[bw].x=Math.random();bu[bw].w=bu.screen.width;bu[bw].h=bu.screen.height;bu[bw].j=bu.innerHeight;bu[bw].e=bu.innerWidth;bu[bw].l=bu.location.href;bu[bw].r=bv.referrer;bu[bw].k=bu.screen.colorDepth;bu[bw].n=bv.characterSet;bu[bw].o=(new Date).getTimezoneOffset();if(bu.dataLayer)for(const bH of Object.entries(Object.entries(dataLayer).reduce(((bI,bJ)=>({...bI[1],...bJ[1]})),{})))zaraz.set(bH[0],bH[1],{scope:"page"});bu[bw].q=[];for(;bu.zaraz.q.length;){const bK=bu.zaraz.q.shift();bu[bw].q.push(bK)}bC.defer=!0;for(const bL of[localStorage,sessionStorage])Object.keys(bL||{}).filter((bN=>bN.startsWith("_zaraz_"))).forEach((bM=>{try{bu[bw]["z_"+bM.slice(7)]=JSON.parse(bL.getItem(bM))}catch{bu[bw]["z_"+bM.slice(7)]=bL.getItem(bM)}}));bC.referrerPolicy="origin";bC.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(bu[bw])));bB.parentNode.insertBefore(bC,bB)};["complete","interactive"].includes(bv.readyState)?zaraz.init():bu.addEventListener("DOMContentLoaded",zaraz.init)}(w,d,"zarazData","script");})(window,document)}catch(e){throw fetch("/cdn-cgi/zaraz/t"),e;};</script></head>
<body>
<div class="wrapper">
<form action id="wizard">

<h2></h2>
<section>
<div class="inner">
<div class="image-holder">
<img src="images/form-wizard-1.jpg" alt>
</div>
<div class="form-content">
<div class="form-header">
<h3>Registration</h3>
</div>
<p>Please fill with your details</p>
<div class="form-row">
<div class="form-holder">
<input type="text" placeholder="First Name" class="form-control">
</div>
<div class="form-holder">
<input type="text" placeholder="Last Name" class="form-control">
</div>
</div>
<div class="form-row">
<div class="form-holder">
<input type="text" placeholder="Your Email" class="form-control">
</div>
<div class="form-holder">
<input type="text" placeholder="Phone Number" class="form-control">
</div>
</div>
<div class="form-row">
<div class="form-holder">
<input type="text" placeholder="Age" class="form-control">
</div>
<div class="form-holder" style="align-self: flex-end; transform: translateY(4px);">
<div class="checkbox-tick">
<label class="male">
<input type="radio" name="gender" value="male" checked> Male<br>
<span class="checkmark"></span>
</label>
<label class="female">
<input type="radio" name="gender" value="female"> Female<br>
<span class="checkmark"></span>
</label>
</div>
</div>
</div>
<div class="checkbox-circle">
<label>
<input type="checkbox" checked> Nor again is there anyone who loves or pursues or desires to obtaini.
<span class="checkmark"></span>
</label>
</div>
</div>
</div>
</section>

<h2></h2>
<section>
<div class="inner">
<div class="image-holder">
<img src="images/form-wizard-2.jpg" alt>
</div>
<div class="form-content">
<div class="form-header">
<h3>Registration</h3>
</div>
<p>Please fill with additional info</p>
<div class="form-row">
<div class="form-holder w-100">
<input type="text" placeholder="Address" class="form-control">
</div>
</div>
<div class="form-row">
<div class="form-holder">
<input type="text" placeholder="City" class="form-control">
</div>
<div class="form-holder">
<input type="text" placeholder="Zip Code" class="form-control">
</div>
</div>
<div class="form-row">
<div class="select">
<div class="form-holder">
<div class="select-control">Your country</div>
<i class="zmdi zmdi-caret-down"></i>
</div>
<ul class="dropdown">
<li rel="United States">United States</li>
<li rel="United Kingdom">United Kingdom</li>
<li rel="Viet Nam">Viet Nam</li>
</ul>
</div>
<div class="form-holder"></div>
</div>
</div>
</div>
</section>

<h2></h2>
<section>
<div class="inner">
<div class="image-holder">
<img src="images/form-wizard-3.jpg" alt>
</div>
<div class="form-content">
<div class="form-header">
<h3>Registration</h3>
</div>
<p>Send an optional message</p>
<div class="form-row">
<div class="form-holder w-100">
<textarea name id placeholder="Your messagere here!" class="form-control" style="height: 99px;"></textarea>
</div>
</div>
<div class="checkbox-circle mt-24">
<label>
<input type="checkbox" checked> Please accept <a href="#">terms and conditions ?</a>
<span class="checkmark"></span>
</label>
</div>
</div>
</div>
</section>
</form>
</div>

<script src="js/jquery-3.3.1.min.js"></script>

<script src="js/jquery.steps.js"></script>
<script src="js/main.js"></script>


<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/v84a3a4012de94ce1a686ba8c167c359c1696973893317" integrity="sha512-euoFGowhlaLqXsPWQ48qSkBSCFs3DPRyiwVu3FjR96cMPx+Fr+gpWRhIafcHwqwCqWS42RZhIudOvEI+Ckf6MA==" data-cf-beacon='{"rayId":"85128a19cec84067","version":"2024.2.0","token":"cd0b4b3a733644fc843ef0b185f98241"}' crossorigin="anonymous"></script>
</body>
</html>
