<?php
require_once "models/real_estate/functions.php";
$type=0;
if(isset($_GET["type"])){
    if($_GET["type"]=="buy"){
        $type=1;
    }else if($_GET["type"]=="rent"){
        $type=2;
    }
}
?>
<div class="container-fluid">
    <div class="row flex" id="nekretnine">
        <div class="col-md-11 col-12" id="searchForm">
            <h3 class="searchH3">Search properties for sell</h3>
            <form action="#" class="flex">
                <div class="flex3 mx-auto col-lg-4 col-10">
                    <label for="propertyType">Property type</label>
                    <select name="propertyType" id="propertyType">
                        <option value="0">-</option>
                        <?php 
                        $property_type=get_property_type();
                        foreach($property_type as $pt):?>
                            <option value="<?=$pt->idPropertyType?>"><?=$pt->type?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="flex3 mx-auto col-lg-4 col-10">
                    <label for="country">Country</label>
                    <select name="country" id="country">
                        <option value="0">-</option>
                        <?php 
                        $country=get_country();
                        foreach($country as $c):?>
                            <option value="<?=$c->idCountry?>"><?=$c->country?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="flex3 mx-auto col-lg-4 col-10">
                    <label for="city">City</label>
                    <select name="city" id="city">
                        <option value="0">-</option>
                        
                    </select>
                </div>
                <div class="flex3 mx-auto col-lg-4 col-10">
                    <label for="sort">Sort</label>
                    <select name="sort" id="sort">
                        <option value="0">Choose</option>
                        <option value="1">Sort by title A-Z</option>
                        <option value="2">Sort by title Z-A</option>
                        <option value="3">Sort by price ascending</option>
                        <option value="4">Sort by price descending</option>#
                    </select>
                </div>
                <div class="flex3 mx-auto col-lg-4 col-10">
                    <label for="price">Price</label>
                    <div class="flex">
                        <input type="number" name="priceMin" placeholder="Min" id="priceMin">
                        <input type="number" name="priceMax" placeholder="Max" id="priceMax">
                    </div>
                </div>
                <div class="flex3 mx-auto col-lg-4 col-10">
                    <label for="size">Size</label>
                    <div class="flex">
                        <input type="number" name="sizeMin" placeholder="Min" id="sizeMin">
                        <input type="number" name="sizeMax" placeholder="Max" id="sizeMax">
                    </div>
                </div>
                <div class="flex3 mx-auto col-lg-4 col-10">
                    <label for="bedrooms">Bedrooms</label>
                    <input type="number" name="bedrooms" placeholder="Any" id="bedrooms">
                </div>
                <div class="flex3 mx-auto col-lg-4 col-10">
                    <label for="bathrooms">Bathrooms</label>
                    <input type="number" name="bathrooms" placeholder="Any" id="bathrooms">
                </div>
                <div class="flex3  mx-auto col-lg-4 col-10">
                    <input type="hidden" name="category" id="category" value="<?=$type?>">
                    <button type="button" id="submit" class="posalji search">Search</button>
                </div>
            </form>
        </div>
    </div>
    <div id="nekretnine2"></div>
</div>
<div class="container-fluid" id="paginationContainer">
    <div class="row">
        <div class="col-12" id="paginacija"></div>
    </div>
</div>