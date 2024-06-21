<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title; ?></title>
  <style>  
  </style>
</head>
<body>

<div class="container">
    <div class="formjudul"><h3>Company Information</h3></div>
    <div class="formisi">
      <form id="TipePembayaranForm"> 
      <div class="form-group">
        <ul>
          <li style="padding : 10px;"><strong>Version:</strong> <?php echo Version; ?></li>
          <li style="padding : 10px;"><strong>Company:</strong> <?php echo GlobCompany; ?></li>
          <li style="padding : 10px;"><strong>Nama BCA:</strong> <?php echo GlobNamaBCA; ?></li>
          <li style="padding : 10px;"><strong>No. Rek BCA:</strong> <?php echo GLobNoRek; ?></li>
          <li style="padding : 10px;"><strong>No. HP:</strong> <?php echo GlobNoHP; ?></li>
          <li style="padding : 10px;"><strong>Alamat:</strong> <?php echo GlobAlamat; ?></li>
        </ul>
      </div>
    </div> 
</body>
</html>
