<!DOCTYPE html>
<html>
    <?php //sunvox file picker
        //including the name of ur file in the url after ?p= should play a specific file
        if (isset($_GET['p'])) {
            $svFile = 'projects/' . $_GET['p'];
        } else {
            $svDir = glob('projects/*.sunvox');
    	    $svFile = $svDir[array_rand($svDir)];
        }
    ?>
    <head>
        <link href="player.css" rel="stylesheet"/>
        <script src="js/sunvox.js"></script>
        <script src="js/sunvox_lib_loader.js"></script>
        <script>
        </script>
    </head>
    <body>
        <div class="boxen">
            <div class="playBox"><button type="button" onclick="svToggle();" class="playButton" id="svController"><big>sound or no</big></button></div>
            <div class="sliderBox"><input type="range" min="1" max="255" value="80" class="slider" id="svVolSlider" oninput="sv_volume(0, this.value);"></div>
        </div>
        <script>
            var svProject = "<?php echo $svFile; ?>";
            var svSlider = document.getElementById("svVolSlider");
            var svButton = document.getElementById("svController");
            var svVolDefault = 80;
            function status( s ) { console.log( s ); }
            svlib.then( function(Module) {
                //
                // SunVox Library was successfully loaded.
                // Here we can perform some initialization:
                //
                svlib = Module;
                status( "SunVoxLib loading is complete" );
                var ver = sv_init( 0, 44100, 2, 0 ); //Global sound system init
                if( ver >= 0 )
                {
	            status( "init ok" );
                }
                else
                {
	            status( "init error" );
	            return;
                }
                sv_open_slot( 0 ); //Open sound slot 0 for SunVox; you can use several slots simultaneously (each slot with its own SunVox engine)
                //
                // Try to load and play some SunVox file:
                //
                status( "loading project..." );
                var req = new XMLHttpRequest();
                req.open( "GET", svProject, true );
                req.responseType = "arraybuffer";
                req.onload = function( e ) 
                {
	            if( this.status != 200 )
	            {
	                status( "error code: " + this.status );
	                return; //good luck if this happens lol
	            }
	            var arrayBuffer = this.response;
	            if( arrayBuffer ) 
	            {
	                var byteArray = new Uint8Array( arrayBuffer );
	                if( sv_load_from_memory( 0, byteArray ) == 0 )
	                {
	    	        fileSize = byteArray.byteLength;
	    	        status( "song loaded" );
                    svVolDefault = sv_volume(0);
                    svSlider.value = svVolDefault;
                    sv_volume(0, svVolDefault);
	                }
	                else
	                {
	    	        status( "song load error" );
	                }
	            }
                };
                req.send( null );
            } );
            function svToggle() {
                if(sv_end_of_song(0)===1) {
                    sv_play_from_beginning(0);
                    svButton.class="stopButton";
                } else {
                    sv_stop(0); sv_stop(0);
                    svButton.class="playButton";
                }
            }
        </script>
    </body>
</html>