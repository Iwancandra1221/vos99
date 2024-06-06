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

   <div class="container">  

        <?php 
            if ($mode === "View")  
            {
        ?> 
            <form class="defaultForm"> 
                <div class="form-section">
                    <label for="KdPenjualan"><b>Kode Penjualan:</b> </label>
                    <input type="text" id="KdPenjualan" name="KdPenjualan" value="<?php echo $DataHD->KdPenjualan; ?>" readonly><br> 
                    <label for="KdTipePembayaran"><b>Tipe Pembayaran:</b> </label>  
                    <select disabled id="KdTipePembayaran" name="KdTipePembayaran" >
                        <option value="">Pilih Tipe Pembayaran</option> 
                        <?php foreach ($listTipePembayaran as $tipe) {
                            if ($DataHD->KdTipePembayaran === $tipe->KdTipePembayaran) { 
                               ?> 
                               <option selected value="<?php echo $tipe->KdTipePembayaran; ?>"><?php echo $tipe->NamaTipePembayaran; ?></option>
                               <?php 
                            } 
                            else
                            {
                                ?> 
                               <option value="<?php echo $tipe->KdTipePembayaran; ?>"><?php echo $tipe->NamaTipePembayaran; ?></option>
                               <?php 
                            } 
                        } ?>
                    </select> 
                    <label for="KdPelanggan"><b>Kode Pelanggan:</b> </label>  
                    <select disabled id="KdPelanggan" name="KdPelanggan" onchange="handlePelangganChange(this)">
                        <option value="">Pilih Pelanggan</option>
                        <?php foreach ($listPelanggan as $Pelanggan) {
                            if ($DataHD->KdPelanggan === $Pelanggan->KdPelanggan) { 
                               ?> 
                               <option selected value="<?php echo $Pelanggan->KdPelanggan; ?>"><?php echo $Pelanggan->KdPelanggan; ?></option>
                               <?php 
                            } 
                            else
                            {
                                ?> 
                               <option value="<?php echo $Pelanggan->KdPelanggan; ?>"><?php echo $Pelanggan->KdPelanggan; ?></option>
                               <?php 
                            } 
                        } ?>
                    </select>
                    <label for="labelNamaPelanggan"><b>Nama Pelanggan:</b> </label> 
                    <input type="text" id="labelNamaPelanggan" name="labelNamaPelanggan" value="<?php echo $DataHD->NamaPelanggan; ?>" readonly><br> 
                    <label for="labelNamaPelanggan"><b>No HP:</b> </label> 
                    <input type="text" id="labelNoHP" name="labelNoHP" value="<?php echo $DataHD->NoHp; ?>" readonly><br>  
                    <h3>Items</h3>
                    <table id="itemsTable">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th> 
                            </tr>
                        </thead>
                        <tbody id="items"> 
                                <?php 
                                foreach ($DataDT as $key => $value) {
                                    ?>
                                    <tr class="item"> 
                                    <td>
                                        <select disabled id="items[0][KdBarang]" name="items[0][KdBarang]" onchange="handleBarangChange(this,0)">
                                            <option value="">Pilih Barang</option>
                                            <?php foreach ($listBarang as $Barang) { 
                                                if ($value->KdBarang === $Barang->KdBarang) { 
                                                   ?> 
                                                    <option selected value="<?php echo $Barang->KdBarang; ?>"><?php echo $Barang->NamaBarang; ?></option>
                                                   <?php 
                                                } 
                                                else
                                                {
                                                    ?> 
                                                    <option value="<?php echo $Barang->KdBarang; ?>"><?php echo $Barang->NamaBarang; ?></option>
                                                   <?php 
                                                } 
                                            } ?>
                                        </select> 
                                    </td>
                                    <td><input disabled type="number" value = "<?php echo $value->Qty; ?>" id="items[0][Qty]" name="items[0][Qty]" placeholder="Qty" oninput="calculateTotal(this)"></td>
                                    <td><input disabled type="number"  value = "<?php echo $value->Harga; ?>" id="items[0][Harga]" name="items[0][Harga]" placeholder="Harga" oninput="calculateTotal(this)"></td>
                                    <td><input disabled type="number"  value = "<?php echo $value->Total; ?>" id="items[0][Total]"  name="items[0][Total]" placeholder="Total" ></td> 
                                    <?php
                                }
                                ?> 
                        </tbody> 
                        <footer> 
                                <tr> 
                                    <th colspan="2" style="text-align: right;"><b>Grand Total:</b></th>
                                    <th colspan="2"><input  value = "<?php echo $DataHD->GrandTotal;; ?>" type="number" id="GrandTotal" name="GrandTotal" style="width: 100%;" disabled></th> 
                                </tr> 
                        </footer>
                    </table> 
                    <button type="button" class="add-item-btn" onclick="Print('<?php echo $DataHD->KdPenjualan; ?>')">Print Nota</button> 
                    <button type="button" class="view-item-btn" onclick="View('<?php echo $DataHD->KdPenjualan; ?>')">View Nota</button> 
                    <button type="button" class="exit-item-btn" onclick="Exit()">Exit</button> 
                </div>
            </form>
        <?php 
            }
            else  
            {
        ?> 
            <form action="<?php echo base_url('Penjualan/Save'); ?>" method="post" class="defaultForm"> 
                <div class="form-section">
                    <label for="KdPenjualan"><b>Kode Penjualan:</b> </label>
                    <input type="text" id="KdPenjualan" name="KdPenjualan" value="<?php echo $DataHD->KdPenjualan; ?>" readonly><br> 
                    <label for="KdTipePembayaran"><b>Tipe Pembayaran:</b> </label>  
                    <select  id="KdTipePembayaran" name="KdTipePembayaran" >
                        <option value="">Pilih Tipe Pembayaran</option> 
                        <?php foreach ($listTipePembayaran as $tipe) {
                            if ($DataHD->KdTipePembayaran === $tipe->KdTipePembayaran) { 
                               ?> 
                               <option selected value="<?php echo $tipe->KdTipePembayaran; ?>"><?php echo $tipe->NamaTipePembayaran; ?></option>
                               <?php 
                            } 
                            else
                            {
                                ?> 
                               <option value="<?php echo $tipe->KdTipePembayaran; ?>"><?php echo $tipe->NamaTipePembayaran; ?></option>
                               <?php 
                            } 
                        } ?>
                    </select> 
                    <label for="KdPelanggan"><b>Kode Pelanggan:</b> </label> 
                    <select  id="KdPelanggan" name="KdPelanggan" onchange="handlePelangganChange(this)">
                        <option value="">Pilih Pelanggan</option>
                        <?php foreach ($listPelanggan as $Pelanggan) {
                            if ($DataHD->KdPelanggan === $Pelanggan->KdPelanggan) { 
                               ?> 
                               <option selected value="<?php echo $Pelanggan->KdPelanggan; ?>"><?php echo $Pelanggan->KdPelanggan; ?></option>
                               <?php 
                            } 
                            else
                            {
                                ?> 
                               <option value="<?php echo $Pelanggan->KdPelanggan; ?>"><?php echo $Pelanggan->KdPelanggan; ?></option>
                               <?php 
                            } 
                        } ?>
                    </select>
                    <label for="labelNamaPelanggan"><b>Nama Pelanggan:</b> </label> 
                    <input type="text" id="labelNamaPelanggan" name="labelNamaPelanggan" value="<?php echo $DataHD->NamaPelanggan; ?>" readonly><br> 
                    <label for="labelNamaPelanggan"><b>No HP:</b> </label> 
                    <input type="text" id="labelNoHP" name="labelNoHP" value="<?php echo $DataHD->NoHp; ?>" readonly><br>  
                    <h3>Items</h3>
                    <table id="itemsTable">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="items"> 
                                <?php 
                                foreach ($DataDT as $key => $value) {
                                    ?>
                                    <tr class="item"> 
                                    <td>
                                        <select id="items[<?php echo $key; ?>][KdBarang]" name="items[<?php echo $key; ?>][KdBarang]" onchange="handleBarangChange(this,0)">
                                            <option value="">Pilih Barang</option>
                                            <?php foreach ($listBarang as $Barang) { 
                                                if ($value->KdBarang === $Barang->KdBarang) { 
                                                   ?> 
                                                    <option selected value="<?php echo $Barang->KdBarang; ?>"><?php echo $Barang->NamaBarang; ?></option>
                                                   <?php 
                                                } 
                                                else
                                                {
                                                    ?> 
                                                    <option value="<?php echo $Barang->KdBarang; ?>"><?php echo $Barang->NamaBarang; ?></option>
                                                   <?php 
                                                } 
                                            } ?>
                                        </select> 
                                    </td>
                                    <td><input type="number" value = "<?php echo $value->Qty; ?>" id="items[<?php echo $key; ?>][Qty]" name="items[<?php echo $key; ?>][Qty]" placeholder="Qty" oninput="calculateTotal(this)"></td>
                                    <td><input type="number"  value = "<?php echo $value->Harga; ?>" id="items[<?php echo $key; ?>][Harga]" name="items[<?php echo $key; ?>][Harga]" placeholder="Harga" readonly oninput="calculateTotal(this)"></td>
                                    <td><input type="number"  value = "<?php echo $value->Total; ?>" id="items[<?php echo $key; ?>][Total]"  name="items[<?php echo $key; ?>][Total]" placeholder="Total" readonly></td> 
                                    <td><button type="button" class="delete-item-btn" onclick="deleteItem(this)">Delete</button></td>
                                    </tr> 
                                    <?php
                                }
                                ?> 
                        </tbody> 
                        <footer> 
                                <tr> 
                                    <th colspan="2" style="text-align: right;"><b>Grand Total:</b></th>
                                    <th colspan="2"><input  value = "<?php echo $DataHD->GrandTotal;; ?>" type="number" id="GrandTotal" name="GrandTotal" style="width: 100%;" readonly></th> 
                                </tr> 
                        </footer>
                    </table>
                    <button type="button" class="add-item-btn" onclick="addItem()">Tambah Item</button>  
                    <input type="submit" value="Update">
                    <button type="button" class="exit-item-btn" onclick="Exit()">Exit</button> 
                </div>
            </form>
        <?php 
            } 
        ?> 
    </div> 
</body>
</html>

<script> 
    let itemCount = 1;
    function handlePelangganChange(selectElement) {      
        const selectedKdPelanggan = selectElement.value; 
        const selectedPelanggan = <?php echo json_encode($listPelanggan); ?>;
        const selectedPelangganData = selectedPelanggan.find(pelanggan => pelanggan.KdPelanggan === selectedKdPelanggan); 
        if (selectedPelangganData) {
            const KdPelanggan = selectedPelangganData.KdPelanggan;
            const namaPelanggan = selectedPelangganData.NamaPelanggan;
            const noHP = selectedPelangganData.NoHp; 
            console.log(namaPelanggan,noHP);

            document.getElementById('KdPelanggan').value = KdPelanggan;
            document.getElementById('labelNamaPelanggan').value = namaPelanggan;
            document.getElementById('labelNoHP').value = noHP; 
        } else { 
            document.getElementById('labelNamaPelanggan').value = '';
            document.getElementById('labelNoHP').value = '';
        }
    }
    function addItem() {
        const table = document.getElementById('items');
        const newRow = document.createElement('tr');
        newRow.classList.add('item');

        newRow.innerHTML = `
            <td>
                <select name="items[${itemCount}][KdBarang]"  onchange="handleBarangChange(this,${itemCount})">
                    <option value="">Pilih Barang</option>
                    <?php foreach ($listBarang as $Barang) { ?>
                        <option value="<?php echo $Barang->KdBarang; ?>"><?php echo $Barang->NamaBarang; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td><input type="number" id="items[0][Qty]" name="items[${itemCount}][Qty]" placeholder="Qty" oninput="calculateTotal(this)"></td>
            <td><input type="number" id="items[0][Harga]" name="items[${itemCount}][Harga]" placeholder="Harga" readonly oninput="calculateTotal(this)"></td>
            <td><input type="number" id="items[0][Total]" name="items[${itemCount}][Total]" placeholder="Total" readonly></td>
            <td><button type="button" class="delete-item-btn" onclick="deleteItem(this)">Delete</button></td>
        `;


        table.appendChild(newRow);
        itemCount++;
    }

    function handleBarangChange(selectElement,index) {
        const row = selectElement.parentNode.parentNode;
        const selectedKdBarang = selectElement.value;
        
        // Mendapatkan harga barang yang dipilih dari listBarang
        const selectedBarang = <?php echo json_encode($listBarang); ?>;
        const selectedBarangData = selectedBarang.find(barang => barang.KdBarang === selectedKdBarang);
        
        // Memeriksa apakah barang yang dipilih ditemukan
        if (selectedBarangData) {
            const harga = selectedBarangData.Harga_Jual;  
            row.querySelector('input[name*="[Harga]"]').value = harga; 
            calculateTotal(selectElement);
        } else {
            row.querySelector('input[name*="[Harga]"]').value = 0; 
        }
    }


    function deleteItem(button) {
        const row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
        updateGrandTotal();
    }

    function calculateTotal(element) {
        const row = element.parentNode.parentNode;
        const qty = row.querySelector('input[name*="[Qty]"]').value;
        const harga = row.querySelector('input[name*="[Harga]"]').value;
        const total = row.querySelector('input[name*="[Total]"]');
        
        if (qty && harga) {
            total.value = qty * harga;
        } else {
            total.value = 0;
        }

        updateGrandTotal();
    }

    function updateGrandTotal() {
        const totals = document.querySelectorAll('input[name*="[Total]"]');
        let grandTotal = 0;

        totals.forEach(total => {
            grandTotal += parseFloat(total.value) || 0;
        });

        document.getElementById('GrandTotal').value = grandTotal;
    }

 
    function Print($KdPenjualan) {
        var encodedKdPenjualan = encodeURIComponent($KdPenjualan); 
        window.location.href = "../Penjualan/CetakNota?Tipe=Print&KdPenjualan=" + encodedKdPenjualan;
    }
    function View($KdPenjualan) {
        var encodedKdPenjualan = encodeURIComponent($KdPenjualan); 
        window.location.href = "../Penjualan/CetakNota?Tipe=View&KdPenjualan=" + encodedKdPenjualan;
    }
    function Exit() {
        window.location.href = "../Penjualan";
    }

</script>

    <style>         
        .defaultForm {
            display: flex;
            position: absolute;
            top: 80px; 
            left: 20px;
            background-color: transparent;
            color: black;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        } 
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-section {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"],
        input[type="submit"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .items {
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .item {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .view-item-btn {
            background-color: skyblue;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .view-item-btn:hover {
            background-color: skyblue;
        }

        .add-item-btn {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .add-item-btn:hover {
            background-color: #218838;
        }

        .exit-item-btn {
            width: 100%;
            background-color: indianred;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .exit-item-btn:hover {
            background-color: darkred;
        }
 
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .add-item-btn, .delete-item-btn {
            margin-top: 10px;
            cursor: pointer;
        } 
 
 
    </style>