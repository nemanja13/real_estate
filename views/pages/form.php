<?php
if(!isset($_SESSION['user'])){
    header("Location: index.php?page=403");
}
require_once "models/real_estate/functions.php";

?>
<div class="container-fluid" id="formContainer">
    <div class="row">
        <div class="col-10 mx-auto formBlock flex2">
            <h1>SUBMIT PROPERTY</h1>
            <p class="crta2"></p>
        </div>
        <form enctype="multipart/form-data">
            <input type="hidden" name="user" id="user" value="<?=$_SESSION['user']->idUser?>">
            <div class="col-10 mx-auto formBlock flex">
                <div class="col-lg-4 col-md-12">
                    <h5>Property Description</h5>
                    <p>This description will appear first in page. Keeping it as a brief overview makes it easier to read.</p>
                </div>
                <div class="col-lg-8 col-md-12 flex3 formInputs">
                    <label for="title">Title (*)</label>
                    <input type="text" name="title" id="title">
                    <label for="description">Description (*)</label>
                    <textarea name="description" id="description" cols="30" rows="10"></textarea>
                </div>
            </div>
            <div class="col-10 mx-auto formBlock flex">
                <div class="col-lg-4 col-md-12">
                    <h5>Property Price</h5>
                    <p>Adding a price will make it easier for users to find your property in search results.</p>
                </div>
                <div class="col-lg-8 col-md-12 flex3 formInputs">
                    <label for="title">Price (*)</label>
                    <input type="text" name="price" id="price">
                </div>
            </div>
            <div class="col-10 mx-auto formBlock flex">
                <div class="col-lg-4 col-md-12">
                    <h5>Select Property Type</h5>
                    <p>Selecting a Property Type will make it easier for users to find you property in search results.</p>
                </div>
                <div class="col-lg-8 col-md-12 flex3 formInputs">
                    <label for="property_type">Property Type (*)</label>
                    <select name="property_type" id="property_type">
                        <option value="0">-</option>
                        <?php 
                        $property_type=get_property_type();
                        foreach($property_type as $pt):?>
                            <option value="<?=$pt->idPropertyType?>"><?=$pt->type?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="col-10 mx-auto formBlock flex">
                <div class="col-lg-4 col-md-12">
                    <h5>Select Category</h5>
                    <p>Selecting a Category will make it easier for users to find you property in search results.</p>
                </div>
                <div class="col-lg-8 col-md-12 flex3 formInputs">
                    <label for="category">Category (*)</label>
                    <select name="category" id="category">
                        <option value="0">-</option>
                        <?php 
                        $category=get_category();
                        foreach($category as $c):?>
                            <option value="<?=$c->idCategory?>"><?=$c->category?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="col-10 mx-auto formBlock flex">
                <div class="col-lg-4 col-md-12">
                    <h5>Listing Media</h5>
                    <p>You can select multiple images to upload at one time.</p>
                </div>
                <div class="col-lg-8 col-md-12 flex3 formInputs">
                    <label for="images">Images (*)</label>
                    <div id="imagePreview">
                        <input type="file" class="p-3" name="images[]" id="images" multiple="multiple">
                        <div id="imageList"></div>
                    </div>
                </div>
            </div>
            <div class="col-10 mx-auto formBlock flex">
                <div class="col-lg-4 col-md-12">
                    <h5>Address</h5>
                </div>
                <div class="col-lg-8 col-md-12 flex listingDetails formInputs">
                    <div class="col-md-5 col-sm-12">
                        <div class="flex3">
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
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <div class="flex3">
                            <label for="city">City</label>
                            <select name="city" id="city">
                                <option value="0">-</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="address">Address</label>
                        <input type="text" name="address" id="address">
                    </div>
                </div>
            </div>
            <div class="col-10 mx-auto formBlock flex">
                <div class="col-lg-4 col-md-12">
                    <h5>Listing Details</h5>
                    <p>Add a little more info about your property.</p>
                </div>
                <div class="col-lg-8 col-md-12 flex listingDetails formInputs">
                    <div class="col-md-5 col-sm-12">
                        <label for="houseSize">House size in m² (*only numbers)</label>
                        <input type="text" name="houseSize" id="houseSize">
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label for="rooms">Rooms (*only numbers)</label>
                        <select name="rooms" id="rooms">
                            <option value="0">-</option>
                            <?php 
                            $rooms=get_rooms();
                            foreach($rooms as $r):?>
                                <option value="<?=$r->idRooms?>"><?=$r->rooms?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label for="bedrooms">Bedrooms (*only numbers)</label>
                        <input type="text" name="bedrooms" id="bedrooms">
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label for="bathrooms">Bathrooms (*only numbers)</label>
                        <input type="text" name="bathrooms" id="bathrooms">
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label for="built">Year Built (*numeric)</label>
                        <input type="text" name="built" id="built">
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label for="heating">Heating</label>
                        <select name="heating" id="heating">
                            <option value="0">-</option>
                            <?php 
                            $heating=get_heating();
                            foreach($heating as $h):?>
                                <option value="<?=$h->idHeating?>"><?=$h->heating?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label for="documentation">Registration</label>
                        <select name="documentation" id="documentation">
                            <option value="0">-</option>
                            <?php 
                            $documentation=get_documentation();
                            foreach($documentation as $d):?>
                                <option value="<?=$d->idDocumentation?>"><?=$d->documentation?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label for="floor_status">Floors</label>
                        <select name="floor_status" id="floor_status">
                            <option value="0">-</option>
                            <?php 
                            $floor_status=get_floor_status();
                            foreach($floor_status as $fs):?>
                                <option value="<?=$fs->idFloorStatus?>"><?=$fs->floor_status?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="col-12 flex1" id="floors">
                        
                    </div>
                </div>
            </div>
            <div class="col-10 mx-auto formBlock flex">
                <div class="col-lg-4 col-md-12">
                    <h5>Amenities and Features</h5>
                    <p>Select what features and amenities apply for your property.</p>
                </div>
                <div class="col-lg-8 col-md-12 flex features formInputs">
                    <?php 
                    $features=get_features();
                    foreach($features as $f):?>
                        <span><input type="checkbox" name="features[]" class="feature" value="<?=$f->idFeature?>"><label><?=$f->feature?></label></span>
                    <?php endforeach;?>
                </div>
            </div>
            <div class="col-10 mx-auto p-5 flex2">
                <button class="posalji" type="button" value="submit" id="addProperty" name="submit">Submit property</button>
            </div>
        </form>
        <div class="col-12" id="addPropertyErrors"></div>
    </div>
</div>