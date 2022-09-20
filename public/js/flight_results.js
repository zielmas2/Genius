let parametreler = {};
var secenekler = [];
var donusSecenekler = [];
var secilmisUcus = false;

var Secimler = { direkt: true, aktarma1: true, aktarma2: true, aktarma3_: false, havayolu: true };
var havaYollari = [];
var havaYollariSecilmis = [];

function havaYoluFiltre(BuUcus) {
    if (havaYollariSecilmis.length == 0) return true;
    return havaYollariSecilmis.some(function (birHavaYolu) {
        return (birHavaYolu == BuUcus.Firma());
    });
}

function aktarmaFiltre(BuUcus, sayi) {
    return ((BuUcus.Aktarma() == sayi) && (BuUcus.DonusVarmi() ? (BuUcus.Donus.Aktarma() == sayi) : true));
}

function aktarma3_Filtre(BuUcus) {
    return ((BuUcus.Aktarma() >= 3) && (BuUcus.DonusVarmi() ? (BuUcus.Donus.Aktarma() >= 3) : true));
}

function genelFiltre(BuUcus) {

    var sonuc = false;

    if (Secimler.direkt) {
        if (aktarmaFiltre(BuUcus, 0)) sonuc = true;
    }

    if ((!sonuc) && Secimler.aktarma1) {
        if (aktarmaFiltre(BuUcus, 1)) sonuc = true;
    }

    if ((!sonuc) && Secimler.aktarma2) {
        if (aktarmaFiltre(BuUcus, 2)) sonuc = true;
    }

    if ((!sonuc) && Secimler.aktarma3_) {
        if (aktarma3_Filtre(BuUcus)) sonuc = true;
    }

    return (sonuc && (Secimler.havayolu ? havaYoluFiltre(BuUcus) : true));
}

tjq(document).ready(function () {
    var link = decodeURI(window.location.href);
    var hashes = link.slice(link.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        var hash = hashes[i].split('=');
        parametreler[hash[0]] = hash[1];
    }
    parametreler['dateFormat'] = 1;

    tjq(".loader").css({ display: "block" });
    var ucaklarRequest = tjq.ajax({
        url: "/ajx-flight-results",
        data: parametreler,
        method: "POST"
    });

    ucaklarRequest.done(function (results_) {

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
            console.log(result_);
            let fr_html = '';
            fr_html = '<article class="box">\n' +
                '                                <figure class="col-xs-3 col-sm-2">\n' +
                '                                    <span><img alt="" src="http://placehold.it/270x160"></span>\n' +
                '                                </figure>\n' +
                '                                <div class="details col-xs-9 col-sm-10">\n' +
                '                                    <div class="details-wrapper">\n' +
                '                                        <div class="first-row">\n' +
                '                                            <div>\n' +
                '                                                <h4 class="box-title">(' + result_.fromWhere + ') to (' + result_.toWhere + ')<small>' + (!result_.isHasTwoWay ? 'Oneway flight' : '') + '</small></h4>\n' +
                '                                                <a class="button btn-mini stop">'+(result_.hasTransfer?('1'+' STOP'):'DIRECT')+'</a>\n' +
                /*'                                                <div class="amenities">\n' +
                '                                                    <i class="soap-icon-wifi circle"></i>\n' +
                '                                                    <i class="soap-icon-entertainment circle"></i>\n' +
                '                                                    <i class="soap-icon-fork circle"></i>\n' +
                '                                                    <i class="soap-icon-suitcase circle"></i>\n' +
                '                                                </div>\n' +*/
                '                                            </div>\n' +
                '                                            <div>\n' +
                '                                                <span class="price"><small>PRICE</small>' + result_.price + '&nbsp;' + result_.currency + '</span>\n' +
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
                '                                        </div>\n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '                                <input type="hidden" name="carrier" value="'+result_.carrier+'"><input type="hidden" name="flight_number" value="'+result_.flightNumber+'"><input type="hidden" name="flight_id" value="'+result_.flightID+'">\n' +
                '                            </article>' +
                '                            <div style="margin-top: 0px; border: 1px dashed; display:none; background: background: #f5f5f5;" id="flight_detail_'+i_r+'" class="flight_detail_">' +
                '                                 <div class="intro table-wrapper full-width hidden-table-sm box" style="padding: 10px;">\n' +
                '                                        <div class="col-md-4 table-cell travelo-box">\n' +
                '                                            <dl class="term-description">\n' +
                '                                                <dt>Airline:</dt><dd>delta</dd>\n' +
                '                                                <dt>Flight Type:</dt><dd>Economy</dd>\n' +
                '                                                <dt>Fare type:</dt><dd>Refundable</dd>\n' +
                '                                                <dt>Flight CHange:</dt><dd>$53 / person</dd>\n' +
                '                                                <dt>Seats &amp; Baggage:</dt><dd>Extra Charge</dd>\n' +
                '                                                <dt>Base fare:</dt><dd>$320.00</dd>\n' +
                '                                                <dt>Taxes &amp; Fees:</dt><dd>$300.00</dd>\n' +
                '                                                <dt>total price:</dt><dd>$620.00</dd>\n' +
                '                                            </dl>\n' +
                '                                        </div>\n' +
                '                                        <div class="col-md-8 table-cell">\n' +
                '                                            <div class="detailed-features booking-details">\n' +
                '                                                <div class="travelo-box">\n' +
                '                                                    <a href="javascript:;" onclick="send_to_sale('+results_.results.searchProcessId+', \''+result_.flightNumber+'\', \''+result_.flightID+'\', \''+result_.fromWhere+'\', \''+result_.toWhere+'\', \''+result_.departingDate+'\', \''+'\', \''+result_.adt+'\', \''+result_.kid+'\', \''+result_.inf+'\', \''+result_.price+'\');" class="button btn-large yellow pull-right">CHECKOUT</a>\n' +
                '                                                    <h4 class="box-title">(' + result_.fromWhere + ') to (' + result_.toWhere + ')<small>' + (!result_.isHasTwoWay ? 'Oneway flight' : '') + '</small></h4>\n' +
                '                                                </div>\n' +
                '                                                <div class="table-wrapper flights">\n' +
                '                                                    <div class="table-row first-flight">\n' +
                '                                                        <div class="table-cell timing-detail">\n' +
                '                                                            <div class="timing">\n' +
                '                                                                <div class="check-in" style="display: inline-block;">\n' +
                '                                                                    <label>Take off</label>\n' +
                '                                                                    <span>13 Nov 2013, 7:50 am</span>\n' +
                '                                                                </div>\n' +
                '                                                                <div class="duration text-center" style="display: inline-block;">\n' +
                '                                                                    <i class="soap-icon-clock"></i>\n' +
                '                                                                    <span>'+result_.flightTimeShort+'</span>\n' +
                '                                                                </div>\n' +
                '                                                                <div class="check-out" style="display: inline-block;">\n' +
                '                                                                    <label>landing</label>\n' +
                '                                                                    <span>13 Nov 2013, 9:20 Am</span>\n' +
                '                                                                </div>\n' +
                '                                                            </div>\n' +
                '                                                        </div>\n' +
                '                                                    </div>\n' +
                '                                                </div>\n' +
                '                                            </div>\n' +
                '                                        </div>\n' +
                '                                    </div>' +
                '                            </div>' +
                '                            <div style="margin-bottom: 30px"></div>';
            tjq('#flight_results').append(fr_html);
        });





        havaYollari.forEach(function (birHavaYolu, i) {
            tjq(".havayollari").append(
                tjq("<div>").addClass("checkbox").append(
                    tjq("<label>").append(tjq('<input type="checkbox" class="firmaFiltre">').val(birHavaYolu).change(function () {
                        havaYollariSecilmis = [];
                        tjq("input[type='checkbox'].firmaFiltre").each(function (i) {
                            if (this.checked) {
                                havaYollariSecilmis.push(tjq(this).val());
                            }
                        });

                        guncelle();
                    }))
                        .append('<span class="cr"><i class="cr-icon fas fa-check"></i></span>')
                        .append(birHavaYolu)
                )
            );
        });

        tjq(".siralamalar").append(tjq('<button type="button" class="btn dropdown-item">').text("Fiyata Göre Sırala").click(function () {
            minimumFiyat();
        }));

        tjq(".siralamalar").append(tjq('<button type="button" class="btn dropdown-item">').text("Toplam Ucuş Süresine Göre Sırala").click(function () {
            minimumUcusSuresi();
        }));

        tjq(".siralamalar").append(tjq('<button type="button" class="btn dropdown-item">').text("Kalkış Saatine Göre Sırala").click(function () {
            minimumKalkisSaati();
        }));

        tjq(".siralamalar").append(tjq('<button type="button" class="btn dropdown-item">').text("Aktarma Sayısına Göre Sırala").click(function () {
            minimumAktarma();
        }));

    });

    ucaklarRequest.fail(function (jqXHR, textStatus) {
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

function minimumFiyat(guncelleme) {
    secenekler.sort(function (a, b) { return a.Ucret - b.Ucret; });
    donusSecenekler.sort(function (a, b) { return a.Ucret - b.Ucret; });
    if (!guncelleme)
        guncelle();
}

function guncelle() {

}

function send_to_sale(id, fNo, fId, from, to, departure, return_departure, adt, kid, inf, price) {
    tjq(".loader").css({ display: "block" });
    tjq.ajax({
        type: "POST",
        url: "/ajx-send-to-sale",
        data: { "sId": id, "fNo": fNo, "fId":fId, "fromWhere": from, "toWhere": to, "departDate": departure, "returnDepartingDate": return_departure, "whoADT": adt, "whoKid": kid, "whoInf": inf, "price": price, "dateFormat": 2 },
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