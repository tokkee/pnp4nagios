<!doctype html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>PNP4Nagios</title>
        <style type="text/css" media="screen">@import "media/jqtouch/jqtouch.css";
        </style>
        <style type="text/css" media="screen">@import "media/jqtouch/theme.css";
        </style>
        <script src="media/js/jquery-min.js" type="text/javascript" charset="utf-8"></script>
        <script src="media/jqtouch/jqtouch.js" type="application/x-javascript" charset="utf-8"></script>
        <script type="text/javascript" charset="utf-8">
            var jQT = new $.jQTouch({
                icon: 'media/jqtouch/jqtouch.png',
                addGlossToIcon: false,
                startupScreen: 'media/jqtouch/jqt_startup.png',
                statusBar: 'blue',
                preloadImages: ['media/jqtouch/img/backButton.png', 'media/jqtouch/img/grayButton.png', 'media/jqtouch/img/whiteButton.png', 'media/jqtouch/img/loading.gif', 'media/jqtouch/img/loading.gif', 'media/jqtouch/img/pinstripes.png']
            });
            $(function(){
                $('#hosts').bind('pageAnimationEnd', function(e, info){
                    if (!$('#hosts').data('got_hosts')) {
                        $("#hosts ul li").each(function(){
                            $(this).remove();
                        });
                        $(this).append($('<div id="loading-hosts"><img src="media/jqtouch/img/loading.gif" alt="Loading" /><p>Loading...</p></div>'));
                        $.getJSON('index.php/json', function(data){
                            $('#loading-hosts').remove();
                            for (i = 0; i < data.length; i++) {
                                $("#hosts ul").append('<li class="arrow"><a rel="' + data[i]['hostname'] + '" href="#services">' + data[i]['hostname'] + '</a></li>');
                            }
                            $('#hosts ul li a').click(function(){
                                $('#hosts').data('selected_host', $(this).attr('rel'));
                            });
                        });
                        $('#hosts').data('got_hosts', true);
                    };
                });
                
                $('#graphs').bind('pageAnimationEnd', function(e, info){
                    $selected_host = $('#hosts').data('selected_host');
                    $selected_plugin = $('#services').data('selected_plugin');
                    $('.selected-host').html($selected_host);
                    $('.selected-plugin').html($selected_plugin);
                    $("#graphs-hour ul, #graphs-hour li,#graphs-hour h2,#graphs-month li,#graphs-year li").each(function(){
                        $(this).remove();
                    });
                    
                    $(this).append($('<div id="loading-graphs"><img src="media/jqtouch/img/loading.gif" alt="Loading" /><p>Loading...</p></div>'));
                    $.getJSON('index.php/json?srv=' + $selected_plugin + '&host=' + $selected_host, function(data){
                        $('#loading-graphs').remove();
                        var last_title = '';
                        for (i = 0; i < data.length; i++) {
                            if(data[i]['title'] != last_title){
                                $("#graphs-hour").append('<h2>' + data[i]['title'] + '</h2>');
                            }
                            $("#graphs-hour").append('<li>' + data[i]['ds_name'] + '<img src="index.php/image?' + data[i]['image_url'] + '"/></li>');
                            last_title = data[i]['title'];        
                        }    
                    });
                });
                $('#services').bind('pageAnimationEnd', function(e, info){
                    $selected_host = $('#hosts').data('selected_host');
                    $('.selected-host').html($selected_host);
                    $("#services ul li").each(function(){
                        $(this).remove();
                    });
                    
                    $(this).append($('<div id="loading-services"><img src="media/jqtouch/img/loading.gif" alt="Loading" /><p>Loading...</p></div>'));
                    $.getJSON('index.php/json?host=' + $selected_host, function(data){
                        $('#loading-services').remove();
            var last_service = '';
                        for (i = 0; i < data.length; i++) {
                            if(data[i]['servicedesc'] != last_service){
                                $("#services ul").append('<li class="arrow"><a rel="' + data[i]['servicedesc'] + '" href="#graphs">' + data[i]['servicedesc'] + '</a></li>');
                            }
                            last_service = data[i]['servicedesc'];
                        }
                        $('#services ul li a').click(function(){
                            $('#services').data('selected_plugin', $(this).attr('rel'));
                        });
                    });
                });
                
            });
        </script>
        <style type="text/css" media="screen">
            body.fullscreen #home .info {
                display: none;
            }
            
            #about {
                padding: 100px 10px 40px;
                text-shadow: rgba(255, 255, 255, 0.3) 0px -1px 0;
                font-size: 13px;
                text-align: center;
            }
            
            #about p {
                margin-bottom: 8px;
            }
            
            #about a {
                color: #333;
                font-weight: bold;
                text-decoration: none;
            }
            
            #loading-hosts, #loading-services, #loading-graphs {
                width: 100px;
                height: 100px;
                margin: 10px auto;
                text-align: center;
                color: #fff;
                -webkit-border-radius: 8px;
            }
            
            #loading-hosts img, #loading-services img, #loading-graphs img {
                margin-top: 10px;
            }
            
            #graphs-hour img, #graphs-day img, #graphs-week img, #graphs-month img, #graphs-year img {
                width: 100%;
            }
        </style>
    </head>
    <body>
        <div id="about" class="selectable ui-widget-content">
            <p>
                <strong>PNP4Nagios</strong>
            </p>
        </div>
        <div id="hosts" class="ui-widget-content">
            <div class="toolbar ui-widget-header">
                <h1>Hosts</h1>
                <a href="#" class="back">Back</a>
                <a class="button slideup" id="infoButton" href="#home">Home</a>
            </div>
            <ul class="rounded"></ul>
        </div>
        <div id="graphs" class="ui-widget-content">
            <div class="toolbar ui-widget-header">
                <a href="#" class="back">Back</a>
                <a class="button slideup" id="infoButton" href="#home">Home</a>
            </div>
            <ul class="rounded" id="graphs-hour"></ul>
        </div>
        <div id="services" class="ui-widget-content">
            <div class="toolbar ui-widget-header">
                <h1><span class="selected-host"></span></h1>
                <a href="#" class="back">Back</a>
                <a class="button slideup" id="infoButton" href="#home">Home</a>
            </div>
            <ul class="rounded"></ul>
        </div>
        <div id="home" class="current ui-widget-content">
            <div class="toolbar ui-widget-header">
                <h1>PNP4Nagios</h1>
                <a class="button slideup" id="infoButton" href="#about">About</a>
            </div>
            <ul class="rounded">
                <li class="arrow">
                    <a href="#hosts">Hosts</a>
                </li>
            </ul>
        </div>
    </body>
</html>
