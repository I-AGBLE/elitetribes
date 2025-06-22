   <footer>
       <center>
           <div class="home_button">
               <a href="<?= htmlspecialchars(ROOT_URL . 'admin/', ENT_QUOTES, 'UTF-8') ?>"><i class="fa-solid fa-house"></i></a>
           </div>

           <div class="footer_link">
               <a href="#">elite<span>Tribes</span>&trade; v1.0 Copyright &copy2025</a>
           </div>
       </center>
   </footer>



   <script>
       document.addEventListener('DOMContentLoaded', function() {
           const avatarInput = document.getElementById('avatar');
           const fileNamesDiv = document.getElementById('file-names');
           if (!avatarInput || !fileNamesDiv) return;

           avatarInput.addEventListener('change', function() {
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