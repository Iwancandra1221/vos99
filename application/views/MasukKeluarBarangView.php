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

    <form id="transaksiForm" class="defaultForm" action="<?php echo base_url('MasukKeluarBarang/tambah_transaksi'); ?>" method="post">
        <div class="formColumn">
            <h2 style="text-align: center;color:black;">Input Masuk / Keluar Barang</h2>

            <label style="color:black;" for="KdStockBarang">Nama Barang:</label>
            <select required id="KdStockBarangTrans" name="KdStockBarangTrans">
                <option value="">Pilih Barang</option>
                <?php foreach ($StockBarang as $Barang) { ?>
                    <option value="<?php echo $Barang->KdStockBarang; ?>" data-qty="<?php echo $Barang->Qty_Real; ?>">
                        <?php echo $Barang->NamaStockBarang ; ?>
                    </option>
                <?php } ?>
            </select>
            <!-- Label untuk menampilkan Qty --> 
            <label >Qty yang tersedia:</label> 
            <label style="color:green;" id="qtyLabel"></label> 
            <label style="color:black;" for="transaksi_type">Jenis Transaksi:</label>
            <select id="transaksi_type" name="transaksi_type" required>
                <option value="in">Barang Masuk</option>
                <option value="out">Barang Keluar</option>
            </select><br>

            <label style="color:black;" for="jumlah">Jumlah:</label>
            <input type="number" id="jumlah" name="jumlah" placeholder="Masukkan Jumlah" required><br>

            <label style="color:black;" for="keterangan">Keterangan:</label>
            <input type="text" id="keterangan" name="keterangan" placeholder="Masukkan Keterangan" required><br>
 
            <div class="actionButtons">
                <button type="submit">SAVE</button>
            </div>
        </div>
    </form>
</body>
</html>
<script> 
  $(document).ready(function() {  

    $('#KdStockBarangTrans').change(function() {
            var selectedOption = $(this).find('option:selected');
            var qty = selectedOption.data('qty');
            $('#qtyLabel').text(qty);
        });
  });  
</script> 
    <style>  
         
 
        .defaultForm {
            display: flex;
            position: absolute;
            top: 70px; 
            left: 20px;
            background-color: white;
            color: black;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            background-color: #d3d3d3; /* StockBarang tombol yang dinonaktifkan */
            color: #808080; /* StockBarang teks tombol yang dinonaktifkan */
            cursor: not-allowed; /* Ubah kursor menjadi tanda tidak diperbolehkan */
        }

        .actionButtons button:disabled:hover {
            background-color: #d3d3d3; /* Pastikan StockBarang hover tidak berubah */
            color: #808080; /* Pastikan StockBarang teks hover tidak berubah */
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
        .error-message {
            color: red;
            font-size: 12px;
        }
    </style>
