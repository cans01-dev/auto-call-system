<?php require './views/templates/header.php'; ?>
<main>
  <h2>ログイン</h2>
  <div>
    <form action="/login" method="post">
      <div>
        <label for="">email:<input type="email" name="email"></label>
      </div>
      <div>
        <label for="">password:<input type="password" name="password"></label>
      </div>
      <div>
        <input type="hidden" name="token" value="<?php echo Session::get("token"); ?>">
        <input type="submit" value="ログイン">
      </div>
    </form>
  </div>
</main>
<?php require './views/templates/footer.php'; ?>