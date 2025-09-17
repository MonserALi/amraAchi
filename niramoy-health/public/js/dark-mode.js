// Dark Mode Toggle
document.addEventListener('DOMContentLoaded', function () {
  const darkModeToggle = document.getElementById('dark-mode-toggle');
  const darkModeStylesheet = document.getElementById('dark-mode-stylesheet');

  // Check for saved dark mode preference or respect OS preference
  const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const savedDarkMode = getCookie('dark_mode');

  // Set initial dark mode state
  if (savedDarkMode === 'true' || (savedDarkMode === null && prefersDarkMode)) {
    enableDarkMode();
  }

  // Toggle dark mode when the toggle is clicked
  if (darkModeToggle) {
    darkModeToggle.addEventListener('change', function () {
      if (this.checked) {
        enableDarkMode();
      } else {
        disableDarkMode();
      }
    });
  }

  // Function to enable dark mode
  function enableDarkMode() {
    document.body.classList.add('dark-mode');
    if (darkModeStylesheet) {
      darkModeStylesheet.disabled = false;
    }
    if (darkModeToggle) {
      darkModeToggle.checked = true;
    }
    setCookie('dark_mode', 'true', 30);

    // Dispatch a custom event for other scripts to listen to
    document.dispatchEvent(new CustomEvent('darkModeEnabled'));
  }

  // Function to disable dark mode
  function disableDarkMode() {
    document.body.classList.remove('dark-mode');
    if (darkModeStylesheet) {
      darkModeStylesheet.disabled = true;
    }
    if (darkModeToggle) {
      darkModeToggle.checked = false;
    }
    setCookie('dark_mode', 'false', 30);

    // Dispatch a custom event for other scripts to listen to
    document.dispatchEvent(new CustomEvent('darkModeDisabled'));
  }

  // Listen for OS-level dark mode changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
    // Only change if the user hasn't set a preference
    if (getCookie('dark_mode') === null) {
      if (e.matches) {
        enableDarkMode();
      } else {
        disableDarkMode();
      }
    }
  });
});

// Helper functions for cookie management
function setCookie(name, value, days) {
  const expires = new Date();
  expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
  document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
}

function getCookie(name) {
  const nameEQ = name + "=";
  const ca = document.cookie.split(';');
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}