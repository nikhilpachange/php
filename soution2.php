2. Fix the below problem where we are allowed to pass in empty email values, but the email key should be there.


<?php
$data = array(
    'user' => array(
        'name'  => 'Alice',
        'email' => '',
    ),
);

if ( array_key_exists('email', $data['user']) ) {
    echo 'Email key is provided.' . "\n";
} else {
    echo 'Email key is missing.' . "\n";
}
?>
