<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Registrasi Peserta - SLIMS COMMEET 2019</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />
    <link rel="stylesheet" href="node_modules/izitoast/dist/css/iziToast.min.css" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css?family=Monda&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="node_modules/animate.css/animate.min.css" />
    <style>
      body {
        background: #fff;
        font: 12pt/1.8 "Monda", sans-serif;
        /* overflow: hidden; */
      }

      #wrapper {
        padding: 50px 50px 50px 50px;
        /* overflow: hidden; */
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
      }

      #content {
        color: #fff;
        background: #16191c;
        height: calc(100vh - 180px);
        padding: 140px 75px 0 75px;
        position: relative;
        margin-top: 80px;
      }

      #resultscan {
        background: #f9f9f9;
        width: 100hw;
        height: calc(100vh - 180px);
        margin-top: 80px;
        position: relative;
      }

      .slow-spin {
        -webkit-animation: fa-spin 3s infinite linear;
        animation: fa-spin 3s infinite linear;
      }

      .form-custom {
        width: 100%;
        padding: 30px 10px !important;
        line-height: 1;
        border-radius: 0;
        border: none;
      }

      .btn-custom {
        background: #3e56d1;
        border-radius: 0;
        color: #fff !important;
        width: 100%;
        padding: 25px 10px !important;
        line-height: 1;
        font-size: 9pt;
        text-transform: uppercase;
        letter-spacing: 1px;
      }

      .spinner {
        display: inline-block;
        opacity: 0;
        max-width: 0;
        -webkit-transition: opacity 0.25s, max-width 0.45s;
        -moz-transition: opacity 0.25s, max-width 0.45s;
        -o-transition: opacity 0.25s, max-width 0.45s;
        transition: opacity 0.25s, max-width 0.45s;
        /* Duration fixed since we animate additional hidden width */
      }

      .has-spinner.active {
        cursor: progress;
      }

      .has-spinner.active .spinner {
        opacity: 1;
        max-width: 50px;
        /* More than it will ever come, notice that this affects on animation duration */
      }

      #registrasi {
        position: absolute;
        z-index: 1;
        margin-top: 15px;
        font-weight: bold;
        width: 100%;
        height: 100px;
        line-height: 0;
        color: #999;
        font-size: 35px;
      }

      #loader {
        position: absolute;
        top: 30vh;
        left: calc(30vw - 50px);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
        margin: 0 auto;
        border: dotted 5px rgba(0, 0, 0, 0.05);
        border-radius: 50%;
        width: 150px;
        height: 150px;
        line-height: 300px;
        text-align: center;
      }

      #waiting {
        position: absolute;
        top: 40vh;
        left: calc(30vw - 135px);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
        margin: 0 auto;
        border: dotted 5px rgba(0, 0, 0, 0.05);
        border-radius: 50%;
        width: 300px;
        height: 300px;
        text-align: center;
        overflow: hidden;
      }

      #waiting img {
        margin-left: -50px;
      }

      #scan-result {
        padding-top: calc(20vw - 75px);
        text-align: center;
        line-height: 1.4;
        width: 100%;
        font-size: 20pt;
        color: #999;
        transition: all 0.5s ease-in-out;
        text-transform: capitalize;
      }

      #name {
        text-transform: uppercase;
        font-family: "Montserrat", serif;
        color: #000;
        font-size: 40pt;
        font-weight: 700;
      }

      #scanning {
        top: 375px;
        left: 75px;
        width: 300px;
        text-align: center;
        margin: 0 auto;
        position: absolute;
        z-index: 999;
      }

      #video {
        width: 300px;
        height: 100% !important;
        display: block;
      }

      #sourceSelectPanel {
        display: none;
      }
    </style>
  </head>
  <body>
    <div id="wrapper">
      <div id="registrasi">
        REGISTRASI PESERTA SLIMS COMMEET 2019
      </div>
      <div class="row no-gutters">
        <div class="col-md-4">
          <div id="content">
            <div id="scanning" class="animated fadeIn d-none">
              <video id="video"></video>
              <button type="button" class="btn btn-custom" id="resetButton">Close Camera</button>
              <div id="sourceSelectPanel">
                <label for="sourceSelect">Change video source:</label>
                <select id="sourceSelect" style="max-width:400px"> </select>
              </div>
            </div>
            <form action="/" method="post" id="formRegs" class="needs-validation" enctype="multipart/form-data" autocomplete="off">
              <div class="form-group">
                <div class="input-group mb-3">
                  <input type="text" class="form-control form-custom" name="regcode" id="regcode" aria-describedby="regcodeHelp" placeholder="Kode Registrasi" required />
                  <div class="input-group-append">
                    <button type="button" class="btn btn-light" id="startButton"><div class="fa fa-barcode"></div></button>
                  </div>
                  <small id="regcodeHelp" class="form-text text-muted">Ketik kode registrasi atau gunakan Scanner</small>
                </div>
              </div>
              <button type="submit" class="btn btn-custom" id="daftar">
                <span class="spinner"><i class="fa fa-cog fa-spin fa-fw"></i></span> Cek Kode Registrasi
              </button>
            </form>
          </div>
        </div>

        <div class="col-md-8">
          <div id="resultscan">
            <div id="scan-result" class="animated fadeIn">
              <h3 id="pagetitle">Ketik Kode Registrasi Atau</h3>
              <div id="name">SCAN BARCODE</div>
              <br />
              <div id="type">...</div>
              <div id="company">...</div>
              <div id="barcode_id">...</div>
            </div>
            <div id="waiting" class="d-none">
              <img src="antri.gif" alt="Antri" class="h-100" />
            </div>
            <!-- <div id="loader" class="slow-spin d-none"></div> -->
          </div>
        </div>
      </div>
    </div>

    <div class="d-none">
      <p>
        <label>Message
            <input type="text" id="text_voice" value=""></input>
        </label>
    </p>
    <p>
        <label>Volume
            <input type="number" id="volume" value="1"></input>
        </label>
    </p>
    <p>
        <label>Rate
            <input type="number" id="rate" value="1"></input>
        </label>
    </p>
    <p>
        <label>Pitch
            <input type="number" id="pitch" value="1"></input>
        </label>
    </p>
    <p>
        <label>Voice
            <select id="say_voice"></select>
        </label>
    </p>
    <p>
        <button type="button" id="speak">Speak!</button>
    </p>

    </div>
    
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="node_modules/izitoast/dist/js/iziToast.min.js "></script>
    <script src="node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="node_modules/@zxing/library/umd/index.min.js"></script>
    <?php $encrypt_key = "8eaa717fa8c6cec706e1d7baa0c46e50"; ?>

    <script>
      $("#regcode").focus();

      // $("#regcode").on("keyup", function() {
      //   if ($(this).val() != "") {
      //     $("#daftar").prop("disabled", false);
      //   } else {
      //     $("#daftar").prop("disabled", true);
      //   }
      // });

      $("#formRegs").submit(function(event) {
        event.preventDefault();
        $("#daftar").toggleClass("active");
        $.post("./api/ticket/?key=<?php echo $encrypt_key ?>", $(this).serialize()).done(function(data) {
          if (data.status == "success") {
            var bcode = data.data.barcode;
            $.post("./api/attendant/"+bcode+"/?key=<?php echo $encrypt_key ?>", $(this).serialize()).done(function(results){
              // console.log(results.status);
              if(results.status == 'success') {
                var set = data.data;
                $('#text_voice').val('Selamat Datang ' + set.name);
                $("#pagetitle").html('Selamat Datang');
                $("#name").html(set.name);
                $("#type").html(set.kinds);
                if (set.company.length > 25) {
                  $("#company").html(set.company.substring(0, 25) + "...");
                } else {
                  $("#company").html(set.company);
                }
                $("#barcode_id").html(set.barcode);
                // $("#printTicket").modal("show");
                $("#waiting").removeClass("d-none");

                setTimeout(() => {
                  $('#speak').trigger('click');
                  $("#waiting").addClass("d-none");
                  $("#scan-result").removeClass("d-none");
                  $("#regcode").val("");
                }, 1000);
                // setTimeout(() => {
                //   $("#scan-result").addClass("fadeOut");
                // }, 4000);
                setTimeout(() => {
                  // $("#scan-result")
                  //   .removeClass("fadeOut")
                  //   .addClass("d-none");
                  $("#pagetitle").html('Ketik Kode Registrasi Atau');
                  $("#name").html('SCAN BARCODE');
                  $("#type").html('...');
                  $("#company").html('...');
                  $("#barcode_id").html('...');
                }, 5000);
              } else {
                $('#text_voice').val('Mesin absensi gagal mencatat. Mohon laporkan pada panitia.');
                $('#speak').trigger('click');
              }
            });

          } else {
            $('#text_voice').val('Sorry, no payment.');
            $('#speak').trigger('click');
            iziToast.error({
              title: "Kode pendaftaran tidak ditemukan.",
              message: "Mohon periksa kembali.",
              position: "bottomCenter"
            });
            setTimeout(() => {
              $("#waiting, #scan-result").addClass("d-none");
              $("#regcode").val("");
            }, 1000);
          }
          $("#daftar").toggleClass("active");
        });
      });


      $("#speak").on("click", function () {
          var message = new SpeechSynthesisUtterance($("#text_voice").val());
          var voices = speechSynthesis.getVoices();
          console.log(message);
          message['volume'] = 1;
          message['rate'] = 1;
          message['pitch'] = 1.5;
          message['lang'] = 'en-EN';
          message['voice'] = voices[1];
          speechSynthesis.cancel(); 
          speechSynthesis.speak(message);
      });

    </script>

    <script type="text/javascript">
      window.addEventListener("load", function() {
        let selectedDeviceId;
        const codeReader = new ZXing.BrowserMultiFormatReader();
        console.log("ZXing code reader initialized");
        codeReader
          .getVideoInputDevices()
          .then(videoInputDevices => {
            const sourceSelect = document.getElementById("sourceSelect");
            selectedDeviceId = videoInputDevices[0].deviceId;
            if (videoInputDevices.length >= 1) {
              videoInputDevices.forEach(element => {
                const sourceOption = document.createElement("option");
                sourceOption.text = element.label;
                sourceOption.value = element.deviceId;
                sourceSelect.appendChild(sourceOption);
              });

              sourceSelect.onchange = () => {
                selectedDeviceId = sourceSelect.value;
              };

              // const sourceSelectPanel = document.getElementById("sourceSelectPanel");
              // sourceSelectPanel.style.display = "block";
            }

            document.getElementById("startButton").addEventListener("click", () => {
              $("#scanning").removeClass("d-none fadeOut");
              codeReader.decodeFromInputVideoDeviceContinuously(selectedDeviceId, "video", (result, err) => {
                if (result) {
                  console.log(result);
                  $("#regcode")
                    .focus()
                    .val(result.text);
                  $("#formRegs").submit();
                }
                if (err && !(err instanceof ZXing.NotFoundException)) {
                  console.error(err);
                  $("#regcode")
                    .focus()
                    .val(err);
                }
              });
              // console.log(`Started continous decode from camera with id ${selectedDeviceId}`);
              // var e = $.Event("keypress", { keyCode: 13 });
              // $("#regcode").trigger(e);
            });

            document.getElementById("resetButton").addEventListener("click", () => {
              codeReader.reset();
              document.getElementById("regcode").textContent = "";
              $("#scanning").addClass("fadeOut");
              $("#regcode")
                .val("")
                .focus();

              console.log("Reset.");
            });
          })
          .catch(err => {
            console.error(err);
          });
      });

      $("#regcode").on("keypress, keyup", function(e) {
        if (e.which == 13) {
          $("#daftar").trigger("click");
        }
      });
    </script>
  </body>
</html>
