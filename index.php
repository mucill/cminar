  <?php include 'header.php' ?>

  <div id="content" class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3 col-sm-12 ">      
        <form action="/" method="post" id="formRegs" class="needs-validation" novalidate>  
        <div class="card">
          <div class="card-header">
            Pendaftaran
          </div>

          <div class="card-body">
              <div class="form-group">
                <label for="kinds">Sebagai Peserta</label>
                <select name="kinds" id="kinds" class="form-control">
                  <option value="umum">Umum/Pustakawan/Mahasiswa S2</option>
                  <option value="mahasiswa">Mahasiswa D3/S1/Komunitas Slims</option>
                </select>
              </div>
              <div class="form-group">
                  <label for="phone">Nomor Ponsel *</label>
                  <input type="number" class="form-control" name="phone" id="phone" aria-describedby="phoneHelp" placeholder="Nomor Ponsel" required>
                  <small id="phoneHelp" class="form-text text-muted">Kode pendaftaran akan dikirimkan melalui SMS.</small>
              </div>
              <div class="form-group">
                <label for="fullname">Nama *</label>
                <input type="text" class="form-control" name="name" id="fullname" aria-describedby="fullnameHelp" placeholder="Nama" required>
                <small id="fullnameHelp" class="form-text text-muted">Nama yang akan digunakan pada sertifikat.</small>
              </div>
              <div class="form-group">
                  <label for="jobs">Pekerjaan Saat Ini</label>
                  <select name="jobs" id="jobs" class="form-control">
                    <option value="Guru">Guru</option>
                    <option value="Kepala Sekolah">Kepala Sekolah</option>
                    <option value="Pegawai Swasta">Pegawai Swasta</option>
                    <option value="Pegawai Negeri">Pegawai Negeri</option>
                    <option value="Pustakawan" selected>Pustakawan</option>
                    <option value="Penggerak Literasi">Penggerak Literasi</option>
                    <option value="Mahasiswa S1">Mahasiswa S1</option>
                    <option value="Mahasiswa S2">Mahasiswa S2</option>
                    <option value="Mahasiswa S3">Mahasiswa S2</option>
                    <option value="Komunitas Slims">Komunitas Slims</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
              </div>
              <div class="form-group">
                  <label for="company">Instansi</label>
                  <input type="text" class="form-control" name="company" id="company" aria-describedby="companyHelp" placeholder="Tempat Anda Bekerja">
                  <small id="companyHelp" class="form-text text-muted d-none">Pekerjaan anda saat ini.</small>
              </div>
              <div class="form-group">
                  <label for="email">Email *</label>
                  <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="your-email@mail.com" required>
                  <small id="emailHelp" class="form-text text-muted">Email ini akan digunakan untuk mengirimkan bukti pendaftaran.</small>
              </div>
              <div class="form-group choose">
                  <label>Pilih kegiatan yang diikuti :</label><br>
                  <input type="hidden" name="seminar" id="seminar" value="350" data-price="350"><i class="fa fa-check"></i>&nbsp;&nbsp;<label>Seminar & Workshop</label> - <strong><span id="currPrice">350</span>K<strong>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="penginapan" value="0" data-price="0" id="penginapan" onclick="calculatePrice('#penginapan', 225)">
                    <label class="custom-control-label" for="penginapan" onclick="calculatePrice('#penginapan',225)">Penginapan - <strong>225K</strong></label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="wisata" value="0" data-price="0" id="wisata" onclick="calculatePrice('#wisata', 200)">
                    <label class="custom-control-label" for="wisata" onclick="calculatePrice('#wisata',200)">Wisata Pahawang - <strong>200K</strong></label>
                  </div>
              </div>
              <div class="form-group">
                  <table class="table">
                    <tr>
                      <td class="px-0">
                        <div class="font-weight-bold">Total Biaya</div>
                      </td>
                      <td align="right" class="px-0">
                        <div class="font-weight-bold">IDR <span id="total">350</span>K</div>
                        <input type="hidden" name="totalfee" id="totalfee" value="350">
                        <input type="hidden" name="penginapanfee" id="penginapanfee" value="0">
                        <input type="hidden" name="wisatafee" id="wisatafee" value="0">
                      </td>
                    </tr>
                  </table>
              </div>
              <div class="alert bg-light border-radius-0 text-center">
                Dengan mengklik tombol di bawah ini, anda menyetujui dan mematuhi peraturan serta ketetapan yang telah dibuat Panitia
              </div>

            </div>
          <div class="card-footer text-right">
            <button type="reset" class="btn btn-default" id="clear">Reset</button>
            <button type="submit" class="btn btn-primary has-spinner" id="daftar" disabled><span class="spinner"><i class="fa fa-cog fa-spin fa-fw"></i></span><span id="daftar-text">Ya, saya setuju dan ingin mendaftar</span></button>
          </div>
        </div>
        </form> 
      </div>
    </div>
  </div>
  <script src="node_modules/jquery/dist/jquery.min.js"></script>
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="node_modules/toastr/build/toastr.min.js "></script>
  <script src="node_modules/jquery-validation//dist/jquery.validate.min.js"></script>
  <script src="node_modules/jquery-validation//dist/localization/messages_id.min.js"></script>
  <script>
  function checkMail() {
    activateButton();
    $.post('./api/regs/checkmail?key=<?php echo $encrypt_key ?>', 
    {
      email: $(this).val()
    })
    .done(function(data){
      // alert(data.status)
      if(data.status == 'failed') {
        toastr.options = {
          "newestOnTop": true,
          "positionClass": "toast-top-center",
          "hideDuration": "600",
          "preventDuplicates": true,
        }
        toastr.error('Alamat email sudah pernah didaftarkan.','Mohon periksa kembali.')
        $('#emailHelp').html('Email sudah pernah terdaftar.').removeClass('text-muted').addClass('text-danger');
        $('#email').addClass('is-invalid').focus().select();
      } else {
        $('#email').removeClass('is-invalid');
        $('#emailHelp').html('Email ini akan digunakan untuk mengirimkan bukti pendaftaran.').addClass('text-muted').removeClass('text-danger');
      }
    });
  }

  toastr.options = {
    "newestOnTop": true,
    "positionClass": "toast-top-center",
    "hideDuration": "600",
    "preventDuplicates": true,
  }

  function checkPhone() {
    activateButton();
    $.post('./api/regs/checkphone?key=<?php echo $encrypt_key ?>', {phone: $(this).val()})
    .done(function(data){
      if(data.status == 'failed') {
        toastr.error('Mohon periksa kembali','Nomor ponsel sudah terdaftar')
        $('#phoneHelp').html('Nomor ponsel sudah pernah terdaftar.').removeClass('text-muted').addClass('text-danger');
        $('#phone').addClass('is-invalid').focus().select();
      } else if(data.status == 'invalid') {
        toastr.error('Mohon periksa kembali','Format ponsel salah')
        $('#phoneHelp').html('Format ponsel salah.').removeClass('text-muted').addClass('text-danger');
        $('#phone').addClass('is-invalid').focus().select();
      } else {
        $('#phoneHelp').html('Kode pendaftaran akan dikirimkan melalui SMS.').addClass('text-muted').removeClass('text-danger');
        $('#phone').removeClass('is-invalid');
      }
    });
  }

  function activateButton() {
    $('#daftar').prop('disabled', true);
    if($('#name, #email, #phone').val() != '') {
      $('#daftar').prop('disabled', false);
    }
  }

  function changePrice() {
    var current_kinds = $(this).val();
    switch (current_kinds) {
      default:
      case 'umum':
      $('#seminar').data('price',350);
      $('#seminar').val(350);
      $('#currPrice').html(350);
      break;    
      case 'mahasiswa':
        $('#seminar').data('price',250);
        $('#seminar').val(250);
        $('#currPrice').html(250);
      break;
    }
    sumAll();
  }

  function sumAll() {
    var calAllSum = 0;
    $("input[data-price]").each(function(){
      calAllSum += $(this).data('price');
    });
    $('#total').html(calAllSum);
    $('#totalfee').val(calAllSum);
  }

  function calculatePrice(target, price) {
    if($(target).prop("checked") == true) {
      $(target).data('price', price);
      $(target).val(price);
      $(target+'fee').val(price);
    } else {
      $(target).data('price', 0);      
      $(target).val(0);
      $(target+'fee').val(0);
    }
    sumAll();
  }

  $('#email').bind('blur', checkMail);
  $('#phone').bind('blur', checkPhone);
  $('#kinds').bind('change', changePrice);
  $('#name, #email, #phone').bind('blur', activateButton);

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
      name: "required",
      email: {
        required: true,
        email: true
      },
      phone: "required",
    },
    submitHandler: function(form, event) {
      event.preventDefault();
      $('#daftar').toggleClass('active');
      $.post('./api/regs/?key=<?php echo $encrypt_key ?>', $(form).serialize())
      .done(function(data){
        if(data.status == 'success') {
          toastr.success('Mohon untuk segera melunasi biaya administrasi.', 'Selamat, anda telah terdaftar.')
        } else {
          toastr.error('Server sedang sibuk atau koneksi internet bermasalah.', 'Gagal merekam informasi pendaftara.')
        }
        $('#daftar-text').html('Sedang mengirimkan email salinan bukti pendaftaran');
        $.post('./api/sendmail/?key=<?php echo $encrypt_key ?>', $(form).serialize())
        .done(function(){
          if(data.status == 'success') {
            toastr.success('Silahkan cek folder INBOX atau SPAM pada akun email anda.', 'Email telah terkirim.')
          } else {
            toastr.error('Server sedang sibuk atau koneksi internet bermasalah.', 'Gagal mengirimkan email.')
          }
          $('#daftar-text').html('Ya, saya setuju dan ingin mendaftar');
          $('#daftar').toggleClass('active');
          $('#clear').trigger('click');
          $('#total, #currPrice').html(350);
          $('#totalfee').val(350);
          $('#penginapan').val(0);
          $('#wisata').val(0);
          $('#penginapan').data('price', 0);
          $('#wisata').data('price', 0);
        });
      });
    }
  });
  
  </script>
  <script src="https://www.google.com/recaptcha/api.js?render=6Ldy56wUAAAAAFnQy2Y-vB1pmoxCq5mjfnfNUgNR"></script>
  <script>
    grecaptcha.ready(function() {
      grecaptcha.execute('6Ldy56wUAAAAAFnQy2Y-vB1pmoxCq5mjfnfNUgNR', {action: 'index.html'}).then(function(token) {
        console.log(token);
      });
    });
  </script>

</body>
</html>