// Console tab switching now lives in video.js (it mounts the real video for each tab)

// Get Route: opens Google Maps directions from typed location to the church address
const routeForm = document.getElementById('routeForm');
if (routeForm) {
  routeForm.addEventListener('submit', e => {
    e.preventDefault();
    const from = document.getElementById('fromInput').value.trim();
    if (!from) return;
    const dest = encodeURIComponent('LoveWorld Arena, Aare Bashiru Street, Chisco B/S, Lekki-Epe Expressway, Lagos');
    window.open(`https://www.google.com/maps/dir/?api=1&origin=${encodeURIComponent(from)}&destination=${dest}&travelmode=driving`, '_blank', 'noopener');
  });
}

// Ad popups: config comes from admin > Ads (first delay, interval, rotation).
// This reads the <template data-ad> tags the homepage renders from the `ads` table.
(function () {
  const ads = [...document.querySelectorAll('template[data-ad]')].map(t => ({ title: t.dataset.title, banner: t.dataset.banner, link: t.dataset.link }));
  if (!ads.length) return;
  let i = 0;
  function showAd() {
    const ad = ads[i % ads.length]; i++;
    const overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed;inset:0;background:rgba(15,23,42,.55);display:flex;align-items:center;justify-content:center;z-index:400;padding:20px';
    overlay.innerHTML = `<div style="background:#fff;border-radius:18px;max-width:270px;overflow:hidden;box-shadow:0 24px 56px -24px rgba(15,23,42,.4)">
      <div style="aspect-ratio:2/1;background:linear-gradient(150deg,#9ec5fe,#0D6EFD);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.9rem;padding:12px;text-align:center">${ad.banner}</div>
      <div style="padding:16px"><b style="font-size:.95rem">${ad.title}</b>
        <div style="display:flex;justify-content:space-between;margin-top:12px">
          <a href="${ad.link}" style="background:#0D6EFD;color:#fff;padding:8px 16px;border-radius:10px;font-size:.82rem;font-weight:600">Learn more</a>
          <button style="background:none;border:none;color:#64748B;font-size:.8rem" onclick="this.closest('div[style*=fixed]')?.remove(); this.parentElement.parentElement.parentElement.remove()">Close</button>
        </div></div></div>`;
    overlay.addEventListener('click', e => { if (e.target === overlay) overlay.remove(); });
    document.body.appendChild(overlay);
  }
  // Defaults; the admin-configured first_delay/every should be injected server-side
  // (e.g. window.AD_CONFIG = {!! json_encode(...) !!} in the layout) rather than hardcoded here.
  const cfg = window.AD_CONFIG || { first_delay: 20, every: 90 };
  setTimeout(function loop() { showAd(); setTimeout(loop, cfg.every * 1000); }, cfg.first_delay * 1000);
})();
