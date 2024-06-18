<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title> 

</head>
<body>  
    <div class="actionButtons">
        <button type="button" id = "New" onclick="newData()">New</button>
        <button type="button" disabled id = "Save" onclick="saveData()">Save</button> 
        <button type="button" disabled  id = "Clear" onclick="clearData()">Clear</button> 
        <button type="button" disabled  id = "Cancel" onclick="cancelData()">Cancel</button>
        <button type="button" id = "Edit" >Edit</button>
        <button type="button" id = "Delete" >Delete</button>   
        <span  id="PesanError" class="error-message" ></span> 
    </div>
  
    <form id="StockBarangForm" class="defaultForm">
 
        <div class="formColumn">
          <table  id="myTable">
              <thead>
                  <tr>
                    <th style="width: 20%;">Kode Stock Barang</th>
                    <th style="width: 35%;">Stock Barang</th> 
                    <th style="width: 15%;">Qty</th> 
                    <th style="width: 15%;">Qty Masuk</th> 
                    <th style="width: 15%;">Qty Keluar</th> 
                  </tr>
              </thead>
              <tbody> 
                <?php foreach ($StockBarang as $item): ?>
                    <tr>
                        <td><?php echo $item->KdStockBarang; ?></td>
                        <td><?php echo $item->NamaStockBarang; ?></td>  
                        <td><?php echo $item->Qty_Real; ?></td> 
                        <td><?php echo $item->Qty_In; ?></td> 
                        <td><?php echo $item->Qty_Out; ?></td>  
                    </tr>
                <?php endforeach; ?>
              </tbody>
          </table>
        </div>

        <div class="formColumn">
            <label for="KdStockBarang">Kode Stock Barang:</label><br>
            <label for="NamaStockBarang">Nama Stock Barang:</label><br> 
            <!-- <label for="Qty">Qty:</label><br>   -->
        </div>
        <div class="formColumn">
            <input disabled type="text" id="KdStockBarang" name="KdStockBarang" value="<?php echo $AutoNumber; ?>"><br>
            <input type="text" id="NamaStockBarang" name="NamaStockBarang"><br>  
            <!-- <input type="number" id="Qty" name="Qty" min="0" step="1" ><br>  -->
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
        document.getElementById('KdStockBarang').value = rowData[0];
        document.getElementById('NamaStockBarang').value = rowData[1];  
        // document.getElementById('Qty').value = rowData[2]; 
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
    document.getElementById('KdStockBarang').value = '<?php echo $AutoNumber; ?>' ;
  }  

  function clearData() {  
    emptyData();
    document.getElementById('KdStockBarang').value = '<?php echo $AutoNumber; ?>' ; 
  }

  function cancelData() {
    emptyData();
    disableButtons();
    document.getElementById('KdStockBarang').value = '<?php echo $AutoNumber; ?>' ;
  }

  function saveData() {
    var data = {
      KdStockBarang: $('#KdStockBarang').val(),
      NamaStockBarang: $('#NamaStockBarang').val(),  
      // Qty: $('#Qty').val(),  
    };  


    var pesanError = "";

    if ($('#NamaStockBarang').val() == '') {
        pesanError = pesanError + 'Kolom Nama StockBarang harus diisi.'; 
    }   

    if (pesanError != "")
    { 
        $('#PesanError').text(pesanError);
        event.preventDefault();
    } 
    if (  ($('#NamaStockBarang').val() != '')) 
    {   
        $.ajax({ 
          type: "POST",
          url: "<?php echo base_url('MsStockBarang/Add'); ?>",
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
      var KdStockBarang = $('#KdStockBarang').val();

      $.ajax({ 
          type: "POST",
          url: "<?php echo base_url('MsStockBarang/Delete'); ?>",
          data: { KdStockBarang: KdStockBarang },
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
    $('#NamaStockBarang').prop('disabled', disable);  
    // $('#Qty').prop('disabled', disable);  
  }
 
 
  function emptyData() { 
    var inputs = document.getElementById('StockBarangForm').getElementsByTagName('input'); 
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
        #myTable th {
            background-color: skyblue;
        }

        #myTable tr:nth-child(even) {
            background-color: lightyellow;
        }

        #myTable tr:hover {
            background-color: #f1f1f1;
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
            background-color: #d3d3d3; /* StockBarang tombol yang dinonaktifkan */
            color: #808080; /* StockBarang teks tombol yang dinonaktifkan */
            cursor: not-allowed; /* Ubah kursor menjadi tanda tidak diperbolehkan */
        }

        .actionButtons button:disabled:hover {
            background-color: #d3d3d3; /* Pastikan StockBarang hover tidak berubah */
            color: #808080; /* Pastikan StockBarang teks hover tidak berubah */
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
