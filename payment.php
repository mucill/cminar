  <?php include_once 'header.php' ?>
  <div id="content" class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3 col-sm-12 ">      
        <form action="./api/payprove/?key=<?php echo $encrypt_key ?>" method="post" id="formRegs" class="needs-validation" enctype="multipart/form-data" novalidate>  
        <div class="card">
          <div class="card-header">
            Bukti Pembayaran
          </div>

          <div class="card-body">
              <div class="form-group">
                  <label for="phone">Nomor Ponsel *</label>
                  <input type="number" class="form-control" name="phone" id="phone" aria-describedby="phoneHelp" placeholder="Nomor Ponsel" required>
                  <small id="phoneHelp" class="form-text text-muted">Kode pendaftaran akan dikirimkan melalui SMS.</small>
              </div>
              <div class="form-group">
                  <label for="userfile">Upload Bukti Pembayaran</label><br>
                  <div class="input-group mb-3">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="userfile" id="userfile" aria-describedby="userfileHelp" placeholder="Tempat Anda Bekerja">
                      <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                    </div>
                  </div>
                  <small id="userfileHelp" class="form-text text-muted">Ukuran file maks. <?php echo ini_get('upload_max_filesize') ?></small>
              </div>
          </div>
          <div class="card-footer text-right">
            <button type="reset" class="btn btn-default" id="clear">Reset</button>
            <button type="submit" class="btn btn-primary has-spinner" id="daftar" disabled><span class="spinner"><i class="fa fa-cog fa-spin fa-fw"></i></span> Kirim Bukti Pembayaran</button>
          </div>
        </div>
        </form> 
      </div>
    </div>
  </div>
  <script src="node_modules/jquery/dist/jquery.min.js"></script>
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="node_modules/toastr/build/toastr.min.js "></script>
  <script src="node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
  <script src="node_modules/jquery-validation/dist/localization/messages_id.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script>
  function checkPhone() {
    $('#phoneHelp').removeAttr('style');
    $.post('./api/regs/checkphone?key=<?php echo $encrypt_key ?>', 
    {
      phone: $(this).val()
    })
    .done(function(data){
      if(data.status === 'failed') {
        $('#phoneHelp').html('Kode pendaftaran akan dikirimkan melalui SMS.').addClass('text-muted').removeClass('text-danger');
        $('#phone').removeClass('is-invalid');
        $('#daftar').prop('disabled', false);
      } else {
        $('#phoneHelp').html('Nomor ponsel tidak ditemukan atau belum terdaftar.').removeClass('text-muted').addClass('text-danger');
        $('#phone').addClass('is-invalid').focus();
        $('#daftar').prop('disabled', true);
      }
    });
  }

  // input plugin
  bsCustomFileInput.init();
    
  // get file and preview image
  $("#userfile").on('change',function(){
      var input = $(this)[0];
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
              $('#preview').attr('src', e.target.result).fadeIn('slow');
          }
          reader.readAsDataURL(input.files[0]);
      }
  });

  $('#phone').bind('blur', checkPhone);

  toastr.options = {
    "newestOnTop": true,
    "positionClass": "toast-top-center",
    "hideDuration": "600",
    "preventDuplicates": true,
  }

  $("#formRegs").submit(function(e){
      e.preventDefault();
      $('#daftar').toggleClass('active');
      $.ajax({
        type: "POST",
        url: './api/payprove/?key=<?php echo $encrypt_key ?>',
        enctype: 'multipart/form-data',
        data:  new FormData(this),
        contentType: false,
        cache: false,
        processData:false,
        success: function(data) {
          if (data.status == 'success') {
            toastr.success('Panitia akan mengirimkan KODE REGISTRASI setelah proses validasi.', 'Berkas berhasil diunggah.')
            $('#clear').trigger('click');
          } else {
            toastr.error('Mohon periksa kembali.', 'Berkas gagal diunggah.')
          }          
          $('#daftar').toggleClass('active');
        }
      });
  });
  
  </script>

</body>
</html>