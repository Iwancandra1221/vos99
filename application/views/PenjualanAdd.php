<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title> 
</head> 
<body>

   <div class="container">  

        <form action="<?php echo base_url('Penjualan/Save'); ?>" method="post" class="defaultForm"> 
            <div class="form-section">
                <label for="KdPenjualan"><b>Kode Penjualan:</b> </label>
                <input type="text" id="KdPenjualan" name="KdPenjualan" value="<?php echo $AutoNumber; ?>" readonly><br>


                <label for="KdPelanggan"><b>Tipe Pembayaran:</b> </label> 
                <select id="TipePembayaran" name="TipePembayaran" >
                    <option value="">Pilih Tipe Pembayaran</option> 
                    <option value="Cash">Cash</option> 
                    <option value="Transfer">Transfer</option> 
                    <option value="BelumLunas">Belum Lunas</option> 
                </select>

                <label for="KdPelanggan"><b>Kode Pelanggan:</b> </label> 
                <select id="KdPelanggan" name="KdPelanggan" onchange="handlePelangganChange(this)">
                    <option value="">Pilih Pelanggan</option>
                    <?php foreach ($listPelanggan as $Pelanggan) { ?>
                        <option value="<?php echo $Pelanggan->KdPelanggan; ?>"><?php echo $Pelanggan->KdPelanggan; ?></option>
                    <?php } ?>
                </select>
                <label for="labelNamaPelanggan"><b>Nama Pelanggan:</b> </label> 
                <input type="text" id="labelNamaPelanggan" name="labelNamaPelanggan" readonly><br> 
                <label for="labelNamaPelanggan"><b>No HP:</b> </label> 
                <input type="text" id="labelNoHP" name="labelNoHP" readonly><br>  
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
                        <tr class="item">
                            <td>
                                <select id="items[0][KdBarang]" name="items[0][KdBarang]" onchange="handleBarangChange(this,0)">
                                    <option value="">Pilih Barang</option>
                                    <?php foreach ($listBarang as $Barang) { ?>
                                        <option value="<?php echo $Barang->KdBarang; ?>"><?php echo $Barang->NamaBarang; ?></option>
                                    <?php } ?>
                                </select> 
                            </td>
                            <td><input type="number" id="items[0][Qty]" name="items[0][Qty]" placeholder="Qty" oninput="calculateTotal(this)"></td>
                            <td><input type="number" id="items[0][Harga]" name="items[0][Harga]" placeholder="Harga" readonly oninput="calculateTotal(this)"></td>
                            <td><input type="number" id="items[0][Total]"  name="items[0][Total]" placeholder="Total" readonly></td>
                            <td><button type="button" class="delete-item-btn" onclick="deleteItem(this)">Delete</button></td>
                        </tr>
                    </tbody> 
                    <footer> 
                            <tr> 
                                <th colspan="3" style="text-align: right;"><b>Grand Total:</b></th>
                                <th colspan="2"><input type="number" id="GrandTotal" name="GrandTotal" style="width: 100%;" readonly></th> 
                            </tr> 
                    </footer>
                </table>
                <button type="button" class="add-item-btn" onclick="addItem()">Tambah Item</button>  
                <input type="submit" value="Simpan">
                <button type="button" class="exit-item-btn" onclick="Exit()">Exit</button> 
            </div>
        </form>
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