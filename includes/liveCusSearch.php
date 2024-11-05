<?php
$xmlDoc = new DOMDocument();
$xmlDoc->load("../xml/customers.xml");

$x = $xmlDoc->getElementsByTagName('customer');

// Get the 'q' parameter from the URL
$q = $_GET["q"];

// Lookup all links from the XML file if length of 'q' > 0
if (strlen($q) > 0) {
    $hint = "";
    for ($i = 0; $i < $x->length; $i++) {
        $y = $x->item($i)->getElementsByTagName('firstname');
        $z = $x->item($i)->getElementsByTagName('lastname');
        $idNode = $x->item($i)->getElementsByTagName('id');

        // Check if firstname node exists
        $firstname = ($y->length > 0) ? $y->item(0)->nodeValue : '';

        // Check if lastname node exists
        $lastname = ($z->length > 0) ? $z->item(0)->nodeValue : '';

        // Check if id node exists
        $id = ($idNode->length > 0) ? $idNode->item(0)->nodeValue : '';

        // Find a match for the search text in either firstname or lastname
        if (stristr($firstname, $q) || stristr($lastname, $q)) {
            // Add a button for each match found
            $displayName = htmlspecialchars("$firstname $lastname");
            $button = "<button onclick=\"handleClick('" . 
                        htmlspecialchars($id) . "', '$displayName')\">" . 
                        $displayName . "</button>";
            
            // Concatenate hints with line breaks if needed
            $hint = ($hint == "") ? $button : $hint . "<br />" . $button;
        }
    }
}

// Set output to "no suggestion" if no hint was found or to the correct values
$response = ($hint == "") ? "no suggestion" : $hint;

// Output the response
echo $response;
?>
