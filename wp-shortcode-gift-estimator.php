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
 *
 * You should have received a copy of the GNU General Public License
 * along with Hot Recipes. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

function add_query_vars_filter($vars)
{
    $vars[] = "firstname";
    return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');

function form_creation()
{
?>
    <form method='POST'>
        First name: <input type='text' name='firstname'><br>
        Last name: <input type='text' name='lastname'><br>
        Message: <textarea name='message'> Enter text here…</textarea>
    </form>
<?php
}

if (get_query_var('firstname')) {

    // If so echo the value
    echo get_query_var('firstname');
}

add_shortcode('test', 'form_creation');



?>