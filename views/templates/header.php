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
						<a class="nav-link" href="/home">
							<span class="text-center d-inline-block me-1" style="width: 24px;">
								<i class="fa-solid fa-house"></i>
							</span>ホーム
						</a>
					</li>
					<?php if ($sv = Fetch::find("surveys", Auth::user()["id"], "user_id")): ?>
						<li class="nav-item">
							<a class="nav-link" href="/surveys/<?= $sv["id"] ?>">
								<span class="text-center d-inline-block me-1" style="width: 24px;">
									<i class="fa-solid fa-comments"></i>
								</span>会話と音声
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/surveys/<?= $sv["id"] ?>/calendar">
								<span class="text-center d-inline-block me-1" style="width: 24px;">
									<i class="fa-solid fa-calendar"></i>
								</span>カレンダー
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/surveys/<?= $sv["id"] ?>/stats">
								<span class="text-center d-inline-block me-1" style="width: 24px;">
									<i class="fa-solid fa-chart-simple"></i>
								</span>統計
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/surveys/<?= $sv["id"] ?>/calls">
								<span class="text-center d-inline-block me-1" style="width: 24px;">
									<i class="fa-solid fa-phone"></i>
								</span>コール一覧
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
										<span class="text-center d-inline-block me-2" style="width: 24px;">
											<i class="fa-solid fa-users"></i>
										</span>ユーザー管理
									</a>
								</li>
								<li class="dropdown-item">
									<a href="/admin/reserves" class="nav-link">
										<span class="text-center d-inline-block me-2" style="width: 24px;">
											<i class="fa-solid fa-table-list fa-lg"></i>
										</span>全ての予約
									</a>
								</li>
								<li class="dropdown-item">
									<a href="/admin/gen_reserve_log" class="nav-link">
										<span class="text-center d-inline-block me-2" style="width: 24px;">
											<i class="fa-solid fa-file-arrow-up fa-lg"></i>
										</span>予約情報ファイル生成ログ
									</a>
								</li>
								<li class="dropdown-item">
									<a href="/admin/receive_result_log" class="nav-link">
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
				<ul class="nav nav-pills vstack gap-2">
					<li class="nav-item">
						<a
						class="nav-link <?= $_SERVER["REDIRECT_URL"] === "/home" ? "active" : "link-body-emphasis" ?>"
						href="/home"
						>
							<span class="text-center d-inline-block me-2" style="width: 24px;">
								<i class="fa-solid fa-house fa-lg"></i>
							</span>ホーム
						</a>
					</li>
					<?php if ($sv = Fetch::find("surveys", Auth::user()["id"], "user_id")): ?>
						<li class="nav-item">
							<a
								class="nav-link <?= $_SERVER["REDIRECT_URL"] === "/surveys/{$sv["id"]}" ? "active" : "link-body-emphasis" ?>"
								href="/surveys/<?= $sv["id"] ?>"
							>
								<span class="text-center d-inline-block me-2" style="width: 24px;">
									<i class="fa-solid fa-comments fa-lg"></i>
								</span>会話と音声
							</a>
						</li>
						<li class="nav-item">
							<a
								class="nav-link <?= $_SERVER["REDIRECT_URL"] === "/surveys/{$sv["id"]}/calendar" ? "active" : "link-body-emphasis" ?>"
								href="/surveys/<?= $sv["id"] ?>/calendar"
							>
								<span class="text-center d-inline-block me-2" style="width: 24px;">
									<i class="fa-solid fa-calendar fa-lg"></i>
								</span>カレンダー
							</a>
						</li>
						<li class="nav-item">
							<a
								class="nav-link <?= $_SERVER["REDIRECT_URL"] === "/surveys/{$sv["id"]}/stats" ? "active" : "link-body-emphasis" ?>"
								href="/surveys/<?= $sv["id"] ?>/stats"
							>
								<span class="text-center d-inline-block me-2" style="width: 24px;">
									<i class="fa-solid fa-chart-simple fa-lg"></i>
								</span>統計
							</a>
						</li>
						<li class="nav-item">
							<a
								class="nav-link <?= $_SERVER["REDIRECT_URL"] === "/surveys/{$sv["id"]}/calls" ? "active" : "link-body-emphasis" ?>"
								href="/surveys/<?= $sv["id"] ?>/calls"
							>
								<span class="text-center d-inline-block me-2" style="width: 24px;">
									<i class="fa-solid fa-phone fa-lg"></i>
								</span>コール一覧
							</a>
						</li>
					<?php else: ?>
						<div class="px-3 py-2 text-center">
							<p>アンケートがありません</p>
							<a class="btn btn-outline-info" href="/home#create">アンケートを作成する</a>
						</div>
					<?php endif; ?>
					<li class="nav-item">
						<a href="/support" class="nav-link <?= $_SERVER["REDIRECT_URL"] === "/support" ? "active" : "link-body-emphasis" ?>">
							<span class="text-center d-inline-block me-2" style="width: 24px;">
								<i class="fa-solid fa-circle-question fa-lg"></i>
							</span>ドキュメント
						</a>
					</li>
					<?php if (Auth::user()["status"] === USER_STATUS_ADMIN): ?>
						<li class="nav-item my-2 p-1 border border-2 rounded-2">
							<h4 class="fs-6">管理者メニュー</h4>
							<a href="/admin/users" class="nav-link <?= $_SERVER["REDIRECT_URL"] === "/admin/users" ? "active" : "link-body-emphasis" ?>">
								<span class="text-center d-inline-block me-2" style="width: 24px;">
									<i class="fa-solid fa-users fa-lg"></i>
								</span>ユーザー管理
							</a>
							<a href="/admin/reserves" class="nav-link <?= $_SERVER["REDIRECT_URL"] === "/admin/reserves" ? "active" : "link-body-emphasis" ?>">
								<span class="text-center d-inline-block me-2" style="width: 24px;">
									<i class="fa-solid fa-table-list fa-lg"></i>
								</span>全ての予約
							</a>
							<a href="/admin/gen_reserve_log" class="nav-link <?= $_SERVER["REDIRECT_URL"] === "/admin/gen_reserve_log" ? "active" : "link-body-emphasis" ?>">
								<span class="text-center d-inline-block me-2" style="width: 24px;">
									<i class="fa-solid fa-file-arrow-up fa-lg"></i>
								</span>予約情報ファイル生成ログ
							</a>
							<a href="/admin/receive_result_log" class="nav-link <?= $_SERVER["REDIRECT_URL"] === "/admin/receive_result_log" ? "active" : "link-body-emphasis" ?>">
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
					<li><a class="dropdown-item" href="/users/<?= Auth::user()["id"] ?>">アカウント設定</a></li>
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