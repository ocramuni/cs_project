<?php

/**
 * Connect to database
 *
 * @return false|mysqli
 * @throws Exception
 */
function dbLink(): false|mysqli
{
    static $mysqli = false;
    if(!$mysqli) {
        $db_host = getConfigVariable('db_host');
        $db_name = getConfigVariable('db_name');
        $db_user = getConfigVariable('db_user');
        $db_password = getConfigVariable('db_password');
        // Returns a MySQL link identifier on success or FALSE on failure
        $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);
    }
    return $mysqli;
}

/**
 * Get all users
 *
 * @return array
 * @throws Exception
 */
function getAllUsers(): array
{
    $mysqli = dbLink();
    $users = array();
    $query = "SELECT * FROM users";
    $result = $mysqli->query($query);

    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $users[] = $row;
    }
    return $users;
}

/**
 * Get all events
 *
 * @return array
 * @throws Exception
 */
function getAllEvents(): array
{
    $mysqli = dbLink();
    $events = array();
    $query = "SELECT * FROM events ORDER BY `event_date` DESC";
    $result = $mysqli->query($query);

    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $events[] = $row;
    }
    return $events;
}

/**
 * Get all categories of the events
 *
 * @return array
 * @throws Exception
 */
function getAllCategories(): array
{
    $mysqli = dbLink();
    $categories = array();
    $query = "SELECT id,description FROM event_categories";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $categories[] = $row;
    }
    return $categories;
}

/**
 * Get all tickets of all events
 *
 * @return array
 * @throws Exception
 */
function getAllTickets(): array
{
    $mysqli = dbLink();
    $tickets = array();
    $query = $mysqli->prepare("SELECT participations.id, participations.event_id, users.first_name, users.last_name, users.email, events.title, events.event_date
FROM participations 
    INNER JOIN users ON participations.user_id = users.id
    INNER JOIN events ON participations.event_id = events.id 
WHERE participations.winner = TRUE
ORDER BY event_date DESC");
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $tickets[] = $row;
    }
    return $tickets;
}

/**
 * Get all tickets of the user identified by id
 *
 * @param int $user_id
 * @return array
 * @throws Exception
 */
function getTicketsByUserID(int $user_id): array
{
    $mysqli = dbLink();
    $tickets = array();
    $query = $mysqli->prepare("SELECT participations.id, participations.event_id, events.title, events.event_date
FROM participations 
    INNER JOIN events ON participations.event_id = events.id 
WHERE participations.winner = TRUE AND participations.user_id = ?
ORDER BY event_date DESC");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $tickets[] = $row;
    }
    return $tickets;
}

/**
 * Get the ticket related to participation of the logged user
 *
 * @todo return a real ticket
 * @param int $participation_id
 * @return bool
 * @throws Exception
 */
function getTicketByParticipationID(int $participation_id): bool
{
    $user_details = isLogged();
    if ($user_details) {
        $mysqli = dbLink();
        $query = $mysqli->prepare("SELECT * FROM participations WHERE user_id = ? AND id = ?");
        $query->bind_param("ii", $user_details['id'], $participation_id);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows == 1) {
            return true;
        }
        return false;
    }
    return false;
}


/**
 * Get the role name
 *
 * @param int $id role id
 * @return string
 * @throws Exception
 */
function getRoleNameById(int $id): string
{
    $mysqli = dbLink();
    $query = $mysqli->prepare("SELECT name FROM roles WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        return $row['name'];
    }
    return false;
}

/**
 * Get the name of the category identified by id
 *
 * @param int $id category id
 * @return string
 * @throws Exception
 */
function getEventCategoryById(int $id): string
{
    $mysqli = dbLink();
    $query = $mysqli->prepare("SELECT name FROM event_categories WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        return $row['name'];
    }
    return false;
}

/**
 * Get all participants of the event identified by id
 *
 * @param int $id event id
 * @return array
 * @throws Exception
 */
function getParticipantsByEventId(int $id): array
{
    $mysqli = dbLink();
    $participants = array();
    $query = $mysqli->prepare("SELECT users.id, users.first_name, users.last_name, users.email 
FROM participations 
    INNER JOIN users ON participations.user_id = users.id 
WHERE participations.event_id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $participants[] = $row;
    }
    return $participants;
}

/**
 * Get participation record
 *
 * @param int $event_id
 * @param int $user_id
 * @return array|false
 * @throws Exception
 */
function getParticipation(int $event_id, int $user_id): array|false
{
    $mysqli = dbLink();
    $query = $mysqli->prepare("SELECT * FROM participations WHERE user_id = ? AND event_id = ?");
    $query->bind_param("ii", $user_id, $event_id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows == 1) {
        return $result->fetch_array(MYSQLI_ASSOC);
    }
    return false;
}

/**
 * Save participation record
 *
 * @param int $event_id
 * @param int $user_id
 * @return bool
 * @throws Exception
 */
function setParticipation(int $event_id, int $user_id): bool
{
    $mysqli = dbLink();
    $query = $mysqli->prepare("INSERT INTO participations (event_id, user_id) VALUES (?, ?)");
    $query->bind_param("ii", $event_id, $user_id);
    return $query->execute();
}

/**
 * Have the logged user participated in the event?
 *
 * @param int $event_id ID of the event
 * @return bool
 * @throws Exception
 */
function haveParticipated(int $event_id): bool
{
    $user_details = isLogged();
    if ($user_details) {
        $participation = getParticipation($event_id, $user_details['id']);
        if ($participation) {
            return true;
        }
    }
    return false;
}

/**
 * Get string from Docker secret file
 *
 * @param string $name Docker secret file name
 * @return string
 * @throws Exception
 */
function getSecret(string $name): string
{
    $filename = "/run/secrets/" . $name;
    if (!file_exists($filename)) {
        throw new Exception('Secret not found');
    }
    return rtrim(file_get_contents($filename));
}

/**
 * Get configuration variable
 *
 * @param string $name configuration variable name
 * @return string
 * @throws Exception
 */
function getConfigVariable(string $name): string
{
    return match ($name) {
        'PEPPER' => getSecret('php_pepper'),
        'DB_PASSWORD' => getSecret('db_password'),
        'SECRET' => getSecret('php_secret'),
        default => getSecret($name),
        //default => getenv($name, true),
    };
}

/**
 * Return password hash
 *
 * @param string $password password string
 * @return string
 * @throws Exception
 */
function getPasswordHash(string $password): string
{
    $pepper = getConfigVariable("PEPPER");
    $password_peppered = hash_hmac("sha256", $password, $pepper);
    return password_hash($password_peppered, PASSWORD_DEFAULT);
}

/**
 * Generate a keyed hash value using the HMAC method
 *
 * @param string $text string to hash
 * @return string
 * @throws Exception
 */
function getHash(string $text): string
{
    $hast_salt = getConfigVariable("SECRET");
    return hash_hmac("sha256", $text, $hast_salt);
}

/**
 * If user is logged return an array with user's details, otherwise an empty array
 *
 * @return array|null
 * @throws Exception
 */
function isLogged(): ?array
{
    if (isset($_SESSION['cs_project_loggedin']) and isset($_SESSION['controls'])) {
        if (hash_equals($_SESSION['controls'], getHash($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']))) {
            return $_SESSION['cs_project_loggedin'];
        }
    }
    return null;
}

/**
 * Is the logged user an admin?
 *
 * @return bool
 * @throws Exception
 */
function isAdmin(): bool
{
    if (isLogged()) {
        if (isset($_SESSION['cs_project_loggedin']['role'])) {
            $role = $_SESSION['cs_project_loggedin']['role'];
            if ($role == 'admin') {
                return true;
            }
        }
    }
    return false;
}

/**
 * Get the full name of the logged user
 *
 * @return string
 * @throws Exception
 */
function getUserFullName(): string
{
    $full_name = '';
    $user_details = isLogged();

    if ($user_details) {
        $full_name = $user_details['first_name'] . ' ' . $user_details['last_name'];
    }
    return $full_name;
}

/**
 * Sanitize user input data
 * trim(): strip unnecessary characters (extra space, tab, newline).
 * stripslashes(): remove backslashes.
 * htmlspecialchars(): converts special characters to HTML entities.
 *
 * @param $data
 * @return string
 */
function sanitize_input($data): string
{
    $sanitized_data = trim($data);
    $sanitized_data = stripslashes($sanitized_data);
    return htmlspecialchars($sanitized_data);
}

/**
 * Validate email format
 *
 * @param string $email
 * @return bool
 */
function isEmailValid(string $email): bool
{
    $email_to_validate = sanitize_input($email);
    if (!filter_var($email_to_validate, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

/**
 * Check if string contains only letters, dashes, apostrophes and whitespaces.
 *
 * @param string $string
 * @return bool
 */
function isStringValid(string $string): bool
{
    $string_to_validate = sanitize_input($string);
    if (!preg_match("/^[a-zA-Z-' ]*$/", $string_to_validate)) {
        return false;
    }
    return true;
}

/**
 * Date must be in ISO 8601 format (2025-06-11T11:11)
 *
 * @param string $datetime
 * @return bool
 */
function isDateValid(string $datetime): bool
{
    $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $datetime);
    $errors = DateTime::getLastErrors();
    if (!empty($errors['warning_count']) || empty($dateTime)) {
        return false;
    } else {
        return true;
    }
}

/**
 * Validate number as int
 *
 * @param int $number
 * @return bool
 */
function isIntValid(int $number): bool
{
    $number_to_validate = sanitize_input($number);
    if (!filter_var($number_to_validate, FILTER_VALIDATE_INT)) {
        return false;
    }
    return true;
}

/**
 * Check if string contains six-digit combination of numbers and letters, preceded by a # symbol
 *
 * @param string $color
 * @return bool
 */
function isColorValid(string $color): bool
{
    $color_to_validate = sanitize_input($color);
    if( preg_match('/^#[a-f0-9]{6}$/i', $color_to_validate) )
    {
        return true;
    } else {
        return false;
    }
}

/**
 * Save user setting
 *
 * @param string $name name of the setting
 * @param string $value value of the setting
 * @return void
 * @throws Exception
 */
function saveSetting(string $name, string $value): void
{
    $settings = getSettings();
    $settings[$name] = $value;
    saveSettings($settings);
}

/**
 * Get user setting
 *
 * @param string $name name of the setting
 * @return ?string
 * @throws Exception
 */
function getSetting(string $name): ?string
{
    $settings = getSettings();
    return $settings[$name] ?? null;
}

/**
 * Get the name of the cookie where is saved all the logged user's settings.
 *
 * @return string
 * @throws Exception
 */
function getSettingsCookieName(): string
{
    $user_details = isLogged();
    return getHash($user_details['id'] . $user_details['email']);
}

/**
 * Get all the logged user's settings.
 *
 * @return array
 * @throws Exception
 */
function getSettings(): array
{
    $cookie_name = getSettingsCookieName();
    if (isset($_COOKIE[$cookie_name])) {
        $ciphertext = $_COOKIE[$cookie_name];
        $plaintext = decryptMessage($ciphertext);
        if ($plaintext) {
            $settings = unserialize($plaintext);
            if ($settings) {
                return $settings;
            }
        }
    }
    return array();
}

/**
 * Save all the logged user's settings.
 *
 * @param array $settings
 * @return void
 * @throws SodiumException
 * @throws \Random\RandomException
 * @throws Exception
 */
function saveSettings(array $settings): void
{
    $cookie_name = getSettingsCookieName();
    $plaintext = serialize($settings);
    $ciphertext = encryptMessage($plaintext);
    $cookie_options = array (
        'expires' => strtotime("+1 year"),
        'path' => '/',
        'domain' => 'localhost',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    );
    setcookie($cookie_name, $ciphertext, $cookie_options);
}

/**
 * Remove the cookie with the logged user's settings.
 *
 * @return void
 * @throws Exception
 */
function resetSettings(): void
{
    $cookie_name = getSettingsCookieName();
    if (isset($_COOKIE[$cookie_name])) {
        $expire_date = time() -3600;
        setcookie($cookie_name, '', $expire_date);
    }
}

/**
 * Encrypt a message
 *
 * @param string $message
 * @return string
 * @throws SodiumException
 * @throws \Random\RandomException
 * @throws Exception
 */
function encryptMessage(string $message): string
{
    $key = getConfigVariable("SECRET");
    $secret_key = sodium_hex2bin($key);
    // A random number that must be only used once, per message. 24 bytes long.
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    // Encrypt the message using the secret key and nonce.
    $ciphertext = sodium_crypto_secretbox($message, $nonce, $secret_key);
    // Convert the encrypted message with the nonce to base64.
    return sodium_bin2base64($nonce . $ciphertext, SODIUM_BASE64_VARIANT_ORIGINAL);
}

/**
 * Decrypt a message
 *
 * @param string $message
 * @return string|null
 * @throws SodiumException
 * @throws Exception
 */
function decryptMessage(string $message): ?string
{
    $key = getConfigVariable("SECRET");
    $secret_key = sodium_hex2bin($key);
    // Convert the base64 encoded message to binary using sodium_base642bin().
    $ciphertext = sodium_base642bin($message, SODIUM_BASE64_VARIANT_ORIGINAL);
    // Extract the nonce from the beginning of the message (take the first 24 bytes).
    $nonce = mb_substr($ciphertext, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
    // The message is the rest of the ciphertext.
    $ciphertext = mb_substr($ciphertext, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
    // Decrypt the message with the secret key and nonce.
    $plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $secret_key);

    // If the plaintext is false, it means the message was corrupted.
    if ($plaintext === false) {
        return null;
    }
    return $plaintext;
}

/**
 * Get color of the avatar icon from user's settings.
 *
 * @return string
 * @throws Exception
 */
function getAvatarColor(): string
{
    $color = getSetting('color');
    if ($color) {
        $avatar_color = $color;
    } else {
        $avatar_color = '#0d6efd';
    }
    return $avatar_color;
}

/**
 * Check if password is in the right format
 *
 * @param string $password
 * @return string|null Error message or null
 */
function isPasswordValid(string $password): ?string
{
    $error = null;
    if (mb_strlen($password) <= 8) {
        $error = "Your Password Must Contain At Least 8 Characters!";
    }
    elseif(!preg_match("#[0-9]+#", $password)) {
        $error = "Your Password Must Contain At Least 1 Number!";
    }
    elseif(!preg_match("#[A-Z]+#", $password)) {
        $error = "Your Password Must Contain At Least 1 Capital Letter!";
    }
    elseif(!preg_match("#[a-z]+#", $password)) {
        $error = "Your Password Must Contain At Least 1 Lowercase Letter!";
    }
    elseif(!preg_match("#\W+#", $password)) {
        $error = "Your Password Must Contain At Least 1 Special Character!";
    }
    return $error;
}

/**
 * Draw n winners from a participants list
 *
 * @param array $participants
 * @param int $tickets
 * @return array
 * @throws \Random\RandomException
 */
function drawWinners(array $participants, int $tickets): array
{
    $winners = array();
    $tickets_number = (count($participants) >= $tickets) ? $tickets : count($participants);
    for ($i = 0; $i < $tickets_number; $i++) {
        $winner = random_int(0, count($participants) - 1);
        $winners[] = $participants[$winner];
        array_splice($participants, $winner, 1);
    }
    return $winners;
}

/**
 * Check if the winner record is valid
 *
 * @param array $winner
 * @param int $event_id
 * @return bool
 * @throws Exception
 */
function isWinner(array $winner, int $event_id): bool
{
    $participation = getParticipation($event_id, $winner['id']);
    if ($participation && $participation['winner']) {
        $checksum = getHash($winner['last_name'] . $winner['first_name'] . $winner['email']);
        if ($checksum === $participation['checksum']) {
            return true;
        }
    }
    return false;
}

/**
 * Check if there has been one draw for the event
 *
 * @param int $event_id
 * @return bool
 * @throws Exception
 */
function wasDrawn(int $event_id): bool
{
    $mysqli = dbLink();
    $query = $mysqli->prepare("SELECT DATE_FORMAT(draw_date, '%Y-%m-%dT%H:%i') AS draw_date FROM events WHERE `id` = ?");
    $query->bind_param("i", $event_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        if ($row['draw_date']) {
            return isDateValid($row['draw_date']);
        }
    }
    return false;
}

/**
 * Save a winner for an event
 *
 * @param array $winner
 * @param int $event_id
 * @return bool
 * @throws Exception
 */
function saveWinner(array $winner, int $event_id): bool
{
    $mysqli = dbLink();
    $checksum = getHash($winner['last_name'] . $winner['first_name'] . $winner['email']);
    $query = $mysqli->prepare("UPDATE participations SET `winner` = true, `checksum` = ? WHERE `event_id` = ? AND `user_id` = ?");
    $query->bind_param("sii", $checksum, $event_id, $winner['id']);
    return $query->execute();
}

/**
 * Save all the winners for an event
 *
 * @param array $winners
 * @param int $event_id
 * @return bool
 * @throws Exception
 */
function saveWinners(array $winners, int $event_id): bool
{
    foreach ($winners as $winner) {
        $result = saveWinner($winner, $event_id);
        if (!$result) {
            return false;
        }
    }
    return true;
}

/**
 * Save the draw date of an event
 *
 * @param int $event_id
 * @return bool
 * @throws Exception
 */
function setDrawDate(int $event_id): bool
{
    $mysqli = dbLink();
    date_default_timezone_set("Europe/Rome");
    $current_date = date("Y-m-d\TH:i");
    $query = $mysqli->prepare("UPDATE events SET `draw_date` = ? WHERE `id` = ?");
    $query->bind_param("si", $current_date, $event_id);
    return $query->execute();
}
