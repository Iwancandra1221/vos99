
<!DOCTYPE html>
<html lang="en">
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
</head>
<body> 

    <div class="container">
    <div class="formjudul2"><h3><?php echo $title; ?></h3></div>
    <div class="formisi2"> 
        <div >
            <form id="SingnatureForm">
                <div class="form-group"> 
                    <div class="center-image-container">
                        <img src="<?php echo $listTandaTangan; ?>" alt="Tanda Tangan">
                    </div>
                    </br>  
                </div> 
                <div class="buttons"  style="margin-bottom: 20px;" > 
                    <button type="button" onclick="openSignaturePopup()"><b>Tulis Tandan Tangan</b></button> 
                </div>
                <span id="PesanError" class="error-message"></span>
            </form>
        </div>
    </div>
</div> 

<div id="signaturePopup">
    <div class="formjudul3" ><h3>Tulis Tanda Tangan Di Sini </h3></div> 
    <div class="formisi3">  
            <form id="SingnatureForm">
                <div class="form-group"> 
                    <div class="center-image-container"> 
                        <canvas id="signatureCanvas" width="400" height="200"></canvas>
                    </div>
                    </br>  
                </div> 
                <div class="buttons" style=" padding:20px;" > 
                    <button type="button" onclick="saveSignature()">Simpan</button>
                    <button type="button" onclick="clearCanvas()">Clear</button>
                    <button type="button" onclick="closeSignaturePopup()">Keluar</button>
                </div> 
            </form> 
    </div> 
</div> 
 
</body>
</html>  
<script>  
        var signaturePad;

        function openSignaturePopup() {
            signaturePad = new SignaturePad(document.getElementById('signatureCanvas'));
            document.getElementById('signaturePopup').style.display = 'block';
        }

        function closeSignaturePopup() {
            document.getElementById('signaturePopup').style.display = 'none';
            signaturePad.clear();
        }

        function clearCanvas() {
            signaturePad.clear();
        }

        function saveSignature() {
            var signatureData = signaturePad.toDataURL(); 
            var formData = new FormData();
            formData.append('signature_data', signatureData);  
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('SignatureController/save_signature'); ?>",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == "Success") {
                        alert('Tanda Tangan Berhasil di Update!');
                        location.reload();
                    } else {
                        alert(response);
                    }
                    closeSignaturePopup();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    closeSignaturePopup();
                }
            });
        }
</script> 

    <style>

        .formjudul3 {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 100%;
            background-color: #2b2d42;
            color: white; 
        }
        .formisi3 {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 100%;
            border: 1px solid #ccc;  
            background-color: #fff;  
        }

        .formjudul2 {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 70%;
            background-color: #2b2d42;
            color: white; 
            border-top-left-radius: 20px; /* lengkungan bawah kiri */
            border-top-right-radius: 20px; /* lengkungan bawah kanan */
        }

        .formisi2 {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 70%;
            background-color: #fff;
            border: 1px solid #ccc;  
            border-bottom-left-radius: 20px; /* lengkungan bawah kiri */
            border-bottom-right-radius: 20px; /* lengkungan bawah kanan */
        }

        .center-title-container { 
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .center-image-container {
            background-color: transparent; 
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .center-image-container img {
            max-width: 100%;
            height: auto;
        }

        #signaturePopup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            border: 1px solid #ccc; 
            z-index: 9999;
        }
        
    </style>
