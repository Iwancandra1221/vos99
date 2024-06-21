<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title> 
</head> 
<body> 
    <div class="container">
        <form id ="myForm" action="<?php echo base_url('Penjualan/Save'); ?>" method="post" >
            <div class="formjudul"><h3><?php echo $title; ?></h3></div>
            <div class="formisi"> 
                <div class="left"> 
                    <div class="form-group"> 
                        <label for="KdPenjualan"><b>Kode Penjualan:</b></label>
                        <input type="text" id="KdPenjualan" name="KdPenjualan" value="<?php echo $AutoNumber; ?>" readonly> 
                    </div>
                    <div class="form-group"> 
                        <label for="tgljt"><b>Tanggal Jatuh Tempo:</b> </label>
                        <input type="date" id="tgljt" name="tgljt" required>
                    </div> 
                    <div class="form-group"> 
                        <label for="KdTipePembayaran"><b>Tipe Pembayaran:</b> </label> 
                        <select id="KdTipePembayaran" name="KdTipePembayaran">
                            <option value="">Pilih Tipe Pembayaran</option> 
                            <?php foreach ($listTipePembayaran as $tipe) {
                            ?> 
                                <option value="<?php echo $tipe->KdTipePembayaran; ?>"><?php echo $tipe->NamaTipePembayaran; ?></option>
                            <?php   
                            } 
                            ?>
                        </select> 
                        <span  id="KdTipePembayaranError" class="error-message" ></span><br>
                    </div> 
                    <div class="form-group"> 
                        <label for="KdPelanggan"><b>Kode Pelanggan:</b> </label> 
                        <select id="KdPelanggan" name="KdPelanggan" onchange="handlePelangganChange(this)" >
                            <option value="">Pilih Pelanggan</option>
                            <?php foreach ($listPelanggan as $Pelanggan) { ?>
                                <option value="<?php echo $Pelanggan->KdPelanggan; ?>"><?php echo $Pelanggan->KdPelanggan . " - " . $Pelanggan->NamaPelanggan;?></option>
                            <?php } ?>
                        </select>
                        <span  id="KdPelangganError" class="error-message" ></span><br>
                    </div> 
                    <div class="form-group"> 
                        <label for="labelNamaPelanggan"><b>Nama Pelanggan:</b> </label> 
                        <input type="text" id="labelNamaPelanggan" name="labelNamaPelanggan" readonly><br> 
                    </div> 
                    <div class="form-group"> 
                        <label for="labelNoHP"><b>No HP:</b> </label> 
                        <input type="text" id="labelNoHP" name="labelNoHP" readonly><br>  
                    </div> 
                </div>
                <div class="right"> 
                    <div class="form-group"> 
                        <label> <h3>Items</h3> </label>  
                        <div class="table-wrapper">
                            <table id="itemsTable"  style="color:black;"  class="table">
                                <thead> 
                                    <tr>
                                        <th colspan="5"> 
                                            <div class="form-inline">
                                                <label for="InputKdBarang"><b>Cari Kode Barang / Nama Barang:</b> </label>
                                                <input type="text" id="InputKdBarang" name="InputKdBarang" onkeyup="searchItems()">
                                            </div>
                                            <div id="searchResults" ></div> 
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%;">Kode Barang</th>
                                        <th style="width: 20%;">Qty</th>
                                        <th style="width: 20%;">Harga</th>
                                        <th style="width: 30%;">Total</th>
                                        <th style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="items">
                                    <!-- <tr class="item">
                                        <td>
                                            <select required id="items[0][KdBarang]" name="items[0][KdBarang]" onchange="handleBarangChange(this,0)">
                                                <option value="">Pilih Barang</option>
                                                <?php foreach ($listBarang as $Barang) { ?>
                                                    <option value="<?php echo $Barang->KdBarang; ?>"><?php echo $Barang->NamaBarang . " " . $Barang->Warna; ?></option>
                                                <?php } ?>
                                            </select> 
                                        </td>
                                        <td>
                                          <input required type="number" id="items[0][Qty]" name="items[0][Qty]" placeholder="Qty" min="1" step="1" oninput="calculateTotal(this)">
                                        </td>

                                        <td><input type="number" id="items[0][Harga]" name="items[0][Harga]" placeholder="Harga" min="0" step="1"  oninput="calculateTotal(this)"></td>
                                        <td><input type="number" id="items[0][Total]"  name="items[0][Total]" min="0" step="1"  placeholder="Total" readonly></td>
                                        <td><button type="button" class="delete-item-btn" onclick="deleteItem(this)">Delete</button></td>
                                    </tr> -->
                                </tbody> 
                                <footer> 
                                        <tr> 
                                            <th colspan="2" style="text-align: right;"><b>Grand Total:</b></th>
                                            <th colspan="2"><input required type="number" id="GrandTotal" name="GrandTotal" style="width: 100%;" readonly></th> 
                                        </tr> 
                                </footer>
                            </table>
                        </div>
                    </div> 
                    <div class="buttons"> 
                            <button type="button" onclick="addItem()">Tambah Item</button>  
                            <button type="submit" >Simpan</button>  
                            <button type="button" onclick="Exit()">Exit</button> 
                    </div>   
                </div> 
            </div> 
        </form> 
    </div>  
</body>
</html>
<script>
    async function searchItems() {
        const input = document.getElementById('InputKdBarang').value;
        if (input == "")
        {
            emptyResults();
        }
        else
        {
            const response = await fetch('<?php echo base_url(); ?>/index.php/search?q=' + input);
            const results = await response.json();
            displayResults(results);
        }
    }
    function emptyResults() {
        const resultsDiv = document.getElementById('searchResults');
        resultsDiv.innerHTML = '';
        resultsDiv.style.display = 'none';
    }
    function displayResults(results) {
        const resultsDiv = document.getElementById('searchResults');
        resultsDiv.innerHTML = '';
        if (results.length > 0) {
            results.forEach(item => {
                const div = document.createElement('div');
                div.textContent = `${item.code} - ${item.name} ${item.warna}`;
                div.addEventListener('click', () => { 
                    addCariItem(item.code);
                    resultsDiv.style.display = 'none';
                    document.getElementById('InputKdBarang').value = '';
                });
                resultsDiv.appendChild(div);
            });
            resultsDiv.style.display = 'block';
        } else {
            resultsDiv.style.display = 'none';
        }
    } 
    $(document).ready(function() { 
        let today = new Date();
        let yyyy = today.getFullYear();
        let mm = String(today.getMonth() + 1).padStart(2, '0'); 
        let dd = String(today.getDate()).padStart(2, '0'); 
        let todayFormatted = yyyy + '-' + mm + '-' + dd;
        document.getElementById('tgljt').value = todayFormatted;

        $('#myForm').submit(function(event) {
            if ($('#KdPelanggan').val() == '') {
                $('#KdPelangganError').text('Kolom Pelanggan harus diisi.');
                event.preventDefault();
            } else {
                $('#KdPelangganError').text('');
            }

            if ($('#KdTipePembayaran').val() == '') {
                $('#KdTipePembayaranError').text('Kolom Tipe Pembayaran harus diisi.');
                event.preventDefault();
            } else {
                $('#KdTipePembayaranError').text('');
            }

            
        });
    });

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
    function addCariItem(itemCari) {
        const table = document.getElementById('items');
        const newRow = document.createElement('tr');
        newRow.classList.add('item');
 
        let options = '<option value="">Pilih Barang</option>'; 
        let harga = '';

        <?php foreach ($listBarang as $Barang) { ?>
            options += `<option value="<?php echo $Barang->KdBarang; ?>" ${itemCari === '<?php echo $Barang->KdBarang; ?>' ? 'selected' : ''}>
                        <?php echo $Barang->NamaBarang . " " . $Barang->Warna; ?></option>`;

            if (itemCari === '<?php echo $Barang->KdBarang; ?>') {
                harga = '<?php echo $Barang->Harga_Jual; ?>';
            }
        <?php } ?>

        newRow.innerHTML = `
            <td>
                <select required name="items[${itemCount}][KdBarang]" onchange="handleBarangChange(this, ${itemCount})">
                    ${options}
                </select>
            </td>
            <td><input required type="number" id="items[${itemCount}][Qty]"  min="1" step="1" name="items[${itemCount}][Qty]" placeholder="Qty" oninput="calculateTotal(this)"></td>
            <td><input value="${harga}" type="number" id="items[${itemCount}][Harga]" name="items[${itemCount}][Harga]" placeholder="Harga"  oninput="calculateTotal(this)"></td>
            <td><input type="number" id="items[${itemCount}][Total]" name="items[${itemCount}][Total]" placeholder="Total" readonly></td>
            <td><button type="button" class="delete-item-btn" onclick="deleteItem(this)">Delete</button></td>
        `;

        table.appendChild(newRow);
        itemCount++;
    }

    function addItem() {
        const table = document.getElementById('items');
        const newRow = document.createElement('tr');
        newRow.classList.add('item');

        newRow.innerHTML = `
            <td>
                <select required name="items[${itemCount}][KdBarang]"  onchange="handleBarangChange(this,${itemCount})">
                    <option value="">Pilih Barang</option>
                    <?php foreach ($listBarang as $Barang) { ?>
                        <option value="<?php echo $Barang->KdBarang; ?>"><?php echo $Barang->NamaBarang . " " . $Barang->Warna; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td><input required type="number" id="items[0][Qty]" name="items[${itemCount}][Qty]"  min="1" step="1" placeholder="Qty" oninput="calculateTotal(this)"></td>
            <td><input type="number" id="items[0][Harga]" name="items[${itemCount}][Harga]" placeholder="Harga"  oninput="calculateTotal(this)"></td>
            <td><input type="number" id="items[0][Total]" name="items[${itemCount}][Total]" placeholder="Total" readonly></td>
            <td><button type="button" class="delete-item-btn" onclick="deleteItem(this)">Delete</button></td>
        `;


        table.appendChild(newRow);
        itemCount++;
    }

    function handleBarangChange(selectElement,index) {
        const row = selectElement.parentNode.parentNode;
        const selectedKdBarang = selectElement.value; 
        const selectedBarang = <?php echo json_encode($listBarang); ?>;
        const selectedBarangData = selectedBarang.find(barang => barang.KdBarang === selectedKdBarang); 
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


    .table-wrapper {
            max-height: 400px; /* Set the height you want */
            overflow-y: auto;
        }
        table {
            width: 100%;
            table-layout: fixed; /* Ensure columns are fixed width */
        }
        thead, tbody {
            display: block; /* Make header and body separate blocks */
        }
        thead {
            width: 100%;
        }
        tbody {
            width: 100%;
        }
        tbody tr {
            display: table; /* Ensure rows behave like table rows */
            width: 100%;
            table-layout: fixed;
        }
        thead tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

    label {
        color: black;
    }

    .form-inline {
        display: flex;
        align-items: center;
    }
 

    #searchResults {
        margin-top: 10px;
        display: none; /* Default disembunyikan */
        max-height: 200px; /* Tinggi maksimum sebelum scroll */
        overflow-y: auto; /* Mengaktifkan vertical scroll */
        border: 1px solid #ccc; /* Opsional: Tambahkan border untuk visualisasi */
        padding: 10px; /* Opsional: Tambahkan padding */
        background-color: #f9f9f9; /* Opsional: Tambahkan background color */
    }

    .error-message {
            color: red;
            font-size: 12px;
    }

    .form-section {
        margin-bottom: 20px;
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
</style>
