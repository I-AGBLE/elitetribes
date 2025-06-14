<?php


include 'partials/header.php';

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . ROOT_URL . "signin.php");
    die();
}

// Fetch user details securely
if (isset($_GET['id'])) {
    // Validate and sanitize ID
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if (!$id || $id <= 0) {
        header("Location: " . ROOT_URL . "admin/");
        die();
    }

    // Verify the user can only edit their own profile
    if ($_SESSION['user_id'] != $id) {
        $_SESSION['edit_profile'] = "You can only edit your own profile!";
        header("Location: " . ROOT_URL . "admin/profiles.php?id=" . $_SESSION['user_id']);
        die();
    }

    // Use prepared statement to fetch user data
    $query = "SELECT * FROM tribesmen WHERE id=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        header("Location: " . ROOT_URL . "admin/");
        die();
    }

    $tribesmen = mysqli_fetch_assoc($result);
    $about = htmlspecialchars($tribesmen['about'], ENT_QUOTES, 'UTF-8');
    $gender = htmlspecialchars($tribesmen['gender'], ENT_QUOTES, 'UTF-8');
} else {
    header("Location: " . ROOT_URL . "admin/");
    die();
}
?>

<main>
    <?php if (isset($_SESSION['edit_profile'])) : ?>
        <div class="alert_message error" id="alert_message">
            <p>
                <?= htmlspecialchars($_SESSION['edit_profile'], ENT_QUOTES, 'UTF-8');
                unset($_SESSION['edit_profile']);
                ?>
            </p>
        </div>
    <?php endif ?>

    <div class="main_log">
        <div class="editing_profile">
            <div class="editing_title">
                <h2>Editing Profile For Good.</h2>
            </div>
            <div class="editing_sub">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore magni consectetur tempora vel aspernatur illum!</p>
            </div>
        </div>

        <form action="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/edit_profile_logic.php" enctype="multipart/form-data" method="post">
            <!-- CSRF Protection -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($tribesmen['id'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="previous_avatar" value="<?= htmlspecialchars($tribesmen['avatar'], ENT_QUOTES, 'UTF-8') ?>">

            <div class="post_field">
                <input type="text" name="username"  id="username" value="<?= $tribesmen['username'] ?>" placeholder="Username" autofocus>

                <input type="tel" name="telephone" value="<?= $tribesmen['telephone'] ?>" placeholder="Telephone">

                <select name="gender">
                    <option value="" disabled <?= $gender == '' ? 'selected' : '' ?>>Gender</option>
                    <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Non-binary" <?= $gender == 'Non-binary' ? 'selected' : '' ?>>Non-binary</option>
                    <option value="Prefer not to say" <?= $gender == 'Prefer not to say' ? 'selected' : '' ?>>Prefer not to say</option>
                </select>

                <input type="email" name="email" value="<?= $tribesmen['email'] ?>" placeholder="Email">

                <textarea name="about" placeholder="About me."><?= $about ?></textarea>

                <label for="avatar">
                    <i class="fa-solid fa-image"></i>
                </label>
                <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/gif" style="display: none;" />

                <!-- Where the selected file names will be shown -->
                <div id="file-names"></div>


                <input class="confirm_human" type="text" name="confirm_human" placeholder="confirm_human">
            </div>

            <input type="submit" name="submit" value="Update">

            <div class="delete_profile">
                <p>
                    Looking to leave the tribesmen? Click
                    <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/delete_profile.php?id=<?= htmlspecialchars($tribesmen['id'], ENT_QUOTES, 'UTF-8') ?>" class="delete_btn">here</a>
                    to delete your account.
                </p>
            </div>
        </form>
    </div>
</main>

<?php
include '../partials/footer.php';
?>