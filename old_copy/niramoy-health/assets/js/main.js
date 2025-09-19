// Language Switcher
const languageSelect = document.getElementById('language-select');
if (languageSelect) {
  languageSelect.addEventListener('change', function () {
    const selectedLang = this.value;
    // Save language preference (optional)
    localStorage.setItem('lang_code', selectedLang);
    document.cookie = `lang=${selectedLang}; path=/; max-age=31536000`;
    // Reload page with language param
    const url = new URL(window.location.href);
    url.searchParams.set('lang', selectedLang);
    window.location.href = url.toString();
  });
}

// Language Toggle Button (one-click)
const languageToggleBtn = document.getElementById('language-toggle-btn');
if (languageToggleBtn) {
  languageToggleBtn.addEventListener('click', function () {
    const currentLang = document.documentElement.lang || 'en';
    const newLang = currentLang === 'en' ? 'bn' : 'en';
    localStorage.setItem('lang_code', newLang);
    document.cookie = `lang=${newLang}; path=/; max-age=31536000`;
    const url = new URL(window.location.href);
    url.searchParams.set('lang', newLang);
    window.location.href = url.toString();
  });
}

// Dark Mode Toggle
const themeToggleBtn = document.getElementById('theme-toggle-btn');
if (themeToggleBtn) {
  themeToggleBtn.addEventListener('click', function () {
    const body = document.body;
    let newTheme;
    if (body.classList.contains('light-mode')) {
      body.classList.remove('light-mode');
      body.classList.add('dark-mode');
      localStorage.setItem('theme_mode', 'dark');
      newTheme = 'dark';
    } else {
      body.classList.remove('dark-mode');
      body.classList.add('light-mode');
      localStorage.setItem('theme_mode', 'light');
      newTheme = 'light';
    }
    // Set cookie for PHP
    document.cookie = `theme_mode=${newTheme}; path=/; max-age=31536000`;
    window.location.reload();
  });
}
