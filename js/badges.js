const BADGE_COLORS = {
  'status:open':         { bg: '#b0efde', text: '#316f61' },
  'status:pending':      { bg: '#fef3c7', text: '#92400e' },
  'status:resolved':     { bg: '#d1d9d8', text: '#2b685b' },
  'status:not-started':  { bg: '#ebeeed', text: '#404847' },
  'type:t1':    { bg: '#ffdad6', text: '#ba1a1a' },
  'type:t2':    { bg: '#b0efde', text: '#316f61' },
  'type:t3':    { bg: '#fef3c7', text: '#92400e' },
  'type:t4':    { bg: '#e0f2fe', text: '#075985' },
  'type:other': { bg: '#e0e3e2', text: '#404847' },
  'priority:high':   { bg: '#ffdad6', text: '#ba1a1a' },
  'priority:medium': { bg: '#fef3c7', text: '#92400e' },
  'priority:low':    { bg: '#e0f2fe', text: '#075985' },
  'user-status:active':   { bg: '#b0efde', text: '#316f61' },
  'user-status:inactive': { bg: '#fef3c7', text: '#92400e' },
  'sagstavle:category': { bg: '#ebeeed', text: '#404847' }
};

const COLOR_PALETTE = {
  red:    { bg: '#ffdad6', text: '#ba1a1a' },
  amber:  { bg: '#fef3c7', text: '#92400e' },
  green:  { bg: '#b0efde', text: '#316f61' },
  blue:   { bg: '#e0f2fe', text: '#075985' },
  purple: { bg: '#f3e8ff', text: '#6b21a8' },
  teal:   { bg: '#ccfbf1', text: '#115e59' },
  orange: { bg: '#ffedd5', text: '#9a3412' },
  gray:   { bg: '#e0e3e2', text: '#404847' }
};

function applyBadges(root = document) {
  root.querySelectorAll('[data-badge], [data-badge-bg]').forEach((el) => {
    const color = (el.dataset.badgeBg && el.dataset.badgeFg)
      ? { bg: el.dataset.badgeBg, text: el.dataset.badgeFg }
      : BADGE_COLORS[el.dataset.badge];
    if (!color) return;
    el.style.backgroundColor = color.bg;
    el.style.color = color.text;
  });
}

(function init() {
  applyBadges();
})();
