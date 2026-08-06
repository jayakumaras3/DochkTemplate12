<?php

/**
 * Translated month name for a 1-12 month number, using the UI_Text.Month_1..Month_12 language keys.
 * Use this instead of PHP's date('F', ...), which always returns the English name regardless of locale.
 */
function translated_month_name(int $month): string
{
    $month = max(1, min(12, $month));
    return lang('UI_Text.Month_' . $month);
}
