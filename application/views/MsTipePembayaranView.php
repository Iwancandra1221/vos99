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
        <button id = "Edit" >Edit</button>
        <button id = "Delete" >Delete</button> 
        <span  id="PesanError" class="error-message" ></span> 
    </div>
  
    <form id="TipePembayaranForm" class="defaultForm">
 
    <div class="formColumn">
      <table  id="myTable">
          <thead>
              <tr>
                  <th>Kode TipePembayaran</th>
                  <th>Tipe Pembayaran</th>  
              </tr>
          </thead>
          <tbody> 
            <?php foreach ($TipePembayaran as $item): ?>
                <tr>
                    <td><?php echo $item->KdTipePembayaran; ?></td>
                    <td><?php echo $item->NamaTipePembayaran; ?></td>  
                </tr>
            <?php endforeach; ?>
          </tbody>
      </table>
    </div>

        <div class="formColumn">
            <label for="KdTipePembayaran">Kode TipePembayaran:</label><br>
            <label for="NamaTipePembayaran">Nama TipePembayaran:</label><br>  
        </div>
        <div class="formColumn">
            <input disabled type="text" id="KdTipePembayaran" name="KdTipePembayaran" value="<?php echo $AutoNumber; ?>"><br>
            <input type="text" id="NamaTipePembayaran" name="NamaTipePembayaran"><br>   
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
        document.getElementById('KdTipePembayaran').value = rowData[0];
        document.getElementById('NamaTipePembayaran').value = rowData[1]; 
        document.getElementById('NoHp').value = rowData[2]; 
        document.getElementById('Edit').disabled = false; 
        document.getElementById('Delete').disabled = false;
        $('#PesanError').text("");
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
    $('#New, #Edit, #Delete').prop('disabled', true);
    $('#Save, #Clear, #Cancel').prop('disabled', false);
    toggleFields(false);
}

function disableButtons() {
    $('#New, #Edit, #Delete').prop('disabled', false);
    $('#Save, #Clear, #Cancel').prop('disabled', true);
    toggleFields(true);
}


  function newData() {
    emptyData();
    enableButtons();
    document.getElementById('KdTipePembayaran').value = '<?php echo $AutoNumber; ?>' ;
  }  

  function clearData() {  
    emptyData();
    document.getElementById('KdTipePembayaran').value = '<?php echo $AutoNumber; ?>' ; 
  }

  function cancelData() {
    emptyData();
    disableButtons();
    document.getElementById('KdTipePembayaran').value = '<?php echo $AutoNumber; ?>' ;
  }

  function saveData() {
    var data = {
      KdTipePembayaran: $('#KdTipePembayaran').val(),
      NamaTipePembayaran: $('#NamaTipePembayaran').val(),  
    };  

    var pesanError = "";

    if ($('#NamaTipePembayaran').val() == '') {
        pesanError = pesanError + 'Kolom Tipe Pembayaran harus diisi.'; 
    }   

    if (pesanError != "")
    { 
        $('#PesanError').text(pesanError);
        event.preventDefault();
    } 
    if (  ($('#NamaTipePembayaran').val() != '')) 
    {  
        $.ajax({ 
          type: "POST",
          url: "<?php echo base_url('MsTipePembayaran/Add'); ?>",
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
      var KdTipePembayaran = $('#KdTipePembayaran').val();

      $.ajax({ 
          type: "POST",
          url: "<?php echo base_url('MsTipePembayaran/Delete'); ?>",
          data: { KdTipePembayaran: KdTipePembayaran },
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
    $('#NamaTipePembayaran').prop('disabled', disable);  
  }
 
 
  function emptyData() { 
    var inputs = document.getElementById('TipePembayaranForm').getElementsByTagName('input'); 
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
            top: 150px; 
            left: 20px;
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
        #myTable th {
            background-color: skyblue;
        }

        #myTable tr:nth-child(even) {
            background-color: lightyellow;
        }

        #myTable tr:hover {
            background-color: #f1f1f1;
        }

        .formColumn {
            flex: 1;
            padding-right: 20px; /* Jarak antara kolom */
        }

        .formColumn label {
            display: block;
            margin-bottom: 10px;
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
