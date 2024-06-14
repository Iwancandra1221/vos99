<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>  
</head>
<body> 
    <div class="actionButtons">
        <button id = "New" onclick="newData()">New</button>
        <button disabled id = "Save" onclick="saveData()">Save</button> 
        <button disabled  id = "Clear" onclick="clearData()">Clear</button> 
        <button disabled  id = "Cancel" onclick="cancelData()">Cancel</button>
        <button id = "Duplicate" >Duplicate</button>
        <button id = "Edit" >Edit</button>
        <button id = "Delete" >Delete</button> 
        <span  id="PesanError" class="error-message" ></span> 
    <form id="barangForm" class="defaultForm">
 
    <div class="formColumn">
      <table  id="myTable">
          <thead>
              <tr>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Qty</th>
                  <th>Harga</th>
                  <th>Harga Jual</th>
                  <th>Merk</th>
                  <th>Tipe</th>
                  <th>Warna</th> 
                  <th hidden>KdWarna</th> 
              </tr>
          </thead>
          <tbody> 
            <?php foreach ($barang as $item): ?>
                <tr>
                    <td><?php echo $item->KdBarang; ?></td>
                    <td><?php echo $item->NamaBarang; ?></td>
                    <td><?php echo $item->Qty; ?></td>
                    <td><?php echo $item->Harga; ?></td>
                    <td><?php echo $item->Harga_Jual; ?></td>
                    <td><?php echo $item->Merk; ?></td>
                    <td><?php echo $item->Tipe; ?></td>
                    <td><?php echo $item->Warna; ?></td> 
                    <td hidden><?php echo $item->KdWarna; ?></td> 
                </tr>
            <?php endforeach; ?>
          </tbody>
      </table>
    </div> 
        <div class="formColumn">
            <label for="KdBarang">Kode Barang:</label><br>
            <label for="NamaBarang">Nama Barang:</label><br>
            <label for="Qty">Qty:</label><br>
            <label for="Harga">Harga:</label><br>
            <label for="Harga_Jual">Harga Jual:</label><br>
            <label for="Merk">Merk:</label><br>
            <label for="Tipe">Tipe:</label><br>
            <label for="Warna">Warna:</label> 
        </div>
        <div class="formColumn">
            <input disabled type="text" id="KdBarang" name="KdBarang" value="<?php echo $AutoNumber; ?>"><br>
            <input type="text" id="NamaBarang" name="NamaBarang"><br>
            <input type="number" id="Qty" name="Qty" min="0" step="1" ><br>
            <input type="number" id="Harga" name="Harga" min="0" step="1" ><br>
            <input type="number" id="Harga_Jual" name="Harga_Jual" min="0" step="1" ><br> 
            <select id="Merk" name="Merk">
                <option value="">Pilih Merk</option>
                <?php foreach ($listMerk as $Merk) { ?>
                    <option value="<?php echo $Merk->KdMerk; ?>"><?php echo $Merk->NamaMerk; ?>
                    </option>
                <?php } ?>
            </select><br><br>  
            <select id="Tipe" name="Tipe">
                <option value="">Pilih Tipe</option>
                <?php foreach ($listTipe as $Tipe) { ?>
                    <option value="<?php echo $Tipe->KdTipe; ?>"><?php echo $Tipe->NamaTipe; ?>
                    </option>
                <?php } ?>
            </select><br><br>  
            <select id="Warna" name="Warna">
                <option value="">Pilih Warna</option>
                <?php foreach ($listWarna as $warna) { ?>
                    <option value="<?php echo $warna->KdWarna; ?>"><?php echo $warna->NamaWarna; ?>
                    </option>
                <?php } ?>
            </select> 
        </div>
    </form>

    
</body>
</html>  
<script> 
  $(document).ready(function() { 
    disableButtons();
    let table = new DataTable('#myTable', { 
        pageLength: 5,
        "lengthChange": false,
    }); 

    $('#myTable tbody').on('click', 'tr', function() {
        // Mendapatkan data dari baris yang diklik
        let rowData = table.row(this).data();
        console.log(rowData);
        // Mengisi textbox dengan data dari baris
        document.getElementById('KdBarang').value = rowData[0];
        document.getElementById('NamaBarang').value = rowData[1];
        document.getElementById('Qty').value = rowData[2];
        document.getElementById('Harga').value = rowData[3];
        document.getElementById('Harga_Jual').value = rowData[4];
        document.getElementById('Merk').value = rowData[5];;
        document.getElementById('Tipe').value = rowData[6];
        document.getElementById('Warna').value = rowData[8]; 
        document.getElementById('Duplicate').disabled = false; 
        document.getElementById('Edit').disabled = false; 
        document.getElementById('Delete').disabled = false;

        $('#PesanError').text("");
    });

    $('#Duplicate').on('click', function() { 
      enableButtons();
      duplicate();
    });


    $('#Edit').on('click', function() { 
      enableButtons();
    });

    // Menambahkan event listener untuk tombol hapus
    $('#Delete').on('click', function() {
        deleteData();
    });
  }); 
  
function enableButtons() {
    $('#New, #Edit, #Duplicate, #Delete').prop('disabled', true);
    $('#Save, #Clear, #Cancel').prop('disabled', false);
    toggleFields(false);
}

function disableButtons() {
    $('#New, #Edit, #Duplicate, #Delete').prop('disabled', false);
    $('#Save, #Clear, #Cancel').prop('disabled', true);
    toggleFields(true);
}  

function duplicate() { 
    document.getElementById('KdBarang').value = '<?php echo $AutoNumber; ?>' ;
  } 


  function newData() {
    emptyData();
    enableButtons();
    document.getElementById('KdBarang').value = '<?php echo $AutoNumber; ?>' ;
  }  

  function clearData() {  
    emptyData();
    document.getElementById('KdBarang').value = '<?php echo $AutoNumber; ?>' ; 
  }

  function cancelData() {
    emptyData();
    disableButtons();
    document.getElementById('KdBarang').value = '<?php echo $AutoNumber; ?>' ;
  }

  function saveData() {
    var data = {
      KdBarang: $('#KdBarang').val(),
      NamaBarang: $('#NamaBarang').val(),
      Qty: $('#Qty').val(),
      Harga: $('#Harga').val(),
      Harga_Jual: $('#Harga_Jual').val(),
      Merk: $('#Merk').val(),
      Tipe: $('#Tipe').val(),
      Warna: $('#Warna').val()
    };  

    var pesanError = "";

    if ($('#NamaBarang').val() == '') {
        pesanError = pesanError + 'Kolom Nama Barang harus diisi, '; 
    } 

    if ($('#Qty').val() == '') { 
        pesanError = pesanError + 'Kolom Qty harus diisi, '; 
    } 

    if ($('#Harga').val() == '') {
        pesanError = pesanError + 'Kolom Harga harus diisi, ';  
        event.preventDefault();
    } 
    if ($('#Harga_Jual').val() == '') { 
        pesanError = pesanError + 'Kolom Harga Jual harus diisi, '; 
        event.preventDefault();
    } 

    if ($('#Warna').val() == '') {
        pesanError = pesanError + 'Kolom Warna harus diisi.'; 
        event.preventDefault();
    }  
    if (pesanError != "")
    { 
        $('#PesanError').text(pesanError);
        event.preventDefault();
    }


    if ( 
        ($('#NamaBarang').val() != '') 
        && ($('#Qty').val() != '') 
        && ($('#Harga').val() != '') 
        && ($('#Harga_Jual').val() != '') 
        && ($('#Warna').val() != '') 
        ) 
    {
        $.ajax({ 
          type: "POST",
          url: "<?php echo base_url('MsBarang/Add'); ?>",
          data: data,
          success: function(response) {
            alert(response);
            if (response = "Success")
            {  
              emptyData();
              location.reload();
            }
          },
          error: function(xhr, status, error) {
            console.log(xhr.responseText);
          }
        }); 
    }

  } 
   
  function deleteData() {
      var KdBarang = $('#KdBarang').val();

      $.ajax({ 
          type: "POST",
          url: "<?php echo base_url('MsBarang/Delete'); ?>",
          data: { KdBarang: KdBarang },
          success: function(response) {
              alert(response);
              if (response == "Success") {
                  clearData();
                  location.reload();
              }
          },
          error: function(xhr, status, error) {
              console.log(xhr.responseText);
          }
      });
  }

  function toggleFields(disable) {
    $('#NamaBarang').prop('disabled', disable);
    $('#Qty').prop('disabled', disable);
    $('#Harga').prop('disabled', disable);
    $('#Harga_Jual').prop('disabled', disable);
    $('#Merk').prop('disabled', disable);
    $('#Tipe').prop('disabled', disable);
    $('#Warna').prop('disabled', disable);
  }
 
 
  function emptyData() { 
    var inputs = document.getElementById('barangForm').getElementsByTagName('input'); 
    for (var i = 0; i < inputs.length; i++) {
      inputs[i].value = '';
    }  
  }

</script> 
    <style>        
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            white-space: nowrap; 
        }
        th {
            background-color: #f2f2f2;
        }
          
        .defaultForm {
            display: flex;
            position: absolute;
            top: 80px;  
            background-color: white;
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

        .error-message {
            color: red;
            font-size: 12px;
        }
    </style>
