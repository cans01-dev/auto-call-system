<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= PAGE_TITLE ?></title>
	<link rel="stylesheet" href="/assets/css/bootstrap.css">
	<link rel="stylesheet" href="/assets/css/style.css">
<body>
<div class="flex-container">
<header class="border-end border-2 text-bg-white">
	<div class="sticky-top container vh-100 p-3 d-flex flex-column">
		<h1 class="fs-3 fw-bold mb-0"><?= PAGE_TITLE ?></h1>
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
					<ul class="nav nav-pills flex-column">
						<?php foreach (Fetch::get("surveys", Auth::user()["id"], "user_id") as $sv): ?>
						<li class="nav-item">
							<a
							class="nav-link <?= $_SERVER["REQUEST_URI"] === "/surveys/{$sv["id"]}" ? "active" : "link-body-emphasis" ?>"
							href="/surveys/<?= $sv["id"] ?>"
							>
							<?= $sv["title"] ?>
							</a>
							<?php if ($_SERVER["REQUEST_URI"] === "/surveys/{$sv["id"]}"): ?>
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
								</ul>
							<?php endif; ?>
						</li>
						<?php endforeach ?>
					</ul>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= $_SERVER["REQUEST_URI"] === "/support" ? "active" : "link-body-emphasis" ?>">
						<span class="text-center d-inline-block me-2" style="width: 24px;">
							<i class="fa-solid fa-circle-question fa-lg"></i>
						</span>サポート
					</a>
				</li>
			</ul>
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