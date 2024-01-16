<?php require './views/templates/header.php'; ?>
<main>
  <h2>ログイン中のユーザー</h2>
  <p>id: <?= $_SESSION["userId"] ?></p>
  <p>email: <?= $_SESSION["userEmail"] ?></p>
  <form action="/logout" method="post">
    <input type="submit" value="ログアウト">
  </form>
  
	<div class="main-container px-3">
		<div class="alert alert-primary" role="alert">
			A simple primary alert—check it out!
			<i class="bi-alarm"></i><i class="bi bi-person-circle"></i>
		</div>
	</div>
</main>
<?php require './views/templates/footer.php'; ?>