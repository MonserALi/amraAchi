<?php
// Date Helper Functions

// Format date
function format_date($date, $format = 'Y-m-d')
{
  if (!$date) {
    return '';
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date($format, $timestamp);
}

// Format time
function format_time($time, $format = 'H:i')
{
  if (!$time) {
    return '';
  }

  $timestamp = strtotime($time);

  if ($timestamp === false) {
    return '';
  }

  return date($format, $timestamp);
}

// Format datetime
function format_datetime($datetime, $format = 'Y-m-d H:i')
{
  if (!$datetime) {
    return '';
  }

  $timestamp = strtotime($datetime);

  if ($timestamp === false) {
    return '';
  }

  return date($format, $timestamp);
}

// Get current date
function current_date($format = 'Y-m-d')
{
  return date($format);
}

// Get current time
function current_time($format = 'H:i')
{
  return date($format);
}

// Get current datetime
function current_datetime($format = 'Y-m-d H:i:s')
{
  return date($format);
}

// Calculate age from birthdate
function calculate_age($birthdate)
{
  if (!$birthdate) {
    return '';
  }

  $birthDate = new DateTime($birthdate);
  $today = new DateTime('today');
  $age = $birthDate->diff($today);

  return $age->y;
}

// Get time ago string
function time_ago($datetime)
{
  if (!$datetime) {
    return '';
  }

  $timestamp = strtotime($datetime);

  if ($timestamp === false) {
    return '';
  }

  $time = time() - $timestamp;

  if ($time < 60) {
    return 'Just now';
  } elseif ($time < 3600) {
    return round($time / 60) . ' minute' . (round($time / 60) > 1 ? 's' : '') . ' ago';
  } elseif ($time < 86400) {
    return round($time / 3600) . ' hour' . (round($time / 3600) > 1 ? 's' : '') . ' ago';
  } elseif ($time < 604800) {
    return round($time / 86400) . ' day' . (round($time / 86400) > 1 ? 's' : '') . ' ago';
  } elseif ($time < 2592000) {
    return round($time / 604800) . ' week' . (round($time / 604800) > 1 ? 's' : '') . ' ago';
  } elseif ($time < 31536000) {
    return round($time / 2592000) . ' month' . (round($time / 2592000) > 1 ? 's' : '') . ' ago';
  } else {
    return round($time / 31536000) . ' year' . (round($time / 31536000) > 1 ? 's' : '') . ' ago';
  }
}

// Get day name
function day_name($date)
{
  if (!$date) {
    return '';
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('l', $timestamp);
}

// Get month name
function month_name($date)
{
  if (!$date) {
    return '';
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('F', $timestamp);
}

// Get year
function year($date)
{
  if (!$date) {
    return '';
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y', $timestamp);
}

// Get month
function month($date)
{
  if (!$date) {
    return '';
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('m', $timestamp);
}

// Get day
function day($date)
{
  if (!$date) {
    return '';
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('d', $timestamp);
}

// Add days to date
function add_days($date, $days)
{
  if (!$date) {
    return '';
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y-m-d', strtotime("+$days days", $timestamp));
}

// Subtract days from date
function subtract_days($date, $days)
{
  if (!$date) {
    return '';
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y-m-d', strtotime("-$days days", $timestamp));
}

// Add months to date
function add_months($date, $months)
{
  if (!$date) {
    return '';
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y-m-d', strtotime("+$months months", $timestamp));
}

// Subtract months from date
function subtract_months($date, $months)
{
  if (!$date) {
    return '';
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y-m-d', strtotime("-$months months", $timestamp));
}

// Add years to date
function add_years($date, $years)
{
  if (!$date) {
    return '';
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y-m-d', strtotime("+$years years", $timestamp));
}

// Subtract years from date
function subtract_years($date, $years)
{
  if (!$date) {
    return '';
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y-m-d', strtotime("-$years years", $timestamp));
}

// Get date difference in days
function date_diff_days($date1, $date2)
{
  if (!$date1 || !$date2) {
    return 0;
  }

  $timestamp1 = strtotime($date1);
  $timestamp2 = strtotime($date2);

  if ($timestamp1 === false || $timestamp2 === false) {
    return 0;
  }

  $diff = abs($timestamp1 - $timestamp2);

  return round($diff / 86400);
}

// Get date difference in hours
function date_diff_hours($date1, $date2)
{
  if (!$date1 || !$date2) {
    return 0;
  }

  $timestamp1 = strtotime($date1);
  $timestamp2 = strtotime($date2);

  if ($timestamp1 === false || $timestamp2 === false) {
    return 0;
  }

  $diff = abs($timestamp1 - $timestamp2);

  return round($diff / 3600);
}

// Get date difference in minutes
function date_diff_minutes($date1, $date2)
{
  if (!$date1 || !$date2) {
    return 0;
  }

  $timestamp1 = strtotime($date1);
  $timestamp2 = strtotime($date2);

  if ($timestamp1 === false || $timestamp2 === false) {
    return 0;
  }

  $diff = abs($timestamp1 - $timestamp2);

  return round($diff / 60);
}

// Check if date is in the past
function is_past_date($date)
{
  if (!$date) {
    return false;
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return false;
  }

  return $timestamp < time();
}

// Check if date is in the future
function is_future_date($date)
{
  if (!$date) {
    return false;
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return false;
  }

  return $timestamp > time();
}

// Check if date is today
function is_today($date)
{
  if (!$date) {
    return false;
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return false;
  }

  return date('Y-m-d', $timestamp) === date('Y-m-d');
}

// Check if date is yesterday
function is_yesterday($date)
{
  if (!$date) {
    return false;
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return false;
  }

  return date('Y-m-d', $timestamp) === date('Y-m-d', strtotime('yesterday'));
}

// Check if date is tomorrow
function is_tomorrow($date)
{
  if (!$date) {
    return false;
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return false;
  }

  return date('Y-m-d', $timestamp) === date('Y-m-d', strtotime('tomorrow'));
}

// Get start of week
function start_of_week($date = null)
{
  if (!$date) {
    $date = current_date();
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y-m-d', strtotime('monday this week', $timestamp));
}

// Get end of week
function end_of_week($date = null)
{
  if (!$date) {
    $date = current_date();
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y-m-d', strtotime('sunday this week', $timestamp));
}

// Get start of month
function start_of_month($date = null)
{
  if (!$date) {
    $date = current_date();
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y-m-01', $timestamp);
}

// Get end of month
function end_of_month($date = null)
{
  if (!$date) {
    $date = current_date();
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y-m-t', $timestamp);
}

// Get start of year
function start_of_year($date = null)
{
  if (!$date) {
    $date = current_date();
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y-01-01', $timestamp);
}

// Get end of year
function end_of_year($date = null)
{
  if (!$date) {
    $date = current_date();
  }

  $timestamp = strtotime($date);

  if ($timestamp === false) {
    return '';
  }

  return date('Y-12-31', $timestamp);
}
