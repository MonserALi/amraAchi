<?php
// Form Helper Functions

// Generate CSRF token
function csrf_token()
{
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }

  return $_SESSION['csrf_token'];
}

// Validate CSRF token
function validate_csrf_token($token)
{
  return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Generate CSRF input field
function csrf_field()
{
  $token = csrf_token();
  return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

// Generate a form input field
function form_input($name, $value = '', $attributes = [])
{
  $attr = '';

  foreach ($attributes as $key => $val) {
    if ($key === 'required' && $val === true) {
      $attr .= ' required';
    } else {
      $attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
    }
  }

  return '<input type="text" name="' . $name . '" value="' . htmlspecialchars($value) . '"' . $attr . '>';
}

// Generate a form password field
function form_password($name, $attributes = [])
{
  $attr = '';

  foreach ($attributes as $key => $val) {
    if ($key === 'required' && $val === true) {
      $attr .= ' required';
    } else {
      $attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
    }
  }

  return '<input type="password" name="' . $name . '"' . $attr . '>';
}

// Generate a form email field
function form_email($name, $value = '', $attributes = [])
{
  $attr = '';

  foreach ($attributes as $key => $val) {
    if ($key === 'required' && $val === true) {
      $attr .= ' required';
    } else {
      $attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
    }
  }

  return '<input type="email" name="' . $name . '" value="' . htmlspecialchars($value) . '"' . $attr . '>';
}

// Generate a form textarea
function form_textarea($name, $value = '', $attributes = [])
{
  $attr = '';

  foreach ($attributes as $key => $val) {
    if ($key === 'required' && $val === true) {
      $attr .= ' required';
    } else {
      $attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
    }
  }

  return '<textarea name="' . $name . '"' . $attr . '>' . htmlspecialchars($value) . '</textarea>';
}

// Generate a form select field
function form_select($name, $options = [], $selected = '', $attributes = [])
{
  $attr = '';

  foreach ($attributes as $key => $val) {
    if ($key === 'required' && $val === true) {
      $attr .= ' required';
    } else {
      $attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
    }
  }

  $html = '<select name="' . $name . '"' . $attr . '>';

  foreach ($options as $value => $label) {
    $isSelected = ($value == $selected) ? ' selected' : '';
    $html .= '<option value="' . htmlspecialchars($value) . '"' . $isSelected . '>' . htmlspecialchars($label) . '</option>';
  }

  $html .= '</select>';

  return $html;
}

// Generate a form checkbox
function form_checkbox($name, $value = '', $checked = false, $attributes = [])
{
  $attr = '';

  if ($checked) {
    $attr .= ' checked';
  }

  foreach ($attributes as $key => $val) {
    if ($key === 'required' && $val === true) {
      $attr .= ' required';
    } else {
      $attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
    }
  }

  return '<input type="checkbox" name="' . $name . '" value="' . htmlspecialchars($value) . '"' . $attr . '>';
}

// Generate a form radio button
function form_radio($name, $value = '', $checked = false, $attributes = [])
{
  $attr = '';

  if ($checked) {
    $attr .= ' checked';
  }

  foreach ($attributes as $key => $val) {
    if ($key === 'required' && $val === true) {
      $attr .= ' required';
    } else {
      $attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
    }
  }

  return '<input type="radio" name="' . $name . '" value="' . htmlspecialchars($value) . '"' . $attr . '>';
}

// Generate a form file input
function form_file($name, $attributes = [])
{
  $attr = '';

  foreach ($attributes as $key => $val) {
    if ($key === 'required' && $val === true) {
      $attr .= ' required';
    } else {
      $attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
    }
  }

  return '<input type="file" name="' . $name . '"' . $attr . '>';
}

// Generate a form submit button
function form_submit($label = 'Submit', $attributes = [])
{
  $attr = '';

  foreach ($attributes as $key => $val) {
    $attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
  }

  return '<button type="submit"' . $attr . '>' . htmlspecialchars($label) . '</button>';
}

// Generate a form button
function form_button($label = 'Button', $attributes = [])
{
  $attr = '';

  if (!isset($attributes['type'])) {
    $attr .= ' type="button"';
  }

  foreach ($attributes as $key => $val) {
    $attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
  }

  return '<button' . $attr . '>' . htmlspecialchars($label) . '</button>';
}

// Generate a form label
function form_label($text, $for = '', $attributes = [])
{
  $attr = '';

  if (!empty($for)) {
    $attr .= ' for="' . htmlspecialchars($for) . '"';
  }

  foreach ($attributes as $key => $val) {
    $attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
  }

  return '<label' . $attr . '>' . htmlspecialchars($text) . '</label>';
}

// Generate a form open tag
function form_open($action = '', $method = 'post', $attributes = [])
{
  $attr = '';

  if (empty($action)) {
    $action = current_url();
  }

  foreach ($attributes as $key => $val) {
    $attr .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
  }

  $html = '<form action="' . htmlspecialchars($action) . '" method="' . htmlspecialchars($method) . '"' . $attr . '>';

  // Add CSRF token for POST forms
  if (strtolower($method) === 'post') {
    $html .= csrf_field();
  }

  return $html;
}

// Generate a form close tag
function form_close()
{
  return '</form>';
}

// Generate a form error message
function form_error($field, $errors)
{
  if (isset($errors[$field])) {
    return '<div class="invalid-feedback">' . htmlspecialchars($errors[$field]) . '</div>';
  }

  return '';
}

// Generate a form input with error message
function form_input_with_error($name, $value = '', $attributes = [], $errors = [])
{
  $html = form_input($name, $value, $attributes);

  if (!empty($errors) && isset($errors[$name])) {
    $html .= form_error($name, $errors);
  }

  return $html;
}

// Generate a form password field with error message
function form_password_with_error($name, $attributes = [], $errors = [])
{
  $html = form_password($name, $attributes);

  if (!empty($errors) && isset($errors[$name])) {
    $html .= form_error($name, $errors);
  }

  return $html;
}

// Generate a form email field with error message
function form_email_with_error($name, $value = '', $attributes = [], $errors = [])
{
  $html = form_email($name, $value, $attributes);

  if (!empty($errors) && isset($errors[$name])) {
    $html .= form_error($name, $errors);
  }

  return $html;
}

// Generate a form textarea with error message
function form_textarea_with_error($name, $value = '', $attributes = [], $errors = [])
{
  $html = form_textarea($name, $value, $attributes);

  if (!empty($errors) && isset($errors[$name])) {
    $html .= form_error($name, $errors);
  }

  return $html;
}

// Generate a form select field with error message
function form_select_with_error($name, $options = [], $selected = '', $attributes = [], $errors = [])
{
  $html = form_select($name, $options, $selected, $attributes);

  if (!empty($errors) && isset($errors[$name])) {
    $html .= form_error($name, $errors);
  }

  return $html;
}

// Generate a form file input with error message
function form_file_with_error($name, $attributes = [], $errors = [])
{
  $html = form_file($name, $attributes);

  if (!empty($errors) && isset($errors[$name])) {
    $html .= form_error($name, $errors);
  }

  return $html;
}

// Set old form input value
function set_old_value($field, $default = '')
{
  return isset($_SESSION['old_input'][$field]) ? $_SESSION['old_input'][$field] : $default;
}

// Clear old form input values
function clear_old_input()
{
  unset($_SESSION['old_input']);
}
