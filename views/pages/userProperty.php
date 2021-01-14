<?php
    if(isset($_GET["id"])){
        $id=$_GET["id"];
        require_once "models/real_estate/functions.php";
        
        $real_estate=is_array(get_real_estate_user($id))?get_real_estate_user($id):false;
    }else{
        header("Location: 403.php");
    }
?>
<div class="container-fluid" id="userProperty">
    <div class="row">
        
        <?php if($real_estate): foreach($real_estate as $rs):?>
            <div class="col-11 flex flexWrap nekretnina">
                <div class="col-lg-3 col-sm-8 col-10 mx-auto slika">
                    <img src="assets/img/<?=$rs->src_thumbnail?>" class="img-fluid" alt="<?=$rs->title?>">
                    <span class="sellRent">for <?=$rs->category?></span>
                </div>
                <div class="col-lg-7 col-10 flex tekst">
                    <h3><?=$rs->title?></h3>
                    <p><?=$rs->city?>, <?=$rs->address?>, <?=$rs->country?></p>
                    <h4><?=price($rs->price)?> &euro;</h4>
                </div>
                <div class="col-2 flex2 editDelete">
                    <a href="index.php?page=editProperty&id=<?=$rs->idRealEstate?>" title="Edit"><i class="fas fa-edit"></i></a>
                    
                    <a href="#" title="Delete" class="delete" data-id="<?=$rs->idRealEstate?>"><i class="fas fa-trash-alt"></i></a>
                </div>
            </div>
        <?php endforeach; else:?>
        
            <div class="col-12 flex2 noResults">
                <h1 id="noResults"><i class="fas fa-home"></i> You do not have any active ads</h1>
            </div>
            
        <?php endif;?>
    </div>
</div>
