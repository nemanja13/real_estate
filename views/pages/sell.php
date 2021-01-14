<div class="container-fluid" id="sellCover">
    <div class="row flex2">
        <div class="col-10 flex3" id="sellCoverText">
            <h1>Sell and lease your property with the lowest commission on the market</h1>
            <h2>Sell or lease your property</h2>
            <?php if(isset($_SESSION["user"]) && $_SESSION["user"]->idRole==1):?>
                <a href="index.php?page=form" id="submitProperty">Add the property</a>
            <?php else:?>
                <a href="#" class="login" id="submitProperty">Add the property</a>
            <?php endif;?>
        </div>
    </div>
</div>
<div class="container" data-aos="fade-up">
    <div class="row">
        <div class="col-12 sellText">
            <h3>We will match you with genuinely interested buyers</h3>
            <p>With our advanced property presentation, online agents’ support, and our entire professional team at your service, you can sell your property on the spot, with the lowest commission on the market. After you fill in the form to add the property, our team will contact you and record every detail of your apartment, house or an office space. In just 24h, we will make an interactive online presentation, which will offer a clear insight into the property. We will be there for you from the very beginning to the end, from photo shoots all the way to signing the contracts.</p>
        </div>
    </div>
</div>
<div class="container" data-aos="fade-up">
    <div class="row p-5">
        <div class="col-12">
            <h3>The innovative system of property listings</h3>
        </div>
        <div class="col-12 flex flexWrap justify-content-md-between justify-content-center">
            <div class="col-lg-4 col-md-5 col-10 sellSteps">
                <div class="flex2">
                    <img src="assets/images/form.png" class="img-fluid" alt="form">
                </div>
                <div class="flex">
                    <p class="broj">01</p>
                    <p class="crta"></p>
                    <p class="sellStepsText">Fill in the form to add the property</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-10 sellSteps">
                <div class="flex2">
                    <img src="assets/images/document.png" class="img-fluid" alt="document">
                </div>
                <div class="flex">
                    <p class="broj">02</p>
                    <p class="crta"></p>
                    <p class="sellStepsText">Have your verification of ownership ready</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-10 sellSteps">
                <div class="flex2">
                    <img src="assets/images/house.png" class="img-fluid" alt="house">
                </div>
                <div class="flex">
                    <p class="broj">03</p>
                    <p class="crta"></p>
                    <p class="sellStepsText">Tidy up your property for professional shooting</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-10 sellSteps">
                <div class="flex2">
                    <img src="assets/images/tablet.png" class="img-fluid" alt="tablet">
                </div>
                <div class="flex">
                    <p class="broj">04</p>
                    <p class="crta"></p>
                    <p class="sellStepsText">We will publish the interactive online presentation</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-10 sellSteps">
                <div class="flex2">
                    <img src="assets/images/buyer.png" class="img-fluid" alt="buyer">
                </div>
                <div class="flex">
                    <p class="broj">05</p>
                    <p class="crta"></p>
                    <p class="sellStepsText">Potential buyers will contact you</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-10 sellSteps">
                <div class="flex2">
                    <img src="assets/images/handshake.png" class="img-fluid" alt="handshake">
                </div>
                <div class="flex">
                    <p class="broj">06</p>
                    <p class="crta"></p>
                    <p class="sellStepsText">You will conclude a contract of sale</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid" id="fillInForm">
    <div class="row">
        <div class="col-12 flex" data-aos="fade-up" id="sell">
            <h2>Fill in the form to add the property</h2>
            <h3>Navigate your first home sale confidently with tips from ours highest-performing real estate agents. Follow their professional advice for every obstacle that could come your way as a first-time home seller.</h3>
            <?php if(isset($_SESSION["user"]) && $_SESSION["user"]->idRole==1):?>
            <a href="index.php?page=form">Fill in the form</a>
            <?php else:?>
                <a href="#" class="login">Fill in the for</a>
            <?php endif;?>
        </div>
    </div>
</div>
<div class="container">
    <div class="row about" data-aos="fade-up">
        <div class="col-12 p-3 mb-3">
            <h2>This is why you should sell your property with Real Estate</h2>
        </div>
        <div class="col-lg-6 col-11 mx-auto flex aboutBlock">
            <div>
                <img src="assets/images/profTeam.jpg" alt="professional team">
            </div>
            <div>
                <h3>Professional team</h3>
                <p>We can give you expert property evaluation, help you determine the deadlines for the property handover, advise you on an appropriate investment, and offer you support throughout the realization of legal and administrative processes. And for all this, we are available all day.</p>
            </div>
        </div>
        <div class="col-lg-6 col-11 mx-auto flex aboutBlock">
            <div>
                <img src="assets/images/lowestCommission.jpg" alt="lowest commission">
            </div>
            <div>
                <h3>Lowest commission on the market</h3>
                <p>We improved the search and changed the way you schedule property viewings. We increased the availability by transferring the agents from the field to our offices. We completed the most important task by setting up an advanced online presentation. By switching to the online model, we were able to reduce our costs and offer the lowest commission on the market.</p>
            </div>
        </div>
        <div class="col-lg-6 col-11 mx-auto flex aboutBlock">
            <div>
                <img src="assets/images/time.jpg" alt="time">
            </div>
            <div>
                <h3>We value your time</h3>
                <p>Since users know in advance how your property looks like, you can expect to meet only sincerely interested buyers. As you don't have to meet with agent anymore, you can plan the viewing when it suits you the best. Your agent is available throughout the day and you will communicate over the phone or electronically.</p>
            </div>
        </div>
        <div class="col-lg-6 col-11 mx-auto flex aboutBlock">
            <div>
                <img src="assets/images/communication.jpg" alt="communication">
            </div>
            <div>
                <h3>Improved communication</h3>
                <p>Viewings without the agent present at the property opened new possibilities for negotiation. Buyers already know how your property looks like, so the viewing is actually an opportunity for you to get to know each other and build mutual trust. Both you and the buyers will have an online agent available for any additional questions.</p>
            </div>
        </div>
    </div>
</div>