
<!doctype html>
<html>
    <head>
        <title>Albumes de fotos</title>
        <link href='simplelightbox-master/dist/simple-lightbox.min.css' rel='stylesheet' type='text/css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
        <script type="text/javascript" src="simplelightbox-master/dist/simple-lightbox.jquery.min.js"></script>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

        <link href='style.css' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class='container'>

            <h1>Albumes</h1>

            <form method="post" action="#">
                <input type="text" name="foldername">
                <input class="input-album" type="submit" name="submit" value="Crear Album">
            </form>


            <div class="gallery">
            <?php 

               if(isset($_POST['submit']) && $_POST['submit']){

                  $foldername = $_POST['foldername'];
                  
                  $foldername = '/images/' . $foldername;                  
                  $structure = dirname(__FILE__).DIRECTORY_SEPARATOR.$foldername;

                  if (!mkdir($structure, 0777, true)) {
                    die('Failed to create folders...');
                  }
               }

            // Image extensions
            $image_extensions = array("png","jpg","jpeg","gif");


            // Target directory
            $dir = 'images/';


            // Must be a function
            if (is_dir($dir)){
                
                if ($dh = opendir($dir)){
                    $count = 1;

                    // Read folders
                    print_r(readdir($dh));


                    // Read files
                    while (($file = readdir($dh)) !== false){

                        if($file != '' && $file != '.' && $file != '..'){
                            
                            // Thumbnail image path
                            $thumbnail_path = "images/thumbnail/".$file;

                            // Image path
                            $image_path = "images/".$file;
                            
                            $thumbnail_ext = pathinfo($thumbnail_path, PATHINFO_EXTENSION);
                            $image_ext = pathinfo($image_path, PATHINFO_EXTENSION);

                            // Check its not folder and it is image file
                            if(!is_dir($image_path) && 
                                in_array($thumbnail_ext,$image_extensions) && 
                                in_array($image_ext,$image_extensions)){
                                ?>

                                <!-- Image -->
                                <a href="<?php echo $image_path; ?>">
                                    <img src="<?php echo $thumbnail_path; ?>" alt="" title=""/>
                                </a>
                                <!-- --- -->
                                <?php

                                // Break
                                if( $count%4 == 0){
                                ?>
                                    <div class="clear"></div>
                                <?php  

                                }
                                $count++;
                            }
                        }
                            
                    }
                    closedir($dh);
                }
            }

            function getFileList($dir)
            {
                // array to hold return value
                $retval = [];

                // add trailing slash if missing
                if(substr($dir, -1) != "/") {
                  $dir .= "/";
                }

                // open pointer to directory and read list of files
                $d = @dir($dir) or die("getFileList: Failed opening directory {$dir} for reading");
                while(FALSE !== ($entry = $d->read())) {
                  // skip hidden files
                  if($entry[0] == ".") continue;
                  if(is_dir("{$dir}{$entry}")) {
                    $retval[] = [
                      'name' => "{$dir}{$entry}/",
                      'type' => filetype("{$dir}{$entry}"),
                      'size' => 0,
                      'lastmod' => filemtime("{$dir}{$entry}")
                    ];
                  } elseif(is_readable("{$dir}{$entry}")) {
                    $retval[] = [
                      'name' => "{$dir}{$entry}",
                      'type' => mime_content_type("{$dir}{$entry}"),
                      'size' => filesize("{$dir}{$entry}"),
                      'lastmod' => filemtime("{$dir}{$entry}")
                    ];
                  }
                }
                $d->close();

                return $retval;
            }

            $dirlist = getFileList("images/");

            ?>


            <div class="w3-row">
                <?php
                    foreach($dirlist as $file) {
                        if($file['type'] == "dir"){
                            
                            $name = str_replace('images/', '', $file['name']);

                            echo '<div style="margin:5px;" class="w3-col s4 w3-gray w3-center">';
                        
                                echo '<header class="w3-container w3-light-grey">';
                                echo "<h3>{$name}</h3>";
                                echo "</header>";

                                echo '<div class="w3-container">';
                                echo "<p>1 {$file['type']}</p>";
                                echo "<hr>";
                                echo '<img src="file-icon.png" alt="Avatar" class="w3-left w3-circle album-image">';
                                echo "<p>{$file['size']}.</p>";
                                echo "</div>";

                                echo '<button class="w3-button w3-block w3-dark-grey">Ver album</button>';

                            echo '</div>';
                        }
                    }
                ?>

            </div>


            </div>
        </div>


        <!-- Script -->
        <script type='text/javascript'>
        $(document).ready(function(){

            // Intialize gallery
            var gallery = $('.gallery a').simpleLightbox();
        });
        </script>
    </body>
</html>