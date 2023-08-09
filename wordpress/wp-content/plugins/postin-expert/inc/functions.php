<?php


function enqueue() {
    wp_enqueue_script('bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js');
    wp_enqueue_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', false, '4.6.1', 'all');
    wp_enqueue_style( 'ope_css', plugins_url('../css/style.css', __FILE__), false, '1.0.0', 'all');
}

add_action('wp_enqueue_scripts', 'enqueue');

/**
 * Get all valid experts
 *
 * @return void
 */
function getAllExperts()
{
	$remote_url = OPE_ENV . 'api/experts';
	$args = array(
		'sslverify'	  => false,
		'method'      => 'GET',
		'headers'     => array(
			'Content-Type'  => 'application/json',
		),
	);
	$response = wp_remote_get($remote_url, $args);

	// Vérifie si la requête a réussi
	if (is_wp_error($response)) {
		$error_message = $response->get_error_message();
	} else {
		$response_code = wp_remote_retrieve_response_code($response);
		$response_body = wp_remote_retrieve_body($response);
	}

	$experts = json_decode($response_body, true);

	return $experts;
}

/**
 * Get all expert info
 *
 * @return void
 */
function getAiExpert($username)
{
	$remote_url = OPE_ENV . 'api/expert/'.$username;
	$args = array(
		'sslverify'	  => false,
		'method'      => 'GET',
		'headers'     => array(
			'Content-Type'  => 'application/json',
		),
	);
	$response = wp_remote_get($remote_url, $args);

	// Vérifie si la requête a réussi
	if (is_wp_error($response)) {
		$error_message = $response->get_error_message();
	} else {
		$response_code = wp_remote_retrieve_response_code($response);
		$response_body = wp_remote_retrieve_body($response);
	}


	$expert = json_decode($response_body, true);

	return $expert[0];
}