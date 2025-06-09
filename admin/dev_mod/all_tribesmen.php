<?php
include 'partials/header.php';



if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    // Redirect unauthorized access
    header('Location: ' . ROOT_URL);
    exit;
}

$id = (int) $_SESSION['user_id'];

// Check if user is admin
$stmt = $connection->prepare("SELECT is_admin FROM tribesmen WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $user = $result->fetch_assoc()) {
    if ((int)$user['is_admin'] !== 1) {
        // Not an admin – redirect to scrolls section
        header('Location: ' . ROOT_URL . 'admin/');
        exit;
    }

} else {
    // User not found – redirect
    header('Location: ' . ROOT_URL);
    exit;
}

$stmt->close();





// Sanitize and validate session user ID
$id = (int) $_SESSION['user_id'];

// Fetch all users from the tribesmen table (newest first)
$stmt = $connection->prepare("SELECT id, username, avatar, created_at FROM tribesmen ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();

$users = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
$stmt->close();
?>

<main>
    <section class="main_left">
        <!--Update -->
    </section>

    <section class="main_content">
        <div class="search_box">
            <center>
                <input type="text" placeholder="Search A Tribesman" id="my_following_box">
            </center>
        </div>

            <?php if (!empty($users)): ?>
                <div class="followings">
                    <div class="post">
                        <?php foreach ($users as $user): ?>
                            <div class="user_details" id="all_tribesmen_list">
                                <a href="<?= ROOT_URL ?>admin/profiles.php?id=<?= (int) $user['id'] ?>">
                                    <div class="user_profile_pic">
                                        <img src="<?= ROOT_URL ?>images/<?= htmlspecialchars($user['avatar'], ENT_QUOTES, 'UTF-8') ?>" 
                                             alt="<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>'s profile picture." />
                                    </div>
                                    <div class="username">
                                        <h4><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></h4>
                                    </div>
                                </a>

                                <div class="user_details_post_time">
                                    <div class="post_date">
                                        <p><?= date("M d, Y", strtotime($user['created_at'])) ?></p>
                                    </div>
                                    <div class="post_time">
                                        <p><?= date("H:i", strtotime($user['created_at'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <p>No tribesmen found.</p>
            <?php endif; ?>
    </section>

    <section class="main_right">
        <!--Update -->
    </section>
</main>

<?php
include 'partials/floating_input.php';
include '../../partials/footer.php';
?>
