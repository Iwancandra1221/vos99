
<!DOCTYPE html>
<html lang="en">
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>  
</head>
<body> 

    <form id="WarnaForm" class="defaultForm"> 
        <div class="formColumn"> 
            <h2>Electronic Signature Popup</h2>
            </br></br>
            <div style="background-color: skyblue; padding: 50px;">
                <img src="<?php echo $listTandaTangan; ?>" style="max-width: 100%; height: auto;">
            </div> 
            </br></br>
            <button  type="button" onclick="openSignaturePopup()"><b>Tulis Tandan Tangan</b></button> 
            <div id="signaturePopup">
                <h3>Sign Below:</h3>
                <canvas id="signatureCanvas" width="400" height="200"></canvas>
                <br>
                <button  type="button" onclick="clearCanvas()">Clear</button>
                <button  type="button" onclick="saveSignature()">Save Signature</button>
                <button  type="button" onclick="closeSignaturePopup()">Close</button>
            </div> 
        </div>
    </form>
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
                        alert('Signature saved!');
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
        #signaturePopup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            z-index: 9999;
        }

        #signatureCanvas {
            border: 1px solid #000;
        }        
         
          
        .defaultForm {
            display: flex;
            position: absolute;
            top: 80px;  
            left: 20px;  
            background-color: transparent;
            color: black;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        } 

        .actionButtons {
            position: absolute; 
            top: 70px; 
            left: 20px;
            display: flex;
            gap: 10px;
        }

        .actionButtons button {
            padding: 15px;
            border: none;
            background-color: silver;
            color: black; 
            cursor: pointer;
            border-radius: 5px;
            outline: none;
        }

        .actionButtons button:hover {
            background-color: #0056b3;
            color: white; 
        } 

        .actionButtons button:disabled {
            background-color: #d3d3d3; /* Warna tombol yang dinonaktifkan */
            color: #808080; /* Warna teks tombol yang dinonaktifkan */
            cursor: not-allowed; /* Ubah kursor menjadi tanda tidak diperbolehkan */
        }

        .actionButtons button:disabled:hover {
            background-color: #d3d3d3; /* Pastikan warna hover tidak berubah */
            color: #808080; /* Pastikan warna teks hover tidak berubah */
        }

        .formColumn {
            flex: 1;
            padding-right: 20px; /* Jarak antara kolom */
        }

        .formColumn label {
            display: block;
            margin-bottom: 10px;
            white-space: nowrap; 
        }

        .formColumn input {
            width: 100%;
            padding: 5px;
            margin-bottom: 20px;
        } 
    </style>
