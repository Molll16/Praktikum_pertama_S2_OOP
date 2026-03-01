<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Find Your Account</title>
  <link rel="stylesheet" href="style-find.css">
  <link rel="shortcut icon" href="ICONB.png" type="image/x-icon">
</head>
<body>
  <main class="page">
    <section class="card">
      <h1>Find Your Acount</h1>

      <div class="icon-wrap" aria-hidden="true">
        <!-- icon -->
        <svg viewBox="0 0 24 24" class="magnifier" xmlns="http://www.w3.org/2000/svg">
          <path d="M10.5 3a7.5 7.5 0 105.303 12.803l4.197 4.197 1.414-1.414-4.197-4.197A7.5 7.5 0 0010.5 3zm0 2a5.5 5.5 0 110 11 5.5 5.5 0 010-11z" fill="#000"/>
        </svg>
      </div>

      <p class="lead">Please enter your number<br>to search for your account.</p>

  <form class="find-form" action="enter-code.php" method="get">
        <input class="input" name="q" type="text" placeholder="Nomor Handphone" required>

        <div class="btn-row">
          <button type="button" class="btn btn-muted" onclick="window.location.href='../Daftar login/Login.php'">Cancel</button>
          <button type="submit" class="btn btn-primary" onclick="window.location.href='../Daftar login/enter-code.php'">Search</button>
        </div>
      </form>

    </section>
  </main>
</body>
</html>