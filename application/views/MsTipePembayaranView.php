<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
</head>
<body>  
<div class="container">
    <div class="formjudul"><h3><?php echo $title; ?></h3></div>
    <div class="formisi">
        <div class="left"> 
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
        <div class="right">
            <form id="TipePembayaranForm">
                <div class="form-group">
                    <label for="KdTipePembayaran">Kode TipePembayaran:</label> 
                    <input disabled type="text" id="KdTipePembayaran" name="KdTipePembayaran" value="<?php echo $AutoNumber; ?>"> 
                </div>
                <div class="form-group">
                    <label for="NamaTipePembayaran">Nama TipePembayaran:</label> 
                    <input type="text" id="NamaTipePembayaran" name="NamaTipePembayaran">  
                </div> 
                <div class="buttons">
                    <button id="New" onclick="newData()">New</button>
                    <button id="Edit">Edit</button>
                    <button disabled id="Save" onclick="saveData()">Save</button>
                    <button disabled id="Clear" onclick="clearData()">Clear</button>
                    <button disabled id="Cancel" onclick="cancelData()">Cancel</button>
                    <button id="Delete">Delete</button>
                </div>
                <span id="PesanError" class="error-message"></span>
            </form>
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
        document.getElementById('KdTipePembayaran').value = rowData[0];
        document.getElementById('NamaTipePembayaran').value = rowData[1]; 
        document.getElementById('NoHp').value = rowData[2];  
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
    enableButtons();
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
        if (i>0)
        {
            inputs[i].value = '';
        }
    }  
  }

</script>  
