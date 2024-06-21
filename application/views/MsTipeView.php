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
            <table id="myTable">
                <thead>
                    <tr>
                        <th >Kode Tipe</th>
                        <th >Tipe</th> 
                    </tr>
                </thead>
                <tbody>
                   <?php foreach ($Tipe as $item): ?>
                        <tr>
                            <td><?php echo $item->KdTipe; ?></td>
                            <td><?php echo $item->NamaTipe; ?></td>  
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table> 
        </div>
        <div class="right">
            <form id="TipeForm">
                <div class="form-group">
                    <label for="KdTipe">Kode Tipe:</label>
                    <input disabled type="text" id="KdTipe" name="KdTipe" value="<?php echo $AutoNumber; ?>">
                </div>
                <div class="form-group">
                    <label for="NamaTipe">Nama Tipe:</label>
                    <input type="text" id="NamaTipe" name="NamaTipe">
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
        document.getElementById('KdTipe').value = rowData[0];
        document.getElementById('NamaTipe').value = rowData[1];   
        $('#PesanError').text("");
    });


    $('#Edit').on('click', function() { 
      enableButtons();
    });
 
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
    document.getElementById('KdTipe').value = '<?php echo $AutoNumber; ?>' ;
  }  

  function clearData() {  
    emptyData(); 
    enableButtons();
  }

  function cancelData() {
    emptyData();
    disableButtons();
    document.getElementById('KdTipe').value = '<?php echo $AutoNumber; ?>' ;
  }

  function saveData() {
    var data = {
      KdTipe: $('#KdTipe').val(),
      NamaTipe: $('#NamaTipe').val(),  
    };  


    var pesanError = "";

    if ($('#NamaTipe').val() == '') {
        pesanError = pesanError + 'Kolom Nama Tipe harus diisi.'; 
    }   

    if (pesanError != "")
    { 
        $('#PesanError').text(pesanError);
        event.preventDefault();
    } 
    if (  ($('#NamaTipe').val() != '')) 
    {   
        $.ajax({ 
          type: "POST",
          url: "<?php echo base_url('MsTipe/Add'); ?>",
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
      var KdTipe = $('#KdTipe').val();

      $.ajax({ 
          type: "POST",
          url: "<?php echo base_url('MsTipe/Delete'); ?>",
          data: { KdTipe: KdTipe },
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
    $('#NamaTipe').prop('disabled', disable);  
  }
 
 
  function emptyData() { 
    var inputs = document.getElementById('TipeForm').getElementsByTagName('input'); 
    for (var i = 0; i < inputs.length; i++) {
        if (i>0)
        {
            inputs[i].value = '';
        }
    }  
  }

</script>  
