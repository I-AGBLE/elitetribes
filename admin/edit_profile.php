<?php

require_once 'partials/header.php';

// Verify user is logged in and session user_id is valid
if (empty($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: " . ROOT_URL);
    exit();
}

// Fetch user details 
if (isset($_GET['id'])) {
    // Validate and sanitize ID
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($id === false || $id <= 0) {
        header("Location: " . ROOT_URL . "admin/");
        exit();
    }

    // Verify the user can only edit their own profile
    if ((int)$_SESSION['user_id'] !== $id) {
        $_SESSION['edit_profile'] = "You can only edit your own profile!";
        header("Location: " . ROOT_URL . "admin/profiles.php?id=" . (int)$_SESSION['user_id']);
        exit();
    }

    // Fetch user data
    $query = "SELECT * FROM tribesmen WHERE id=?";
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        header("Location: " . ROOT_URL . "admin/");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result || mysqli_num_rows($result) == 0) {
        header("Location: " . ROOT_URL . "admin/");
        exit();
    }

    $tribesmen = mysqli_fetch_assoc($result);
    $about = htmlspecialchars($tribesmen['about'] ?? '', ENT_QUOTES, 'UTF-8');
    $gender = htmlspecialchars($tribesmen['gender'] ?? '', ENT_QUOTES, 'UTF-8');
    mysqli_stmt_close($stmt);
} else {
    header("Location: " . ROOT_URL . "admin/");
    exit();
}

// CSRF token generation
if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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
                <h2>Editing Profile.</h2>
            </div>
            <div class="editing_sub">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore magni consectetur tempora vel aspernatur illum!</p>
            </div>
        </div>

        <form action="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/edit_profile_logic.php" enctype="multipart/form-data" method="post" autocomplete="off">

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($tribesmen['id'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="previous_avatar" value="<?= htmlspecialchars($tribesmen['avatar'], ENT_QUOTES, 'UTF-8') ?>">

            <div class="post_field">
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($tribesmen['username'], ENT_QUOTES, 'UTF-8') ?>" placeholder="Username" autofocus>

                <input type="tel" name="telephone" value="<?= htmlspecialchars($tribesmen['telephone'], ENT_QUOTES, 'UTF-8') ?>" placeholder="Telephone">

                <select name="gender">
                    <option value="" disabled <?= $gender == '' ? 'selected' : '' ?>>Gender</option>
                    <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Non-binary" <?= $gender == 'Non-binary' ? 'selected' : '' ?>>Non-binary</option>
                    <option value="Prefer not to say" <?= $gender == 'Prefer not to say' ? 'selected' : '' ?>>Prefer not to say</option>
                </select>

                <input type="email" name="email" value="<?= htmlspecialchars($tribesmen['email'], ENT_QUOTES, 'UTF-8') ?>" placeholder="Email">

                <textarea name="about" maxlength="500"  placeholder="This is where each story begins. Write few lines about who you are, what you do, and what the Tribesmen should know about you. Keep it real, keep it you.."><?= $about ?></textarea>

                <label for="avatar">
                    <i class="fa-solid fa-image"></i>
                </label>
                <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/gif,image/jpg, image/webp, image/svg" style="display: none;" />

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
require_once '../partials/footer.php';
?>