<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Page</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f2f2f2;
    }
    .container {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
      text-align: center;
      color: #333;
    }
    h2 {
      color: #555;
    }
    ul {
      list-style-type: none; 
      padding: 10px;
    }
    li {
      margin-bottom: 10px;
    }
    .info {
      background-color: #f9f9f9;
      padding: 10px;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>About</h1> 

    <div class="info">
      <h2>Company Information</h2>
      <ul>
        <li><strong>Version:</strong> <?php echo Version; ?></li>
        <li><strong>Company:</strong> <?php echo GlobCompany; ?></li>
        <li><strong>Nama BCA:</strong> <?php echo GlobNamaBCA; ?></li>
        <li><strong>No. Rek BCA:</strong> <?php echo GLobNoRek; ?></li>
        <li><strong>No. HP:</strong> <?php echo GlobNoHP; ?></li>
        <li><strong>Alamat:</strong> <?php echo GlobAlamat; ?></li>
      </ul>
    </div>
  </div>
</body>
</html>
