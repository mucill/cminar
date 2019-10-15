<?php require_once 'secure.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
    <title>Pendaftaran</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css" />
    <link rel="stylesheet" href="node_modules/izitoast/dist/css/iziToast.min.css" />

    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/components.css" />
    <link rel="stylesheet" href="assets/css/custom.css" />
  </head>
  <?php include_once 'config.php' ?>
  <body class="sidebar-mini">
    <div id="app">
      <div class="main-wrapper">
        <?php include_once 'nav.php' ?>
        <!-- Main Content -->
        <div class="main-content">
          <section class="section">
            <div class="section-header">
              <h1>Pendaftaran</h1>
              <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Pendaftaran</div>
              </div>
            </div>

            <div class="section-body">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped datatable" id="datatable">
                          <thead>
                            <tr>
                              <th>Nama</th>
                              <th>Seminar</th>
                              <th>Hotel</th>
                              <th>Wisata</th>
                              <th>Total</th>
                              <th>Dibayarkan</th>
                              <th>Status</th>
                              <th>Pembayaran</th>
                              <th>Registrasi</th>
                              <th>Pesan</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
        <?php include_once 'footer.php' ?>
      </div>
    </div>

    <!-- Bukti Bayar -->
    <div class="modal fade" id="bukti-bayar" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
      <form action="/" method="post" id="update-bukti-bayar">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalScrollableTitle">Bukti Pembayaran</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <img id="photo" class="w-100" />
                  </div>
                  <div class="card ">
                    <table class="table m-0">
                      <thead>
                        <tr>
                          <th class="border-top-0">Pilihan</th>
                          <th class="border-top-0 text-right">Biaya</th>
                          <th class="border-top-0 text-right">Sesuai</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Seminar <span id="peserta"></span></td>
                          <td class="text-right" id="pay-seminar"></td>
                          <td class="text-right">
                            <label class="custom-switch mt-2">
                              <input type="checkbox" data-price="0" onclick="calculatePrice(this)" name="seminar" id="seminar" class="custom-switch-input" />
                              <span class="custom-switch-indicator"></span>
                            </label>
                            <input type="hidden" name="pay_seminar" value="0" id="pay_seminar" />
                          </td>
                        </tr>
                        <tr>
                          <td>Penginapan</td>
                          <td class="text-right" id="pay-hotel"></td>
                          <td class="text-right">
                            <label class="custom-switch mt-2">
                              <input type="checkbox" data-price="0" onclick="calculatePrice(this)" name="hotel" id="hotel" class="custom-switch-input" />
                              <span class="custom-switch-indicator"></span>
                            </label>
                            <input type="hidden" name="pay_hotel" value="0" id="pay_hotel" />
                          </td>
                        </tr>
                        <tr>
                          <td>Wisata Pahawang</td>
                          <td class="text-right" id="pay-wisata"></td>
                          <td class="text-right">
                            <label class="custom-switch mt-2">
                              <input type="checkbox" data-price="0" onclick="calculatePrice(this)" name="wisata" id="wisata" class="custom-switch-input" />
                              <span class="custom-switch-indicator"></span>
                            </label>
                            <input type="hidden" name="pay_wisata" value="0" id="pay_wisata" />
                          </td>
                        </tr>
                      </tbody>
                      <tfoot class="bg-light">
                        <tr>
                          <th>Total</th>
                          <th class="text-right" id="totalfee">0</th>
                          <th class="text-danger text-right" id="total">0</th>
                        </tr>
                      </tfoot>
                    </table>
                    <button type="reset" class="d-none" id="reset">Reset</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" id="curr_id" name="id" />
              <input type="hidden" id="curr_total" name="total" />
              <button type="button" class="btn btn-link" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="assets/js/stisla.js"></script>

    <!-- JS Libraies -->
    <script src="node_modules/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js"></script>
    <script src="node_modules/izitoast/dist/js/iziToast.min.js"></script>
    <!-- Template JS File -->
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/custom.js"></script>

    <!-- Page Specific JS File -->
    <script src="assets/js/page/modules-datatables.js"></script>
    <script>
      $(document).ready(function() {
        var dataSet = [];
        var table = $("#datatable").DataTable({
          dom: "Bfrtip",
          retrieve: true
        });
        table.destroy();
        $.ajax({
          type: "POST",
          url: "<?php echo $server ?>/regs/?key=8eaa717fa8c6cec706e1d7baa0c46e50",
          crossDomain: true,
          dataType: "JSON",
          async: true,
          success: function success(data) {
            // console.log(data);
            if (data.length != 0) {
              var sno = 1;
              $.each(data.data, function(index, v) {
                // console.log(v.name + "--" + v.pay_status);
                dataSet.push([
                  "<strong>" + v.name + "</strong><br><small>" + v.kinds.charAt(0).toUpperCase() + v.kinds.slice(1) + "<br>" + v.phone + "<br>" + v.email + "</small>",
                  // v.fee_seminar == 0 ? "Rp0" : "Rp" + v.fee_seminar + ".000",
                  // v.fee_hotel == 0 ? "Rp0" : "Rp" + v.fee_hotel + ".000",
                  // v.fee_tour == 0 ? "Rp0" : "Rp" + v.fee_tour + ".000",
                  v.fee_seminar == 0 ? '<span><i class="fa fa-close text-danger"></i></span>' : '<span><i class="fa fa-check text-success"></i></span>',
                  v.fee_hotel == 0 ? '<span><i class="fa fa-close text-danger"></i></span>' : '<span><i class="fa fa-check text-success"></i></span>',
                  v.fee_tour == 0 ? '<span><i class="fa fa-close text-danger"></i></span>' : '<span><i class="fa fa-check text-success"></i></span>',
                  v.fee_total == 0 ? "Rp0" : "Rp" + v.fee_total + ".000",
                  v.pay_amount == null ? "Rp0" : "Rp" + v.pay_amount + ".000",
                  v.status == null ? "Terdaftar" : v.status,
                  v.pay_photo == null || v.pay_photo == ""
                    ? '<div class="badge badge-danger"><i class="fa fa-close"></i> Belum Ada</div>'
                    : v.pay_status != null
                    ? '<div class="badge badge-success">' + v.pay_status.charAt(0).toUpperCase() + v.pay_status.slice(1) + "</div>"
                    : "<button onclick=\"openBuktiBayar('" + v.id + "','" + v.pay_photo + "','" + v.fee_seminar + "','" + v.fee_hotel + "','" + v.fee_tour + "','" + v.kinds + "','" + v.fee_total + '\')" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Periksa</button>',
                  "<strong>" + v.barcode + "</strong>",
                  (v.pay_status == null || v.pay_status == "null" || $.trim(v.pay_status) == ""
                    ? '<button type="button" class="btn btn-sm btn-light text-secondary" disabled><i class="fa fa-envelope"></i></button>'
                    : v.confirm_sms == "1"
                    ? "Terkirim"
                    : '<button type="button" onclick="return sendMessage(\'#sms-' +
                      v.id +
                      "','" +
                      v.phone +
                      "','" +
                      v.email +
                      "','" +
                      v.name +
                      "','" +
                      v.barcode +
                      "','" +
                      v.id +
                      '\')" id="sms-' +
                      v.id +
                      '" class="btn btn-icon btn-info has-spinner"><span class="spinner"><i class="fa fa-cog fa-spin fa-fw"></i></span><i class="fa fa-envelope"></i></button>') + "</div>"
                ]);
              });
              $("#datatable").DataTable({
                data: dataSet,
                deferRender: true,
                dom: "Bfrtip",
                responsive: true
              });
            } else {
              table.draw();
              table.clear();
            }
          },
          error: function error() {
            //console.log("error in executing");
            //onError();
          }
        });
      });
      $("#bukti-bayar").on("hidden.bs.modal", function() {
        $("input[data-price]").each(function() {
          $(this).data("price", 0);
        });
        $("#reset").trigger("click");
        $("#total")
          .html("0")
          .addClass("text-danger");
        sumAll();
      });

      function sendMessage(target, curr_phone, curr_email, curr_name, curr_barcode, curr_id) {
        $(target).toggleClass("active btn-danger");
        if (confirm("Proses ini hanya bisa dilakukan 1 kali.\nAnda yakin akan mengirimkan Kode Registrasi ?")) {
          var userkey = "floxq2";
          var passkey = "k3w6ocg6b1";
          var text = "Kode registrasi anda " + curr_barcode + ". Mohon cetak tiket melalui web 2019.slimscommeet.web.id dan bawa saat registrasi ulang. Terima kasih. Panitia SC2019";
          var sms_server = "https://reguler.zenziva.net/apps/smsapi.php?userkey=" + userkey + "&passkey=" + passkey + "&nohp=" + curr_phone + "&pesan=" + text;
          $.post(sms_server).done(function(xml) {
            var xml = "<response><message><to>081234567890</to><status>0</status><text>Success</text><balance>9999</balance></message></response>";
            var xmlDoc = $.parseXML(xml);
            var getxml = $(xmlDoc);
            var status = getxml.find("text");
            // console.log(status.text());
            if (status.text() == "Success") {
              iziToast.info({ position: "topRight", message: "Pesan SMS berhasil terkirim", title: "Berhasil." });
              $.post("<?php echo $server ?>/sendmail/?key=8eaa717fa8c6cec706e1d7baa0c46e50", {
                id: curr_id,
                name: curr_name,
                barcode: curr_barcode,
                email: curr_email,
                phone: curr_phone
              }).done(function(data) {
                if (data.status == "success") {
                  iziToast.info({ position: "topRight", message: "Email telah berhasil terkirim", title: "Berhasil." });
                } else {
                  iziToast.error({ position: "topRight", message: "Email gagal terkirim.", title: "Error." });
                }
              });
            } else {
              iziToast.error({ position: "topRight", message: "Mohon cek SMS Credit anda.", title: "Error." });
            }
          });
        } else {
          iziToast.error({ position: "topRight", message: "Proses pengiriman kode registrasi dibatalkan.", title: "Error." });
        }
        $(target).toggleClass("active btn-danger");
        return false;
      }

      function openBuktiBayar(id, photo, fee_seminar, fee_hotel, fee_wisata, peserta, feetotal) {
        $("#bukti-bayar #curr_id").val(id);
        $("#bukti-bayar #totalfee").html("Rp" + feetotal + ".000");
        $("#bukti-bayar #curr_total").val(feetotal);
        $("#bukti-bayar #photo").attr("src", "../uploads/" + photo);
        $("#bukti-bayar #peserta").html("- " + peserta.charAt(0).toUpperCase() + peserta.slice(1) + "");
        $("#bukti-bayar #pay-seminar").html(fee_seminar == 0 ? "Rp0" : "Rp" + fee_seminar + ".000");
        $("#bukti-bayar #pay-hotel").html(fee_hotel == 0 ? "Rp0" : "Rp" + fee_hotel + ".000");
        $("#bukti-bayar #pay-wisata").html(fee_wisata == 0 ? "Rp0" : "Rp" + fee_wisata + ".000");
        $("#bukti-bayar #seminar").val(fee_seminar);
        $("#bukti-bayar #hotel").val(fee_hotel);
        $("#bukti-bayar #wisata").val(fee_wisata);
        $("#bukti-bayar").modal("show");
      }

      function sumAll() {
        var calAllSum = 0;
        $("input[data-price]").each(function() {
          calAllSum += parseInt($(this).data("price"));
        });
        var check = "";
        if ($("#totalfee").html() === "Rp" + calAllSum + ".000") {
          check = '<i class="fa fa-check text-success">';
          $("#total").removeClass("text-danger");
          $("#total").addClass("text-success");
        } else {
          $("#total").addClass("text-danger");
          $("#total").removeClass("text-success");
        }
        if (calAllSum != 0) {
          $("#total").html("Rp" + calAllSum + ".000 " + check);
          $("#curr_total").val(calAllSum);
        } else {
          $("#total").html(0);
          $("#curr_total").val(0);
        }
      }

      function calculatePrice(target) {
        var setVal = "#pay_" + $(target).attr("name");
        if ($(target).prop("checked") == true) {
          $(target).data("price", $(target).val());
          $(setVal).val($(target).val());
        } else {
          $(target).data("price", 0);
          $(setVal).val(0);
        }
        sumAll();
      }
      $("#update-bukti-bayar").submit(function(e) {
        e.preventDefault();
        if (confirm("Anda yakin akan menyimpan data ini ?")) {
          if ("Rp" + $("#curr_total").val() + ".000" != $("#totalfee").html()) {
            if (confirm("Nilai pembayaran tidak sesuai.\nAnda yakin akan mengubah nominal pembayaran ?")) {
              $.ajax({
                type: "POST",
                url: "<?php echo $server ?>/paymentstatus/?key=8eaa717fa8c6cec706e1d7baa0c46e50",
                crossDomain: true,
                data: $(this).serialize(),
                dataType: "JSON",
                async: true,
                success: function success(data) {
                  if (data.status == "success") {
                    $("#bukti-bayar").modal("hide");
                    window.location.reload();
                  } else {
                    alert("Gagal merekam data.");
                  }
                },
                error: function error() {
                  alert("Gagal merekam data.");
                }
              });
            } else {
              return false;
            }
          } else {
            $.ajax({
              type: "POST",
              url: "<?php echo $server ?>/paymentstatus/?key=8eaa717fa8c6cec706e1d7baa0c46e50",
              crossDomain: true,
              data: $(this).serialize(),
              dataType: "JSON",
              async: true,
              success: function success(data) {
                if (data.status == "success") {
                  $("#bukti-bayar").modal("hide");
                  window.location.reload();
                } else {
                  alert("Gagal merekam data.");
                }
              },
              error: function error() {
                alert("Gagal merekam data.");
              }
            });
          }
        }
        return false;
      });
    </script>
  </body>
</html>
