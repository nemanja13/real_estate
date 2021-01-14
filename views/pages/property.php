<?php
    if(isset($_GET["id"])){
        $id=$_GET["id"];
        require_once "models/real_estate/real_estate_get_one.php";
        $real_estate=get_real_estate($id);
    }else{
        header("Location: 403.php");
    }
?>
<div class="container-fluid" id="propertyContainer">
    <div class="row">
        <div class="col-lg-6" >
            <div class="col-12 flex2" id="image">
                <div class=" slider slider-for">
                <?php
                $images=get_images_real_estate($id);
                foreach($images as $im): ?>
                    <div>
                        <img src="assets/img/<?=$im->src_medium?>" alt="<?=$im->title?>"  draggable="false"/>
                    </div>
                <?php endforeach;?>
                </div>
                    <div class=" slider slider-nav">
                        <?php foreach($images as $im): ?>
                            <div>
                                <img src="assets/img/<?=$im->src_thumbnail?>" alt="<?=$im->title?>"  draggable="false"/>
                            </div>
                        <?php endforeach;?>
                    </div>
            </div>
            <p id="propertyTypeCity"><?=$real_estate->type ." for ". $real_estate->category ." ". $real_estate->city?></p>
        </div>
        <div class="col-lg-6" id="propertyTitle">
            <h1 id="titleProperty" class="blue"><?=$real_estate->title?></h1>
            <h3 id="address"><?=$real_estate->city.", ". $real_estate->address.", ". $real_estate->country?></h3>
        </div>
        <div id="visitContainer" class="col-10 col-sm-8 col-md-6 col-lg-4">
            <div id="visit">
                <div class="flex1">
                    <span><h2 class="blue"><?=get_price($id)[0]?> &euro;</h2> <h3 class="oldPrice"><?= !empty(get_price($id)[1])? get_price($id)[1]."&euro;" : ""?></h3></span> <i class="far fa-heart fa-lg mx-3"></i>
                </div>
                <div id="visitFeatures" class="flex flexWrap">
                    <span class="flex"><i class="fas fa-pencil-ruler blue"></i> <p><?=$real_estate->size?> m<sup>2<sup></p></span>
                    <span class="flex"><i class="fas fa-thermometer-three-quarters blue"></i> <p><?=get_heating_real_estate($id)?></p></span>
                    <span class="flex"><i class="fas fa-bed blue"></i> <p><?=$real_estate->bedrooms?></p></span>
                    <span class="flex"><i class="fas fa-bath blue"></i> <p><?=$real_estate->bathrooms?></p></span>
                </div>
                <form class="flex2">
                    <input type="text" name="date" id="date" placeholder="Select date" onfocus="(this.type='date')">
                    <input type="text" name="time" id="time" placeholder="Select time" onfocus="(this.type='time')">
                    <input type="hidden" name="property" id="property" value="<?=$real_estate->idRealEstate?>">
                    <input type="hidden" name="user" id="user" value="<?=(isset($_SESSION["user"]))?$_SESSION["user"]->idUser:""?>">
                    <input type="button" class="<?=(isset($_SESSION["user"]) && $_SESSION["user"]->idRole==1)?($_SESSION["user"]->idUser==$real_estate->idUser)?"disabled":"visit":"login"?>" value="Schedule visit">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row flex3" id="property">
        <div class="col-12">
           <h3>Property info</h3>
        </div>
        <div class="col-lg-6 flex1 flexWrap" id="propertyInfo">
            <span class="flex1">
                <p class="propertyInfo">Floor</p>
                <p class="bold"><?=get_floor($id)?></p>
            </span>
            <span class="flex1">
                <p class="propertyInfo">Number of floors</p>
                <p class="bold"><?=get_number_of_floors($id)?></p>
            </span>
            <span class="flex1">
                <p class="propertyInfo">Price per m<sup>2</sup> </p>
                <p class="bold"><?=get_price_per_m2(get_price($id)[0],$real_estate->size)?>&euro;</p>
            </span>
            <span class="flex1">
                <p class="propertyInfo">Area </p>
                <p class="bold"><?=$real_estate->size?> m<sup>2</sup></p>
            </span>
            <span class="flex1">
                <p class="propertyInfo">Number of rooms </p>
                <p class="bold"><?=get_rooms_real_estate($id)?></p>
            </span>
            <span class="flex1">
                <p class="propertyInfo">Registration </p>
                <p class="bold"><?=get_documentation_real_estate($id)?></p>
            </span>
            <span class="flex1">
                <p class="propertyInfo">Year of construction </p>
                <p class="bold"><?=$real_estate->year_built?></p>
            </span>
            <span class="flex1">
                <p class="propertyInfo">Heating </p>
                <p class="bold"><?=get_heating_real_estate($id)?></p>
            </span>
            <span class="flex1">
                <p class="propertyInfo">Number of bedrooms </p>
                <p class="bold"><?=$real_estate->bedrooms?></p>
            </span>
            <span class="flex1">
                <p class="propertyInfo">Number of bathrooms </p>
                <p class="bold"><?=$real_estate->bathrooms?></p>
            </span>
        </div>
        <div class="col-lg-6 flex flexWrap" id="featuresProperty">
            <p>Additional features:</p>
            <?php 
            $feature_real_estate=get_features_real_estate($id);
            foreach($feature_real_estate as $f):?>
            <p class="featureProperty"><?=$f?></p>
            <?php endforeach;?>
        </div>
        <div class="col-12"><h3>Description</h3></div>
        <div class="col-lg-6">
        <?=$real_estate->description?>
        </div>
    </div>
</div>