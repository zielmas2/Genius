tjq('#frm_sale').submit(function (e) {
    e.preventDefault();

    tjq(".loader").css({ display: "block" });
    tjq.ajax({
        type: "POST",
        url: "/ajx-sale",
        data: tjq('#frm_sale').serialize(),
    }).done(function (xhr) {
        tjq(".loader").css({ display: "none" });

        if (xhr.status == false) {
            Swal.fire({
                type: 'error',
                html: xhr.message
            });
            return false;
        }

        //window.location.href = '/checkout/' + xhr.results.processId;
        Swal.fire({
            type: 'success',
            html: xhr.message
        });

        Swal.fire({
            type: 'success',
            //title: '',
            timer: 5000,
            text: xhr.message
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                window.location.href = '/booking-result/'+xhr.pnr;
            }
            else {
                window.location.href = '';
            }
        });

    }).fail(function (xhr) {
        tjq(".loader").css({ display: "none" });
        Swal.fire({
            type: 'error',
            html: xhr.textStatus + "\n" + JSON.stringify(xhr, null, "\t")
        });
    });
});