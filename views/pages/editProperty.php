<?php
if(!isset($_SESSION['user']) || !isset($_GET["id"])){
    header("Location: index.php?page=403");
}
    $id=$_GET["id"];
require_once "models/real_estate/functions.php";
require_once "models/real_estate/real_estate_get_one.php";
    $real_estate=get_real_estate($id);
    if($_SESSION["user"]->idRole!=2){
        if($real_estate->idUser!=$_SESSION["user"]->idUser){
            header("Location: 403.php");
        }
        $idUser=$_SESSION["user"]->idUser;
    }else{
        $idUser=$real_estate->idUser;
    }
    $price_real_estate=get_price($id);
    $images_real_estate=get_images_real_estate($id);
    $features_real_estate=get_features_real_estate($id);

?>
<div class="container-fluid" id="formContainer">
    <div class="row">
        <div class="col-10 mx-auto formBlock flex2">
            <h1>EDIT PROPERTY</h1>
            <p class="crta2"></p>
        </div>
        <form enctype="multipart/form-data">
            <input type="hidden" name="user" id="user" value="<?=$idUser?>">
            <input type="hidden" name="id" id="id" value="<?=$_GET["id"]?>">
            <input type="hidden" name="property_type" id="property_type" value="<?=$real_estate->idPropertyType?>">
            <div class="col-10 mx-auto formBlock flex">
                <div class="col-lg-4 col-md-12">
                    <h5>Property Description</h5>
                    <p>This description will appear first in page. Keeping it as a brief overview makes it easier to read.</p>
                </div>
                <div class="col-lg-8 col-md-12 flex3 formInputs">
                    <label for="title">Title (*)</label>
                    <input type="text" name="title" id="title" value="<?=$real_estate->title?>">
                    <label for="description">Description (*)</label>
                    <textarea name="description" id="description" cols="30" rows="10"><?=$real_estate->description?></textarea>
                </div>
            </div>
            <div class="col-10 mx-auto formBlock flex">
                <div class="col-lg-4 col-md-12">
                    <h5>Property Price</h5>
                </div>
                <div class="col-lg-8 col-md-12 flex3 formInputs">
                    <label for="title">Price (*)</label>
                    <input type="text" name="price" id="price" value="<?=preg_replace("/,/", "",$price_real_estate[0])?>">
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
                        <div class="flex flexWrap">
                            <?php for($i=0; $i<count($images_real_estate); $i++):?>
                                <div class="editImages">
                                    <img src="assets/img/<?=$images_real_estate[$i]->src_thumbnail?>" alt="<?=$real_estate->title?>">
                                    <a href="#" class="deleteImage flex2" title="Delete image" data-id="<?=$images_real_estate[$i]->idImage?>"><i class="fas fa-times"></i></a>
                                </div>
                            <?php endfor;?>
                        </div>
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
                    <div class="col-12">
                        <label for="address">Address</label>
                        <input type="text" name="address" id="address" value="<?=$real_estate->address?>">
                    </div>
                </div>
            </div>
            <div class="col-10 mx-auto formBlock flex">
                <div class="col-lg-4 col-md-12">
                    <h5>Listing Details</h5>
                    <p>Edit info about your property.</p>
                </div>
                <div class="col-lg-8 col-md-12 flex listingDetails formInputs">
                    <div class="col-md-5 col-sm-12">
                        <label for="houseSize">House size in m² (*only numbers)</label>
                        <input type="text" name="houseSize" id="houseSize" value="<?=$real_estate->size?>">
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label for="rooms">Rooms (*only numbers)</label>
                        <select name="rooms" id="rooms">
                            <option value="0">-</option>
                            <?php 
                            $rooms=get_rooms();
                            foreach($rooms as $r):?>
                                <option value="<?=$r->idRooms?>" <?=($real_estate->idRooms==$r->idRooms)?"selected='selected'":true?>><?=$r->rooms?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label for="bedrooms">Bedrooms (*only numbers)</label>
                        <input type="text" name="bedrooms" id="bedrooms" value="<?=$real_estate->bedrooms?>">
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label for="bathrooms">Bathrooms (*only numbers)</label>
                        <input type="text" name="bathrooms" id="bathrooms" value="<?=$real_estate->bathrooms?>">
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label for="heating">Heating</label>
                        <select name="heating" id="heating">
                            <option value="0">-</option>
                            <?php 
                            $heating=get_heating();
                            foreach($heating as $h):?>
                                <option value="<?=$h->idHeating?>" <?=($real_estate->idHeating==$h->idHeating)?"selected='selected'":true?>><?=$h->heating?></option>
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
                                <option value="<?=$d->idDocumentation?>" <?=($real_estate->idDocumentation==$d->idDocumentation)?"selected='selected'":true?>><?=$d->documentation?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <?php if($real_estate->idPropertyType==2):?>
                        <div class="col-md-5 col-sm-12">
                            <label for="floor_status">Floors</label>
                            <select name="floor_status" id="floor_status">
                                <option value="0">-</option>
                                <?php 
                                $floor_status=get_floor_status();
                                foreach($floor_status as $fs):?>
                                    <option value="<?=$fs->idFloorStatus?>" <?=($real_estate->idFloorStatus==$fs->idFloorStatus)?"selected='selected'":true?>><?=$fs->floor_status?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    <?php endif;?>
                    <?php if($real_estate->idFloorStatus!=1):?>
                        <div class="col-md-5 col-sm-12">
                            <div class="col-12">
                                <label for="number_of_floors">Number of floors</label>
                                <input type="text" name="number_of_floors" id="number_of_floors" value="<?=get_number_of_floors($id)?>">
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="col-10 mx-auto formBlock flex">
                <div class="col-lg-4 col-md-12">
                    <h5>Amenities and Features</h5>
                    <p>Edit what features and amenities apply for your property.</p>
                </div>
                <div class="col-lg-8 col-md-12 flex features formInputs">
                    <?php 
                    $features=get_features();
                    foreach($features as $f):?>
                        <span><input type="checkbox" name="features[]" class="feature" value="<?=$f->idFeature?>" <?=(in_array($f->feature, $features_real_estate))?"checked='checked'":true?>><label><?=$f->feature?></label></span>
                    <?php endforeach;?>
                </div>
            </div>
            <div class="col-10 mx-auto p-5 flex2">
                <button class="posalji" type="button" value="submit" id="editProperty" name="submit">Edit property</button>
            </div>
        </form>
        <div class="col-12" id="editPropertyErrors"></div>
    </div>
</div>