<?php
// liveCarSearch.php
$xmlDoc = new DOMDocument();
$xmlDoc->load("../xml/cars.xml");

$cars = $xmlDoc->getElementsByTagName('car');

// Get the 'q' parameter from the URL
$q = $_GET["q"];

// Lookup all cars from the XML file if the length of 'q' > 0
if (strlen($q) > 0) {
    $hint = "";
    for ($i = 0; $i < $cars->length; $i++) {
        $licenseNode = $cars->item($i)->getElementsByTagName('license');
        $brandNode = $cars->item($i)->getElementsByTagName('brand');
        $modelNode = $cars->item($i)->getElementsByTagName('model');
        $idNode = $cars->item($i)->getElementsByTagName('id');

        // Retrieve values or default to an empty string if the node doesn't exist
        $license = ($licenseNode->length > 0) ? $licenseNode->item(0)->nodeValue : '';
        $brand = ($brandNode->length > 0) ? $brandNode->item(0)->nodeValue : '';
        $model = ($modelNode->length > 0) ? $modelNode->item(0)->nodeValue : '';
        $id = ($idNode->length > 0) ? $idNode->item(0)->nodeValue : '';

        // Check if the search query matches license, brand, or model
        if (stristr($license, $q) || stristr($brand, $q) || stristr($model, $q)) {
            // Display brand and model for each result button
            $displayName = htmlspecialchars("$brand $model ($license)");
            $button = "<button onclick=\"handleCarClick('" . 
                        htmlspecialchars($id) . 
                        "','" . 
                        $displayName . 
                        "')\">" . 
                        $displayName . "</button>";
            
            // Concatenate hints with line breaks if needed
            $hint = ($hint == "") ? $button : $hint . "<br />" . $button;
        }
    }
}

// Set output to "no suggestion" if no hint was found, or output the correct values
$response = ($hint == "") ? "no suggestion" : $hint;

// Output the response
echo $response;
?>
