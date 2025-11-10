<?php
// We'll build a single array of all possible messages
$all_messages = [];
$session = session();

// 1. Check for a single 'success' message
if ($session->get('success')) {
    $all_messages[] = ['type' => 'success', 'message' => $session->get('success')];
}

// 2. Check for a single 'error' message
if ($session->get('error')) {
    $all_messages[] = ['type' => 'danger', 'message' => $session->get('error')];
}

// 3. Check for a single 'warning' message
if ($session->get('warning')) {
    $all_messages[] = ['type' => 'warning', 'message' => $session->get('warning')];
}

// 4. Check for a neutral 'message' (used by your PasswordController)
if ($session->get('message')) {
    $all_messages[] = ['type' => 'info', 'message' => $session->get('message')];
}

// 5. Check for validation errors (which is an array)
if ($session->get('errors')) {
    $errors = $session->get('errors');

    if (is_array($errors) && !empty($errors)) {
        // Format them as a list inside one alert
        $message_html = '<ul class="mb-0">';
        foreach ($errors as $error) {
            $message_html .= '<li>' . esc($error) . '</li>';
        }
        $message_html .= '</ul>';

        $all_messages[] = ['type' => 'danger', 'message' => $message_html];
    }
}

// Now, loop through and display all messages
if (!empty($all_messages)):
    ?>
    <?php foreach ($all_messages as $msg): ?>

    <div class="alert alert-<?= $msg['type'] ?> alert-dismissible fade show" role="alert">

        <?php
        // We check if the message is our pre-formatted HTML list
        // If it is, we don't escape it. All other simple messages are escaped.
        ?>
        <?php if ($msg['type'] === 'danger' && strpos($msg['message'], '<ul>') === 0): ?>
            <?= $msg['message'] // This is our HTML list of validation errors ?>
        <?php else: ?>
            <?= esc($msg['message']) // This is a simple text message ?>
        <?php endif; ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endforeach; ?>
<?php endif; ?>