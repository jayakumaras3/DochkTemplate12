<!DOCTYPE html>
<html lang="en">
    <head>
        <title>DoChek</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="icon" type="newtheme/image/ico" href="<?php echo base_url(); ?>/public/img/favicon.ico"/>

        <link href="<?php echo base_url() ?>/public/newtheme/css/stylesheets.css" rel="stylesheet" type="text/css" />        

        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/jquery/jquery.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/jquery/jquery-ui.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/jquery/jquery-migrate.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/jquery/globalize.js'></script>    
        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/bootstrap/bootstrap.min.js'></script>

        <!--script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script-->
        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/uniform/jquery.uniform.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/fancybox/jquery.fancybox.pack.js'></script>

        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/datatables/jquery.dataTables.min.js'></script>

        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/knob/jquery.knob.js'></script>
        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/sparkline/jquery.sparkline.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/flot/jquery.flot.js'></script>     
        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/flot/jquery.flot.resize.js'></script>

        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins.js'></script>    
        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/actions.js'></script>    
        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/charts.js'></script>
        



        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/uniform/jquery.uniform.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url() ?>/public/newtheme/js/plugins/flot/jquery.flot.pie.js'></script>    

		<script type="text/jscript">
  function disableContextMenu()
  {
   
    /* document.getElementById("customframe").contentWindow.document.oncontextmenu = function(){
		console.log("No right click"); 
		return false;
		
			
			
		};*/
		
  }  
</script>

        <style type="text/css">
		
		

            .badge1 {
                position:relative;
            }
            .badge1[data-badge]:after {
                content:attr(data-badge);
                position:absolute;
                top:-7px;
                right:-10px;
                font-size:.7em;
                background:orangered;
                color:white;
                width:18px;height:18px;
                text-align:center;
                border-radius:50%;

            }

            .row{
                margin-left:0px !important;
                margin-right:0px !important
            }
			
			.hiddenscrollclass {
				overflow: hidden; 
			}
			
			iframe {
        width:100%;
        height:auto;
        border:none;
        overflow:hidden;
    }
    @media screen and (max-width:1166px) {
        iframe {
            width:100%;
            height:1388px;
        }

    }
    @media screen and (max-width:1024px) {
        iframe {
            width:100%;
            height:1388px;
        }
    }
    @media screen and (max-width:980px) {
        iframe {
            width:100%;
            height:1388px;
        }
    }
    @media screen and (max-width:767px) {
        iframe {
            width:100%;
            height:1388px;
        }
    }
    @media screen and (max-width:599px) {
        iframe {
            width:100%;
            height:1388px;
        }
    }
    @media screen and (max-width:479px) {
        iframe {
            width:100%;
            height:1388px;
        }
    }
    @media screen and (max-width:374px) {
        iframe {
            width:100%;
            height:1388px;
        }

    }
        </style>
		
		<script type="text/javascript">
		
			$(window).on("load resize", function () {
				var width = $(window).width();
				var height = $(window).height();
				console.log('height :'+height);
				$('iframe').css("height",height);

			}).resize();

			var pageurl ;
			
			if(window.opener && !window.opener.closed) {
				//document.write(window.opener.newpage);
				
				//document.getElementById('customframe').src = window.opener.newpage;
				//window.frames['customframe'].location.replace(window.opener.newpage);
				//$("#customframe").attr("src", window.opener.newpage);
				//pageurl = 
				
                //$("#ifrm").attr("src", pageurl);
			}
			
			/*$(document).bind("contextmenu",function(e){
				return false;
			});*/
			
			jQuery(window).load(function () {
				var custURL=window.opener.newpage;
				var custURLarray = custURL.split(".");
				console.log(custURLarray);
				if (typeof custURLarray !== 'undefined') {
					var lastArray = custURLarray.length;
					/*if(custURLarray[lastArray-1] =='mp4'){
						
						$('#beforeiframe').html('<video controls="" autoplay="" allowfullscreen="true" name="media" controls controlsList="nodownload" ><source src="'+custURL+'"></video>');
					}else{
						$("#customframe").attr("src", window.opener.newpage);
					}*/
					$("#customframe").attr("src", window.opener.newpage);
				}else{
					
				}
				
				
			});
			
			
		</script>

    </head>
	<body class="bg-img-num1 hiddenscrollclass" data-settings="open" onload="disableContextMenu();" oncontextmenu="return false" >
	<div class="">
        <div class="col-xs-12" >
            <div class="">
                <div class="" id="beforeiframe">
					<iframe src="" id='customframe' class="embed-responsive-item" oncontextmenu="return false" onload="disableContextMenu();" onMyLoad="disableContextMenu();" controls controlsList="nodownload" /></iframe>
					
					<!--iframe id='customframe' class="embed-responsive-item" src=""></iframe-->
				</div>
            </div>
        </div>
	</div>
	</body>
</html>