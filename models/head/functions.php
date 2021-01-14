<?php

function meta_data_get($page)
{
 $meta = [
 "home" => ["title" => "Real Estate | Homes for Sale, Purchase and Rental", "description" => "Site number 1 for real estates in Serbia! Advertisements for renting and selling apartments, houses, shops, offices, business premises in Belgrade, Serbia and abroad.", "keywords" => "Real Estate, House, Apartment, Buy, Rent, Agents"],
 "properties" => ["title" => "Real Estate | Properties for sell and rent", "description" => "The largest selection of real estate for sale and rent in the entire Balkans!", "keywords" => "House, Apartment, Buy, Rent"],
 "sell" => ["title" => "Real Estate | Sell and lease your property", "description" => "Sell your property quickly and safely, very easily with the help of our agents", "keywords" => "Property, Sell, lease"],
 "contact" => ["title" => "Real Estate | Contact", "description" => "Contact us if you have any additional questions or need additional instructions on selling / renting a property", "keywords" => "Contact, Address, Phone, Email"],
 "author" => ["title" => "Real Estate | Author", "description" => "The author of this website is Nemanja Maksimovic", "keywords" => "Author, Nemanja, Maksimovic"],
 "editProperty" => ["title" => "Real Estate | Edit your property", "description" => "You can here change all informations about your property", "keywords" => "Edit, Property, Address, Title"],
 "form" => ["title" => "Real Estate | Form for adding real estate ads", "description" => "Add your real estate ad on number 1 site for real estates in Serbia!", "keywords" => "Edit, Property, Address, Title"],
 "property" => ["title" => "Real Estate | Property", "description" => "Always the best offers for the lowest prices with the greatest security and the least time!", "keywords" => "Property, Real Estate, House, Appartment"],
 "register" => ["title" => "Real Estate | Registration form", "description" => "Register now. On the best site for buying and selling real estate", "keywords" => "Register, Name, Email, Password"],
 "userProperty" => ["title" => "Real Estate | Properties", "description" => "All your real estate in one place, easy insight, modification and deletion of your ads", "keywords" => "House, Appartment, Price, Properties"],
 "403" => ["title" => "Real Estate | 403", "description" => "An error 403 has occurred", "keywords" => "403, error, Real Estate"],
 "404" => ["title" => "Real Estate | 404", "description" => "An error 404 has occurred", "keywords" => "404, error, Real Estate"]
];
 return $meta[$page];
}


?>