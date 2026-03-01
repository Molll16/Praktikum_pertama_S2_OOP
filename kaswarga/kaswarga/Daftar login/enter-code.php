<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Enter Security Code</title>
  <link rel="stylesheet" href="enter-code.css">
  <link rel="shortcut icon" href="ICONB.png" type="image/x-icon">
</head>
<body>
  <main class="page">
    <section class="card">
      <div class="card-inner">
        <div class="left">
          <h1>Enter Security Code</h1>
          <p class="desc">Please check your number for a message with your code.</p>
          
          <form class="code-form" onsubmit="event.preventDefault(); alert('Berhasil (demo)'); window.location.href='../Daftar login/Login.php' ">
            <input class="code-input" type="text" placeholder="Enter Code" required>
            <div class="action-row">
              <button class="btn btn-primary" type="submit">Continue</button>
            </div>
          </form>
        </div>
    </section>
  </main>
</body>
</html>