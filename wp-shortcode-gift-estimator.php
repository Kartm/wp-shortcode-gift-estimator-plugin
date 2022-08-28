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

function get_form()
{
    return <<<HTML
    <style>
        form {
            display: flex;
            flex-direction: column;
        }

        form fieldset {
            display: block;
            margin: 0;
            margin-bottom: 8px;
        }

        form button[type='submit'] {
            align-self: flex-end;
        }

        .field-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            align-items: center;
            gap: 8px;
        }

        .field-grid input {
            max-width: 100px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById("contribution_known").addEventListener('change', (e) => {
                document.getElementById("contribution_value").required = e.target.checked ;
            })

            document.getElementById("target_known").addEventListener('change', (e) => {
                document.getElementById("target_value").required = e.target.checked ;
            })
        })
    </script>

    <form method="GET">
        <fieldset>
        <legend>Składka na:</legend>
        <div>
            <input
            type="radio"
            id="boy_day"
            value="boy_day"
            name="holiday"
            checked
            />
            <label for="boy_day">Dzień Chłopaka</label>
        </div>

        <div>
            <input type="radio" id="girl_day" value="girl_day" name="holiday" />
            <label for="girl_day">Dzień Kobiet</label>
        </div>
        </fieldset>

        <fieldset class="">
            <legend>Liczba osób w klasie</legend>
            <div class="field-grid">
                <label for="boy_count">Chłopcy: </label>
                <input type="number" id="boy_count" name="boy_count" min="0" required />
                <label for="girl_count">Dziewczyny: </label>
                <input type="number" id="girl_count" name="girl_count" min="0" required />
            </div>
        </fieldset>

        <fieldset>
            <legend>Wybierz jedną z dwóch opcji</legend>
            <div class="field-grid">
                <div>
                    <input
                    type="radio"
                    id="contribution_known"
                    value="contribution_known"
                    name="criteria"
                    checked
                    />
                    <label for="contribution_known">Wiemy po ile możemy się składać: </label>
                </div>
                <input type="number" name="contribution_value" id='contribution_value' min="0" required/>

                <div>
                    <input
                    type="radio"
                    id="target_known"
                    value="target_known"
                    name="criteria"
                    />
                    <label for="target_known">Wiemy o jakiej wartości prezent chcemy dać: </label>
                </div>
                <input type="number" name="target_value" id='target_value' min="0" />
            </div>
        </fieldset>
        <button type="submit">Oblicz</button>
    </form>
HTML;
}

function formatted_money($value)
{
    $text = round($value, 2) . " zł";

    return sprintf('<h6 style=\'display: inline; margin: 0;\'>%s</h6>', htmlspecialchars($text));
}

function get_result($holiday, $boy_count, $girl_count, $criteria, $contribution_value, $target_value)
{
    $text = '';
    $contributors = $holiday === 'boy_day' ? $girl_count : $boy_count;

    if ($criteria === 'contribution_known') {
        $text = sprintf(
            'Przy składce %s, %s stać na prezent o łącznej wartości %s.',
            formatted_money($contribution_value),
            $holiday === 'girl_day' ? 'chłopaków' : 'dziewczyny',
            formatted_money($contribution_value * $contributors)
        );
    } else if ($criteria === 'target_known') {
        $target_people = $holiday === 'girl_day' ? $girl_count : $boy_count;

        $text = sprintf(
            '%s muszą złożyć się po %s, żeby kupić %s prezent o wartości %s.',
            $holiday === 'girl_day' ? 'Chłopaki' : 'Dziewczyny',
            formatted_money(($target_value * $target_people) / $contributors),
            $holiday === 'girl_day' ? 'każdej dziewczynie' : 'każdemu chłopakowi',
            formatted_money($target_value),
        );
    }

    return <<<HTML
        <div>
            <fieldset>
            <legend>Wyniki</legend>
            <div>
                $text
            </div>
            </fieldset>
        </div>
HTML;
}

function form_creation()
{
    $holiday = filter_input(INPUT_GET, 'holiday', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
    $boy_count = filter_input(INPUT_GET, 'boy_count', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    $girl_count = filter_input(INPUT_GET, 'girl_count', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    $criteria = filter_input(INPUT_GET, 'criteria', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
    $contribution_value = filter_input(INPUT_GET, 'contribution_value', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    $target_value = filter_input(INPUT_GET, 'target_value', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

    $show_result = !is_null($holiday) && !empty($holiday) && !is_null($boy_count) && !is_null($girl_count) && !is_null($criteria) && !empty($criteria)
        && (!is_null($contribution_value) || !is_null($target_value));

    return $show_result ? get_result($holiday, $boy_count, $girl_count, $criteria, $contribution_value, $target_value) : get_form();
}

add_shortcode('test', 'form_creation');
