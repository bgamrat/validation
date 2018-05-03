<?php
// Success is so ephermal anyway
$success = null;

// Read the .ini file, with sections
$ini = parse_ini_file( 'contact-us.ini', true );

// Set all the validation
$definition = [
    'contact-name' => [ 'filter' => FILTER_VALIDATE_REGEXP,
        'options' => ['regexp' => '/^' . $ini['contact-name']['V'] . '$/i']],
    'email' => ['filter' => FILTER_VALIDATE_EMAIL],
    'message' => [ 'filter' => FILTER_VALIDATE_REGEXP,
        'options' => ['regexp' => $ini['message']['V']]],
    'subscribe' => ['filter' => FILTER_VALIDATE_REGEXP,
        'options' => ['regexp' => '/^on$/', 'default' => 'off']]
];

// Prepare an array of values which will be used to set the input values
$data = array_fill_keys( array_keys( $definition ), '' );

$method = $_SERVER['REQUEST_METHOD'];
// If this is a form submission
if( $method === 'POST' )
{
    // Be optimistic!
    $success = true;

    // Handle the validation
    $data = filter_input_array( INPUT_POST, $definition );

    // Loop through all the inputs
    foreach( $data as $name => $d )
    {
        // Validate the data and copy the submitted values for display
        // to make it easier for user's to correct them
        if( empty( $d ) && $ini[$name]['R'] === "true" )
        {
            $success = false;
            $data[$name] = $_POST[$name];
        }
    }
}
else
{
    // This is a new page load, set the default values
    foreach( $ini as $name => $section )
    {
        if( !empty( $section['D'] ) )
        {
            $data[$name] = $section['D'];
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Contact Us</title>
        <link href="contact-us.css" rel="stylesheet">
    </head>
    <body>
        <div class="contact-us">
            <h1>Contact Us</h1>
            <div id="status">
                <?php if( $success === true ) : ?>
                    <div class="success">
                        Success
                    </div>
                <?php else : ?>
                    <!-- Display any error messages -->
                    <?php if( $success === false ) : ?>
                        <div class="error">
                            <ul>
                                <?php foreach( $data as $d => $value ) : ?>
                                    <?php if( empty( $value ) && $ini[$d]['R'] === "true" && !empty( $ini[$d]['M'] ) ) : ?>
                                        <li><?= $ini[$d]['M'] ?></li>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>
                <?php endif ?>
            </div>
            <!-- The novalidate attribute will allow the data in the form to be sent
                to the server, even if it is not valid -->
            <form class="contact-form" action="#" method="post" <?php if( isset( $_GET['novalidate'] ) ) : ?>novalidate<?php endif ?>>
                <div class="block">
                    <label for="contact-name">Name</label>
                    <input id="contact-name" name="contact-name" type="text" pattern="<?= $ini['contact-name']['V'] ?>" <?php if( $ini['contact-name']['R'] === 'true' ) : ?>required<?php endif ?> value="<?= $data['contact-name'] ?>">
                </div>
                <div class="block">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" <?php if( $ini['email']['R'] === 'true' ) : ?>required<?php endif ?>  value="<?= $data['email'] ?>">
                </div>
                <div class="block">
                    <label for="message">Message</label>
                    <textarea <?php if( $method === 'GET' ) : ?>class="valid"<?php endif ?> id="message" name="message" data-pattern="<?= $ini['message']['V'] ?>" maxlength="<?= $ini['message']['L'] ?>" <?php if( $ini['message']['R'] === 'true' ) : ?>required<?php endif ?>><?= $data['message'] ?></textarea>
                </div>
                <div class="block">
                    <label for="subscribe"><input id="subscribe" name="subscribe" type="checkbox" checked> Subscribe</label>
                    <div class="block buttons">
                        <button type="submit">Send</button>
                    </div>
                </div>
            </form>
        </div>
        <script src="contact-us.js"></script>
    </body>
</html>