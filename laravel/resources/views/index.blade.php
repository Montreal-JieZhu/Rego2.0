@extends('master')
@section('title','Welcome to Rego')
@section('head')
@parent
@endsection
@section('header')
<!--
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-G06bX784j_fV2pvBc4XJ0b0CsHSDYhE&callback=initMap&language=en">
</script>
-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-G06bX784j_fV2pvBc4XJ0b0CsHSDYhE&callback=initMap&language=en">
</script>
<script>
    var results;
    var country = null;
    var city = null;
    var province = null;
    var productItem = [];
    $.ajaxSetup({
        async: false
    });
    function getLocation() {
        //alert(0);
        if (navigator.geolocation) {
            //alert(2);
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        //alert(5);
        var geocoder = new google.maps.Geocoder;
        var latlng = {lat: parseFloat(position.coords.latitude), lng: parseFloat(position.coords.longitude)};
        geocoder.geocode({'location': latlng}, function (results, status) {
            //alert(6);
            if (status === 'OK') {
                var len = results.length;
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        var typeLen = results[i].types.length;
                        for (var j = 0; j < typeLen; j++) {
                            if ((results[i].types[j].indexOf("administrative_area_level") > -1) || results[i].types[j].indexOf("country") > -1) {
                                var addressCom = results[i].address_components;
                                var addressLen = addressCom.length;
                                for (var ii = 0; ii < addressLen; ii++) {
                                    var addressType = addressCom[ii].types;
                                    var addressTypeLen = addressType.length;
                                    for (var jj = 0; jj < addressTypeLen; jj++) {
                                        if (addressType[jj].indexOf("country") > -1) {
                                            country = addressCom[ii].long_name;
                                            //alert(country);
                                        }
                                        if (addressType[jj].indexOf("administrative_area_level_2") > -1) {
                                            city = addressCom[ii].long_name;
                                        }
                                        if (addressType[jj].indexOf("administrative_area_level_1") > -1) {
                                            province = addressCom[ii].long_name;
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    window.alert('No results found');
                }
                createBreadcrumb();
                loadCountriesList();
                loadTopRestaurant(country, province, city);
                loadAllRestaurant();
            } else {
                window.alert('Geocoder failed due to: ' + status);
            }
        });
    }
    function createBreadcrumb() {
        $('#locationBreadcrumb').empty();
        //alert(3);
        var emptyLi;
        //alert(country);
        if (country !== null) {
            //alert(555);
            emptyLi = $('#breadcrumbLi').clone().removeAttr("id");
            emptyLi.find('a').text(country);
            emptyLi.appendTo('#locationBreadcrumb');
            //alert(country);
            $("<option></option>").text(country).appendTo('#countryList');
            setCookie('rego_country', country, 1);
        }
        if (province !== null) {
            emptyLi = $('#breadcrumbLi').clone().removeAttr("id");
            emptyLi.find('a').text(province);
            emptyLi.appendTo('#locationBreadcrumb');
            $("<option></option>").text(province).appendTo('#provinceList');
            setCookie('rego_province', province, 1);
        }
        if (city !== null) {
            emptyLi = $('#activeLi').clone().removeAttr("id");
            emptyLi.text(city);
            emptyLi.appendTo('#locationBreadcrumb');
            setCookie('rego_city', city, 1);
        }
    }
    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                x.innerHTML = "User denied the request for Geolocation."
                break;
            case error.POSITION_UNAVAILABLE:
                x.innerHTML = "Location information is unavailable."
                break;
            case error.TIMEOUT:
                x.innerHTML = "The request to get user location timed out."
                break;
            case error.UNKNOWN_ERROR:
                x.innerHTML = "An unknown error occurred."
                break;
        }
    }
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toGMTString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    function getRestaurants(type, region) {
        var productQuery = new Object();
        productQuery.option = type;
        productQuery.name = region;
        var topProductRequest = '/RestaurantRESTAPI?' + $.param(productQuery);
        var dataReturn;
        $.get(topProductRequest, function (data, status) {
            //alert("Data: " + data.regionRestaurant.length + "\nStatus: " + status);
            if (status === 'success') {
                //alert(3);
                dataReturn = data;
            }
        });
        return dataReturn;
    }
    function appendRestaurantsToTop(recordNum, data) {
        var cloneLiElement = $('#liElementClone').clone().removeAttr('class', 'id');
        var cloneItemElement = $('#myCarouselItemsClone').clone().removeAttr('id class').empty();
        var cloneProductListElement = $('#myCarouselListClone').clone().removeAttr('id').empty();
        var cloneProductItemElement = $('#productItemClone').clone().removeAttr('id');
        var itemNum = Math.ceil(recordNum / 3);
        for (var i = 0; i < itemNum; i++) {
            if (i === 0) {
                cloneLiElement.clone().attr({'data-slide-to': i, 'class': 'active'}).appendTo('#myCarouselIndicators');
                var item = cloneItemElement.clone().attr('class', "item active");
                var productList = cloneProductListElement.clone().appendTo(item);
                for (var j = i * 3; j < (i + 1) * 3 && j < recordNum; j++) {
                    var record = data[j];
                    var stars = record.star;
                    var product = cloneProductItemElement.clone();
                    var imgPath = "assets5/img/" + record.logo;
                    product.find('img:first').attr('src', imgPath);
                    product.find('h2:first').children('a:first').text(record.name);
                    product.find('a.small-text:first').text(record.reviewNum + " reviews");
                    product.find('p.product-description:first').text(record.description);
                    var starContainer = product.find('div.product-rating:first');
                    for (var s = 0; s < 5 - stars; s++) {
                        starContainer.children()[0].remove();
                    }
                    product.appendTo(productList);
                }
                item.appendTo('#myCarouselInner');
            } else {
                cloneLiElement.clone().attr('data-slide-to', i).appendTo('#myCarouselIndicators');
                var item = cloneItemElement.clone().attr('class', "item");
                var productList = cloneProductListElement.clone().appendTo(item);
                for (var j = i * 3; j < (i + 1) * 3 && j < recordNum; j++) {
                    var record = data[j];
                    var stars = record.star;
                    var product = cloneProductItemElement.clone();
                    var imgPath = "assets5/img/" + record.logo;
                    product.find('img:first').attr('src', imgPath);
                    product.find('h2:first').children('a:first').text(record.name);
                    product.find('a.small-text:first').text(record.reviewNum + " reviews");
                    product.find('p.product-description:first').text(record.description);
                    var starContainer = product.find('div.product-rating:first');
                    for (var s = 0; s < 5 - stars; s++) {
                        starContainer.children()[0].remove();
                    }
                    product.appendTo(productList);
                }
                item.appendTo('#myCarouselInner');
            }
        }
    }
    function loadTopRestaurant(pCountry, pProvince, pCity) {
        var data = getRestaurants(1, pCity);
        //loadAllRestaurant(data);
        var recordNum = data.length;
        if (recordNum === 0) {
            data = getRestaurants(2, pProvince);
            recordNum = data.length;
            if (recordNum === 0) {
                data = getRestaurants(3, pCountry);
                recordNum = data.length;
                if (recordNum === 0) {
                    data = getRestaurants(4, '');
                    recordNum = data.length;
                    if (recordNum !== 0) {
                        $('#topTitle').text('Top Cuisines in the world');
                    }
                } else {
                    $('#topTitle').text('Top Cuisines in ' + pCountry);
                }
            } else {
                $('#topTitle').text('Top Cuisines in ' + pProvince);
            }
        } else {
            $('#topTitle').text('Top Cuisines in ' + pCity);
        }

        if (recordNum === 0) {
            $('#topTitle').text('Sorry! There is no any available restaurant!');
        } else {
            appendRestaurantsToTop(recordNum, data);
        }
    }

    function loadAllRestaurant() {
        var data = getRestaurants(4, '');
        var recordNum = data.length;
        var itemNum = Math.ceil(recordNum / 3);
        for (var i = 0; i < recordNum; i++) {
            var product = data[i];
            var starnum = product.star;
            var starContent = '';
            for (var j = 0; j < starnum; j++) {
                starContent += '<i class="fa fa-star"></i>';
            }
            //alert(i);
            productItem[i] = ('<div class="col-md-4 col-sm-6 product-item">' +
                    '<div class="product-container">' +
                    '<div class="row">' +
                    '<div class="col-md-12">' +
                    '<a href="#" class="product-image">' +
                    '<img src="' + 'assets5/img/' + product.logo + '">' +
                    '</a>' +
                    '</div>' +
                    '</div>' +
                    '<div class="row">' +
                    '<div class="col-xs-8">' +
                    '<h2>' +
                    '<a href="#">' +
                    product.name +
                    '</a>' +
                    '</h2>' +
                    '</div>' +
                    '</div>' +
                    '<div class="product-rating">' +
                    starContent +
                    '<a href="#" class="small-text">' +
                    product.reviewNum + ' reviews' +
                    '</a>' +
                    '</div>' +
                    '<div class="row">' +
                    '<div class="col-xs-12">' +
                    '<p class="product-description">' +
                    product.description +
                    '</p>' +
                    '<div class="row">' +
                    '<div class="col-xs-6">' +
                    '<button class="btn btn-default" type="button">' +
                    'Reserve Now!' +
                    '</button>' +
                    '</div>' +
                    '<div class="col-xs-6">' +
                    '<p class="product-price">' +
                    '$599.00' +
                    '</p>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>');
        }

        window.pagObj = $('#pagination').twbsPagination({
            totalPages: itemNum,
            visiblePages: 3,
            onPageClick: function (event, page) {
                console.info(page + ' (from options)');
                var showContent = '';
                for (var ii = (page - 1) * 3; ii < page * 3 && ii < recordNum; ii++) {
                    //alert(productItem[ii]);
                    showContent += productItem[ii];
                }
                //alert(showContent);
                $('#allRestaurantList').html(showContent);
            }
        }).on('page', function (event, page) {
            console.info(page + ' (from event listening)');
        });
    }
    //getLocation();
</script>
@parent
@endsection
@section('content')
<div class="container container-fluid">
    <form class="form-inline right">
        <div class="input-group ">
            <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
            <select class="form-control" id="countryList" name="countryList" style="width: 150px">
                <!--<option><input class="form-control" id="myCountryInput" type="text" placeholder="Search.."></option>-->
            </select>
            <span class="input-group-addon"><i class=" glyphicon glyphicon-map-marker"></i></span>
            <select class="form-control" id="provinceList" name="provinceList" style="width: 150px">
            </select>
            <span class="input-group-addon">City</span>
            <select class="form-control" id="cityList" name="cityList" style="width: 150px">
            </select>
            <div class="input-group-btn">
                <button class="btn btn-default" id='searchRegionBtn' type="button"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
    </form>

    <ul class="breadcrumb" id="locationBreadcrumb" style="height:30px">
    </ul>

    <ul hidden="true">
        <li id="breadcrumbLi"><a href="#"></a></li>
        <li id="activeLi" class="active"></li>
    </ul>
    <div class="row">
        <div class="col-md-11"><h2 id="topTitle">Top Cuisines</h2></div>
        <div class="col-md-1">
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <!--<span class="sr-only">Previous</span>-->
            </a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <!--<span class="sr-only">Next</span>-->
            </a>
        </div>
    </div>
    <!--
        <div id="myCarousel" class="carousel slide" data-ride="carousel">

            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
            </ol>


            <div class="carousel-inner">

                <div class="item active">
                    <div class="row product-list">
                        <div class="col-md-4 col-sm-6 product-item">
                            <div class="product-container">
                                <div class="row">
                                    <div class="col-md-12"><a href="#" class="product-image"><img src="assets5/img/iphone6.jpg"></a></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <h2><a href="#">iPhone 6s</a></h2>
                                    </div>
                                </div>
                                <div class="product-rating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half"></i><a href="#" class="small-text">82 reviews</a></div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <p class="product-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ornare sem sed nisl dignissim, facilisis dapibus lacus vulputate. Sed lacinia lacinia magna. </p>
                                        <div class="row">
                                            <div class="col-xs-6"></div>
                                            <div class="col-xs-6">
                                                <div class="col-xs-6"><button class="btn btn-default" type="button">Reserve Now!</button></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 product-item">
                            <div class="product-container">
                                <div class="row">
                                    <div class="col-md-12"><a href="#" class="product-image"><img src="assets5/img/iphone6.jpg"></a></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <h2><a href="#">iPhone 6s</a></h2>
                                    </div>

                                </div>
                                <div class="product-rating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half"></i><a href="#" class="small-text">82 reviews</a></div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <p class="product-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ornare sem sed nisl dignissim, facilisis dapibus lacus vulputate. Sed lacinia lacinia magna. </p>
                                        <div class="row">
                                            <div class="col-xs-6"><button class="btn btn-default" type="button">Buy Now!</button></div>
                                            <div class="col-xs-6">
                                                <p class="product-price">$599.00 </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 product-item">
                            <div class="product-container">
                                <div class="row">
                                    <div class="col-md-12"><a href="#" class="product-image"><img src="assets5/img/iphone6.jpg"></a></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <h2><a href="#">iPhone 6s</a></h2>
                                    </div>

                                </div>
                                <div class="product-rating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half"></i><a href="#" class="small-text">82 reviews</a></div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <p class="product-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ornare sem sed nisl dignissim, facilisis dapibus lacus vulputate. Sed lacinia lacinia magna. </p>
                                        <div class="row">
                                            <div class="col-xs-6"><button class="btn btn-default" type="button">Buy Now!</button></div>
                                            <div class="col-xs-6">
                                                <p class="product-price">$599.00 </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <img src="assets5/img/2.jpg" alt="Chicago" style="width:100%;height:400px">
                    <div class="carousel-caption">
                        <h3>Chicago</h3>
                        <p>Thank you, Chicago!</p>
                    </div>
                </div>
                <div class="item">
                    <img src="assets5/img/6.jpg" alt="New York" style="width:100%;height:400px">
                    <div class="carousel-caption">
                        <h3>New York</h3>
                        <p>We love the Big Apple!</p>
                    </div>
                </div>
            </div>
        </div>
    -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol id='myCarouselIndicators' class="carousel-indicators">
        </ol>
        <!-- Wrapper for slides -->
        <div id='myCarouselInner' class="carousel-inner">
        </div>
    </div>
    <div id="myCarouselClone" class="carousel slide" data-ride="carousel" hidden="true">
        <!-- Indicators -->
        <ol id='myCarouselIndicatorsClone' class="carousel-indicators">
            <li id='liElementClone' data-target="#myCarousel" data-slide-to="0" class="active"></li>
        </ol>
        <!-- Wrapper for slides -->
        <div id='myCarouselInnerClone' class="carousel-inner">
            <div id="myCarouselItemsClone" class="item active">
                <div id='myCarouselListClone' class="row product-list">
                    <div id='productItemClone' class="col-md-4 col-sm-6 product-item">
                        <div class="product-container">
                            <div class="row">
                                <div class="col-md-12"><a href="#" class="product-image"><img src="assets5/img/iphone6.jpg"></a></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-8">
                                    <h2><a href="#">iPhone 6s</a></h2>
                                </div>
                            </div>
                            <div class="product-rating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half"></i><a href="#" class="small-text">82 reviews</a></div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <p class="product-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ornare sem sed nisl dignissim, facilisis dapibus lacus vulputate. Sed lacinia lacinia magna. </p>
                                    <div class="row">
                                        <div class="col-xs-6"></div>
                                        <div class="col-xs-6">
                                            <div class="col-xs-6"><button class="btn btn-default" type="button">Reserve Now!</button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="projects-horizontal container-fluid">
    <div class="container">
        <div class="intro">
            <h2 class="text-center">
                Projects
            </h2>
            <p class="text-center">
                Nunc luctus in metus eget fringilla. Aliquam sed justo ligula. Vestibulum nibh erat, pellentesque ut laoreet vitae.
            </p>
        </div>
        <div class="container">
            <div id='allRestaurantList' class="row product-list"></div>
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <nav aria-label="Page navigation">
                    <ul class="pagination right" id="pagination"></ul>
                </nav>
            </div>
        </div>
        <!--<div class="row product-list" hidden='true'>
            <div class="col-md-4 col-sm-6 product-item">
                <div class="product-container">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="#" class="product-image">
                                <img src="assets5/img/iphone6.jpg">
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <h2>
                                <a href="#">
                                    iPhone 6s
                                </a>
                            </h2>
                        </div>
                    </div>
                    <div class="product-rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star-half"></i>
                        <a href="#" class="small-text">
                            82 reviews
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <p class="product-description">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ornare sem sed nisl dignissim, facilisis dapibus lacus vulputate. Sed lacinia lacinia magna.
                            </p>
                            <div class="row">
                                <div class="col-xs-6">
                                    <button class="btn btn-default" type="button">
                                        Buy Now!
                                    </button>
                                </div>
                                <div class="col-xs-6">
                                    <p class="product-price">
                                        $599.00
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 product-item">
                <div class="product-container">
                    <div class="row">
                        <div class="col-md-12"><a href="#" class="product-image"><img src="assets5/img/iphone6.jpg"></a></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <h2><a href="#">iPhone 6s</a></h2>
                        </div>

                    </div>
                    <div class="product-rating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half"></i><a href="#" class="small-text">82 reviews</a></div>
                    <div class="row">
                        <div class="col-xs-12">
                            <p class="product-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ornare sem sed nisl dignissim, facilisis dapibus lacus vulputate. Sed lacinia lacinia magna. </p>
                            <div class="row">
                                <div class="col-xs-6"><button class="btn btn-default" type="button">Buy Now!</button></div>
                                <div class="col-xs-6">
                                    <p class="product-price">$599.00 </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 product-item">
                <div class="product-container">
                    <div class="row">
                        <div class="col-md-12"><a href="#" class="product-image"><img src="assets5/img/iphone6.jpg"></a></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <h2><a href="#">iPhone 6s</a></h2>
                        </div>

                    </div>
                    <div class="product-rating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half"></i><a href="#" class="small-text">82 reviews</a></div>
                    <div class="row">
                        <div class="col-xs-12">
                            <p class="product-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam ornare sem sed nisl dignissim, facilisis dapibus lacus vulputate. Sed lacinia lacinia magna. </p>
                            <div class="row">
                                <div class="col-xs-6"><button class="btn btn-default" type="button">Buy Now!</button></div>
                                <div class="col-xs-6">
                                    <p class="product-price">$599.00 </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
</div>
<!--
<div class="container">
    <div id="page-content"></div>
    <div class="col-md-7"></div>
    <div class="col-md-5">
        <nav aria-label="Page navigation">
            <ul class="pagination right" id="pagination"></ul>
        </nav>
    </div>
</div>
--><br><br>
@endsection
@section('footer')
@parent
<button onclick="loadTopRestaurant()">Try It</button>
<p id="demo"></p>
<script>
    /*
     $(function () {
     window.pagObj = $('#pagination').twbsPagination({
     totalPages: 35,
     visiblePages: 5,
     onPageClick: function (event, page) {
     console.info(page + ' (from options)');
     $('#page-content').text('Page ' + page);
     }
     }).on('page', function (event, page) {
     console.info(page + ' (from event listening)');
     });
     });
     */
</script>
<script>
    var x = document.getElementById("demo");
    $(document).ready(function () {
        getLocation();
        //alert(1);
        //createBreadcrumb();
        //LoadCountriesList();
        //this part can help user to filter country name in countrylist
        $("#myCountryInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#countryList option").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
        //This part is for refresh provincelist everytime we change the contry list
        $("#countryList").change(function () {
            var countryId = $(this).val();
            //alert($(this).text());
            var selectedOption = '[value=' + countryId + ']';
            var countryName = $(this).find(selectedOption).text();
            $('#locationBreadcrumb').empty();
            var emptyLi = $('#breadcrumbLi').clone().removeAttr("id");
            emptyLi.find('a').text(countryName);
            emptyLi.appendTo('#locationBreadcrumb');

            loadProvincesList(countryId);

            setCookie('rego_country', countryName, 1);
        });
        $("#provinceList").change(function () {
            var regionId = $(this).val();
            var countryId = $('#countryList').val();
            var selectedOption = '[value=\'' + regionId + '\']';
            var provinceName = $(this).find(selectedOption).text();
            var emptyLi = $('#breadcrumbLi').clone().removeAttr("id");
            emptyLi.find('a').text(provinceName);
            while ($('#locationBreadcrumb').children().length > 1) {
                $("#locationBreadcrumb li:eq(1)").remove();
            }
            $("#locationBreadcrumb li:eq(0)").after(emptyLi);

            loadCitiesList(countryId, regionId);

            setCookie('rego_province', provinceName, 1);
        });

        $("#cityList").change(function () {
            var cityId = $(this).val();
            var selectedOption = '[value=\'' + cityId + '\']';
            var cityName = $(this).find(selectedOption).text();
            var emptyLi = $('#breadcrumbLi').clone().removeAttr("id");
            emptyLi.find('a').text(cityName);
            while ($('#locationBreadcrumb').children().length > 2) {
                $("#locationBreadcrumb li:eq(2)").remove();
            }
            $("#locationBreadcrumb li:eq(1)").after(emptyLi);
            setCookie('rego_city', cityName, 1);
        });

        $('#searchRegionBtn').click(function () {
            var countryCode = $('#countryList').val();
            var provinceCode = $('#provinceList').val();
            var cityName = $('#cityName').val();
            var countrySelector = '[value=\'' + countryCode + '\']';
            var countryName = $('#countryList').find(countrySelector).text();
            var provinceSelector = '[value=\'' + provinceCode + '\']';
            var provinceName = $('#provinceList').find(provinceSelector).text();
            alert(countryName);
            alert(provinceName);
            //loadTopRestaurant(pCountry, pProvince, pCity);
        });
    });
    function loadCitiesList(countryId, regionId) {
        //alert(currentCountryInList);
        $('#cityList').empty();
        var cityQuery = new Object();
        cityQuery.option = 3;
        cityQuery.countryId = countryId;
        cityQuery.regionId = regionId;
        var cityRequest = '/LocationRESTAPI?' + $.param(cityQuery);
        //alert(provinceRequest);
        $.get(cityRequest, function (data, status) {
            //alert("Data: " + data + "\nStatus: " + status);
            if (status === 'success') {
                //$("#demo").html(data);
                var len = data.length;
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        //$("<option></option>").text(data[i]['name']).val(data[i]['code']).appendTo('#provinceList');
                        if (city === data[i]['name']) {
                            $("<option></option>").text(data[i]['name']).val(data[i]['id']).attr('selected', 'true').appendTo('#cityList');
                        } else {
                            $("<option></option>").text(data[i]['name']).val(data[i]['id']).appendTo('#cityList');
                        }
                    }
                }
            }
        });
    }
    function loadProvincesList(countryId) {
        //alert(currentCountryInList);
        $('#provinceList').empty();
        var provinceQuery = new Object();
        provinceQuery.option = 2;
        provinceQuery.countryId = countryId;
        var provinceRequest = '/LocationRESTAPI?' + $.param(provinceQuery);
        //alert(provinceRequest);
        $.get(provinceRequest, function (data, status) {
            //alert("Data: " + data + "\nStatus: " + status);
            if (status === 'success') {
                //$("#demo").html(data);
                var len = data.length;
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        //$("<option></option>").text(data[i]['name']).val(data[i]['code']).appendTo('#provinceList');
                        if (province === data[i]['name']) {
                            $("<option></option>").text(data[i]['name']).val(data[i]['id']).attr('selected', 'true').appendTo('#provinceList');
                            //alert(data[i]['id']);
                            loadCitiesList(countryId, data[i]['id']);
                        } else {
                            $("<option></option>").text(data[i]['name']).val(data[i]['id']).appendTo('#provinceList');
                        }
                    }
                }
            }
        });
    }
    function loadCountriesList() {
        var countryQuery = new Object();
        countryQuery.option = 1;
        var countryRequest = '/LocationRESTAPI?' + $.param(countryQuery);
        $.get(countryRequest, function (data, status) {
            //alert("Data: " + data + "\nStatus: " + status);
            if (status === 'success') {
                //$("#demo").html(data);
                var len = data.length;
                if (len > 0) {
                    $('#countryList').empty();
                    for (var i = 0; i < len; i++) {
                        if (country === data[i]['name']) {
                            //alert(data[i]['name']);
                            $("<option></option>").text(data[i]['name']).val(data[i]['id']).attr('selected', 'true').appendTo('#countryList');
                            //alert(data[i]['id']);
                            loadProvincesList(data[i]['id']);
                        } else {
                            $("<option></option>").text(data[i]['name']).val(data[i]['id']).appendTo('#countryList');
                        }
                    }
                }
            }
        });
        /*
         $.ajax({
         url: countryRequest,
         type: 'GET',
         error: function (xhr) {
         alert("An error occured: " + xhr.status + " " + xhr.statusText);
         },
         success: function (result) {
         $("demo").html(result);
         }
         });
         */
    }

</script>

@endsection