  <?php include 'header.php' ?>
  <div id="content" class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3 col-sm-12 ">      
        <form action="/" method="post" id="formRegs" class="needs-validation" enctype="multipart/form-data" novalidate>  
        <div class="card">
          <div class="card-header">
            Cetak Tiket Peserta
          </div>

          <div class="card-body">
            <div class="form-group">
                <label for="regcode">Kode Registrasi</label>
                <input type="text" class="form-control" name="regcode" id="regcode" aria-describedby="regcodeHelp" placeholder="Misal. ABC123" required>
                <small id="regcodeHelp" class="form-text text-muted">Masukkan kode registrasi yang diterima dari Panitia</small>
            </div>
          </div>
          <div class="card-footer text-right">
            <button type="reset" class="btn btn-default" id="clear">Reset</button>
            <button type="submit" class="btn btn-primary has-spinner" id="daftar" disabled><span class="spinner"><i class="fa fa-cog fa-spin fa-fw"></i></span> Cetak Ticket Acara</button>
          </div>
        </div>
        </form> 
      </div>
    </div>
  </div>  

  <div class="modal fade" id="printTicket" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Print Preview</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="email" style="height:400px; overflow: auto;">
            <style>
              #email-container {
                font: 12pt/1 'Anonymous Pro', sans-serif;
              }
              #email-container h1,h3 {
                margin: 0;
                line-height: 1;
              }
              
              #email-container .block {
                width: 100%;
                margin-bottom: 10px;
                margin-left: auto;
                margin-right: auto;
              }
          
              #email-container .border {
                border: solid 5px #000 !important;
              }
          
              #email-container .border td {
                border-left: solid 2px #000;
                padding: 20px;      
              }
          
              #email-container .border td.none {
                border-left: none;
              }
          
              #email-container .border td:first-child {
                border: none;
              }
          
            </style>
            <div id="email-container">
              <table class="block border" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="25">
                    <img src="https://2019.slimscommeet.web.id/wp-content/uploads/2019/07/logo-sc-2019-sm.png" width="72px" alt="Slims Commeet 2019">      
                  </td>
                  <td class="none">
                    TANGGAL<br>
                    <h1>22-23 AGT 2019</h1>
                    UNIVERSITAS MALAHAYATI LAMPUNG
                  </td>
                  <td>
                    MULAI
                    <h3>08:00 AM</h3>
                    <br>
                    SELESAI
                    <h3>16:00 PM</h3>
                  </td>
                </tr>
              </table>
              
              <table class="block border" cellpadding="0" cellspacing="0">
                <tr>
                  <td>
                    Nama
                    <h3 style="text-transform: uppercase" id="name"></h3>
                  </td>
                  <td>
                    Peserta<br>
                    <h3 id="type" style="text-transform: uppercase"></h3>
                  </td>
                  <td rowspan="2" class="text-center">
                    <div class="p-30">
                      <img src="" id="barcode" alt="Barcode" />
                    </div>
                    <br>
                    <strong id="barcode_id"></strong>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" style="border-top: solid 2px #000;">
                    INSTANSI
                    <h3 id="company" style="text-transform: uppercase"></h3>
                  </td>
                </tr>
              </table>  
            </div>
          </div>
        </div>
        <div class="modal-footer">
            <div class="input-group col-md-10">
              <input type="text" class="form-control" placeholder="your-mail@address.com" aria-label="your-mail@address.com" aria-describedby="Email" id="usermail">
              <div class="input-group-append">
                <button type="button" class="btn btn-secondary has-spinner" id="send-mail"><span class="spinner"><i class="fa fa-cog fa-spin fa-fw"></i></span><span id="mail-text">Kirimkan ke Email</span></button>
                <button type="button" class="btn btn-primary" id="print">Cetak Ticket Acara</button>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>  
  <script src="node_modules/jquery/dist/jquery.min.js"></script>
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="node_modules/toastr/build/toastr.min.js "></script>
  <script src="node_modules/jquery-validation//dist/jquery.validate.min.js"></script>
  <script src="node_modules/jquery-validation//dist/localization/messages_id.min.js"></script>
  <script src="node_modules/print-this/printThis.js"></script>
  <script>

  $('#regcode').on('keyup', function(){
    if($(this).val() != '') {
      $('#daftar').prop('disabled', false);
    } else {
      $('#daftar').prop('disabled', true);
    }
  });

  $('#print').click(function(){
    $('#email').printThis();
  });

  toastr.options = {
    "newestOnTop": true,
    "positionClass": "toast-top-center",
    "hideDuration": "600",
    "preventDuplicates": true,
  }

  $("#formRegs").validate({
    errorClass: "form-text text-danger",
    errorElement: "small",
    rules: {
      regcode: "required",
    },
    submitHandler: function(form, event) {
      event.preventDefault();
      $('#daftar').toggleClass('active');
      $.post('./api/ticket/?key=<?php echo $encrypt_key ?>', $(form).serialize())
      .done(function(data){
        if (data.status == 'success') {
          var set = data.data;
          $('#name').html(set.name);
          $('#type').html(set.kinds);
          if(set.company.length > 25) {
            $('#company').html(set.company.substring(0, 25)+'...');
          } else {
            $('#company').html(set.company);            
          }
          $('#barcode_id').html(set.barcode);
          $.get('./api/ticket/barcode/'+data.data.barcode+'?key=<?php echo $encrypt_key ?>')
          .done(function(barcode_img){
            document.getElementById("barcode").src = barcode_img;
          });
          $('#printTicket').modal('show');
        } else {
          toastr.error('Mohon periksa kembali.', 'Kode pendaftaran tidak ditemukan.')
        }
        $('#daftar').toggleClass('active');
      });
    }
  });

  $('#printTicket').on('hidden.bs.modal', function(){
    $('#usermail').val('');
  });

  toastr.options = {
    "newestOnTop": true,
    "positionClass": "toast-top-center",
    "hideDuration": "600",
    "preventDuplicates": true,
  }
  
  $('#send-mail').click(function(){
    $(this).toggleClass('active');
    $('#mail-text').html('Mengirimkan email');
    $('#usermail').prop('readonly', true);
    $.post('./api/mailticket/?key=<?php echo $encrypt_key ?>', {
      regcode: $('#regcode').val(),
      email: $('#usermail').val()
    })
    .done(function(data){
      if (data.status == 'success') {
        $('#usermail').prop('readonly', false);
        toastr.success('Silahkan cek di folder INBOX atau SPAM pada akun email anda.', 'Email terkirim.')
      } else {
        toastr.error('Mohon periksa kembali.', 'Email gagal terkirim.')
      }
      $('#send-mail').toggleClass('active');
      $('#mail-text').html('Kirimkan ke Email');
    });
  });
  </script>

</body>
</html>