<?php
require("libs/config.php");
//https://api.vk.com/method/users.get?user_id=9&fields=nickname,screen_name,sex,bdate,city,country,timezone,photo_big,has_mobile,contacts,education,counters,relation
//https://api.vk.com/method/photos.get?owner_id=291168449&album_id=wall&count=1&offset=0&photo_sizes=1&extended=1
//profile, wall, saved.
//http://localhost:8888/Vkphotos/index.php?id=273557538&a_id=profile
$id = $_GET['id'] ;
$a_id = $_GET['a_id'] ;

// echo $id;
// echo "*".$a_id;

if (!$id) {
  exit();
}

if (!$a_id) {
  exit();
}

$json = file_get_contents("https://api.vk.com/method/photos.get?owner_id=".$id."&album_id=".$a_id."&count=1000&offset=0&photo_sizes=1&extended=1");

$data = json_decode($json) ;
$response = $data->response;
// var_dump($response[0]);

// echo "This is pid : ".$response[0]->pid."<br></br>" ;
// echo "This is aid : ".$response[0]->aid."<br></br>" ;
// echo "This is owner_id : ".$response[0]->owner_id."<br></br>" ;
// // echo "This is sizes : ".$response[0]->sizes."<br></br>" ;
// // echo "This is text : ".$response[0]->text."<br></br>" ;
// echo "This is likes : ".$response[0]->likes->count."<br></br>" ;

// // var_dump($sizes);
// storeImages($response[0]->sizes, $response[0]->pid);

storeData($response);

function storeData($response){
	for ($i=0; $i < count($response) ; $i++) { 
		// echo "This is pid : ".$response[$i]->pid."<br></br>" ;
		// echo "This is aid : ".$response[$i]->aid."<br></br>" ;
		// echo "This is owner_id : ".$response[$i]->owner_id."<br></br>" ;
		// storeImages($response[$i]->sizes, $response[$i]->pid);	
		// echo "This is likes : ".$response[$i]->likes->count."<br></br>" ;
		// echo "************** <br></br>" ;

    $sql = "INSERT INTO PHOTOS (PID, AID, OWNER_ID, LIKES) 
    VALUES ('".$response[$i]->pid."','".$response[$i]->aid."','".$response[$i]->owner_id."','".$response[$i]->likes->count."')";
    $res = mysql_query($sql) ;
    $msg  = $res ? successMessage("Uploaded and saved to Photos.") : errorMessage( "Problem in saving to photos") ;
    echo $response[$i]->pid ." : ". $msg." <br>";
      if ($res) {
        storeImages($response[$i]->sizes, $response[$i]->pid);  
      }
      echo "item :". $i."<br></br>";
	}
	echo "=== count : ". count($response)."<br></br>";
}

function storeImages($sizes, $photo_id) {
	for ($i=0; $i <count($sizes) ; $i++) { 
		// echo "This is sizes src : ".$sizes[$i]->src."<br></br>" ;
		// echo "This is sizes width : ".$sizes[$i]->width."<br></br>" ;
		// echo "This is sizes height : ".$sizes[$i]->height."<br></br>" ;
		// echo "This is sizes type : ".$sizes[$i]->type."<br></br>" ; 	
		// echo "This is sizes type : ".$photo_id."<br></br>" ; 	
		// echo "<img src=\"".$sizes[$i]->src."\" >";

    $sql = "INSERT INTO IMAGES (PID, SRC, WIDTH, HEIGHT, TYPE) 
    VALUES ('".$photo_id."','".mysql_real_escape_string($sizes[$i]->src)."','".$sizes[$i]->width."','".$sizes[$i]->height."','".mysql_real_escape_string($sizes[$i]->type)."')";
    
    $res = (mysql_query($sql)) ;
    $msg  = $res ? successMessage("Uploaded and saved to IMAGES.") : errorMessage( "Problem in saving to IMAGES") ;
    echo "=== : ".$photo_id."  : ". $msg." <br>";
  }
}

/* 
  ["pid"]=>
  int(365420132)
  ["aid"]=>
  int(-7)
  ["owner_id"]=>
  int(291168449)
  ["sizes"]=>
  array(7) {
    [0]=>
    object(stdClass)#3 (4) {
      ["src"]=>
      string(54) "http://cs625522.vk.me/v625522449/33d2f/S7tNjlk4MdU.jpg"
      ["width"]=>
      int(75)
      ["height"]=>
      int(14)
      ["type"]=>
      string(1) "s"
    }

s — proportional copy with 75px max width;
m — proportional copy with 130px max width;
x — proportional copy with 604px max width;
o — if original image's "width/height" ratio is less or equal to 3:2, then proportional copy with 130px max width. If original image's "width/height" ratio is more than 3:2, then copy of cropped by left side image with 130px max width and 3:2 sides ratio.
p — еif original image's "width/height" ratio is less or equal to 3:2, then proportional copy with 200px max width. If original image's "width/height" ratio is more than 3:2, then copy of cropped by left side image with 200px max width and 3:2 sides ratio.
q — if original image's "width/height" ratio is less or equal to 3:2, then proportional copy with 320px max width. If original image's "width/height" ratio is more than 3:2, then copy of cropped by left side image with 320px max width and 3:2 sides ratio.
y — proportional copy with 807px max width;
z — proportional copy with 1280x1024px max size;
w — proportional copy with 2560x2048px max size.

*/

?>