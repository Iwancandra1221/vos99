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
        <div class="left2">     
            <select id="filter">
                    <option value="all">All</option>
                    <option value="lunas">Lunas</option>
                    <option selected value="belum-lunas">Belum Lunas</option>
            </select>  
            <table  id="myTable">
                <thead>
                    <tr>
                        <th>Kode Penjualan / Tipe Pembayaran</th>  
                        <th>Nama Pelanggan / No HP</th>
                        <th>Grand Total</th> 
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody> 
                <?php foreach ($Penjualan as $item): ?> 
                    <?php if ($item->Lunas == 0) { ?>
                        <tr data-lunas="<?php echo $item->Lunas; ?>">
                            <td><?php echo '<b>'.$item->KdPenjualan .'</b><br>'. $item->NamaTipePembayaran; ?></td> 
                            <td><?php echo '<b>'.$item->NamaPelanggan .'</b><br>'. $item->NoHp; ?></td> 
                            <td><?php echo '<b>'.$item->GrandTotal.'</b>'; ?></td>
                            <td> 
                                <div class="actions" <?php if ($item->Lunas == 1) echo 'style="display:none;"'; ?>>
                                    <button style="margin-right: 20px;" type="button" class="btn print-item-btn btn-print" data-id="<?php echo $item->KdPenjualan; ?>">Print</button> 
                                    <button type="button" class="btn lunas-item-btn btn-lunas" data-id="<?php echo $item->KdPenjualan; ?>">Lunas</button> 
                                </div>
                            </td>
                        </tr> 
                    <?php } ?> 
                <?php endforeach; ?>
                </tbody>
            </table>   
        </div>
    </div>
</div>   
</body>
</html>  
<script> 
    let table;
    function refreshTable(filter) {
        // Hapus instance DataTables yang ada
        table.destroy();

        // Isi ulang tabel dengan data baru berdasarkan filter
        fillTable(filter);

        // Inisialisasi kembali DataTables setelah tabel diisi ulang
        $(document).ready(function() {
            table = new DataTable('#myTable', { 
                pageLength: 10, 
                "paging": true, 
                "lengthChange": false,  
                "searching": false, 
                "info": true 
            }); 
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
                    <td><b>${item.KdPenjualan}</b><br>${item.NamaTipePembayaran}</td>
                    <td><b>${item.NamaPelanggan}</b><br>${item.NoHp}</td> 
                    <td>${item.GrandTotal}</td>
                    <td>
                        <div class="actions" ${item.Lunas === 1 ? 'style="display:none;"' : ''}>
                            <button style="margin-right: 20px;"  type="button" class="btn print-item-btn btn-print" data-id="${item.KdPenjualan}">Print</button> 
                            <button type="button" class="btn lunas-item-btn btn-lunas" data-id="${item.KdPenjualan}">Lunas</button> 
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

     


    $('.btn-print').on('click', function() {
        var KdPenjualan = $(this).data('id');
        var encodedKdPenjualan = encodeURIComponent(KdPenjualan);  
        window.location.href = "PenjualanMobile/CetakNota?Tipe=Print&KdPenjualan=" + encodedKdPenjualan;
    
    });
    $('.btn-lunas').on('click', function() {
        var KdPenjualan = $(this).data('id');
        var encodedKdPenjualan = encodeURIComponent(KdPenjualan);  
        if (confirm('Apakah Anda yakin ingin Melunaskan nota ini?')) {
        window.location.href = "PenjualanMobile/CetakNota?Tipe=Lunas&KdPenjualan=" + encodedKdPenjualan;
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
        .print-item-btn {
            background-color: gray;
            color: #fff;
            border: none; 
            cursor: pointer;
            border-radius: 4px; 
        }
        .print-item-btn:hover {
            background-color: blue;
        }
         
    </style>
