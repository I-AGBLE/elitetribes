<section class="footer">
    <center>
        <footer>
            <a href="#">elite<span>Tribes</span>&trade; v1.0 Copyright &copy2025</a>
        </footer>
    </center>
</section>



<script>
                document.addEventListener('DOMContentLoaded', function () {
                const avatarInput = document.getElementById('avatar');
                const fileNamesDiv = document.getElementById('file-names');
                if (!avatarInput || !fileNamesDiv) return;

                avatarInput.addEventListener('change', function () {
                    fileNamesDiv.innerHTML = '';
                    if (avatarInput.files && avatarInput.files.length > 0) {
                        const names = Array.from(avatarInput.files).map(f => f.name);
                        fileNamesDiv.textContent = names.join(', ');
                    }
                });
            });
</script>
</body>
</html>