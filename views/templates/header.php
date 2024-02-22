<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= PAGE_TITLE ?></title>
	<link rel="stylesheet" href="<?= url("/assets/css/bootstrap.css") ?>">
	<link rel="stylesheet" href="<?= url("/assets/css/markdown.css") ?>">
	<link rel="stylesheet" href="<?= url("/assets/css/style.css") ?>">
	<script src="https://kit.fontawesome.com/285c1d0655.js" crossorigin="anonymous"></script>
	<script src="<?= url("/assets/script/dist/main.js") ?>" defer></script>
<body>
<header class="sticky-top" id="header-navber">
	<nav class="navbar navbar-expand-md bg-body-tertiary">
		<div class="container-fluid">
			<a class="navbar-brand" href="/home">
				<?= PAGE_TITLE ?>
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a
						class="nav-link"
						href="/home"
						>
							<span class="text-center d-inline-block me-1" style="width: 24px;">
								<i class="fa-solid fa-house"></i>
							</span>ホーム
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link link-body-emphasis" href="/home#create">
							<span class="text-center d-inline-block me-1" style="width: 24px;">
								<i class="fa-solid fa-square-plus"></i>
							</span>新規作成
						</a>
					</li>
					<?php if ($sv = Fetch::find("surveys", Auth::user()["id"], "user_id")): ?>
						<li class="nav-item">
							<a
							class="nav-link"
							href="/surveys/<?= $sv["id"] ?>"
							>
							<?= $sv["title"] ?>
							</a>
						</li>
					<?php endif; ?>
					<?php if (Auth::user()["status"] === USER_STATUS_ADMIN): ?>
						<li class="nav-item dropdown">
							<button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
								管理者メニュー
							</button>
							<ul class="dropdown-menu">
								<li class="dropdown-item">
									<a href="/admin/users" class="nav-link">
										<span class="text-center d-inline-block me-1" style="width: 24px;">
											<i class="fa-solid fa-users"></i>
										</span>ユーザー管理
									</a>
								</li>
								<li class="dropdown-item">
									<a href="/admin/receive_result_log" class="nav-link <?= $_SERVER["REQUEST_URI"] === "/admin/receive_result_log" ? "active" : "link-body-emphasis" ?>">
										<span class="text-center d-inline-block me-2" style="width: 24px;">
											<i class="fa-solid fa-file-arrow-down fa-lg"></i>
										</span>結果ファイル受信ログ
									</a>
								</li>
							</ul>
						</li>
					<?php endif; ?>
					<li class="nav-item">
						<a href="/support" class="nav-link">
							<span class="text-center d-inline-block me-1" style="width: 24px;">
								<i class="fa-solid fa-circle-question"></i>
							</span>ドキュメント
						</a>
					</li>
					<li class="nav-item dropdown">
						<button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
							<?= Auth::user()["email"] ?>
						</button>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="/account">アカウント設定</a></li>
							<li><hr class="dropdown-divider"></li>
							<li>
								<form action="/logout" method="post">
									<?= csrf() ?>
									<button class="dropdown-item" href="/logout">ログアウト</button>
								</form>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</header>
<div class="flex-container">
	<header class="border-end border-2 text-bg-white" id="header-sideber">
		<div class="sticky-top container vh-100 p-3 d-flex flex-column">
			<h1 class="fs-3 fw-bold mb-0">
				<a href="/home" class="text-black" style="text-decoration: none;"><?= PAGE_TITLE ?></a>
			</h1>
			<hr>
			<nav id="navbar-example2" class="mb-auto">
				<ul class="nav nav-pills flex-column">
					<li class="nav-item">
						<a
						class="nav-link <?= $_SERVER["REQUEST_URI"] === "/home" ? "active" : "link-body-emphasis" ?>"
						href="/home"
						>
							<span class="text-center d-inline-block me-2" style="width: 24px;">
								<i class="fa-solid fa-house fa-lg"></i>
							</span>ホーム
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link link-body-emphasis" href="/home#create">
							<span class="text-center d-inline-block me-2" style="width: 24px;">
								<i class="fa-solid fa-square-plus fa-lg"></i>
							</span>新規作成
						</a>
					</li>
					<li class="nav-item my-2 p-1 border border-2 rounded-2">
						<?php if ($sv = Fetch::find("surveys", Auth::user()["id"], "user_id")): ?>
							<ul class="nav nav-pills flex-column">
								<li class="nav-item">
									<a
									class="nav-link <?= $_SERVER["REQUEST_URI"] === "/surveys/{$sv["id"]}" ? "active" : "link-body-emphasis" ?>"
									href="/surveys/<?= $sv["id"] ?>"
									>
									<?= $sv["title"] ?>
									</a>
									<ul class="nav nav-pills flex-column ps-4 pt-1">
										<li class="nav-item">
											<a class="nav-link" href="/surveys/<?= $sv["id"] ?>#greeting-ending">グリーティング・エンディング</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="/surveys/<?= $sv["id"] ?>#faqs">質問一覧</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="/surveys/<?= $sv["id"] ?>#calendar">カレンダー</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="/surveys/<?= $sv["id"] ?>#area">エリア</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="/surveys/<?= $sv["id"] ?>#billing">料金</a>
										</li>
										<li class="nav-item">
											<a
												class="nav-link <?= $_SERVER["REQUEST_URI"] === "/surveys/{$sv["id"]}/calls" ? "active" : "link-body-emphasis" ?>"
												href="/surveys/<?= $sv["id"] ?>/calls"
											>
												コール一覧
											</a>
										</li>
									</ul>
								</li>
							</ul>
						<?php else: ?>
							<div class="px-3 py-2 text-center">
								<p>アンケートがありません</p>
								<a class="btn btn-outline-info" href="/home#create">アンケートを作成する</a>
							</div>
						<?php endif; ?>
					</li>
					<li class="nav-item">
						<a href="/support" class="nav-link <?= $_SERVER["REQUEST_URI"] === "/support" ? "active" : "link-body-emphasis" ?>">
							<span class="text-center d-inline-block me-2" style="width: 24px;">
								<i class="fa-solid fa-circle-question fa-lg"></i>
							</span>ドキュメント
						</a>
					</li>
					<?php if (Auth::user()["status"] === USER_STATUS_ADMIN): ?>
						<li class="nav-item my-2 p-1 border border-2 rounded-2">
							<h4 class="fs-6">管理者メニュー</h4>
							<a href="/admin/users" class="nav-link <?= $_SERVER["REQUEST_URI"] === "/admin/users" ? "active" : "link-body-emphasis" ?>">
								<span class="text-center d-inline-block me-2" style="width: 24px;">
									<i class="fa-solid fa-users fa-lg"></i>
								</span>ユーザー管理
							</a>
							<a href="/admin/gen_reserve_log" class="nav-link <?= $_SERVER["REQUEST_URI"] === "/admin/gen_reserve_log" ? "active" : "link-body-emphasis" ?>">
								<span class="text-center d-inline-block me-2" style="width: 24px;">
									<i class="fa-solid fa-file-arrow-up fa-lg"></i>
								</span>予約情報ファイル生成ログ
							</a>
							<a href="/admin/receive_result_log" class="nav-link <?= $_SERVER["REQUEST_URI"] === "/admin/receive_result_log" ? "active" : "link-body-emphasis" ?>">
								<span class="text-center d-inline-block me-2" style="width: 24px;">
									<i class="fa-solid fa-file-arrow-down fa-lg"></i>
								</span>結果ファイル受信ログ
							</a>
						</li>
					<?php endif; ?>
				</ul>
				<?php if (Auth::user()["status"] === 2): ?>
					<div class="alert alert-danger mt-2 mb-0" role="alert">
						<span class="text-danger">
							<i class="fa-solid fa-circle-exclamation"></i>
						</span>
						アカウントが利用停止されています<br>
						<small>ご利用を再開するには管理者に<a href="/support">お問い合わせ</a>ください</small>
					</div>
				<?php endif; ?>
			</nav>
			<hr>
			<div class="dropdown">
				<button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
					<?= Auth::user()["email"] ?>
				</button>
				<ul class="dropdown-menu dropdown-menu-dark">
					<li><a class="dropdown-item" href="/account">アカウント設定</a></li>
					<li><hr class="dropdown-divider"></li>
					<li>
						<form action="/logout" method="post">
							<?= csrf() ?>
							<button class="dropdown-item" href="/logout">ログアウト</button>
						</form>
					</li>
				</ul>
			</div>
		</div>
	</header>
<main>
<div class="main-container position-relative">