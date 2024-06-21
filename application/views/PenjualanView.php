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
            // Membuat fungsi untuk menghilangkan pesan sukses setelah beberapa detik
            setTimeout(function() {
                var messageElement = document.getElementById('success-message');
                if (messageElement) {
                    messageElement.style.display = 'none';
                }
            }, 5000); // Menghilangkan pesan setelah 5 detik (5000 milidetik)
        </script>
    <?php endif; ?> 

    <div class="container">
    <div class="formjudul"><h3><?php echo $title; ?></h3></div>
    <div class="formisi">
        <div class="left">     
                <select id="filter">
                    <option value="all">All</option>
                    <option value="lunas">Lunas</option>
                    <option selected value="belum-lunas">Belum Lunas</option>
                </select>  
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
                    <tr data-lunas="<?php echo $item->Lunas; ?>">
                        <td><?php echo $item->KdPenjualan; ?></td>
                        <td><?php echo $item->NamaTipePembayaran; ?></td>
                        <td><?php echo $item->NamaPelanggan; ?></td>
                        <td><?php echo $item->NoHp; ?></td>
                        <td><?php echo $item->GrandTotal; ?></td>
                        <td>
                            
                            <div class="actions" <?php if ($item->Lunas == 1) echo 'style="display:none;"'; ?>>
                                <button type="button" class="btn lunas-item-btn btn-lunas" data-id="<?php echo $item->KdPenjualan; ?>">Lunas</button>
                                <button type="button" class="btn view-item-btn btn-view" data-id="<?php echo $item->KdPenjualan; ?>">View</button>
                                <button type="button" class="btn edit-item-btn btn-edit" data-id="<?php echo $item->KdPenjualan; ?>">Edit</button>
                                <button type="button" class="btn delete-item-btn btn-delete" data-id="<?php echo $item->KdPenjualan; ?>">Delete</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table> 

            <div class="buttons" >  
                <button id = "New"  class="btn btn-primary" onclick="newData()">Tambah</button>   
            </div>  
        </div>
    </div>
</div>   
</body>
</html>  
<script> 

    document.addEventListener('DOMContentLoaded', function() {
            const filterDropdown = document.getElementById('filter');
            const rows = document.querySelectorAll('#myTable tbody tr'); 
            filterDropdown.addEventListener('change', function() {
                const filterValue = this.value; 
                rows.forEach(row => {
                    const isLunas = row.getAttribute('data-lunas');
                    if (filterValue === 'all') {
                        row.classList.remove('hidden');
                    } else if (filterValue === 'lunas' && isLunas === '1') {
                        row.classList.remove('hidden');
                    } else if (filterValue === 'belum-lunas' && isLunas === '0') {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            });
        });

   function defaultload() { 
    const filterValue = 'belum-lunas'; 
    const rows = document.querySelectorAll('#myTable tbody tr'); 
    rows.forEach(row => {
        const isLunas = row.getAttribute('data-lunas');
        if (filterValue === 'all') {
            row.classList.remove('hidden');
        } else if (filterValue === 'lunas' && isLunas === '1') {
            row.classList.remove('hidden');
        } else if (filterValue === 'belum-lunas' && isLunas === '0') {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
 }
  $(document).ready(function() {  
    defaultload();
    let table = new DataTable('#myTable', { 
        pageLength: 5,
        "lengthChange": false,
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


    $('.btn-lunas').on('click', function() {
        var id = $(this).data('id');
        var encodedKdPenjualan = encodeURIComponent(id); 
        window.location.href = "Penjualan/CetakNota?Tipe=Lunas&KdPenjualan=" + encodedKdPenjualan;
    
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
                    if (response == "Success") { 
                        location.reload();
                        alert("Berhasil Hapus Transaksi");
                    }
                    else
                    { 
                        alert(response);
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
        .hidden { display: none; }  
        .lunas-item-btn {
            background-color: gray;
            color: #fff;
            border: none; 
            cursor: pointer;
            border-radius: 4px; 
        }
        .lunas-item-btn:hover {
            background-color: orange;
        }
        .view-item-btn {
            background-color: gray;
            color: #fff;
            border: none; 
            cursor: pointer;
            border-radius: 4px; 
        }

        .view-item-btn:hover {
            background-color: green;
        }
        .edit-item-btn {
            background-color: gray;
            color: #fff;
            border: none; 
            cursor: pointer;
            border-radius: 4px; 
        }

        .edit-item-btn:hover {
            background-color: blue;
        }

        .delete-item-btn {
            background-color: gray;
            color: #fff;
            border: none; 
            cursor: pointer;
            border-radius: 4px; 
        }
        .delete-item-btn:hover {
            background-color: red;
        } 
    </style>
