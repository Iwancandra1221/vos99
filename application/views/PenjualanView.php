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
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 50px;">
            <?php echo $this->session->flashdata('success_message'); ?> 
        </div>
        <script> 
            setTimeout(function(){
                $('.alert').alert('close');
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
    <div class="container2" >
    <div class="formjudul"><h3><?php echo $title; ?></h3></div>
    <div class="formisi">
        <div class="left2">     
            <select id="filter">
                    <option value="all">All</option>
                    <option value="lunas">Lunas</option>
                    <option selected value="belum-lunas">Belum Lunas</option>
            </select>  
            <table  id="myTable">
                <thead>
                    <tr>
                        <th>Kode Penjualan</th> 
                        <th>Tanggal Nota</th> 
                        <th>Tipe Pembayaran</th>
                        <th>Nama Pelanggan</th>
                        <th>No HP</th>
                        <th>Grand Total</th> 
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody> 
                <?php foreach ($Penjualan as $item): ?> 
                    <?php if ($item->Lunas == 0) { ?>
                        <tr data-lunas="<?php echo $item->Lunas; ?>">
                            <td><?php echo $item->KdPenjualan; ?></td>
                            <td><?php echo date("d M Y", strtotime($item->TglTrans)); ?></td>
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
                    <?php } ?> 
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
    let table;
    function refreshTable(filter) { 
        table.destroy(); 
        fillTable(filter); 
        $(document).ready(function() {
            table = new DataTable('#myTable', { 
                pageLength: 10, 
                "paging": true, 
                "lengthChange": false,  
                "searching": false, 
                "info": true 
            }); 
        });
            $('.btn-view').on('click', function() {
        var id = $(this).data('id');
        window.location.href = "Penjualan/View?KdPenjualan=" + id;
    });


    $('.btn-lunas').on('click', function() {
        var id = $(this).data('id');
        var encodedKdPenjualan = encodeURIComponent(id); 
        if (confirm('Apakah Anda yakin ingin Melunaskan nota ini?')) {
            window.location.href = "Penjualan/CetakNota?Tipe=Lunas&KdPenjualan=" + encodedKdPenjualan;
        }
    
    });

    // Event listener for the Edit button
    $('.btn-edit').on('click', function() {
        var id = $(this).data('id'); 
        window.location.href = "Penjualan/Edit?KdPenjualan=" + id;
    });

    // Event listener for the Delete button
    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');
        if (confirm('Apakah Anda yakin ingin Menghapus nota ini?')) { 
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
    }

    function fillTable(filter) {
        const $Penjualan = <?php echo json_encode($Penjualan); ?>;  
        const table = document.getElementById('myTable');
        const tbody = table.querySelector('tbody'); 
        tbody.innerHTML = ''; 
        $Penjualan.forEach(item => { 
            if (filter === 'all' || (filter === 'lunas' && item.Lunas === 1) || (filter === 'belum-lunas' && item.Lunas === 0)) { 
                const row = document.createElement('tr');  
                row.innerHTML = `
                    <td>${item.KdPenjualan}</td>
                    <td>${item.NamaTipePembayaran}</td>
                    <td>${item.NamaPelanggan}</td>
                    <td>${item.NoHp}</td>
                    <td>${item.GrandTotal}</td>
                    <td>
                        <div class="actions" ${item.Lunas === 1 ? 'style="display:none;"' : ''}>
                            <button type="button" class="btn lunas-item-btn btn-lunas" data-id="${item.KdPenjualan}">Lunas</button>
                            <button type="button" class="btn view-item-btn btn-view" data-id="${item.KdPenjualan}">View</button>
                            <button type="button" class="btn edit-item-btn btn-edit" data-id="${item.KdPenjualan}">Edit</button>
                            <button type="button" class="btn delete-item-btn btn-delete" data-id="${item.KdPenjualan}">Delete</button>
                        </div>
                    </td>
                `; 
                tbody.appendChild(row);
            }
        });
    }
 
    document.addEventListener('DOMContentLoaded', function() {
            const filterDropdown = document.getElementById('filter'); 
            filterDropdown.addEventListener('change', function() {
                const filterValue = this.value; 
                refreshTable(filterValue);
            });
        }); 

  $(document).ready(function() {   
    table = new DataTable('#myTable', { 
        pageLength: 10, 
        "paging": true, 
        "lengthChange": false,  
        "searching": false, 
        "info": true 
    }); 

    $('#myTable tbody').on('click', 'tr', function() { 
        let rowData = table.row(this).data(); 
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
        if (confirm('Apakah Anda yakin ingin Melunaskan nota ini?')) {
            window.location.href = "Penjualan/CetakNota?Tipe=Lunas&KdPenjualan=" + encodedKdPenjualan;
        }
    
    });

    // Event listener for the Edit button
    $('.btn-edit').on('click', function() {
        var id = $(this).data('id'); 
        window.location.href = "Penjualan/Edit?KdPenjualan=" + id;
    });

    // Event listener for the Delete button
    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');
        if (confirm('Apakah Anda yakin ingin Menghapus nota ini?')) { 
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
