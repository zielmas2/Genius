let parameters = {};
var options = [];
var airlines = [];
var airlinesSelected = [];

tjq(document).ready(function () {
    var link = decodeURI(window.location.href);
    var hashes = link.slice(link.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        var hash = hashes[i].split('=');
        parameters[hash[0]] = hash[1];
    }
    parameters['dateFormat'] = 1;

    tjq(".loader").css({ display: "block" });
    var flightRequest = tjq.ajax({
        url: "/ajx-flight-results",
        data: parameters,
        method: "POST"
    });

    flightRequest.done(function (results_) {

        tjq(".loader").css({ display: "none" });
        if (!results_.status) {

            //alert(result_.Hata);
            Swal.fire({
                type: 'error',
                html: results_.message
            });

            return;
        }

        tjq('#flight_results').html('');
        tjq('#result_count').text(results_.results.flights.length);

        tjq(results_.results.flights).each(function (i_r, result_) {
            let fr_html = '';
            if (result_.hasToWay==true) { alert('zz1'); }
            fr_html = '<article class="box price_ price_1 airline_ airline_'+result_.airlineCode+'">\n' +
                '                                <figure class="col-xs-3 col-sm-2">\n' +
                '                                    <span><img alt="" src="http://placehold.it/270x160"><div style="position: absolute; font-size: 0.75em; top: 45%; left: 13%;">'+result_.airline+'</div> </span>\n' +
                '                                </figure>\n' +
                '                                <div class="details col-xs-9 col-sm-10">\n' +
                '                                    <div class="details-wrapper">\n' +
                '                                        <div class="first-row">\n' +
                '                                            <div>\n' +
                '                                                <h4 class="box-title">(' + result_.fromWhere + ') to (' + result_.toWhere + ')<small>' + (!result_.hasTwoWay ? 'Oneway flight' : 'Returning flight') + '</small></h4>\n' +
                '                                                <a class="button btn-mini stop">'+(result_.hasTransfer?('1'+' STOP'):'DIRECT')+'</a>\n' +
                /*'                                                <div class="amenities">\n' +
                '                                                    <i class="soap-icon-wifi circle"></i>\n' +
                '                                                    <i class="soap-icon-entertainment circle"></i>\n' +
                '                                                    <i class="soap-icon-fork circle"></i>\n' +
                '                                                    <i class="soap-icon-suitcase circle"></i>\n' +
                '                                                </div>\n' +*/
                '                                            </div>\n' +
                '                                            <div>\n' +
                '                                                <span class="price"><small>PRICE</small>' + result_.totalPriceDisp + '</span>\n' +
                '                                            </div>\n' +
                '                                        </div>\n' +
                '                                        <div class="second-row">\n' +
                '                                            <div class="time">\n' +
                '                                                <div class="take-off col-sm-4">\n' +
                '                                                    <div class="icon"><i class="soap-icon-plane-right yellow-color"></i></div>\n' +
                '                                                    <div>\n' +
                '                                                        <span class="skin-color">Take off</span><br />'+result_.departingDateDisp+'\n' +
                '                                                    </div>\n' +
                '                                                </div>\n' +
                '                                                <div class="landing col-sm-4">\n' +
                '                                                    <div class="icon"><i class="soap-icon-plane-right yellow-color"></i></div>\n' +
                '                                                    <div>\n' +
                '                                                        <span class="skin-color">landing</span><br />'+result_.arrivingDateDisp+'\n' +
                '                                                    </div>\n' +
                '                                                </div>\n' +
                '                                                <div class="total-time col-sm-4">\n' +
                '                                                    <div class="icon"><i class="soap-icon-clock yellow-color"></i></div>\n' +
                '                                                    <div>\n' +
                '                                                        <span class="skin-color">total time</span><br />'+result_.flightTimeDisp+'\n' +
                '                                                    </div>\n' +
                '                                                </div>\n' +
                '                                            </div>\n' +
                '                                            <div class="action">\n' +
                '                                                <a href="javascript:;" class="button btn-small full-width" onclick="flight_detail('+i_r+')">SELECT</a>\n' +
                '                                            </div>\n' +
                '                                        </div>\n';
                if (result_.returnFlight) {
                    let return_fl = result_.returnFlight;
                    fr_html += '                                        <div style="position: absolute; left: 40%; color: red; font-size: large;"><i class="fa fa-arrow-down"></i> </div>\n' +
                        '                                        <div class="third-row">\n' +
                        '                                            <div class="time" style="padding-top: 30px;">\n' +
                        '                                                <div class="take-off col-sm-4">\n' +
                        '                                                    <div class="icon"><i class="soap-icon-plane-right yellow-color"></i></div>\n' +
                        '                                                    <div>\n' +
                        '                                                        <span class="skin-color">Take off</span><br />' + return_fl.departingDateDisp + '\n' +
                        '                                                    </div>\n' +
                        '                                                </div>\n' +
                        '                                                <div class="landing col-sm-4">\n' +
                        '                                                    <div class="icon"><i class="soap-icon-plane-right yellow-color"></i></div>\n' +
                        '                                                    <div>\n' +
                        '                                                        <span class="skin-color">landing</span><br />' + return_fl.arrivingDateDisp + '\n' +
                        '                                                    </div>\n' +
                        '                                                </div>\n' +
                        '                                                <div class="total-time col-sm-4">\n' +
                        '                                                    <div class="icon"><i class="soap-icon-clock yellow-color"></i></div>\n' +
                        '                                                    <div>\n' +
                        '                                                        <span class="skin-color">total time</span><br />' + return_fl.flightTimeDisp + '\n' +
                        '                                                    </div>\n' +
                        '                                                </div>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="action">\n' +
                        '                                                &nbsp;\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n';
                }
                fr_html += '                                    </div>\n' +
                '                                </div>\n' +
                '                                <input type="hidden" name="carrier" value="'+result_.carrier+'"><input type="hidden" name="flight_number" value="'+result_.flightNumber+'"><input type="hidden" name="flight_id" value="'+result_.flightID+'">\n' +
                '                            </article>' +
                '                            <div style="margin-top: 0px; border: 1px dashed; display:none; background: background: #f5f5f5;" id="flight_detail_'+i_r+'" class="flight_detail_">' +
                '                                 <div class="intro table-wrapper full-width hidden-table-sm box" style="padding: 10px;">\n' +
                '                                        <div class="col-md-4 table-cell travelo-box">\n' +
                '                                            <dl class="term-description">\n' +
                '                                                <dt>Airline:</dt><dd>'+result_.airline+'</dd>\n' +
                '                                                <dt>Flight Type:</dt><dd>'+result_.flightType+'</dd>\n' +
                '                                                <dt>Seats &amp; Baggage:</dt><dd>'+result_.freeBag+'</dd>\n' +
                '                                                <dt>Price:</dt><dd>'+result_.priceADT+' '+result_.currency+'</dd>\n';
                if (result_.priceKid>0) { fr_html += '                                                <dt>Price Kid:</dt><dd>'+result_.priceKid+' '+result_.currency+'</dd>\n'; }
                if (result_.priceInf>0) { fr_html += '                                                <dt>Price Baby:</dt><dd>'+result_.priceInf+' '+result_.currency+'</dd>\n'; }
                fr_html += '                                                <dt>Taxes &amp; Fees:</dt><dd>'+result_.tax+'</dd>\n' +
                '                                                <dt>total price:</dt><dd>'+result_.totalPriceDisp+'</dd>\n' +
                '                                            </dl>\n' +
                '                                        </div>\n' +
                '                                        <div class="col-md-8 table-cell">\n' +
                '                                            <div class="detailed-features booking-details">\n' +
                '                                                <div class="travelo-box">\n' +
                '                                                    <a href="javascript:;" onclick="send_to_sale('+results_.results.searchProcessId+', \''+result_.flightNumber+'\', \''+result_.flightID+'\', \''+result_.fromWhere+'\', \''+result_.toWhere+'\', \''+result_.departingDate+'\', \''+(result_.hasTwoWay?result_.returnFlight.departingDate:'')+'\', \''+result_.adt+'\', \''+result_.kid+'\', \''+result_.inf+'\', \''+result_.price+'\');" class="button btn-large yellow pull-right">CHECKOUT</a>\n' +
                '                                                    <h4 class="box-title">(' + result_.fromWhere + ') to (' + result_.toWhere + ')<small>' + (!result_.isHasTwoWay ? 'Oneway flight' : '') + '</small></h4>\n' +
                '                                                </div>\n' +
                '                                                <div class="table-wrapper flights">\n' +
                '                                                    <div class="table-row first-flight">\n' +
                '                                                        <div class="table-cell timing-detail">\n' +
                '                                                            <div class="timing">\n' +
                '                                                                <div class="check-in" style="display: inline-block;">\n' +
                '                                                                    <label>Take off</label>\n' +
                '                                                                    <span>'+result_.departingDateDisp+'</span>\n' +
                '                                                                </div>\n' +
                '                                                                <div class="duration text-center" style="display: inline-block;">\n' +
                '                                                                    <i class="soap-icon-clock"></i>\n' +
                '                                                                    <span>'+result_.flightTimeShort+'</span>\n' +
                '                                                                </div>\n' +
                '                                                                <div class="check-out" style="display: inline-block;">\n' +
                '                                                                    <label>landing</label>\n' +
                '                                                                    <span>'+result_.arrivingDateDisp+'</span>\n' +
                '                                                                </div>\n' +
                '                                                            </div>\n' +
                '                                                        </div>\n' +
                '                                                    </div>\n';
            if (result_.returnFlight) {
                let return_fl = result_.returnFlight;
                    fr_html += '                                             <div class="table-row second-flight">\n' +
                        '                                                        <div class="table-cell logo" style="display: none;">\n' +
                        '                                                            <img src="http://placehold.it/140x30" alt="">\n' +
                        '                                                            <label>AI-635 Economy</label>\n' +
                        '                                                        </div>\n' +
                        '                                                        <div class="table-cell timing-detail">\n' +
                        '                                                            <div class="timing">\n' +
                        '                                                                <div class="check-in" style="display: inline-block;">\n' +
                        '                                                                    <label>Take off</label>\n' +
                        '                                                                    <span>'+return_fl.departingDateDisp+'</span>\n' +
                        '                                                                </div>\n' +
                        '                                                                <div class="duration text-center" style="display: inline-block;">\n' +
                        '                                                                    <i class="soap-icon-clock"></i>\n' +
                        '                                                                    <span>'+return_fl.flightTimeShort+'</span>\n' +
                        '                                                                </div>\n' +
                        '                                                                <div class="check-out" style="display: inline-block;">\n' +
                        '                                                                    <label>landing</label>\n' +
                        '                                                                    <span>'+return_fl.arrivingDateDisp+'</span>\n' +
                        '                                                                </div>\n' +
                        '                                                            </div>\n' +
                        '                                                        </div>\n' +
                        '                                                    </div>\n';
                }
                fr_html += '                                                </div>\n' +
                '                                            </div>\n' +
                '                                        </div>\n' +
                '                                    </div>' +
                '                            </div>' +
                '                            <div style="margin-bottom: 30px"></div>';
            tjq('#flight_results').append(fr_html);

            if(jQuery.inArray(result_.airlineCode, airlines) != -1) {
                console.log("is in array");
            } else {
                airlines.push(result_.airlineCode);
                tjq(".airlines_ait").append('<li><label>'+result_.airline+'</label><input type="checkbox" class="airline_flt" value="'+result_.airlineCode+'" checked></li>');
            }
        });

        tjq('.airline_flt').on('click', function () {
            let airline_ch = tjq(this).val();
            //alert(airline_ch);
            //tjq('.airline_').css('display', 'block');
            if (tjq(this).prop('checked')) {
                tjq('.airline_'+airline_ch).fadeIn('fast');
            }
            else {
                tjq('.airline_'+airline_ch).fadeOut('fast');
            }
        });

    });

    flightRequest.fail(function (jqXHR, textStatus) {
        tjq(".loader").css({ display: "none" });
        //alert("Request failed: " + textStatus + "\n" + JSON.stringify(jqXHR, null, "\t"));
        Swal.fire({
            type: 'error',
            html: textStatus + "\n" + JSON.stringify(jqXHR, null, "\t")
        });
    });


});

function flight_detail(i_) {
    tjq('.flight_detail_').fadeOut('fast');
    tjq('#flight_detail_'+i_).fadeIn();
}

function send_to_sale(id, fNo, fId, from, to, departure, return_departure, adt, kid, inf, price) {
    tjq(".loader").css({ display: "block" });
    tjq.ajax({
        type: "POST",
        url: "/ajx-send-to-sale",
        data: { "sId": id, "fNo": fNo, "fId":fId, "fromWhere": from, "toWhere": to, "departDate": departure, "returnDate": return_departure, "whoADT": adt, "whoKid": kid, "whoInf": inf, "price": price, "dateFormat": 2 },
    }).done(function (xhr) {
        tjq(".loader").css({ display: "none" });

        if (xhr.status == false) {
            Swal.fire({
                type: 'error',
                html: xhr.message
            });
            return false;
        }

        window.location.href = '/checkout/' + xhr.results.processId;

    }).fail(function (xhr) {
        tjq(".loader").css({ display: "none" });
        Swal.fire({
            type: 'error',
            html: xhr.textStatus + "\n" + JSON.stringify(xhr, null, "\t")
        });
    });
}