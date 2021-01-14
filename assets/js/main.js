var url=window.location.href;
$(document).ready(function(){
    AOS.init({
        duration:1500
    });
    $("#bars").click(function(){
      $("#header2").slideToggle();
      $("#bars").toggleClass("MeniBar")
  });

  $(document).on("click", "#login", login);
  $(document).on("click", ".logout", logout);
  $(document).on("click", ".profile", profile);

  $("#goUp").click(function(){
    $("html").animate({ scrollTop: 0 }, 0);
  });


  $(".login").click(function(e){ e.preventDefault(); showModal()});

  /* HOME PAGE */
  
  if(url.match(/.*index.php$/) || url.indexOf("page=home")!=-1){
    newest_real_estate();
    real_estate_agent();
    $(document).scroll(function(){
      counter();
     });
  
     counter();

  }

  /* PROPERTIES PAGE */

  if(url.indexOf("page=properties")!=-1){
    values_reset();
    $("#submit").click(filter);
    filter();
    $("#country").change(get_city);
    $("#sort").change(filter);
  }

  /* EDIT PROPERTY PAGE */

  if(url.indexOf("page=editProperty")!=-1){
    $(document).on("click", "#editProperty", edit_property);
    $(document).on("click", ".deleteImage", delete_image);
    image_preview();
  }

  /* FORM PAGE */

  if(url.indexOf("page=form")!=-1){
    $("#country").change(get_city);
    $(document).on("change", "#floor_status", get_floors);
    $(document).on("change", "#property_type", get_floors);
    $(document).on("click", "#addProperty", add_property);
    image_preview();
  
  }

  /* REGISTER PAGE */

  if(url.indexOf("page=register")!=-1){
    $("#formRegister #registration").click(registration);
    $("#formRegister #editProfile").click(function(){edit_profile("user")});
  }

  /* CONTACT PAGE */

  if(url.indexOf("page=contact")!=-1){
    $("#mail").click(send_mail);
    
    $("#contactForm input, #contactForm textarea").focus(function(){
      $(this).prev().css("color", "rgb(43, 65, 187)");
    });
  
    $("#contactForm input, #contactForm textarea").blur(function(){
      $(this).prev().css("color", "rgb(0, 0, 0)");
    });
  }

  /* USER PROPERTY PAGE */

  if(url.indexOf("page=userProperty")!=-1){
    $(".delete").click(function(e){ e.preventDefault(); delete_property($(this))});
  }

  /* PROPERTY PAGE */

  if(url.indexOf("page=property")!=-1){
    $(".disabled").click(disabled);
    $(".visit").click(visit);
    $('.slider-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      asNavFor: '.slider-nav',
      autoplay: true
    });
    $('.slider-nav').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      asNavFor: '.slider-for',
      dots: false,
      nextArrow: "<div class='right flex2'><i class='fa fa-angle-right'></i></div>",
      prevArrow: "<div class='left flex2'><i class='fa fa-angle-left'></i></div>",
      centerMode: true,
      focusOnSelect: true

    });
        
  }

  /* ADMIN PANEL */

  if(url.indexOf("admin=home")!=-1){
    $(document).on("click", "#users", function(e){e.preventDefault(); get_users(1)});
    $(document).on("click", "#agents", function(e){e.preventDefault(); get_users(3)});
    $(document).on("click", "#admins", function(e){e.preventDefault(); get_users(2)});
    $(document).on("click", "#real_estates", admin_real_estates);
    $(document).on("click", "#paginacija .page", function(){
      let page=$(this).data("page");
      $("#paginacija .aktivan").removeClass("aktivan");
      $(this).addClass("aktivan");
      $.ajax({
          url :  "models/real_estate/real_estate_get_all.php",
          method : "post",
          dataType : "json",
          data : {
              submit: true,
              page : page,
          },
          success : function(data){
              show_real_estate_admin(data.real_estate, "approved");
          },
          error: function(xhr, status, error){
            error_message(xhr);
            no_results($("#nekretnine2"));
          }
      });
  })
    $(document).on("click", "#new_real_estates", admin_real_estates_new);
    $(document).on("click", "#deleted_real_estates", admin_real_estates_deleted);
    $(document).on("click", "#new_emails", new_email);
    $(document).on("click", ".seen", mailSeen);
    $(document).on("click", "#scheduled_visits", visit_get);
    $(document).on("click", "#assignAgent", agent_assign);
    $(document).on("click", "#readed_emails", readed_email);
    $(document).on("click", "#agent_profile", agent_get_one);
    $(document).on("click", "#show_input", function(e){e.preventDefault(); document.getElementById("agentProfileImg").click()});
    $(document).on("change", "#agentProfileImg", agent_set_photo);
    $(document).on("click", "#editAgentProfile", edit_agent_profile);
    $(document).on("click", ".delete", function(e){e.preventDefault(); delete_property($(this)); admin_real_estates()});



    $(document).on("click", ".edit", userEdit);
    $(document).on("click", "#home", function(){window.location.reload()});
    $(document).on("click", "#editProfile", function(){edit_profile("admin")});
    $(document).on("click", "#visit_outcome", visit_outcome_get);
    $(document).on("click", "#outcome", visit_outcome_insert);
    $(document).on("click", ".activate", propertyActivate);
    $(document).on("click", ".approve", propertyApproved);

    
  }
});

/* HOME PAGE FUNCTIONS START */

function counter(){
  if($(".counter").parent().parent().css("opacity")!=0){
    $('.counter').each(function() {
      var $this = $(this),
          countTo = $this.attr('data-count');
    
      $({ countNum: $this.text()}).animate({
        countNum: countTo
      },
    
      {
    
        duration: 3000,
        easing:'linear',
        step: function() {
          $this.text(Math.floor(this.countNum));
        },
        complete: function() {
          $this.text(this.countNum);
        }
      });
    });
  }    
}
function newest_real_estate(){
  $.ajax({
    url: "models/real_estate/new_real_estate.php",
    method: "GET",
    dataType: "json",
    success: function(data){
      let html="";
      data.forEach(el => {
        html+=`
        <div class='blok'>
        <a href="index.php?page=property&id=${el.idRealEstate}">
          <div class="blokSlika">
              <p class="new">NEW</p>
              <img src='assets/img/${el.src_medium}' alt='${el.title}' class='img-fluid'>
              <h3 class="price">Price: ${price(el.price)}&euro; </h3>
          </div>
          <p>Bed: ${el.bedrooms}</p>
          <p>Bath: ${el.bathrooms}</p>
          <p>Area: ${el.size} m<sup>2<sup></p>
          <p>Address: ${el.address}, ${el.city}, ${el.country}</p>
        </a>
      </div>`;
      });
      $("#newest").html(html);
    },
    error: function(xhr, status, error){
      error_message(xhr);
    }
  });
}
function real_estate_agent(){
  $.ajax({
    url: "models/agents/agent_get_all.php",
    method: "GET",
    dataType: "json",
    success: function(data){
      let html="";
      data.forEach(el => {
        html+=`
        <div class="col-sm-4 col-10 mx-auto flex2">
            <div class="agentSlika">
                <img src="assets/img_agent/${el.image}" alt="${el.firstName} ${el.lastName}">
            </div>
            <p class="agentIme">${el.firstName} ${el.lastName}</p>
            <i class="mt-2">${el.type}</i>
            <div class="agentText flex">
                <p class="crta"></p>
                <p>${el.description}</p>
            </div>
            <div class="flex agentContact">
                <a href="mailto:${el.email}" target="_blank" title="Mail"><i class="fas fa-paper-plane"></i></a>
                <a href="tel:${el.phone}" target="_blank" title="Mobile phone"><i class="fas fa-phone"></i></a>
                <a href="${el.linkLinkedin}" target="_blank" title="Linkedin"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>`;
      });
      $("#agenti").append(html);
    },
    error: function(xhr, status, error){
      error_message(xhr);
    }
  });
}

/* HOME PAGE FUNCTIONS END */

/* PROPERTIES PAGE FUNCTIONS START */

function filter(){
  let obj=values();
  $.ajax({
      url : "models/real_estate/real_estate_get_all.php",
      method : "post",
      dataType : "json",
      data : {
          submit: true,
          page: 1,
          category: obj.category,
          propertyType: obj.propertyType,
          country: obj.country,
          city: obj.city,
          priceMin: obj.priceMin,
          priceMax: obj.priceMax,
          sizeMin: obj.sizeMin,
          sizeMax: obj.sizeMax,
          bedrooms: obj.bedrooms,
          bathrooms: obj.bathrooms,
          sort: obj.sort
      },
      success : function(data){
          show_real_estate(data.real_estate);
          pagination(data.pages);
          let obj=values();
          $(document).on("click", "#paginacija .page",function(){
            let page=$(this).data("page");
            $("#paginacija .aktivan").removeClass("aktivan");
            $(this).addClass("aktivan");
            $.ajax({
                url :  "models/real_estate/real_estate_get_all.php",
                method : "post",
                dataType : "json",
                data : {
                    submit: true,
                    page : page,
                    category: obj.category,
                    propertyType: obj.propertyType,
                    country: obj.country,
                    city: obj.city,
                    priceMin: obj.priceMin,
                    priceMax: obj.priceMax,
                    sizeMin: obj.sizeMin,
                    sizeMax: obj.sizeMax,
                    bedrooms: obj.bedrooms,
                    bathrooms: obj.bathrooms
                },
                success : function(data){
                    show_real_estate(data.real_estate);
                },
                error: function(xhr, status, error){
                  error_message(xhr);
                  no_results($("#nekretnine2"));
                }
            });
        })
      },
      error: function(xhr, status, error){
        error_message(xhr);
        no_results($("#nekretnine2"));
      }
  });
}
function values(){
  let category=$("#category").val();
  let country=$("#country").val();
  let city=$("#city").val();
  let priceMin=$("#priceMin").val();
  let priceMax=$("#priceMax").val();
  let sizeMin=$("#sizeMin").val();
  let sizeMax=$("#sizeMax").val();
  let propertyType=$("#propertyType").val();
  let bedrooms=$("#bedrooms").val();
  let bathrooms=$("#bathrooms").val();
  let sort=$("#sort").val();

  return {
    category:category,
    country:country,
    city:city,
    priceMin:priceMin,
    priceMax:priceMax,
    sizeMin:sizeMin,
    sizeMax:sizeMax,
    propertyType:propertyType,
    bedrooms:bedrooms,
    bathrooms:bathrooms,
    sort:sort
  }
}
function values_reset(){
  $("#country").val(0);
  $("#city").val(0);
  $("#priceMin").val("");
  $("#priceMax").val("");
  $("#sizeMin").val("");
  $("#sizeMax").val("");
  $("#bedrooms").val("");
  $("#bathrooms").val("");
  $("#propertyType").val(0);
  $("#sort").val(0);
}
function get_city(){
  let id=$(this).val();
  $.ajax({
    url : "models/real_estate/get_city.php",
    method : "post",
    dataType : "json",
    data : {
        submit: true,
        id: id
    },
    success : function(data){
        show_city(data.city);
    },
    error: function(xhr, status, error){
      error_message(xhr);
    }
});
}
function show_city(city){
  let html="<option value='0'>-</option>";
  city.forEach(el => {
    html+=`<option value=${el.idCity}>${el.city}</option>`;
  });
  $("#city").html(html);
}

/* PROPERTIES PAGE FUNCTIONS END */

/* EDIT PROPERTY PAGE FUNCTIONS START */

function image_preview(){
  document.getElementById("images").addEventListener("change", function(){
    let images=document.getElementById("images");
    if(images.files.length){
      $("#imageList").html("");
      for(let i=0; i<images.files.length; i++){
        var reader= new FileReader()

        reader.addEventListener("load", function(){
          let image=`<img class="imagePreview" src="${this.result}" alt="imagePreview${i}"/>`
          $("#imageList").append(image);

        })
        reader.readAsDataURL(images.files[i]);
      }

    }
  })
}
function edit_property(){
  let title=$("#title");
  let description=$("#description");
  let price=$("#price");
  let address=$("#address");
  let houseSize=$("#houseSize");
  let rooms=$("#rooms");
  let bedrooms=$("#bedrooms");
  let property_type=$("#property_type");
  let bathrooms=$("#bathrooms");
  let floor_status=$("#floor_status");
  let number_of_floors=$("#number_of_floors");
  let heating=$("#heating");
  let documentation=$("#documentation");
  let user=$("#user");
  let id=$("#id");

  let regExpTitle=/^\w+(\s\w+)*$/;
  let regExpPrice=/^\d+$/;
  let regExpAddress=/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,30}(\,?\s[A-ZŠĐŽČĆa-zšđžčć]{2,30})*(\s\d*\w?)*$/;
  let regExpSize=/^\d{2,4}$/;
  let regExpBB=/^\d+$/;
  let regExpNOF=/^\d{1,2}$/;
  let regExp=/[^0]/;

  submit=[];

  submit.push(checkProperty(title, regExpTitle, "You must enter the property title", "Not a valid format"));
  submit.push(checkProperty(price, regExpPrice, "You must enter the property price", "Not a valid format"));
  submit.push(checkProperty(address, regExpAddress, "You must enter the property address", "Not a valid format"));
  submit.push(checkProperty(houseSize, regExpSize, "You must enter the property size", "Not a valid format"));
  submit.push(checkProperty(bedrooms, regExpBB, "You must enter the number of bedrooms", "Not a valid format"));
  submit.push(checkProperty(bathrooms, regExpBB, "You must enter the number of bathrooms", "Not a valid format"));
  submit.push(checkProperty(rooms, regExp, "", "You must choose the number of rooms"));
  submit.push(checkProperty(heating, regExp, "", "You must choose the heating"));
  submit.push(checkProperty(documentation, regExp, "", "You must choose the documentation"));
  var images=document.getElementById("images");
  var totalImages=images.files.length;
    
  if(property_type.val()==2){
    submit.push(checkProperty(floor_status, regExp, "", "You must choose value"));
    if(floor_status.val()==2){
      submit.push(checkProperty(number_of_floors, regExpNOF, "You must enter the number of floors", "Not a valid format"));
    }
  }
  if(property_type.val()!=2){
    submit.push(checkProperty(number_of_floors, regExpNOF, "You must enter the number of floors", "Not a valid format"));
  }
  

  if(!submit.includes(false)){
    var formData=new FormData();
    console.log($(".feature:checked"))
    var features=$(".feature:checked");
    var featuresArray=[];
    for(let i=0; i<features.length; i++){
      featuresArray.push(features[i].value)
    }
    formData.append("submit", true)
    formData.append("title", title.val())
    formData.append("description", description.val())
    formData.append("price", price.val())
    formData.append("address", address.val())
    formData.append("houseSize", houseSize.val())
    formData.append("rooms", rooms.val())
    formData.append("bedrooms", bedrooms.val())
    formData.append("bathrooms", bathrooms.val())
    formData.append("heating", heating.val())
    formData.append("documentation", documentation.val())
    formData.append("property_type", property_type.val())
    formData.append("user", user.val())
    formData.append("id", id.val())
    formData.append("features", featuresArray)
    if(property_type.val()==2){
      formData.append("floor_status", floor_status.val())
      if(floor_status.val()==2){
        formData.append("number_of_floors", number_of_floors.val())
      }
    }
    if(property_type.val()!=2){
      formData.append("number_of_floors", number_of_floors.val())
    }

    for(let i=0; i<totalImages; i++){
      formData.append("images[]", images.files[i]);
    }

    $.ajax({
      url: "models/real_estate/update.php",
      method: "post",
      dataType: "json",
      data:formData,
      contentType: false,
      processData: false,
      success: function(data){
        $("#editPropertyErrors").html("<h3 class='p-3 text-center'>"+data.success+"</h3>");
      },
      error: function(xhr, status, error){
        let html="<ol class='errorText flex3'>";
        if(xhr.status==422){
          xhr.responseJSON.errors.forEach(el => {
            html+=`<li>${el}</li>`;
          });
        }
        if(xhr.status==500){
          html+="<li>"+xhr.responseJSON.error_message+"</li>"
        }
        html+="</ol>";
        $("#editPropertyErrors").html(html);
        if(xhr.status==400){
          error_message(xhr);
        }
      }
    });
  }
}
function delete_image(e){
  e.preventDefault();
  if($(".editImages:visible").length>1){
    if(confirm("Do you want to delete this image")){
      let val=$(this).data("id");
      let image=$(this)
      $.ajax({
        url: "models/real_estate/deleteImage.php",
        method: "post",
        data:{
          submit:true,
          id:val
        },
        success: function(data){
          image.parent().hide();
        },
        error: function(xhr,status,error){
          error_message(xhr);
        }
      })
      }
  }
}

/* EDIT PROPERTY PAGE FUNCTIONS END */

/* FORM PAGE FUNCTIONS START */

function get_floors(){
  let id=$("#floor_status").val();
  let propertyType=$("#property_type").val();
  $.ajax({
    url: "models/real_estate/get_floors.php",
    method: "get",
    dataType: "json",
    success: function(data){
      if(id==2 && propertyType==2){
        let html=`
        <div class="col-12">
        <label for="number_of_floors">Number of floors</label>
        <input type="text" name="number_of_floors" id="number_of_floors">
        </div>`;
        $("#floors").html(html)
            
            
      }else if(propertyType==2){
        let html2=`
        <label for="floor_status">Floors</label>
        <select name="floor_status" id="floor_status">
            <option value="0">-</option>`;
        data.floor_status.forEach(el => {
          html2+=`<option value="${el.idFloorStatus}">${el.floor_status}</option>`
        });
        html2+="</select>";
        $("#number_of_floors").parent().html(html2)
        $("#floors").html("")
      }
      else if(propertyType!=2 && propertyType!=0){
        let html3=`
        <label for="number_of_floors">Number of floors</label>
        <input type="text" name="number_of_floors" id="number_of_floors">`
       
        $("#floor_status").parent().html(html3)

        let html4=`
        <div class="col-12">
        <label for="floor">Floor</label>
        <select name="floor" id="floor">
            <option value="0">-</option>`
        data.floor.forEach(el => {
          html4+=`<option value="${el.idFloor}">${el.floor}</option>`
        })
        html4+="</select></div>"
        $("#floors").html(html4)
      }
      else{
        $("#floors").html("")
      }
    },
    error : function(xhr, status, error){
      error_message(xhr);
    }
  })
}
function add_property(){
  let title=$("#title");
  let description=$("#description");
  let image=$("#images");
  let price=$("#price");
  let property_type=$("#property_type");
  let country=$("#country");
  let city=$("#city");
  let address=$("#address");
  let houseSize=$("#houseSize");
  let rooms=$("#rooms");
  let bedrooms=$("#bedrooms");
  let bathrooms=$("#bathrooms");
  let built=$("#built");
  let floor_status=$("#floor_status");
  let number_of_floors=$("#number_of_floors");
  let floor=$("#floor");
  let category=$("#category");
  let heating=$("#heating");
  let documentation=$("#documentation");
  let user=$("#user");

  let regExpTitle=/^\w+(\s\w+)*$/;
  let regExpPrice=/^\d+$/;
  let regExpAddress=/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,30}(\,?\s[A-ZŠĐŽČĆa-zšđžčć]{2,30})*(\s\d*\w?)*$/;
  let regExpSize=/^\d{2,4}$/;
  let regExpBB=/^\d+$/;
  let regExpBuilt=/^\d{4}$/;
  let regExpNOF=/^\d{1,2}$/;
  let regExp=/[^0]/;

  submit=[];

  submit.push(checkProperty(title, regExpTitle, "You must enter the property title", "Not a valid format"));
  submit.push(checkProperty(price, regExpPrice, "You must enter the property price", "Not a valid format"));
  submit.push(checkProperty(property_type, regExp, "", "You must choose the property type"));
  submit.push(checkProperty(country, regExp, "", "You must choose the property country"));
  submit.push(checkProperty(city, regExp, "", "You must choose the property city"));
  submit.push(checkProperty(address, regExpAddress, "You must enter the property address", "Not a valid format"));
  submit.push(checkProperty(houseSize, regExpSize, "You must enter the property size", "Not a valid format"));
  submit.push(checkProperty(bedrooms, regExpBB, "You must enter the number of bedrooms", "Not a valid format"));
  submit.push(checkProperty(bathrooms, regExpBB, "You must enter the number of bathrooms", "Not a valid format"));
  submit.push(checkProperty(built, regExpBuilt, "You must enter the year of construction", "Not a valid format"));
  submit.push(checkProperty(rooms, regExp, "", "You must choose the number of rooms"));
  submit.push(checkProperty(category, regExp, "", "You must choose the category"));
  submit.push(checkProperty(heating, regExp, "", "You must choose the heating"));
  submit.push(checkProperty(documentation, regExp, "", "You must choose the documentation"));
  var images=document.getElementById("images");
  var totalImages=images.files.length;
    if(totalImages==0){
      if(!image.parent().hasClass("error")){
        image.parent().addClass("error");
        image.after(`<span class="errorText">You must add a picture of the property<span>`);
      }
      submit.push(false);
    }else{
      image.parent().removeClass("error");
      image.next(".errorText").hide();
    }


    
    
  if(property_type.val()==2){
    submit.push(checkProperty(floor_status, regExp, "", "You must choose value"));
    if(floor_status.val()==2){
      submit.push(checkProperty(number_of_floors, regExpNOF, "You must enter the number of floors", "Not a valid format"));
    }
  }
  if(property_type.val()!=2){
    submit.push(checkProperty(floor, regExp, "", "You must choose the property floor"));
    submit.push(checkProperty(number_of_floors, regExpNOF, "You must enter the number of floors", "Not a valid format"));
    if(Number($("#floor option:selected").text())){
      if(Number($("#floor option:selected").text())>number_of_floors.val()){
        if(!floor.hasClass("error")){
          floor.addClass("error");
          floor.after(`<span class="errorText">Not a valid value<span>`);
        }
        submit.push(false);
      }else{
        floor.removeClass("error");
        floor.next(".errorText").hide();
      }
    }
  }
  var date=new Date();
  if(built.val()>date.getFullYear() || built.val()==""){
    if(!built.hasClass("error")){
      built.addClass("error");
      built.after(`<span class="errorText">Not a valid value<span>`);
    }
    submit.push(false);
  }else{
    built.removeClass("error");
    built.next(".errorText").hide();
  }
  

  if(!submit.includes(false)){
    var formData=new FormData();
    console.log($(".feature:checked"))
    var features=$(".feature:checked");
    var featuresArray=[];
    for(let i=0; i<features.length; i++){
      featuresArray.push(features[i].value)
    }
    formData.append("submit", true)
    formData.append("title", title.val())
    formData.append("description", description.val())
    formData.append("price", price.val())
    formData.append("property_type", property_type.val())
    formData.append("country", country.val())
    formData.append("city", city.val())
    formData.append("address", address.val())
    formData.append("houseSize", houseSize.val())
    formData.append("rooms", rooms.val())
    formData.append("bedrooms", bedrooms.val())
    formData.append("bathrooms", bathrooms.val())
    formData.append("built", built.val())
    formData.append("category", category.val())
    formData.append("heating", heating.val())
    formData.append("documentation", documentation.val())
    formData.append("user", user.val())
    formData.append("features", featuresArray)
    if(property_type.val()==2){
      formData.append("floor_status", floor_status.val())
      if(floor_status.val()==2){
        formData.append("number_of_floors", number_of_floors.val())
      }
    }
    if(property_type.val()!=2){
      formData.append("floor", floor.val())
      formData.append("number_of_floors", number_of_floors.val())
    }

    for(let i=0; i<totalImages; i++){
      formData.append("images[]", images.files[i]);
    }

    $.ajax({
      url: "models/real_estate/insert.php",
      method: "post",
      dataType: "json",
      data:formData,
      contentType: false,
      processData: false,
      success: function(data){
        alert("You have successfully add your property");
        window.location.href="index.php?page=home";
      },
      error: function(xhr, status, error){
        let html="<ol class='errorText flex3'>";
        if(xhr.status==422){
          xhr.responseJSON.errors.forEach(el => {
            html+=`<li>${el}</li>`;
          });
        }
        if(xhr.status==500){
          html+="<li>"+xhr.responseJSON.error_message+"</li>"
        }
        html+="</ol>";
        $("#addPropertyErrors").html(html);
        if(xhr.status==400){
          error_message(xhr);
        }
      }
    });
  }
}
function checkProperty(input, regExp, errorTextEmpty, errorTextFormat){
  if(input.val()==""){
    if(!input.hasClass("error")){
      input.addClass("error");
      input.after(`<span class="errorText">${errorTextEmpty}<span>`);
    }
    return false
  }else if(!regExp.test(input.val().trim())){
    if(!input.hasClass("error")){
      input.addClass("error");
      input.after(`<span class="errorText">${errorTextFormat}<span>`);
    }
    return false
  }else{
    input.removeClass("error");
    input.next(".errorText").hide();
    return true;
  }
}
/* FORM PAGE FUNCTIONS END */

/* REGISTER PAGE FUNCTIONS START */

function registration(){
  let firstName=$("#firstName");
  let lastName=$("#lastName");
  let email=$("#email");
  let password=$("#password");
  let passwordConfirm=$("#passwordConfirm");
  let phoneNumber=$("#phone");
  let captchaText=$("#captchaText");

  let regExpFN=/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,15}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,15})*$/;
  let regExpLN=/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,19}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,19})*$/;
  let regExpEmail=/^[^@]+@[^@]+\.[^@\.]+$/;
  let regExpPassword=/^\w{10,}$/;
  let regExpPN=/^\+?\d{7,20}$/;

  let errorTextFN="Not a valid format. <br/>Format (first capital letter, the word must not be longer than 16 characters)";
  let errorTextLN="Not a valid format. <br/>Format (first capital letter, the word must not be longer than 20 characters)";
  let errorTextEmail="Not a valid format";
  let errorTextPassword="Word length must be minimum 10";
  let errorTextPN="Not a valid format. <br/>Total digits 20";
  let errorTextPasswordConf="Passwords are not the same";


  let submit=[];
  submit.push(checkValue(firstName, regExpFN, errorTextFN));
  submit.push(checkValue(lastName, regExpLN, errorTextLN));
  submit.push(checkValue(email, regExpEmail, errorTextEmail));
  submit.push(checkValue(phoneNumber, regExpPN, errorTextPN));
  submit.push(checkValue(password, regExpPassword, errorTextPassword));
  if(password.val()!=passwordConfirm.val()){
    submit.push(false);
    if(!passwordConfirm.hasClass("error")){
      passwordConfirm.addClass("error");
      passwordConfirm.parent().append(`<span class="errorText">${errorTextPasswordConf}<span>`);
    }
  }else{
    passwordConfirm.removeClass("error");
    passwordConfirm.next(".errorText").hide();
  }

  if(!submit.includes(false)){
    $.ajax({
      url: "models/registration/registration.php",
      method: "post",
      dataType: "json",
      data:{
        submit: true,
        firstName: firstName.val(),
        lastName: lastName.val(),
        email: email.val(),
        phoneNumber: phoneNumber.val(),
        password: password.val(),
        passwordConfirm: passwordConfirm.val(),
        captchaText: captchaText.val()
      },
      success: function(data){
        $("#formRegisterErrors").html("<h3 class='p-3 text-center'>"+data.success+"</h3>");
        showModal();
        document.getElementById("formRegister").reset();
      },
      error: function(xhr, status, error){
        let html="<ol class='errorText flex3'>";
        if(xhr.status==422){
          xhr.responseJSON.errors.forEach(el => {
            html+=`<li>${el}</li>`;
          });
        }
        if(xhr.status==500){
          html+="<li>"+xhr.responseJSON.error+"</li>"
        }
        html+="</ol>";
        $("#formRegisterErrors").html(html);
        if(xhr.status==400){
          error_message(xhr);
        }
      }
    });
  }
}

/* REGISTER PAGE FUNCTIONS END */

/* CONTACT PAGE FUNCTIONS START */

function send_mail(){
  let firstName=$("#firstNameContact");
  let lastName=$("#lastNameContact");
  let email=$("#email");
  let subject=$("#subject");
  let message=$("#message");

  let regExpFN=/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,15}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,15})*$/;
  let regExpLN=/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,19}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,19})*$/;
  let regExpEmail=/^[^@]+@[^@]+\.[^@\.]+$/;
  let regExp=/^[\w\.,]+(\s[\w\.,]+)*$/;

  let errorTextFN="Not a valid format. <br/>Format (first capital letter, the word must not be longer than 16 characters)";
  let errorTextLN="Not a valid format. <br/>Format (first capital letter, the word must not be longer than 20 characters)";
  let errorTextEmail="Not a valid format";
  let errorTextSubject="You must enter an email subject";
  let errorTextMessage="You must enter a message";


  let submit=[];
  submit.push(checkValue(firstName, regExpFN, errorTextFN));
  submit.push(checkValue(lastName, regExpLN, errorTextLN));
  submit.push(checkValue(email, regExpEmail, errorTextEmail));
  submit.push(checkValue(subject, regExp, errorTextSubject));
  submit.push(checkValue(message, regExp, errorTextMessage));


  if(!submit.includes(false)){
    $.ajax({
      url: "models/users/send_mail.php",
      method: "post",
      dataType: "json",
      data:{
        submit: true,
        firstName: firstName.val(),
        lastName: lastName.val(),
        email: email.val(),
        subject: subject.val(),
        message: message.val()
      },
      success: function(data){
        $("#contactErrors").html("<h5 class='p-3 text-center'>"+data.success+"</h5>");
        document.getElementById("contactForm").reset();
      },
      error: function(xhr, status, error){
        let html="<ol class='errorText flex3'>";
        if(xhr.status==422){
          xhr.responseJSON.errors.forEach(el => {
            html+=`<li>${el}</li>`;
          });
        }
        if(xhr.status==500){
          html+="<li>"+xhr.responseJSON.error_message+"</li>"
        }
        html+="</ol>";
        $("#contactErrors").html(html);
        if(xhr.status==400){
          error_message(xhr);
        }
      }
    });
  }
}

/* CONTACT PAGE FUNCTIONS END */

/* PROPERTY PAGE FUNCTIONS START */

function visit(){
  let date=$("#date");
  let time=$("#time");
  let property=$("#property");
  let user=$("#user");

  let regExpDate=/^\d{4}-\d{2}-\d{2}$/;
  let regExpTime=/^\d{2}:\d{2}$/;

  let errorTextDate="You must enter the date of the visit";
  let errorTextTime="You must enter the time of the visit";

  let submit=[];
  if(!regExpDate.test(date.val().trim())){
    submit.push(false);
    if(!date.hasClass("error")){
      date.addClass("error");
      date.before(`<span class="errorText">${errorTextDate}<span>`);
    }
  }else{
    currentDate=Date.now()
    if(Date.parse(date.val().trim())<=currentDate){
      submit.push(false);
      if(!date.hasClass("error")){
        date.addClass("error");
        date.before(`<span class="errorText">Not a valid date<span>`);
      }
    }else{
      date.removeClass("error");
      date.prev(".errorText").hide();
    }
  }
  if(!regExpTime.test(time.val().trim())){
    submit.push(false);
    if(!time.hasClass("error")){
      time.addClass("error");
      time.before(`<span class="errorText">${errorTextTime}<span>`);
    }
  }else{
    time.removeClass("error");
    time.prev(".errorText").hide();
  }
  

  if(!submit.includes(false)){
    $.ajax({
      url: "models/visit/visit.php",
      method: "post",
      dataType: "json",
      data:{
        submit: true,
        date: date.val(),
        time: time.val(),
        property: property.val(),
        user: user.val()
      },
      success: function(data){
        date.removeClass("error");
        date.prev(".errorText").hide();
        time.removeClass("error");
        time.prev(".errorText").hide();
        alert(data.success);
        window.location.reload();
      },
      error: function(xhr, status, error){
        if(xhr.status==422){
          if(xhr.responseJSON.errors.errorDate){
            if(!date.hasClass("error")){
              date.addClass("error");
              date.before(`<span class="errorText">You must enter a valid date<span>`);
            }
          }
          if(xhr.responseJSON.errors.errorTime){
            if(!time.hasClass("error")){
              time.addClass("error");
              time.before(`<span class="errorText">${errorTextTime}<span>`);
            }
          }
        }
        if(xhr.status==500){
          error_message(xhr);
          alert(xhr.responseJSON.error_message);
        }
        if(xhr.status==400){
          error_message(xhr);
        }
      }
    });
  }
}

/* PROPERTY PAGE FUNCTIONS END */

/* ADMIN PANEL FUNCTIONS START */

function edit_profile(role){
  let passwordConfirm=null;
  let idRole=null;
  if(role=="user"){
    passwordConfirm=$("#passwordConfirm");
  }else{
    idRole=$("#role").val();
  }
  let firstName=$("#firstName");
  let lastName=$("#lastName");
  let email=$("#email");
  let password=$("#newPassword");
  let phoneNumber=$("#phone");
  let user=$("#user");

  let regExpFN=/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,15}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,15})*$/;
  let regExpLN=/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,19}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,19})*$/;
  let regExpEmail=/^[^@]+@[^@]+\.[^@\.]+$/;
  let regExpPassword=/^\w{10,}$/;
  let regExpPN=/^\+?\d{7,20}$/;

  let errorTextFN="Not a valid format. <br/>Format (first capital letter, the word must not be longer than 16 characters)";
  let errorTextLN="Not a valid format. <br/>Format (first capital letter, the word must not be longer than 20 characters)";
  let errorTextEmail="Not a valid format";
  let errorTextPassword="Word length must be minimum 10";
  let errorTextPN="Not a valid format. <br/>Total digits 20";


  let submit=[];
  submit.push(checkValue(firstName, regExpFN, errorTextFN));
  submit.push(checkValue(lastName, regExpLN, errorTextLN));
  submit.push(checkValue(email, regExpEmail, errorTextEmail));
  submit.push(checkValue(phoneNumber, regExpPN, errorTextPN));
  if(role=="user"){
    submit.push(checkValue(passwordConfirm, regExpPassword, errorTextPassword));
    passwordConfirm=passwordConfirm.val();
  }

  if(password.val()!=""){
    submit.push(checkValue(password, regExpPassword, errorTextPassword));
  }

  if(!submit.includes(false)){
    $.ajax({
      url: "models/users/update.php",
      method: "post",
      dataType: "json",
      data:{
        submit: role,
        firstName: firstName.val(),
        lastName: lastName.val(),
        email: email.val(),
        role: idRole,
        phoneNumber: phoneNumber.val(),
        password: password.val(),
        passwordConfirm: passwordConfirm,
        user: user.val()
      },
      success: function(data){
        console.log(data)
        $("#formRegisterErrors").html("<h3 class='p-3 text-center'>You have successfully update profile</h3>");
      },
      error: function(xhr, status, error){
        let html="<ol class='errorText flex3'>";
        if(xhr.status==422){
          xhr.responseJSON.errors.forEach(el => {
            html+=`<li>${el}</li>`;
          });
        }
        if(xhr.status==500){
          html+="<li>"+xhr.responseJSON.error_message+"</li>"
        }
        html+="</ol>";
        $("#formRegisterErrors").html(html);
        if(xhr.status==400){
          error_message(xhr);
        }
      }
    });
  }
}
function delete_property(link){
  let val=link.data("id");
  
  if(confirm("Do you want to delete this property")){
    $.ajax({
      url: "models/real_estate/delete.php",
      method: "post",
      dataType: "json",
      data:{
        submit: true,
        id: val
      },
      success: function(data){
        link.parent().parent().hide();
      },
      error: function(xhr, status, error){
        error_message(xhr);
        alert(xhr.responseJSON.error_message);
      }
    });
  }
}
function admin_real_estates(){
  real_estate_all(function(callback){
    show_real_estate_admin(callback.real_estate, "approved");
    pagination(callback.pages);
  })
}
function real_estate_unapproved(callback){
  $.ajax({
    url: "models/real_estate/real_estate_get_unapproved.php",
    method: "GET",
    success: callback
  })
}
function admin_real_estates_new(e){
  e.preventDefault();
    real_estate_unapproved(function(callback){
    if(callback.length){
      show_real_estate_admin(callback , "unapproved");
      $("#paginacija").html("");
    }else{
      no_results($("#main-content"));
    }
  })

}
function real_estate_deleted(callback){
  $.ajax({
    url: "models/real_estate/real_estate_get_deleted.php",
    method: "GET",
    success: callback
  })
}
function admin_real_estates_deleted(e){
  e.preventDefault();
    real_estate_deleted(function(callback){
    if(callback.length){
      show_real_estate_admin(callback, "deleted");
      $("#paginacija").html("");
    }else{
      no_results($("#main-content"));
    }
  })

}
function show_real_estate_admin(real_estate, type){
  let html="";
    real_estate.forEach(e => {
      html+=`
          <div class="col-11 flex flexWrap nekretnina">
              <div class="col-lg-3 col-sm-8 col-10 mx-auto slika">
                  <img src="assets/img/${e.src_medium}" class="img-fluid" alt="${e.title}">
                  <span class="sellRent">for ${e.category}</span>
              </div>
              <div class="col-lg-7 col-10 flex tekst">
                  <h3>${e.title}</h3>
                  <p>${e.city}, ${e.address}, ${e.country}</p>
                  <h4>${price(e.price)} &euro;</h4>
              </div>
              <div class="col-2 flex2 mx-auto editDelete">
                  ${icons(type, e.idRealEstate)}
              </div>
          </div>
          `
    });
    $("#main-content").html(html);
}
function icons(type, id){
  if(type=="approved"){
    return `<a href="index.php?page=editProperty&id=${id}" title="Edit"><i class="fas fa-edit"></i></a>
            <a href="#" title="Delete" class="delete" data-id="${id}"><i class="fas fa-trash-alt"></i></a>`
  }
  else if(type=="unapproved"){
    return `<a href="#" title="Approve" class="approve" data-id="${id}"><i class="fas fa-check-square"></i></a>
            <a href="#" title="Delete" class="delete" data-id="${id}"><i class="fas fa-trash-alt"></i></a>`
  }else if(type=="unreaded"){
    return `<a href="#" title="Seen" class="seen" data-id="${id}"><i class="fas fa-check"></i></a>`
  }else if(type=="deleted"){
    return `<a href="#" title="Activate" class="activate" data-id="${id}"><i class="fas fa-upload"></i></a>`
  }else if(type=="edit"){
    return `<a href="#" title="Edit" class="edit" data-id="${id}"><i class="fas fa-edit"></i></a>`
  }else{
    return "";
  }
}
function new_email(e){
  e.preventDefault();
  $.ajax({
    url: "models/users/email_new.php",
    method: "GET",
    dataType: "json",
    success: function(data){
      if(data.length){
        show_email(data, "unreaded");
        $("#paginacija").html("");
      }else{
        no_results($("#main-content"));
      }
    },
    error: function(xhr, status, error){
      if(xhr.status==500){
        error_message(xhr);
        alert(xhr.responseJSON.error_message);
      }
      if(xhr.status==400){
        error_message(xhr);
      }
    }
  })
}
function readed_email(e){
  e.preventDefault();
  $.ajax({
    url: "models/users/email_readed.php",
    method: "GET",
    dataType: "json",
    success: function(data){
      if(data.length){
        show_email(data, "");
        $("#paginacija").html("");
      }else{
        no_results($("#main-content"));
      }
    },
    error: function(xhr, status, error){
      if(xhr.status==500){
        error_message(xhr);
        alert(xhr.responseJSON.error_message);
      }
      if(xhr.status==400){
        error_message(xhr);
      }
    }
  })
}
function show_email(emails, type){
  let html="";
  emails.forEach(e => {
    html+=`<div class="col-11 flex flexWrap email">
              <div class="col-lg-3">
                <p class="name">${e.firstName} ${e.lastName}</p>
                <p class="address">${e.email}</p>
                <p>${e.date}</p>
              </div>
              <div class="col-lg-7 mail col-sm-10">
                <h5 class="subject">${e.subject}</h5>
                <p class="message">${e.message}</p>
              </div>
              <div class="col-12 col-sm-2 mx-auto flex2 editDelete">
                  ${icons(type, e.idContact)}
              </div>
          </div>`
  });
  $("#main-content").html(html);
}
function get_users(idRole){
  $.ajax({
    url: "models/users/user_get_all.php",
    method: "POST",
    dataType: "json",
    data:{
      idRole:idRole
    },
    success: function(data){
      if(data.length){
        show_users(data, "edit");
        $("#paginacija").html("");
      }else{
        no_results($("#main-content"));
      }
    },
    error: function(xhr, status, error){
      if(xhr.status==500){
        error_message(xhr);
        alert(xhr.responseJSON.error_message);
      }
      if(xhr.status==400){
        error_message(xhr);
      }
    }
  })
}
function show_users(users, type){
  let html="";
  users.forEach(e => {
    html+=`<div class="col-lg-6 col-sm-10 flex flexWrap email">
              <div class="col-10">
                <h5>ID: ${e.idUser}</h5>
                <p class="name">Name: ${e.firstName} ${e.lastName}</p>
                <p>Email: ${e.email}</p>
                <p>Mobile phone: ${e.phone}</p>
                <p>Registrated: ${e.date}</p>
              </div>
              <div class="col-sm-2 flex2 mx-aut0 editDelete">
                  ${icons(type, e.idUser)}
              </div>
          </div>`
  });
  $("#main-content").html(html);
}
function userEdit(e){
  e.preventDefault();
  let val=$(this).data("id");
  let link=$(this);
  $.ajax({
    url: "models/users/user_get_one.php",
    method: "POST",
    dataType: "json",
    data:{
      submit:true,
      idUser:val
    },
    success: function(data){
      link.parent().parent().after(editUserForm(data))
    },
    error: function(xhr, status, error){
      if(xhr.status==500){
        error_message(xhr);
        alert(xhr.responseJSON.error_message);
      }
      if(xhr.status==400){
        error_message(xhr);
      }
    }
  })
}
function editUserForm(data){
  return `
  <div class="col-10 mx-auto flex2" id="formEdit">
    <form class="flex flexWrap">
    
    <input type="hidden" id="user" name="user" value="${data.user.idUser}">
        <div class="col-md-5 col-10 mx-auto blokReg flex3">
            <label for="firstName">First name</label>
            <input type="text" id="firstName" name="firstName" value="${data.user.firstName}">
        </div>
        <div class="col-md-5 col-10 mx-auto blokReg flex3">
            <label for="lastName">Last name</label>
            <input type="text" id="lastName" name="lastName" value="${data.user.lastName}">
            
        </div>
        <div class="col-md-5 col-10 mx-auto blokReg flex3">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="${data.user.email}">
        </div>
        <div class="col-md-5 col-10 mx-auto blokReg flex3">
            <label for="phone">Phone number</label>
            <input type="text" id="phone" name="phone" value="${data.user.phone}">
        </div>

        <div class="col-md-5 col-10 mx-auto blokReg flex3">
            <label for="newPassword">New Password</label>
            <input type="password" id="newPassword">
        </div>

        <div class="col-md-5 col-10 mx-auto blokReg flex3">
            <label for="role">Role</label>
            ${roles(data)}
        </div>

        <div class="col-md-5 col-10 mx-auto flex3">
            <button type="button" class="posalji" id="editProfile">Submit</button>
        </div>
    </form>
    <div id="formRegisterErrors"></div>
  </div>   
  `
}
function roles(data){
  let html="<select id='role'>";
  data.role.forEach(element => {
    if(data.user.idRole==element.idRole){
      html+=`<option value="${element.idRole}" selected="selected">${element.role}</option>`;
    }else{
      html+=`<option value="${element.idRole}">${element.role}</option>`;
    }
  });
  html+="</select>";
  return html;
}
function mailSeen(e){
  e.preventDefault();
  let val=$(this).data("id");
  let link=$(this);
  $.ajax({
    url: "models/users/email_update.php",
    method: "POST",
    dataType: "json",
    data:{
      submit: true,
      idContact:val
    },
    success: function(data){
      link.parent().parent().hide()
    },
    error: function(xhr, status, error){
      if(xhr.status==500){
        error_message(xhr);
        alert(xhr.responseJSON.error_message);
      }
      if(xhr.status==400){
        error_message(xhr);
      }
    }
  })
}
function propertyActivate(e){
  e.preventDefault();
  let val=$(this).data("id");
  let link=$(this);
  $.ajax({
    url: "models/real_estate/real_estate_activate.php",
    method: "POST",
    dataType: "json",
    data:{
      submit: true,
      idRealEstate:val
    },
    success: function(data){
      link.parent().parent().hide()
    },
    error: function(xhr, status, error){
      if(xhr.status==500){
        error_message(xhr);
        alert(xhr.responseJSON.error_message);
      }
      if(xhr.status==400){
        error_message(xhr);
      }
    }
  })
}
function propertyApproved(e){
  e.preventDefault();
  let val=$(this).data("id");
  let link=$(this);
  $.ajax({
    url: "models/real_estate/real_estate_approve.php",
    method: "POST",
    dataType: "json",
    data:{
      submit: true,
      idRealEstate:val
    },
    success: function(data){
      link.parent().parent().hide()
    },
    error: function(xhr, status, error){
      if(xhr.status==500){
        error_message(xhr);
        alert(xhr.responseJSON.error_message);
      }
      if(xhr.status==400){
        error_message(xhr);
      }
    }
  })
}
function visit_get(e){
  e.preventDefault();
  $.ajax({
    url: "models/visit/visit_get_all.php",
    method: "POST",
    dataType: "json",
    success: function(data){
      show_visit(data, show_agents(data.agents), "assignAgent");
      $("#paginacija").html("");
    },
    error: function(xhr, status, error){
      if(xhr.status==500){
        error_message(xhr);
        alert(xhr.responseJSON.error_message);
      }
      if(xhr.status==400){
        error_message(xhr);
      }
    }
  })
}
function show_visit(data, fun, id){
  let html="";
  if(data.visits.length){
    data.visits.forEach(e => {
      html+=`<div class="col-lg-8 col-md-10 col-sm-11  flex flexWrap nekretnina">
              <div class="col-lg-8 col-10 mx-auto slika">
                  <img src="assets/img/${e.src_medium}" class="img-fluid" alt="${e.title}">
                  <span class="sellRent">for ${e.category}</span>
              </div>
              <div class="col-11 mx-auto flex2">
                <h3>${e.title}</h3>
                <p>${e.city}, ${e.address}, ${e.country}</p>
                <h4>${price(e.price)} &euro;</h4>  
              </div>
              <div class="col-md-8 flex1 flexWrap mx-auto">
                <div class="col-md-7">
                  <h5>Client</h5>
                  <p>Name: ${e.firstName} ${e.lastName}</p>
                  <p>Mobile phone: ${e.phone}</p>
                  <p>Email: ${e.email}</p>
                  <p>Time of visit: ${e.dateVisit}, ${e.time}</p>
                </div>
                <div class="col-md-5 flex2">
                  ${fun}
                  <button type="button" class="posalji m-3" id="${id}" value="${e.idVisit}">Assign</button>
                  <div class="assignAgentError"></div>
                </div>
              </div>
            </div>`;
    });
  }else{
    html+=`
    <div class="col-12 flex2 noResults">
      <h1 id="noResults"><i class="fas fa-home"></i> No visits scheduled</h1>
    </div>`;
  }
  $("#main-content").html(html);
}
function show_agents(data){

  let html=`<h5 class="mb-4">Assign agent</h5> <select id='agent'> <option value='0'>Choose</option>`;
  data.forEach(element => {
    html+=`<option value="${element.idAgent}">${element.firstName} ${element.lastName}</option>`;
  });
  html+=`</select>`;
  return html;
}
function agent_assign(){
  let val=$(this).val();
  let link=$(this);
  let agent=$("#agent");
  let submit=checkValue(agent, /^[^0]+$/, "You must choose agent");
  if(submit){
    $.ajax({
      url: "models/visit/agent_visit_insert.php",
      method: "POST",
      dataType: "json",
      data:{
        submit: true,
        agent: agent.val(),
        visit: val
      },
      success: function(data){
        link.parent().parent().parent().hide();
        link.next(".assignAgentError").html("");
      },
      error: function(xhr, status, error){
        if(xhr.status==422){
          let html="<ol class='errorText flex3'>";
          xhr.responseJSON.errors.forEach(el => {
            html+=`<li>${el}</li>`;
          });
          html+="</ol>";
          link.next(".assignAgentError").html(html);
        }
        if(xhr.status==500){
          error_message(xhr);
          alert(xhr.responseJSON.error_message);
        }
        if(xhr.status==400){
          error_message(xhr);
        }
      }
    })
  }
  
}
function visit_outcome_get(e){
  let val=$(this).data("id");
  e.preventDefault();
  $.ajax({
    url: "models/visit/agent_visit_get.php",
    method: "POST",
    dataType: "json",
    data:{
      id:val
    },
    success: function(data){
      show_visit(data, show_outcome(data.outcome), "outcome");
    },
    error: function(xhr, status, error){
      if(xhr.status==500){
        error_message(xhr);
        alert(xhr.responseJSON.error_message);
      }
      if(xhr.status==400){
        error_message(xhr);
      }
    }
  })
}
function show_outcome(data){

  let html=`<h5 class="mb-4">Outcome</h5> <select id='outcomeDdl'> <option value='0'>Choose</option>`;
  data.forEach(element => {
    html+=`<option value="${element.idOutcome}">${element.outcome}</option>`;
  });
  html+=`</select><h5 class="mb-4">Description</h5><textarea rows="5" id="outcomeDescription"></textarea>`;
  return html;
}
function visit_outcome_insert(){
  let val=$(this).val();
  let link=$(this);
  let outcome=$("#outcomeDdl");
  let outcomeDescription=$("#outcomeDescription");
  let submit=[];
  submit.push(checkValue(outcome, /^[^0]+$/, "You must choose outcome"));
  submit.push(checkValue(outcomeDescription, /^\w+(\s\w+)*$/, "You must enter the description"));
  if(!submit.includes(false)){
    $.ajax({
      url: "models/visit/visit_outcome_insert.php",
      method: "POST",
      dataType: "json",
      data:{
        submit: true,
        outcome: outcome.val(),
        outcomeDescription: outcomeDescription.val(),
        visit: val
      },
      success: function(data){
        link.parent().parent().parent().hide();
        link.next(".assignAgentError").html("");
      },
      error: function(xhr, status, error){
        if(xhr.status==422){
          let html="<ol class='errorText flex3'>";
          xhr.responseJSON.errors.forEach(el => {
            html+=`<li>${el}</li>`;
          });
          html+="</ol>";
          link.next(".assignAgentError").html(html);
        }
        if(xhr.status==500){
          error_message(xhr);
          alert(xhr.responseJSON.error_message);
        }
        if(xhr.status==400){
          error_message(xhr);
        }
      }
    })
  }
  
}
function agent_get_one(e){
  let val=$(this).data("id");
  e.preventDefault();
  $.ajax({
    url: "models/agents/agent_get_one.php",
    method: "POST",
    dataType: "json",
    data:{
      id:val
    },
    success: function(data){
      show_agent(data);
    },
    error: function(xhr, status, error){
      if(xhr.status==500){
        error_message(xhr);
        alert(xhr.responseJSON.error_message);
      }
      if(xhr.status==400){
        error_message(xhr);
      }
    }
  })
}
function show_agent(data){
  let html=`
  <div class="col-12  flex flexWrap">
    <div class="col-lg-3">
        <img src="assets/img_agent/${data.agent.image}" class="img-fluid agentProfilePhoto" alt="${data.agent.firstName} ${data.agent.lastName}">
        <a href="#" class="d-block text-center mt-1" id="show_input">Add new profile photo</a>
        <input type="file" name="agentProfileImg" class="mx-auto" id="agentProfileImg">
    </div>
    <div class="col-lg-9 mx-auto">
      <form class="flex flexWrap">
          <input type="hidden" id="user" name="user" value="${data.agent.idAgent}">
          <div class="col-md-5 col-10 mx-auto blokReg flex3">
              <label for="firstName">First name</label>
              <input type="text" id="firstName" name="firstName" value="${data.agent.firstName}">
          </div>
          <div class="col-md-5 col-10 mx-auto blokReg flex3">
              <label for="lastName">Last name</label>
              <input type="text" id="lastName" name="lastName" value="${data.agent.lastName}">
              
          </div>
          <div class="col-md-5 col-10 mx-auto blokReg flex3">
              <label for="email">Email</label>
              <input type="text" id="email" name="email" value="${data.agent.email}">
          </div>
          <div class="col-md-5 col-10 mx-auto blokReg flex3">
              <label for="phone">Phone number</label>
              <input type="text" id="phone" name="phone" value="${data.agent.phone}">
          </div>
          <div class="col-md-5 col-10 mx-auto blokReg flex3">
            <label for="linkedin">Linkedin</label>
            <input type="text" id="linkedin" name="linkedin" value="${data.agent.linkLinkedin}">
        </div>
          <div class="col-md-5 col-10 mx-auto blokReg flex3">
              <label for="profession">Profession</label>
              ${agent_type(data)}
          </div>
          <div class="col-11 mx-auto blokReg flex3">
              <label for="desc">Biography</label>
              <textarea id="agentDesc" rows="5">${data.agent.description}</textarea>
          </div>

          <div class="col-5 mx-auto flex3">
              <button type="button" class="posalji m-4" id="editAgentProfile">Submit</button>
          </div>
      </form>
      <div id="formEditProfileErrors"></div>
    </div>
  </div>`;

  $("#main-content").html(html);
}
function agent_type(data){
  let html="<select id='agentType'> <option value='0'>Choose</option>";
  data.type.forEach(element => {
    if(data.agent.idType==element.idType){
      html+=`<option value="${element.idType}" selected="selected">${element.type}</option>`;
    }else{
      html+=`<option value="${element.idType}">${element.type}</option>`;
    }
  });
  html+="</select>";
  return html;
}
function agent_set_photo(){
  var reader= new FileReader()

  reader.addEventListener("load", function(){
    $(".agentProfilePhoto").attr("src", this.result)

  })
  reader.readAsDataURL(this.files[0]);

  let formData=new FormData();
  formData.append("submit", true);
  formData.append("image", this.files[0]);
  formData.append("id", $("#user").val());
  $.ajax({
    url: "models/agents/agent_image.php",
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    error: function(xhr, status, error){
      if(xhr.status==500){
        error_message(xhr);
        alert(xhr.responseJSON.error_message);
      }
      if(xhr.status==400){
        error_message(xhr);
      }
    }
  })
}
function edit_agent_profile(){
  let firstName=$("#firstName");
  let lastName=$("#lastName");
  let email=$("#email");
  let phoneNumber=$("#phone");
  let agentType=$("#agentType");
  let agentDesc=$("#agentDesc");
  let linkedin=$("#linkedin");
  let user=$("#user");

  let regExpFN=/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,15}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,15})*$/;
  let regExpLN=/^[A-ZŠĐŽČĆ][a-zšđžčć]{2,19}(\s[A-ZŠĐŽČĆ][a-zšđžčć]{2,19})*$/;
  let regExpEmail=/^[^@]+@[^@]+\.[^@\.]+$/;
  let regExpPN=/^\+?\d{7,20}$/;
  let regExp=/^[^0]+$/;
  let regExp2=/^[\w\.,]+(\s[\w\.,]+)*$/;


  let errorTextFN="Not a valid format. <br/>Format (first capital letter, the word must not be longer than 16 characters)";
  let errorTextLN="Not a valid format. <br/>Format (first capital letter, the word must not be longer than 20 characters)";
  let errorTextEmail="Not a valid format";
  let errorTextPN="Not a valid format. <br/>Total digits 20";
  let errorTextType="You must choose profession";
  let errorTextDesc="You must enter the biography";



  let submit=[];
  submit.push(checkValue(firstName, regExpFN, errorTextFN));
  submit.push(checkValue(lastName, regExpLN, errorTextLN));
  submit.push(checkValue(email, regExpEmail, errorTextEmail));
  submit.push(checkValue(phoneNumber, regExpPN, errorTextPN));
  submit.push(checkValue(agentType, regExp, errorTextType));
  submit.push(checkValue(agentDesc, regExp2, errorTextDesc));
  submit.push(checkValue(agentType, regExp2, errorTextType));

  if(!submit.includes(false)){
    $.ajax({
      url: "models/agents/update.php",
      method: "post",
      dataType: "json",
      data:{
        submit: true,
        firstName: firstName.val(),
        lastName: lastName.val(),
        email: email.val(),
        phoneNumber: phoneNumber.val(),
        agentType: agentType.val(),
        agentDesc: agentDesc.val(),
        linkedin: linkedin.val(),
        agent: user.val()
      },
      success: function(data){
        $("#formEditProfileErrors").html("<h3 class='p-3 text-center'>You have successfully update profile</h3>");
      },
      error: function(xhr, status, error){
        let html="<ol class='errorText flex3'>";
        if(xhr.status==422){
          xhr.responseJSON.errors.forEach(el => {
            html+=`<li>${el}</li>`;
          });
        }
        if(xhr.status==500){
          html+="<li>"+xhr.responseJSON.error_message+"</li>"
        }
        html+="</ol>";
        $("#formEditProfileErrors").html(html);
        if(xhr.status==400){
          error_message(xhr);
        }
      }
    });
  }
}

/* ADMIN PANEL FUNCTIONS END */

function price(price){
  price=price.substr(0, price.length-3).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return price;
}

function real_estate_all(callback){
  $.ajax({
      url: "models/real_estate/real_estate_get_all.php",
      method: "GET",
      success: callback
  });
}

function no_results(div){
  let html=`
  <div class="col-12 flex2 noResults">
    <h1 id="noResults"><i class="fas fa-search"></i> Your search did not return any results. Please try again with other information</h1>
  </div>`
  div.html(html);
  $("#paginacija").html("");
}

function pagination(pg){
  let ispis="<ul>";
      for(let i=1; i<=pg; i++){
          if(i==1) ispis+=`<li><a href="#" class="flex2 page aktivan" data-page="${i}">${i}</a></li>`;
          else ispis+=`<li><a href="#" class="flex2 page" data-page="${i}">${i}</a></li>`;
      }
      ispis+="</ul>"
      document.getElementById("paginacija").innerHTML=ispis;
}

function show_real_estate(data){
  let html="";
  data.forEach(el => {
    html+=`
    <div class="col-11 flex flexWrap nekretnina">
      <div class="col-lg-5 col-md-10 slika mx-auto">
          <img src="assets/img/${el.src_medium}" class="img-fluid" alt="${el.title}">
          <span class="sellRent">for ${el.category}</span>
      </div>
      <div class="col-lg-7 col-md-10 flex tekst mx-auto">
          <a href="index.php?page=property&id=${el.idRealEstate}"><h3>${el.title}</h3></a>
          <p>${el.city}, ${el.address}, ${el.country}</p>
          <h4>${price(el.price)} &euro;</h4>
          <p>${el.description}</p>
          <div class="pt-3">
              <span class="info"> <img src="assets/images/m2.png" alt="size"> ${el.size}m<sup>2</sup></span>
              <span class="info"> <img src="assets/images/bed.png" alt="bed"> ${el.bedrooms} Bedrooms</span>
              <span class="info"> <img src="assets/images/bath.png" alt="bath"> ${el.bathrooms} Bathrooms</span>
          </div>
      </div>
    </div>`
  });
  $("#nekretnine2").html(html);
}

function error_message(xhr){
  console.error(xhr.responseJSON.error_message);
}

function checkValue(input, regExp, errorText){
  if(!regExp.test(input.val().trim())){
    if(!input.hasClass("error")){
      input.addClass("error");
      input.parent().append(`<span class="errorText">${errorText}<span>`);
    }
    return false
  }else{
    input.removeClass("error");
    input.next(".errorText").hide();
    return true;
  }
}

function match(a, b, bErrorText){
  if(a.val()!=b.val()){
    submit.push(false);
    if(!b.hasClass("error")){
      b.addClass("error");
      b.parent().append(`<span class="errorText">${bErrorText}<span>`);
    }
  }else{
    b.removeClass("error");
    b.next(".errorText").hide();
  }
}

function showModal(){
  $("html").append(`
  <div class="mojModalContainer col-12">
    <div class="mojModal">
      <div class="modalHeader flex1">
        <div id="modalLogoImg" class="col-11 flex">
          <img src="assets/images/logo2.png" alt="logo Real Estate white" id="modalLogo"/>
          <h3>Login</h3>
        </div>
        <i class="fas fa-times closeModal"></i>
      </div>
      <form action="#" method="post">
        <div class="modalContent flex2">
        <span><i class="fas fa-envelope"></i> <input type="text" id="loginEmail" name="loginEmail" placeholder="Your email"/></span><span class="errorText" id="loginEmailError"></span>
        <span><i class="fas fa-lock"></i></i> <input type="password" id="loginPassword" name="loginPassword" placeholder="Your password"/></span><span class="errorText"  id="loginPasswordError"></span>
        <p class="textLoginRegister">Don't have an account? Click here to <a href="index.php?page=register">sing up.</a></p>
        </div>
        <div class="modalFooter"><input type="button" id="login" value="Login"></div>
      </form>
    </div>
  </div>`);
  $(".modalContent input").focus(function(){
    $(this).prev().css("color", "rgb(43, 65, 187)");
  });
  $(".modalContent input").blur(function(){
    $(this).prev().css("color", "rgb(0, 0, 0)");
  });
  $(".mojModal .closeModal").click(function(){
    $(".mojModalContainer").css("display", "none");
  })
}

function login(e){
  e.preventDefault();
  let email=$("#loginEmail");
  let password=$("#loginPassword");

  let regExpEmail=/^[^@]+@[^@]+\.[^@\.]+$/;
  let regExpPassword=/^\w{10,}$/;

  let errorTextEmail="Not a valid format";
  let errorTextPassword="Word length must be minimum 10";


  let submit=true;
  if(!regExpEmail.test(email.val().trim())){
    $("#loginEmailError").text(errorTextEmail);
    submit=false;
  }else{
    $("#loginEmailError").text("");
  }
  if(!regExpPassword.test(password.val().trim())){
    $("#loginPasswordError").text(errorTextPassword);
    submit=false;
  }else{
    $("#loginPasswordError").text("");
  }

  if(submit){
    $.ajax({
      url: "models/login/login.php",
      method: "post",
      dataType: "json",
      data:{
        submit: true,
        email: email.val(),
        password: password.val()
      },
      success: function(data){
        window.location.href="index.php?page=home";
      },
      error: function(xhr, status, error){
        if(xhr.status==422){
          if(xhr.responseJSON.errorPassword){
            $("#loginPasswordError").text(xhr.responseJSON.errorPassword);
          }
          if(xhr.responseJSON.errorEmail){
            $("#loginEmailError").text(xhr.responseJSON.errorEmail);
          }
        }
        if(xhr.status==500){
          error_message(xhr);
          alert(xhr.responseJSON.error_message);
        }
        if(xhr.status==400){
          error_message(xhr);
        }
      }
    });
  }
}

function logout(e){
  e.preventDefault();
  $.ajax({
    url: "models/logout/logout.php",
    method: "post",
    success: function(){
      window.location="index.php?page=home";
    }
  })
}

function profile(){
  $(".profilePopup").slideToggle()
}

function disabled(e){
  e.preventDefault();
  alert("You cannot schedule a visit to your own property");
}