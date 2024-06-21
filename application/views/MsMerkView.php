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
                    <th style="width: 30%;">Kode Merk</th>
                    <th style="width: 60%;">Merk</th> 
                  </tr>
              </thead>
              <tbody> 
                <?php foreach ($Merk as $item): ?>
                    <tr>
                        <td><?php echo $item->KdMerk; ?></td>
                        <td><?php echo $item->NamaMerk; ?></td>  
                    </tr>
                <?php endforeach; ?>
              </tbody>
          </table>
        </div>
        <div class="right">
            <form id="MerkForm">
                <div class="form-group">
                    <label for="KdMerk">Kode Merk:</label><br>
                    <input disabled type="text" id="KdMerk" name="KdMerk" value="<?php echo $AutoNumber; ?>"><br>
                </div>
                <div class="form-group">
                    <label for="NamaMerk">Nama Merk:</label><br>  
                    <input type="text" id="NamaMerk" name="NamaMerk"><br>   
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
        document.getElementById('KdMerk').value = rowData[0];
        document.getElementById('NamaMerk').value = rowData[1];   
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
    document.getElementById('KdMerk').value = '<?php echo $AutoNumber; ?>' ;
  }  

  function clearData() {  
    emptyData(); 
    enableButtons();
  }

  function cancelData() {
    emptyData();
    disableButtons();
    document.getElementById('KdMerk').value = '<?php echo $AutoNumber; ?>' ;
  }

  function saveData() {
    var data = {
      KdMerk: $('#KdMerk').val(),
      NamaMerk: $('#NamaMerk').val(),  
    };  


    var pesanError = "";

    if ($('#NamaMerk').val() == '') {
        pesanError = pesanError + 'Kolom Nama Merk harus diisi.'; 
    }   

    if (pesanError != "")
    { 
        $('#PesanError').text(pesanError);
        event.preventDefault();
    } 
    if (  ($('#NamaMerk').val() != '')) 
    {   
        $.ajax({ 
          type: "POST",
          url: "<?php echo base_url('MsMerk/Add'); ?>",
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
      var KdMerk = $('#KdMerk').val();

      $.ajax({ 
          type: "POST",
          url: "<?php echo base_url('MsMerk/Delete'); ?>",
          data: { KdMerk: KdMerk },
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
    $('#NamaMerk').prop('disabled', disable);  
  }
 
 
  function emptyData() { 
    var inputs = document.getElementById('MerkForm').getElementsByTagName('input'); 
    for (var i = 0; i < inputs.length; i++) {
        if (i>0)
        {
            inputs[i].value = '';
        }
    }  
  }

</script>  
