<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title> 

</head>
<body> 

    <?php if ($this->session->flashdata('success_message')): ?>
        <div id="success-message" class="success-message" style="color: green; text-align: center;"><b><?php echo $this->session->flashdata('success_message'); ?></b></div>
        <script>
            // Membuat fungsi untuk menghilangkan pesan sukses setelah beberapa detik
            setTimeout(function() {
                var messageElement = document.getElementById('success-message');
                if (messageElement) {
                    messageElement.style.display = 'none';
                }
            }, 5000); // Menghilangkan pesan setelah 5 detik (5000 milidetik)
        </script>
    <?php endif; ?>

    <div class="actionButtons">
        <button id = "New"  class="btn btn-primary " onclick="newData()">Tambah</button>  
    </div> 
  
    <form id="PenjualanForm" class="defaultForm">
 
    <div class="formColumn">
      <table  id="myTable">
          <thead>
              <tr>
                  <th>Kode Penjualan</th> 
                  <th>Tipe Pembayaran</th>
                  <th>Nama Pelanggan</th>
                  <th>No HP</th>
                  <th>Grand Total</th> 
                  <th>Actions</th>
              </tr>
          </thead>
          <tbody> 
            <?php foreach ($Penjualan as $item): ?>
                <tr>
                    <td><?php echo $item->KdPenjualan; ?></td> 
                    <td><?php echo $item->NamaTipePembayaran; ?></td> 
                    <td><?php echo $item->NamaPelanggan ; ?></td>
                    <td><?php echo $item->NoHp; ?></td>
                    <td><?php echo $item->GrandTotal; ?></td>     
                    <td>
                         <table border="0" style="border-collapse: collapse; width: 100%;">
                            <Tr>
                                <td style="border: none;">
                                    <button  type="button" class="btn btn-primary btn-view" data-id="<?php echo $item->KdPenjualan; ?>">
                                        View
                                    </button>
                                </td>
                                <td style="border: none;">
                                    <button  type="button" class="btn btn-warning btn-edit" data-id="<?php echo $item->KdPenjualan; ?>">
                                        Edit
                                    </button> 
                                </td>
                                <td style="border: none;">
                                    <button  type="button" class="btn btn-danger btn-delete" data-id="<?php echo $item->KdPenjualan; ?>">
                                        Delete
                                    </button>
                                </td>
                            </Tr>
                        </table>
                    </td>
                </tr>
            <?php endforeach; ?>
          </tbody>
      </table>
    </div> 
    </form>

    
</body>
</html>  
<script> 
  $(document).ready(function() {  
    let table = new DataTable('#myTable', { 
        pageLength: 5,
    }); 

    $('#myTable tbody').on('click', 'tr', function() { 
        let rowData = table.row(this).data();
        console.log(rowData); 
    }); 
  }); 
   
   function newData() {
     window.location.href = "Penjualan/Add";
 }

    $('.btn-view').on('click', function() {
        var id = $(this).data('id');
        window.location.href = "Penjualan/View?KdPenjualan=" + id;
    });

    // Event listener for the Edit button
    $('.btn-edit').on('click', function() {
        var id = $(this).data('id'); 
        window.location.href = "Penjualan/Edit?KdPenjualan=" + id;
    });

    // Event listener for the Delete button
    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this record?')) { 
            $.ajax({ 
                  type: "POST",
                  url: "<?php echo base_url('Penjualan/Delete'); ?>",
                  data: { KdPenjualan: id },
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
    }); 

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
        }
        th {
            background-color: #f2f2f2;
        }
          
        .defaultForm {
            display: flex;
            position: absolute;
            top: 150px; 
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
        }

        .formColumn input {
            width: 100%;
            padding: 5px;
            margin-bottom: 20px;
        }
    </style>