<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
</head>
<body>   
    <?php if ($this->session->flashdata('success_message')): ?>
        <div id="success-message" class="success-message" style="color: green; text-align: center;"><b><?php echo $this->session->flashdata('success_message'); ?></b></div>
        <script> 
            setTimeout(function() {
                var messageElement = document.getElementById('success-message');
                if (messageElement) {
                    messageElement.style.display = 'none';
                }
            }, 5000); 
        </script>
    <?php endif; ?>

<style> 
        .container2 { 
            padding: 70px; 
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start; 
        }
</style>

<div class="container2">
    <div class="formjudul"><h3><?php echo $title; ?></h3></div>
    <div class="formisi">
        <div class="left"> 
          <table  id="myTable">
              <thead>
                  <tr>
                    <th style="width: 20%;">Kode Barang</th>
                    <th style="width: 35%;">Nama Barang</th> 
                    <th style="width: 15%;">Qty Sisa</th> 
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
        <div class="right">
            <form id="StockBarangForm">
                <div class="form-group">
            <label for="KdStockBarang">Kode Barang:</label> 
            <input disabled type="text" id="KdStockBarang" name="KdStockBarang" value="<?php echo $AutoNumber; ?>">
                </div>
                <div class="form-group">
            <label for="NamaStockBarang">Nama Barang:</label> 
            <input type="text" id="NamaStockBarang" name="NamaStockBarang">
                </div> 
                <div class="buttons">
                    <button id="New" onclick="newData()">New</button>
                    <button id="Edit">Edit</button>
                    <button disabled id="Save" onclick="saveData()">Save</button>
                    <button disabled id="Clear" onclick="clearData()">Clear</button>
                    <button disabled id="Cancel" onclick="cancelData()">Cancel</button>
                    <button id="Delete">Delete</button>
                    <button type="button" id="openPopup">Masuk Keluar Barang</button>
                </div>
                <span id="PesanError" class="error-message"></span>
            </form>
        </div>
    </div>
</div>   
</body>
</html>  

<div id="popupForm" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <div class="formjudul3" ><h2>Input Masuk / Keluar Barang</h2></div> 
            <div class="formisi3">   
                <form id="transaksiForm" style="width:100%;" action="<?php echo base_url('MsStockBarang/tambah_transaksi'); ?>" method="post">
                    <div class="form-group">
                        <label for="KdStockBarangTrans" style="color: black;">Nama Barang:</label>
                        <select id="KdStockBarangTrans" name="KdStockBarangTrans" required>
                            <option value="">Pilih Barang</option>
                            <?php foreach ($StockBarang as $Barang) { ?>
                                <option value="<?php echo $Barang->KdStockBarang; ?>" data-qty="<?php echo $Barang->Qty_Real; ?>">
                                    <?php echo $Barang->NamaStockBarang; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Qty Sisa:</label>
                        <label id="qtyLabel" style="color: green;"></label>
                    </div>
                    <div class="form-group">
                        <label for="transaksi_type" style="color: black;">Jenis Transaksi:</label>
                        <select id="transaksi_type" name="transaksi_type" required>
                            <option value="in">Barang Masuk</option>
                            <option value="out">Barang Keluar</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah" style="color: black;">Jumlah:</label>
                        <input type="number" id="jumlah" name="jumlah" placeholder="Masukkan Jumlah" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan" style="color: black;">Keterangan:</label>
                        <input type="text" id="keterangan" name="keterangan" placeholder="Masukkan Keterangan" required>
                    </div>
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">SAVE</button>
                    </div>
                </form>
            </div> 
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('openPopup').addEventListener('click', function() {
                document.getElementById('popupForm').style.display = 'block';
            });

            document.querySelector('.close').addEventListener('click', function() {
                document.getElementById('popupForm').style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                var popup = document.getElementById('popupForm');
                if (event.target === popup) {
                    popup.style.display = 'none';
                }
            });

            document.getElementById('KdStockBarangTrans').addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                var qty = selectedOption.getAttribute('data-qty');
                document.getElementById('qtyLabel').textContent = qty;
            });
        });
    </script>
   
    <style>

        .form-group {
            padding: 20px;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-group label { 
            flex: 2; 
            text-align: left;
        }
        .form-group select,
        .form-group input {
            flex: 2;
        } 
        .formjudul3 {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 100%;
            background-color: #fff; 
        }
        .formisi3 {
            display: flex; 
            justify-content: center;
            align-items: flex-start;
            width: 100%;
            border: 1px solid #ccc;  
            background-color: #fff;  
        } 

        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .popup-content {
            position: relative;
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        } 
    </style> 
<script>  

  $(document).ready(function() {   
    disableButtons();
    let table = new DataTable('#myTable', { 
        pageLength: 10,
        "lengthChange": false,
    }); 

    $('#myTable tbody').on('click', 'tr', function() {
        cancelData() 
        let rowData = table.row(this).data();
        console.log(rowData);  
        document.getElementById('KdStockBarang').value = rowData[0];
        document.getElementById('NamaStockBarang').value = rowData[1];   
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
    enableButtons();
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
        if (i>0)
        {
            inputs[i].value = '';
        }
    }  
  }
 

</script>  