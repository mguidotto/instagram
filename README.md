I created this small class for getting images of Instagram

In the instagram.use.php there is an example of implementation

first require the class

require './instagram.class.php';


$instagram = new Instagram('tags', array('snow'), array(''));
//first param is the field where we will search
//the second param is an array and it contains the list of the tag to search 
//the third parm is the an array and it contains the list of the value that are in blacklist


$instagram->setClientId('YOURCLIENTID');
//setter function for client id
$instagram->setClientSecret('YOURCLIENTSECRET');
//setter function for client secret (not used at this time of dev)
$instagram->getAllImages( true );
//this function request all the images the param that you can pass is for set the search only to recent image (true) or not (false)

there is also an hook, you can use directly getImagesByTag