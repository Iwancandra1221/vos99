<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
</head>
<body> 

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
        <div class="left hideOnMobile"> 
          <table id="myTable">
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
                    </tr>
                <?php endforeach; ?>
              </tbody>
          </table>
          
                <div class="buttons">
                    <button id="New" onclick="newData()">New</button>
                    <button id="Edit">Edit</button>
                    <button disabled id="Save" onclick="saveData()">Save</button>
                    <button disabled id="Clear" onclick="clearData()">Clear</button>
                    <button disabled id="Cancel" onclick="cancelData()">Cancel</button>
                    <button id = "Duplicate" >Duplicate</button>
                    <button id="Delete">Delete</button>
                </div>
                <span id="PesanError" class="error-message"></span>
        </div>

        <div class="left colMobile"> 
          <table id="myTable">
              <thead>
                  <tr>
                      <th>Barang</th>
                      <th>Qty</th>
                  </tr>
              </thead>
              <tbody> 
                <?php foreach ($barang as $item): ?>
                    <tr>
                        <td>
                            <?php 
                                echo $item->NamaBarang.'<br>'.$item->Merk.'<br>'.$item->Tipe.'<br>'.$item->Warna; 
                            ?> 
                        </td> 
                        <td>
                            <?php 
                                echo $item->Qty.'<br>'.$item->Harga.'<br>'.$item->Harga_Jual; 
                            ?> 
                        </td> 
                    </tr>
                <?php endforeach; ?>
              </tbody>
          </table>
          
                <div class="buttons">
                    <button id="New" onclick="newData()">New</button>
                    <button id="Edit">Edit</button>
                    <button disabled id="Save" onclick="saveData()">Save</button>
                    <button disabled id="Clear" onclick="clearData()">Clear</button>
                    <button disabled id="Cancel" onclick="cancelData()">Cancel</button>
                    <button id = "Duplicate" >Duplicate</button>
                    <button id="Delete">Delete</button>
                </div>
                <span id="PesanError" class="error-message"></span>
        </div>

        <div class="right hideOnMobile">
            <form id="BarangForm">
                <div class="form-group "> 
                    <label for="KdBarang">Kode Barang:</label> 
                    <input disabled type="text" id="KdBarang" name="KdBarang" value="<?php echo $AutoNumber; ?>"> 
                </div>
                <div class="form-group"> 
                    <label for="NamaBarang">Nama Barang:</label> 
                    <input type="text" id="NamaBarang" name="NamaBarang"> 
                </div> 
                <div class="form-group"> 
                    <label for="Qty">Qty:</label> 
                    <input type="number" id="Qty" name="Qty" min="0" step="1" > 
                </div> 
                <div class="form-group"> 
                    <label for="Harga">Harga:</label> 
                    <input type="number" id="Harga" name="Harga" min="0" step="1" > 
                </div> 
                <div class="form-group"> 
                    <label for="Harga_Jual">Harga Jual:</label> 
                    <input type="number" id="Harga_Jual" name="Harga_Jual" min="0" step="1" > 
                </div> 
                <div class="form-group"> 
                    <label for="Merk">Merk:</label> 
                    <select id="Merk" name="Merk">
                        <option value="">Pilih Merk</option>
                        <?php foreach ($listMerk as $Merk) { ?>
                            <option value="<?php echo $Merk->KdMerk; ?>"><?php echo $Merk->NamaMerk; ?>
                            </option>
                        <?php } ?>
                    </select>   
                </div> 
                <div class="form-group"> 
                    <label for="Tipe">Tipe:</label> 
                    <select id="Tipe" name="Tipe">
                        <option value="">Pilih Tipe</option>
                        <?php foreach ($listTipe as $Tipe) { ?>
                            <option value="<?php echo $Tipe->KdTipe; ?>"><?php echo $Tipe->NamaTipe; ?>
                            </option>
                        <?php } ?>
                    </select>   
                </div> 
                <div class="form-group"> 
                    <label for="Warna">Warna:</label> 
                    <select id="Warna" name="Warna">
                        <option value="">Pilih Warna</option>
                        <?php foreach ($listWarna as $warna) { ?>
                            <option value="<?php echo $warna->KdWarna; ?>"><?php echo $warna->NamaWarna; ?>
                            </option>
                        <?php } ?>
                    </select> 
                </div> 
            </form>
        </div>
    </div>
</div> 
</body>
</html>  

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
        document.getElementById('KdBarang').value = rowData[0];
        document.getElementById('NamaBarang').value = rowData[1];
        document.getElementById('Qty').value = rowData[2];
        document.getElementById('Harga').value = rowData[3];
        document.getElementById('Harga_Jual').value = rowData[4];   
  
        var bool = false;
        var MerkSelect = document.getElementById('Merk');
        var newTextMerk = rowData[5];
        for (var i = 0; i < MerkSelect.options.length; i++) {
            console.log(MerkSelect.options[i].text,newTextMerk);
            if (MerkSelect.options[i].text === newTextMerk) { 
                MerkSelect.selectedIndex = i;
                bool = true;
                break; 
            }
        }   

        if (!bool)
        {
            MerkSelect.selectedIndex = 0;
        }

        var TipeSelect = document.getElementById('Tipe');
        var newTextTipe = rowData[6];
        for (var i = 0; i < TipeSelect.options.length; i++) {
            console.log(TipeSelect.options[i].text,newTextTipe);
            if (TipeSelect.options[i].text === newTextTipe) { 
                TipeSelect.selectedIndex = i;
                bool = true;
                break; 
            }
        }  

        if (!bool)
        {
            TipeSelect.selectedIndex = 0;
        }

        var WarnaSelect = document.getElementById('Warna');
        var newTextWarna = rowData[7];
        for (var i = 0; i < WarnaSelect.options.length; i++) {
            console.log(WarnaSelect.options[i].text,newTextWarna);
            if (WarnaSelect.options[i].text === newTextWarna) { 
                WarnaSelect.selectedIndex = i;
                bool = true;
                break; 
            }
        }  

        if (!bool)
        {
            WarnaSelect.selectedIndex = 0;
        }

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
    enableButtons();
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
    var inputs = document.getElementById('BarangForm').getElementsByTagName('input'); 
    for (var i = 0; i < inputs.length; i++) {
        if (i>0)
        {
            inputs[i].value = '';
        }
    }  
  }

</script>  

 
