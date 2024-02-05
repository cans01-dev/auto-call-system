</div>
</main>
</div>

<?= ($toast = Session::get("toast")) ? Components::toast($toast): ""; ?>

</body>
</html>