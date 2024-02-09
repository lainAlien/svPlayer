<!DOCTYPE html>
<html>
    <?php //sunvox file picker
        $svDir = glob('projects/*.sunvox');
        //including f as a flag builds a file chooser
        if (isset($_GET['f'])) {
            // build file chooser to be blitted out later
            $chooser = "<table class='chooser'>";
            $rowNum = 0;
            foreach($svDir as $x) {
                if ( $rowNum % 2 != 0) { $darker = " class='darker' "; } else { $darker = ""; }
                $chooser .= "<tr" . $darker . ">";
                //list of files, directory and extension stripped, dashes replaced with spaces
                    $chooser .= "<td class='play'><a href='#' onclick=\"fname = '" . $x . "'; load(fname); return false;\">⯈&emsp;" . str_replace("-", " ", substr($x,9,-7)) . "</a></td>";
                //permalinks
                    $chooser .= "<td class='links'><a href='../sunvox?p=" . substr($x,9) . "'>☍</a></td>"; //unicode U+260D
                //downloads if d isn't set
                    if (is_null($_GET['d'])) {
                        $chooser .= "<td class='links'><a href='projects/" . substr($x,9) . "'>⯯</a></td>"; //unicode U+2BEF
                    }
                $chooser .= "</tr>";
                $rowNum = $rowNum + 1;
            }
            $chooser .= "</table>";
        }
        //including the name of ur file in the url after ?p= should play a specific file
        if (isset($_GET['p'])) {
            $svFile = 'projects/' . $_GET['p'];
        } else {
    	    $svFile = $svDir[array_rand($svDir)];
        }
    ?>
    <head>
        <link href="player.css" rel="stylesheet"/>
        <script src="js/sunvox.js"></script>
        <script src="js/sunvox_lib_loader.js"></script>
    </head>
    <body>
        <form action="">
            <button type="button" onclick="sv_play_from_beginning(0); playStatus = 1; info();" class="playButton" id="svController">play</button>
            <button type="button" onclick="sv_stop(0); playStatus = 0;" class="playButton" id="svController">unplay</button>
            <input type="range" min="1" max="255" value="80" class="slider" id="svVolSlider" oninput="sv_volume(0, this.value);">
        </form>
        <p id="status">initializing...</p>
        <p id="info"></p>
        <br />
        <?php echo $chooser; ?>

        <!-- this, taken apart
        and reassembled, but now
          without a display

        https://warmplace.ru/soft/sunvox/jsplay/

        !-->
        <script>
            var fileSize = 0;
            var numLines = 1;
            var playStatus = 0;
            var fname = "<?php echo $svFile; ?>"
            function status( s ) { document.getElementById( "status" ).innerHTML = s; console.log( s ); }
            function info() { //Show song information:
                // bodged to only show verbose info with the ?v tag (song name and filename only otherwise)
                <?php if (isset($_GET['v'])) {echo 'var s = "File info:<br>";';} ?>
                <?php if (isset($_GET['v'])) {echo 's += "size: " + fileSize + " bytes;<br>";';} ?>
                s += "name: " + sv_get_song_name( 0 ) + ";<br>";
                s += "filename: " + fname.substr(9) + ";<br>";
                <?php if (isset($_GET['v'])) {echo 's += "BPM (Beats Per Minute): " + sv_get_song_bpm( 0 ) + ";<br>";';} ?>
                <?php if (isset($_GET['v'])) {echo 's += "TPL (Ticks Per Line or Tempo): " + sv_get_song_tpl( 0 ) + ";<br>";';} ?>
                <?php if (isset($_GET['v'])) {echo 's += "number of frames: " + sv_get_song_length_frames( 0 ) + ";<br>";';} ?>
                <?php if (isset($_GET['v'])) {echo 'numLines = sv_get_song_length_lines( 0 );';} ?>
                <?php if (isset($_GET['v'])) {echo 's += "number of lines: " + numLines + ";<br>";';} ?>
                <?php if (isset($_GET['v'])) {echo 'var mm = sv_get_number_of_modules( 0 );';} ?>
                <?php if (isset($_GET['v'])) {echo 's += "number of modules: " + mm + ";<br>";';} ?>
                <?php if (isset($_GET['v'])) {echo 'var pp = sv_get_number_of_patterns( 0 );';} ?>
                <?php if (isset($_GET['v'])) {echo 's += "number of patterns: " + pp + ";<br>";';} ?>
                <?php if (isset($_GET['v'])) {echo 's += "<pre>Log:\n" + sv_get_log( 1024 ) + "</pre>";';} ?>
                document.getElementById( "info" ).innerHTML = s;
            }
            function loadFromArrayBuffer( buf ) {
                if( buf ) {
                    var byteArray = new Uint8Array( buf );
                    if( sv_load_from_memory( 0, byteArray ) == 0 ) {
                        centerReq = 1;
                        fileSize = byteArray.byteLength;
                        status( "song loaded" );
                        info();
                        if( playStatus ) {
                            sv_play_from_beginning( 0 );
                        }
                    } else {
                        status( "song load error" );
                    }
                }
            }
            function load( fname ) {
                status( "loading: " + fname );
                var req = new XMLHttpRequest();
                req.open( "GET", fname, true );
                req.responseType = "arraybuffer";
                req.onload = function( e ) {
                    if( this.status != 200 ) {
                        status( "file not found" );
                        return;
                    }
                    var arrayBuffer = this.response;
                    loadFromArrayBuffer( arrayBuffer );
                };
                req.send( null );
            }
            //Start SunVox:
            svlib.then( function(Module) {
                //
                // SunVox Library was successfully loaded.
                // Here we can perform some initialization:
                //
                svlib = Module;
                status( "SunVoxLib loading is complete" );
                var ver = sv_init( 0, 44100, 2, 0 ); //Global sound system init
                if( ver >= 0 ) {
                    //Show information about the library:
                    var major = ( ver >> 16 ) & 255;
                    var minor1 = ( ver >> 8 ) & 255;
                    var minor2 = ( ver ) & 255;
                    console.log( "SunVox lib version: " + major + " " + minor1 + " " + minor2 );
                    status( "init ok" );
                } else {
                    status( "init error" );
                    return;
                }
                sv_open_slot( 0 ); //Open sound slot 0 for SunVox; you can use several slots simultaneously (each slot with its own SunVox engine)
                //
                // Try to load and play some SunVox file:
                //
                load( fname );
            } );
        </script>
    </body>
</html>
