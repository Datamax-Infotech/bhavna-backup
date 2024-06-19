<?php session_start();
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
require_once('boomerange_common_header.php');
?>
<style>
    .section-title {
        overflow: hidden;
        position: relative;
        text-align: center;
        margin: 10px 0px 25px 0px;
    }

    .section-title>span {
        display: inline-block;
        background: white;
        padding: 0 30px;
        position: relative;
        color: #000;
        font-weight: 600;
    }

    .section-title::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0px;
        border-bottom: 2px solid;
        width: 100%;
        color: #000;
    }

    .main_section {
        padding: 2rem 5rem;
    }

    .bg_F4F4F4 {
        background: #F4F4F4;
    }

    .color_orange {
        color: #e25820;
    }
    .color_FFF{
        color: #FFF;
    }
    .color_173D6E{
        color: #173D6E;
    }
    .footer-top{
        color: #FFF;
        background: #5CB726;
    }
    .product-category-title {
        display: flex;
        text-transform: uppercase;
        align-items: center;
        font-weight: 600;
    }

    .product-category-title .number {
        font-size: 70px;
    }
    .category-img{
        height: 169px;
        width: 186px;
    }
    .product-category-title .description {
        margin-left: 10px;
    }

    .product-category-title .description .title1 {
        font-size: 25px;
    }

    .product-category-title .description .title2 {
        font-size: 16px;
    }

    #gallery .carousel-control-prev {
        left: -20px;
    }

    #gallery .carousel-control-next {
        right: -20px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' stroke='%2322313F' stroke-miterlimit='10' stroke-width='2' viewBox='0 0 34.589 66.349'%3E%3Cpath d='M34.168.8 1.7 33.268 34.168 65.735'/%3E%3C/svg%3E");
        height: 100px;
    }

    .carousel-control-next-icon {
        transform: rotate(180deg);
    }

    #gallery .carousel-indicators {
        bottom: -35px;
        margin: 0px 10px;
    }

    #gallery .carousel-indicators li {
        background-color: gray;
        height: 10px;
        width: 10px;
        border-radius: 50%;
    }
    .width_50px{
        width:50px;
    }

    #gallery .carousel-indicators li.active {
        background-color: #5CB726;
    }

    /* medium - display 4  */
    @media (min-width: 768px) {

        #gallery .carousel-inner .carousel-item-right.active,
        #gallery .carousel-inner .carousel-item-next {
            transform: translateX(33.33333%);
        }

        #gallery .carousel-inner .carousel-item-left.active,
        #gallery .carousel-inner .carousel-item-prev {
            transform: translateX(-33.33333%);
        }
    }

    /* large - display 5 */
    @media (min-width: 992px) {

        #gallery .carousel-inner .carousel-item-right.active,
        #gallery .carousel-inner .carousel-item-next {
            transform: translateX(90%);
        }

        #gallery .carousel-inner .carousel-item-left.active,
        #gallery .carousel-inner .carousel-item-prev {
            transform: translateX(-90%);
        }
    }

    #gallery .carousel-inner .carousel-item-right,
    #gallery .carousel-inner .carousel-item-left {
        transform: translateX(0);
    }


    /* gallery slider */
    #gallery .carousel-inner .carousel-item.active,
    #gallery .carousel-inner .carousel-item-next,
    #gallery .carousel-inner .carousel-item-prev {
        display: flex;
    }

    @media (max-width: 768px) {
        #gallery .carousel-inner .carousel-item>div {
            display: none;
        }

        #gallery .carousel-inner .carousel-item>div:first-child {
            display: block;
            text-align: center;
        }
    }
</style>
<div class="container-fluid main_section bg_F4F4F4">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <a href="client_dashboard_new.php?show=inventory">
                <img src="images/UsedGaylordTotes_MainBanner.png" class="img-fluid">
            </a>
        </div>
        <div class="width_50px"></div>
        <div class="col-md-4 ">
            <img src="images/UsedShippingBoxes_MainBanner_ComingSoon.png" class="img-fluid">
        </div>
    </div>
</div>
<div class="container-fluid main_section product-categories">
    <h2 class="section-title"><span>Product Categories</span></h2>
    <div class="px-5">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <a href="client_dashboard_new.php?show=inventory"><img src="images/product_category1.jpg" class="img-fluid category-img"></a>
                <a href="client_dashboard_new.php?show=inventory">
                    <h5 class="text-center mt-2">Used Gaylord Totes</h5>
                </a>
            </div>
            <div class="width_50px"></div>
            <div class="col-md-2">
                <img src="images/product_category2.jpeg" class="img-fluid category-img">
                <h5 class="text-center mt-2">Used Shipping Boxes</h5>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-4">
                <div class="product-category-title">
                    <div class="number color_orange">1</div>
                    <div class="description">
                        <span class="title1 color_orange">Who</span><br>
                        <span class="title2">We Are</span>
                    </div>
                </div>
                <p>
                    <b>THE brand name in used boxes</b>. Founded in 2006, now the largest processor and broker of used gaylord totes and shipping boxes in North America. Our
                    passion is creating profit by reducing waste…with integrity and transparency... leveraging technology to make reuse accessible and easy for everyone. We are a part of
                    UCBEnviromental, a family of brands focused on Sustainable Sustainability.
                </p>
            </div>
            <div class="col-md-4">
                <div class="product-category-title">
                    <div class="number color_orange">2</div>
                    <div class="description">
                        <span class="title1 color_orange">What</span><br>
                        <span class="title2">We do</span>
                    </div>
                </div>
                <p>
                    <b>Buy and sell used gaylord totes and shipping boxes.</b> We buy used gaylord totes and shipping boxes from large companies, for premiums above recycling rebates, who
                    would otherwise landfill or recycle them after unpacking. We then inspect, sort, separate, palletize, inventory, and resell those boxes for cheaper than the cost of brand new boxes. All
                    boxes are sourced in North America and can ship anywhere globally. Unpackers make more money, buyers save money, we create jobs and turn a profit, all while doing the right thing
                    for the environment.
                </p>
            </div>
            <div class="col-md-4">
                <div class="product-category-title">
                    <div class="number color_orange">3</div>
                    <div class="description">
                        <span class="title1 color_orange">Why</span><br>
                        <span class="title2">CHOOSE UCB</span>
                    </div>
                </div>
                <p>
                    <b>Boxes that are Cheaper. Greener. Guaranteed.<sup>TM</sup></b> UsedCardboardBoxes.com is the ONLY fully online marketplace for wholesale used gaylord
                    totes and shipping boxes. We make it easy for you to cost compare against your current vendors, and order what you need. All boxes are commercially sourced and savings of
                    upwards of 75% less than comparable brand new box prices. Our industry leading customer service team will help you every step of the way.
                </p>
            </div>
        </div>
        <div class="row mt-5 align-items-center justify-content-center">
            <div class="col-sm-1">
                <img src="images/clients_slider/news01.avif" class="img-fluid">
            </div>
            <div class="col-sm-1">
                <img src="images/clients_slider/news02.webp" class="img-fluid">
            </div>
            <div class="col-sm-1">
                <img src="images/clients_slider/news03.webp" class="img-fluid">
            </div>
            <div class="col-sm-1">
                <img src="images/clients_slider/news04.webp" class="img-fluid">
            </div>
            <div class="col-md-2  text-center">
                <h5><b>AS SEEN ON</b></h5>
            </div>
            <div class="col-sm-1">
                <img src="images/clients_slider/news01.avif" class="img-fluid">
            </div>
            <div class="col-sm-1">
                <img src="images/clients_slider/news02.webp" class="img-fluid">
            </div>
            <div class="col-sm-1">
                <img src="images/clients_slider/news03.webp" class="img-fluid">
            </div>
            <div class="col-sm-1">
                <img src="images/clients_slider/news04.webp" class="img-fluid">
            </div>
        </div>
    </div>
</div>
<div class="container-fluid main_section">
    <p class="section-title"><span>UsedCardboardBoxes.com is a UCBEnvironmental company<br> whose clients include</span></p>
    <div class="px-5">
        <div class="row mx-auto h-100">
            <div id="gallery" class="carousel slide w-100 align-self-center px-5" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#gallery" data-slide-to="0" class="active"></li>
                    <li data-target="#gallery" data-slide-to="1"></li>
                    <li data-target="#gallery" data-slide-to="2"></li>
                    <li data-target="#gallery" data-slide-to="3"></li>
                    <li data-target="#gallery" data-slide-to="4"></li>
                    <li data-target="#gallery" data-slide-to="5"></li>
                    <li data-target="#gallery" data-slide-to="6"></li>
                    <li data-target="#gallery" data-slide-to="7"></li>
                    <li data-target="#gallery" data-slide-to="8"></li>
                    <li data-target="#gallery" data-slide-to="9"></li>
                    <li data-target="#gallery" data-slide-to="10"></li>
                    <li data-target="#gallery" data-slide-to="11"></li>
                    <li data-target="#gallery" data-slide-to="12"></li>
                    <li data-target="#gallery" data-slide-to="13"></li>
                    <li data-target="#gallery" data-slide-to="14"></li>
                    <li data-target="#gallery" data-slide-to="15"></li>
                    <li data-target="#gallery" data-slide-to="16"></li>
                    <li data-target="#gallery" data-slide-to="17"></li>
                    <li data-target="#gallery" data-slide-to="18"></li>
                    <li data-target="#gallery" data-slide-to="19"></li>
                    <li data-target="#gallery" data-slide-to="20"></li>
                    <li data-target="#gallery" data-slide-to="21"></li>
                    <li data-target="#gallery" data-slide-to="22"></li>
                    <li data-target="#gallery" data-slide-to="23"></li>
                    <li data-target="#gallery" data-slide-to="24"></li>
                    <li data-target="#gallery" data-slide-to="25"></li>
                    <li data-target="#gallery" data-slide-to="26"></li>
                    <li data-target="#gallery" data-slide-to="27"></li>
                    <li data-target="#gallery" data-slide-to="28"></li>
                    <li data-target="#gallery" data-slide-to="29"></li>
                    <li data-target="#gallery" data-slide-to="30"></li>
                    <li data-target="#gallery" data-slide-to="31"></li>
                    <li data-target="#gallery" data-slide-to="32"></li>
                    <li data-target="#gallery" data-slide-to="33"></li>
                    <li data-target="#gallery" data-slide-to="34"></li>
                    <li data-target="#gallery" data-slide-to="35"></li>
                    <li data-target="#gallery" data-slide-to="36"></li>
                    <li data-target="#gallery" data-slide-to="37"></li>
                    <li data-target="#gallery" data-slide-to="38"></li>
                    <li data-target="#gallery" data-slide-to="39"></li>
                    <li data-target="#gallery" data-slide-to="40"></li>
                    <li data-target="#gallery" data-slide-to="41"></li>
                    <li data-target="#gallery" data-slide-to="42"></li>
                    <li data-target="#gallery" data-slide-to="43"></li>
                    <li data-target="#gallery" data-slide-to="44"></li>
                    <li data-target="#gallery" data-slide-to="45"></li>
                    <li data-target="#gallery" data-slide-to="46"></li>
                    <li data-target="#gallery" data-slide-to="47"></li>
                    <li data-target="#gallery" data-slide-to="48"></li>
                    <li data-target="#gallery" data-slide-to="49"></li>
                    <li data-target="#gallery" data-slide-to="50"></li>
                    <li data-target="#gallery" data-slide-to="51"></li>
                    <li data-target="#gallery" data-slide-to="52"></li>
                    <li data-target="#gallery" data-slide-to="53"></li>
                    <li data-target="#gallery" data-slide-to="54"></li>
                    <li data-target="#gallery" data-slide-to="55"></li>
                    <li data-target="#gallery" data-slide-to="56"></li>
                    <li data-target="#gallery" data-slide-to="57"></li>
                    <li data-target="#gallery" data-slide-to="58"></li>
                    <li data-target="#gallery" data-slide-to="59"></li>
                    <li data-target="#gallery" data-slide-to="60"></li>
                    <li data-target="#gallery" data-slide-to="61"></li>
                    <li data-target="#gallery" data-slide-to="62"></li>
                    <li data-target="#gallery" data-slide-to="63"></li>
                    <li data-target="#gallery" data-slide-to="64"></li>
                    <li data-target="#gallery" data-slide-to="65"></li>
                    <li data-target="#gallery" data-slide-to="66"></li>
                    <li data-target="#gallery" data-slide-to="67"></li>
                    <li data-target="#gallery" data-slide-to="68"></li>
                    <li data-target="#gallery" data-slide-to="69"></li>
                </ol>
                <div class="carousel-inner mx-auto w-90" role="listbox" data-toggle="modal" data-target="#lightbox">
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/1.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/2.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/3.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/4.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/5.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/6.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/7.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/8.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/9.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/10.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/11.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/12.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/13.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/14.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/15.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/16.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/17.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/18.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/19.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/20.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/21.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/22.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/23.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/24.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/25.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/26.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/27.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/28.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/29.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/30.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/31.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/32.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/33.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/34.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/35.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/36.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/37.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/38.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/39.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/40.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/41.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/42.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/43.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/44.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/45.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/46.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/47.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/48.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/49.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/50.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/51.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/52.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/53.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/54.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/55.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/56.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/57.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/58.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/59.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/60.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/61.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/63.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/64.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/65.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/66.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/67.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/68.png" class="img-fluid">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="col-md-2">
                            <img src="images/clients_slider/69.png" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="w-100">
                    <a class="carousel-control-prev w-auto" href="#gallery" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next w-auto" href="#gallery" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid main_section footer-top">
    <div class="px-5">
        <div class="row justify-content-between">
            <div class="col-md-3">
                <h5>SHOP B2B </h5>
                <a class="color_FFF" href="client_dashboard_new.php?show=inventory">Used Gaylord Totes</a>
            </div>
            <div class="col-md-3">
                <h5>CONTACT</h5>
                <p>Account Manager: Zac Fratkin</p>
                <p>Phone: 1-323-724-2500 x701 </p>
                <p>Email: <a class="color_173D6E" href="mailto:Sales@UsedCardboardBoxes.com">Sales@UsedCardboardBoxes.com</a></p>
                <h5 class="mt-2">UCB Environment Companies</h5>
                <ul>
                    <li><a class="color_FFF" href="https://www.usedcardboardboxes.com/">- UsedCardboardBoxes.com</a></li>
                    <li><a class="color_FFF" href="https://www.ucbzerowaste.com/">- UCBZeroWaste</a></li>
                    <li><a class="color_FFF" href="https://www.ucbpalletsolutions.com/">- UCBPalletSolutions</a></li>
                    <li><a class="color_FFF" href="https://organicrecyclersofamerica.com/">- UCBOrganicSolutions</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery('#gallery').carousel({
        interval: 4000
    })

    // Modify each slide to contain five columns of images
    jQuery('#gallery.carousel .carousel-item').each(function() {
        var minPerSlide = 4;
        var next = jQuery(this).next();
        if (!next.length) {
            next = jQuery(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo(jQuery(this));

        for (var i = 0; i < minPerSlide; i++) {
            next = next.next();
            if (!next.length) {
                next = jQuery(this).siblings(':first');
            }

            next.children(':first-child').clone().appendTo(jQuery(this));
        }
    });

    // Initialize carousel
    jQuery(".carousel-item:first-of-type").addClass("active");
    jQuery(".carousel-indicators:first-child").addClass("active");
</script>
<?php require_once('boomerange_common_footer.php'); ?>