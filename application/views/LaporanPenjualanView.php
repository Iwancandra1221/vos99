<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title> 
</head> 
<body>
	<div class="container"> 
		<form id ="myForm" action="<?php echo base_url('ReportPenjualan/CetakReport'); ?>" method="post" class="defaultForm"> 
			<div class="page-title" style="text-align: center;"><b><?php echo(strtoupper($title));?></b></div>  
			<div class="row">
				<div class="col-3 col-m-3">Periode PO</div>
				<div class="col-2 col-m-2 date">
					<input type="date" class="form-control" id="dp1"  name="dp1" autocomplete="off" required>
				</div>
				<div class="col-1 col-m-1">SD</div>
				<div class="col-2 col-m-2 date">
					<input type="date" class="form-control" id="dp2" name="dp2" autocomplete="off" required>
				</div>
			</div>   
	      	<div class="row" align="center" style="padding-top:50px;" id="div_pdf"> 
	         	<input type = "submit" name="btnPreview" value="PREVIEW"/>
	         	<input type = "submit" name="btnExcel" value="EXPORT EXCEL"/>
	         	<input type = "submit" name="btnPdf" value="EXPORT PDF"/>
	      	</div>
	    </form>
	</div>  
</body>
</html>
<style type="text/css">

	.row {
		padding: 20px;
    	line-height:30px; 
    	vertical-align:middle;
    	clear:both;
	}
	.row-label, .row-input {
    	float:left;
	}
	.row-label {
    	padding-left: 15px;
    	width:180px;
	}
	.row-input {
    	width:420px;
	} 

	.defaultForm { 
		background-color: white;
		color: black;
		padding: 20px;
		border: 1px solid #ccc;
		border-radius: 5px;
		box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
	}  
</style>
<script>
    $(document).ready(function() {    

        let today = new Date();
        let yyyy = today.getFullYear();
        let mm = String(today.getMonth() + 1).padStart(2, '0'); 
        let dd = String(today.getDate()).padStart(2, '0'); 
        let todayFormatted = yyyy + '-' + mm + '-' + dd;
        document.getElementById('dp1').value = todayFormatted;
        document.getElementById('dp2').value = todayFormatted;

	} );
</script>
	  
	  
	  
 
 


