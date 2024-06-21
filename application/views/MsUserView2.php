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
                        <th>Kode User</th>
                        <th>Nama User</th>
                        <th>Password</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($User as $item): ?>
                        <tr>
                            <td><?php echo $item->KdUser; ?></td>
                            <td><?php echo $item->NamaUser; ?></td>
                            <td><?php echo $item->Password; ?></td>
                            <td><?php echo $item->Role; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="right">
            <form id="UserForm">
                <div class="form-group">
                    <label for="KdUser">Kode User:</label>
                    <input disabled type="text" id="KdUser" name="KdUser" value="<?php echo $AutoNumber; ?>">
                </div>
                <div class="form-group">
                    <label for="NamaUser">Nama User:</label>
                    <input type="text" id="NamaUser" name="NamaUser">
                </div>
                <div class="form-group">
                    <label for="Password">Password:</label>
                    <input type="password" id="Password" name="Password">
                </div>
                <div class="form-group">
                    <label for="Role">Role:</label>
                    <select id="Role" name="Role">
                        <option value="">Pilih Role</option>
                        <option value="Admin">Admin</option>
                        <option value="User">User</option>
                    </select>
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
        // Mendapatkan data dari baris yang diklik
        let rowData = table.row(this).data();
        console.log(rowData);
        // Mengisi textbox dengan data dari baris
        document.getElementById('KdUser').value = rowData[0];
        document.getElementById('NamaUser').value = rowData[1];
        document.getElementById('Password').value = rowData[2];
        document.getElementById('Role').value = rowData[3]; 
        document.getElementById('Edit').disabled = false; 
        document.getElementById('Delete').disabled = false; 
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
    document.getElementById('KdUser').value = '<?php echo $AutoNumber; ?>' ;
  }  

  function clearData() {  
    emptyData();
    document.getElementById('KdUser').value = '<?php echo $AutoNumber; ?>' ; 
  }

  function cancelData() {
    emptyData();
    disableButtons();
    document.getElementById('KdUser').value = '<?php echo $AutoNumber; ?>' ;
  }

  function saveData() {
    var data = {
      KdUser: $('#KdUser').val(),
      NamaUser: $('#NamaUser').val(),
      Password: $('#Password').val(),
      Role: $('#Role').val(), 
    };  


    var pesanError = "";

    if ($('#NamaUser').val() == '') {
        pesanError = pesanError + 'Kolom Nama User harus diisi, '; 
    }  
    
    if ($('#Password').val() == '') {
        pesanError = pesanError + 'Kolom Password harus diisi, '; 
    } 
    
    if ($('#Role').val() == '') {
        pesanError = pesanError + 'Kolom Role harus diisi, '; 
    } 

    if (pesanError != "")
    { 
        $('#PesanError').text(pesanError);
        event.preventDefault();
    } 
    if (  ($('#NamaUser').val() != '') && ($('#Password').val() != '') && ($('#Role').val() != '') ) 
    { 
        $.ajax({ 
          type: "POST",
          url: "<?php echo base_url('MsUser/Add'); ?>",
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
      var KdUser = $('#KdUser').val();

      $.ajax({ 
          type: "POST",
          url: "<?php echo base_url('MsUser/Delete'); ?>",
          data: { KdUser: KdUser },
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
    $('#NamaUser').prop('disabled', disable);
    $('#Password').prop('disabled', disable);
    $('#Role').prop('disabled', disable); 
  }
 
 
  function emptyData() { 
    var inputs = document.getElementById('UserForm').getElementsByTagName('input'); 
    for (var i = 0; i < inputs.length; i++) {
      inputs[i].value = '';
    }  
  }
</script>  
