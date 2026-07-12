const BADGE_COLORS = {
  'status:Åben':        { bg: '#b0efde', text: '#316f61' },
  'status:Afventer':    { bg: '#fef3c7', text: '#92400e' },
  'status:Løst':        { bg: '#d1d9d8', text: '#2b685b' },
  'status:Ikke startet':{ bg: '#ebeeed', text: '#404847' },
  'type:T1':            { bg: '#ffdad6', text: '#ba1a1a' },
  'type:T2':            { bg: '#b0efde', text: '#316f61' },
  'type:T3':            { bg: '#fef3c7', text: '#92400e' },
  'type:T4':            { bg: '#e0f2fe', text: '#075985' },
  'type:Andet':         { bg: '#e0e3e2', text: '#404847' },
  'priority:Høj':       { bg: '#ffdad6', text: '#ba1a1a' },
  'priority:Medium':    { bg: '#fef3c7', text: '#92400e' },
  'priority:Lav':       { bg: '#e0f2fe', text: '#075985' },
  'user-status:Aktiv':  { bg: '#b0efde', text: '#316f61' },
  'user-status:Inaktiv':{ bg: '#fef3c7', text: '#92400e' },
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
