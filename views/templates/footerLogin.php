<?php if ($toast = Session::get("toast")): ?>
	<div class="toast-container position-fixed bottom-0 start-50 p-3 translate-middle-x">
		<div class="toast align-items-center text-bg-<?= $toast["type"] ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="d-flex">
				<div class="toast-body"><?= $toast["text"] ?></div>
				<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>
	</div>
	<?php Session::delete("toast"); ?>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/285c1d0655.js" crossorigin="anonymous"></script>
<script src="/assets/script/dist/main.js"></script>
</body>
</html>