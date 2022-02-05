<!--Goat Roleplay - Copyright by Marvellous(15 Şubat 2021) -->
<html>
	<head>
		<title>Goat Roleplay - Harita</title>
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		<meta http-equiv="content-type" content="text-html; charset=UTF-8" />
		<script type="text/javascript" data-cfasync="false" src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" data-cfasync="false" src="res/js/scrollsync.js"></script>
		<script type="text/javascript" data-cfasync="false" src="res/js/dragscrollable.js"></script> 
		<link rel="stylesheet" type="text/css" href="map.css">
		<script type="text/javascript" data-cfasync="false" >			
			var _factions = new Array();
			
			$('#viewport').scroll(function() {
			  $('#viewport').toggle();
			});
					
			
			$(function() {
				$('#viewport').
					scrollsync({targetSelector: '#viewport', axis : 'x'});
				$('#viewport').
					dragscrollable({dragSelector: '.dragger', acceptPropagatedEvent: false});
				$('#panel').
					scrollsync({targetSelector: '#panel', axis : 'x'});
				$('#panel').
					dragscrollable({dragSelector: '.dragger:first', acceptPropagatedEvent: true});
				 				
			});
			
			$(document).ready(function() {
				scrMapTo(5100, 4600)
			});
			
			function scrMapTo(x, y)
			{
				$("#viewport").animate({scrollTop: y-(($(window).height())*0.5), scrollLeft: x-(($(window).width())*0.5)},500);
			}		
			function makeSVG(tag, attrs) {
				var el= document.createElementNS('http://www.w3.org/2000/svg', tag);
				for (var k in attrs)
					el.setAttribute(k, attrs[k]);
				return el;
			}			
		</script>
	</head>
	<body>		
		<div id="viewport">
			<div id="mapa" class="dragger">
				
			</div>
			<svg id="_mapa" class="dragger" xmlns="http://www.w3.org/2000/svg">
			</svg>
			<div id="map_images">
				<div id="actBox">
					<a href="#" class="actBoxAct" id="actShowThread" target="_blank">Konuya Git</a>
				</div>
			</div>
		</div>
		<span id="map_coords">goatroleplay.com © Faction Management</span>
		<div id="panel">
			<div id="panel_content">
			<div id="goatlogo"> 
			<div style="width:180px; height:94px; margin-left:auto; margin-right:auto;"> <img src="res/img/goatlogo.png">  </div>
			<hr style="color: white; background: white; border: 1px solid #AAA; margin: 8px 0 8px 0;"></hr>	
			
				<h2>Harita</h2>
				<hr style="color: white; background: white; border: 1px solid #AAA; margin: 8px 0 8px 0;"></hr>	
				<strong>Legal Oluşum Listesi</strong>
				<ul id="factlist" class="dragger"></ul>
 
			</div> 
		</div>
		<script>
			$("#clearPoints").click(function(){
				$(".point").remove();
			});
			function LoadFactions(){
				$("#actBox").hide();
				__t = -1;
				__f = -1;
				$("#factlist").empty();
				$("polygon").remove();
				$(".point").remove();
				$(".captionzzz").remove();
				$.post('engine.json.php', function(data){
					var f = jQuery.parseJSON(data);
					f.factions.forEach(function(faction){
						_factions[faction.id] = faction;
						$("#factlist").append("<li class=\"fact fact"+faction.id+"\"><div class=\"factSquare\" style=\"background-color: #" + faction.color + "\"></div> " + faction.name + "<span class=\"factid\">"+faction.id+"</span></li>");
						var i = 0;
						faction.turfs.forEach(function(points){
							var turfSVG = makeSVG('polygon', {'class': faction.id, 'name': i, 'points': points, stroke: 'white', 'stroke-width': 2, fill: '#' + faction.color, opacity: 0.3});
							var turf = $(turfSVG).appendTo("#_mapa"); 
							_factions[faction.id].turfPolygon[i] = turf;
							var fSize = 0.5;
							if(faction.dimensions[i][0] > 200)
							{
								fSize = 0.9;
							}
							else if(faction.dimensions[i][0] > 90)
							{
								fSize = 0.6;							
							}
							else if(faction.dimensions[i][0] < 30)
							{
								fSize = 0.3;
							}
							$("<div>"+faction.name+"</div>").css({
								'position': 'absolute',
								'top': faction.turfcenter[i][1] - 10,
								'left': faction.limits[i].minx + 'px',
								'width': faction.limits[i].maxx - faction.limits[i].minx + 'px',
								'text-align': 'center',
								'font-size': fSize + 'em',
								'font-weight': 'bold',
								'color': 'white',
								'text-transform': 'uppercase',
								'font-family': 'tahoma',
								'pointer-events': 'none'
							}).addClass('captionzzz').appendTo("#map_images");
							i++;
						});
					})
				}).done(function(){
					RefreshPolygonList();				
				});
			}
			
			function RefreshPolygonList(){
				_factions.forEach(function(_f){
					$(".polylist_" + _f.id).remove();
					if(_f.turfPolygon.length > 0){
						$(".fact"+_f.id).after("<ul class=\"polylist polylist_"+_f.id+"\" style='display:none;'></ul>");
						var i = 0;
						_f.turfPolygon.forEach(function(poly){
							i++;
							$(".polylist_"+_f.id).append("<li class=\"turfinlist\">Bölge #<span class=\"turfid\">" + i + "</span></li>");
						})
					}
				});
			}
			
			$(document).on("click", ".fact", function(){
				var fid = $(this).children(".factid").text();
				selectedFaction = fid;
				$(".fact").css("background-color", "black");
				$(this).css("background-color", "#555");
			});
			
			$("#addfact_color").keyup(function(){
				$("#previewCol").css("background-color", "#" + $(this).val());
			})
			
			
			LoadFactions();
			
			/*$("#_mapa").mousemove( function(event){
				$("#map_coords").text('Cursor position: X = ' + (($(this).offset().left + 3000) * (-1) + event.pageX) + ', Y = ' + ($(this).offset().top + 3000 - event.pageY));			
				mX = ($(this).offset().left) * -1 + event.pageX;
				mY = ($(this).offset().top) * -1 + event.pageY;
			});*/
			
			$(function(){
				$(document).on("mouseenter", "polygon", function(){
					$(this).css({"opacity": "0.5", "cursor": "pointer"});
				}).on("mouseout", "polygon", function(){
					$(this).css({"opacity": "0.3", "cursor": "pointer"});
				});
				
			});
			
			$(document).on("click", ".turfinlist", function(){
				var _f = $(this).parent().prev().children(".factid").text();
				var _t = $(this).children(".turfid").text();
				scrMapTo(_factions[_f].turfcenter[_t - 1][0], _factions[_f].turfcenter[_t - 1][1]);			
			})	

			$(document).on("click", "polygon", function(e){
				var _f = $(this).attr('class');
				var _t = $(this).attr('name');
				__f = _f;
				__t = _t;
				if(_factions[_f].thread.length > 0){
					var _box = $("#actBox");
					_box.css({
						'display': 'block',
						'left': e.clientX - $("#_mapa").offset().left + 10 + 'px',
						'top': e.clientY - $("#_mapa").offset().top + 'px'
					});
					$("#actShowThread").show();
					$("#actShowThread").attr('href', _factions[_f].thread);		
				}
			});			
			
			$("#_mapa").click(function(){
				if($("polygon:hover").length == 0){
					if($("#actBox").css('display') != 'none'){
						__t = -1;
						__f = -1;
						return $("#actBox").hide();
					}
				}
			});
			
			$(document).on("click", ".fact", function(e){
				$(this).next(".polylist:first").toggle();
			})
		</script>
	</body>
</html>
<!--Goat Roleplay - Copyright by Marvellous(15 Şubat 2021) -->