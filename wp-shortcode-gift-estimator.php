<?php

/**
 * Gift Estimator
 *
 * @package GIFTESTIMATOR
 * @author lblachnicki
 * @license gplv2-or-later
 * @version 1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: Gift Estimator
 * Description: Gift contribution estimator.
 * Version: 1.0.0
 * Author: Łukasz Blachnicki
 * Author URI: https://lblachnicki.com
 * Text Domain: gift-estimator
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

function form_creation()
{
    $firstname = $_GET['firstname'] ?? 'nulllll';

    $html = <<<HTML
      <div class="card">
        <form method='GET'>
            First name: <input type='text' name='firstname'><br>
            Last name: <input type='text' name='lastname'><br>
            Message: <textarea name='message'> Enter text here…</textarea>
            <button type='submit' value='Submit' />
        </form>
      </div>
HTML;

    return $html;
}

add_shortcode('test', 'form_creation');
