<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= PAGE_TITLE ?></title>
	<!-- css -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous"/>
  <link rel="stylesheet" href="/assets/css/style.css">
<body>
<div class="flex-container">
<header class="border-end border-2 text-bg-white">
	<div class="sticky-top container vh-100 p-3 d-flex flex-column">
		<h1 class="fs-3 fw-bold mb-0">オートコールシステム</h1>
		<hr>
		<ul class="nav nav-pills flex-column mb-auto">
			<li class="nav-item">
				<a class="nav-link <?= $_SERVER["REQUEST_URI"] === "/" ? "active" : "link-body-emphasis" ?>" href="/">
					<i class="fa-solid fa-house d-inline-block fa-lg" style="width: 28px;"></i>ホーム（ダッシュボード）
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?= $_SERVER["REQUEST_URI"] === "/surveys/create" ? "active" : "link-body-emphasis" ?>" href="/surveys/create">
					<i class="fa-solid fa-square-plus d-inline-block fa-lg" style="width: 28px;"></i>新規作成
				</a>
			</li>
			<li class="nav-item">
				<ul class="nav nav-pills flex-column">
					<li class="nav-item">
						<a class="nav-link <?= $surveyId == 1 ? "active" : "link-body-emphasis" ?>" href="/surveys/1">アンケート1</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= $surveyId == 2 ? "active" : "link-body-emphasis" ?>" href="/surveys/2">アンケート2</a>
						<ul class="nav nav-pills flex-column ps-4">
							<li class="nav-item">
								<a class="nav-link link-body-emphasis" href="/surveys/2#faqs">質問一覧</a>
							</li>
							<li class="nav-item">
								<a class="nav-link link-body-emphasis" href="/surveys/2#calendar">カレンダー</a>
							</li>
							<li class="nav-item">
								<a class="nav-link link-body-emphasis" href="/surveys/2#area">エリア</a>
							</li>
						</ul>
					</li>
				</ul>
			</li>
			<li class="nav-item">
				<a class="nav-link <?= $_SERVER["REQUEST_URI"] === "/support" ? "active" : "link-body-emphasis" ?>">
					<i class="fa-solid fa-circle-question d-inline-block fa-lg" style="width: 28px;"></i>サポート
				</a>
			</li>
		</ul>
		<hr>
		<div class="dropdown">
			<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
				<?= Session::get("userEmail") ?>
			</button>
			<ul class="dropdown-menu dropdown-menu-dark">
				<li><a class="dropdown-item" href="/account">アカウント設定</a></li>
				<li><hr class="dropdown-divider"></li>
				<li>
					<form action="/logout" method="post">
						<button class="dropdown-item" href="/logout">ログアウト</button>
					</form>
				</li>
			</ul>
		</div>
	</div>
</header>
<main>
<div class="main-container">