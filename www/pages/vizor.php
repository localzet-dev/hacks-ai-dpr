<?php


function mainpages_vizor(){
    $file = '
    <html class="no-js" lang="ru">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="x-ua-compatible" content="ie=edge" />
		<title>Визор</title>
		<meta name="viewport" content="width=device-width,initial-scale=1" />
        <link rel="stylesheet" href="https://pay.exesfull.com/assets_select/css/bst.css?v=1.1.0">
        <link rel="stylesheet" href="https://pay.exesfull.com/assets_select/css/fa.css?v=1.1.0">
        <link rel="stylesheet" href="https://pay.exesfull.com/assets_select/css/shards.min.css">
        <link rel="stylesheet" href="https://pay.exesfull.com/assets_select/css/shards-demo.css?v=1.1.0">
        <link rel="icon" type="image/png" href="https://wcpt.exesfull.com/favicon.ico" />
        <link rel="apple-touch-icon" href="https://wcpt.exesfull.com/favicon.ico">
        <link rel="apple-touch-icon-precomposed" href="https://wcpt.exesfull.com/favicon.ico"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            .welcome{
            }
            .checkbox-ios {
	display: inline-block;    
	height: 28px;    
	line-height: 28px;  
	margin-right: 10px;      
	position: relative;
	vertical-align: middle;
	font-size: 14px;
	user-select: none;	
}
.checkbox-ios .checkbox-ios-switch {
	position: relative;	
	display: inline-block;
	box-sizing: border-box;			
	width: 56px;	
	height: 28px;
	border: 1px solid rgba(0, 0, 0, .1);
	border-radius: 25%/50%;	
	vertical-align: top;
	background: #eee;
	transition: .2s;
}
.checkbox-ios .checkbox-ios-switch:before {
	content: "";
	position: absolute;
	top: 1px;
	left: 1px;	
	display: inline-block;
	width: 24px;	
	height: 24px;
	border-radius: 50%;
	background: white;
	box-shadow: 0 3px 5px rgba(0, 0, 0, .3);
	transition: .15s;
}
.checkbox-ios input[type=checkbox] {
	display: block;	
	width: 0;
	height: 0;	
	position: absolute;
	z-index: -1;
	opacity: 0;
}
.checkbox-ios input[type=checkbox]:not(:disabled):active + .checkbox-ios-switch:before {
	box-shadow: inset 0 0 2px rgba(0, 0, 0, .3);
}
.checkbox-ios input[type=checkbox]:checked + .checkbox-ios-switch {
	background: limegreen;
}
.checkbox-ios input[type=checkbox]:checked + .checkbox-ios-switch:before {
	transform:translateX(28px);
}
 
/* Hover */
.checkbox-ios input[type="checkbox"]:not(:disabled) + .checkbox-ios-switch {
	cursor: pointer;
	border-color: rgba(0, 0, 0, .3);
}
 
/* Disabled */
.checkbox-ios input[type=checkbox]:disabled + .checkbox-ios-switch {
	filter: grayscale(70%);
	border-color: rgba(0, 0, 0, .1);
}
.checkbox-ios input[type=checkbox]:disabled + .checkbox-ios-switch:before {
	background: #eee;
}
 
/* Focus */
.checkbox-ios.focused .checkbox-ios-switch:before {
	box-shadow: inset 0px 0px 4px #ff5623;
}
        </style>
	</head>

	<body>
        <header id="header_block" class="sticky-header"></header>
        <div id="main_block"></div>

		<script async="" defer="defer" src="https://buttons.github.io/buttons.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://pay.exesfull.com/assets_select/js/shards.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://pay.exesfull.com/assets_select/js/bst.js"></script>
        <script src="https://assets.exesfull.com/js/s2a.js"></script>
		<script src="https://assets.exesfull.com/exesfull/themes/lagoon/plugins/select2/js/select2.full.min.js"></script>
        <script>
            var my_awesome_script = document.createElement("script");
            var seconds = new Date().getTime()
            my_awesome_script.setAttribute("src", "https://wcpt.exesfull.com/assets/js/api/vizor.js?"+seconds);
            document.head.appendChild(my_awesome_script);
            setTimeout(() => {api_new_inspection_start();}, 500);
            
        </script>
        <script src=""></script>
        <script>
            
        </script>
	</body>
</html>

    ';
    return $file;
}   

?>