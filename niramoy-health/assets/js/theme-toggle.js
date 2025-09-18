// On page load, apply theme from localStorage
const savedTheme = localStorage.getItem('theme_mode');
if (savedTheme) {
  document.body.classList.remove('light-mode', 'dark-mode');
  document.body.classList.add(savedTheme + '-mode');
}
