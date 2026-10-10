// Video playback for every page: click-to-play thumbnails, HLS (.m3u8) streams,
// and the homepage console (Live / Excerpts / Featured tabs) with its comment panel.
(function () {
  var IFRAME_ALLOW = 'accelerometer; autoplay; encrypted-media; picture-in-picture';

  // ---- URL helpers ----
  // Turns pasted watch/share links into embeddable URLs. Returns the input unchanged if it isn't recognised.
  function toEmbed(url) {
    var u;
    try { u = new URL(url, window.location.href); } catch (e) { return url; }
    var host = u.hostname.replace(/^www\./, '');
    var id = null, m;

    if (host === 'youtu.be') {
      id = u.pathname.slice(1).split('/')[0];
    } else if (host === 'youtube.com' || host === 'm.youtube.com' || host === 'youtube-nocookie.com') {
      if (u.pathname === '/watch') {
        id = u.searchParams.get('v');
      } else {
        m = u.pathname.match(/^\/(embed|live|shorts)\/([^\/?]+)/);
        id = m && m[2];
      }
    }
    if (id) return 'https://www.youtube.com/embed/' + id;

    if (host === 'vimeo.com') {
      m = u.pathname.match(/^\/(\d+)/);
      if (m) return 'https://player.vimeo.com/video/' + m[1];
    }
    return url;
  }

  function isEmbedHost(url) {
    return /^https?:\/\/(www\.)?(youtube\.com|youtube-nocookie\.com|youtu\.be|player\.vimeo\.com|vimeo\.com)\//i.test(url);
  }

  // Decide how to play a source, trusting the URL over data-kind when they disagree.
  function detectKind(src, kind) {
    if (/\.m3u8(\?|#|$)/i.test(src)) return 'hls';
    if (isEmbedHost(src)) return 'iframe';
    return kind || 'video';
  }

  // ---- HLS: use native support (Safari) or load hls.js once, only when needed ----
  var hlsLoader;
  function loadHls() {
    if (window.Hls) return Promise.resolve(window.Hls);
    if (!hlsLoader) {
      hlsLoader = new Promise(function (resolve, reject) {
        var s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/hls.js@1';
        s.onload = function () { resolve(window.Hls); };
        s.onerror = reject;
        document.head.appendChild(s);
      });
    }
    return hlsLoader;
  }
  function attachHls(video, src) {
    if (video.canPlayType('application/vnd.apple.mpegurl')) { video.src = src; return; }
    loadHls().then(function (Hls) {
      if (!Hls.isSupported()) return;
      var h = new Hls();
      h.loadSource(src);
      h.attachMedia(video);
    }).catch(function (err) {
      console.warn('[video.js] Could not load hls.js', err);
    });
  }

  // ---- builders ----
  function withAutoplay(src) { return src + (src.indexOf('?') > -1 ? '&' : '?') + 'autoplay=1'; }

  function makeIframe(src, title, autoplay) {
    var f = document.createElement('iframe');
    f.className = 'video-frame';
    f.src = autoplay ? withAutoplay(src) : src;
    f.title = title || 'Video';
    f.allowFullscreen = true;
    f.allow = IFRAME_ALLOW;
    f.setAttribute('frameborder', '0');
    return f;
  }

  function makeFacade(src, thumb, title) {
    var b = document.createElement('button');
    b.type = 'button';
    b.className = 'video-frame video-facade';
    b.dataset.embed = src;
    b.style.backgroundImage = "url('" + thumb.replace(/'/g, '%27') + "')";
    b.setAttribute('aria-label', 'Play ' + (title || 'video'));
    b.innerHTML = '<span class="play-btn"><svg viewBox="0 0 24 24"><path d="M6 4l14 8-14 8z"/></svg></span>';
    return b;
  }

  function makeVideo(thumb) {
    var v = document.createElement('video');
    v.className = 'video-frame';
    v.controls = true;
    v.playsInline = true;
    if (thumb) v.poster = thumb;
    // Download prevention
    v.setAttribute('controlsList', 'nodownload noremoteplayback');
    v.disablePictureInPicture = true;
    v.dataset.hardened = '1';
    v.addEventListener('contextmenu', function (e) { e.preventDefault(); });
    return v;
  }

  // ---- thumbnail -> real player (delegated, works regardless of load order) ----
  document.addEventListener('click', function (e) {
    var btn = e.target.closest && e.target.closest('.video-facade');
    if (!btn) return;
    var label = (btn.getAttribute('aria-label') || '').replace(/^Play /, '');
    btn.replaceWith(makeIframe(btn.dataset.embed, label, true));
  });

  // ---- homepage console ----
  function initConsole() {
    var player = document.getElementById('consolePlayer');
    var tabs = document.querySelectorAll('.console-tab');
    if (!player || !tabs.length) return;
    var badge = document.getElementById('consoleBadge');
    var caption = document.getElementById('consoleCaption');

    // Comment panel elements (may be absent on other layouts)
    var ccList = document.getElementById('ccList');
    var ccForm = document.getElementById('ccForm');
    var ccNote = document.getElementById('ccNote');
    var ccCount = document.getElementById('ccCount');
    var ccError = document.getElementById('ccError');
    var activeTab = null;

    function emptyState(tab) {
      var box = document.createElement('div');
      box.className = 'console-empty';
      var b = document.createElement('b');
      b.textContent = tab.dataset.label + ' is not available right now';
      box.appendChild(b);
      [tab.dataset.schedule, tab.dataset.fallback].forEach(function (t) {
        if (!t) return;
        var s = document.createElement('span');
        s.textContent = t;
        box.appendChild(s);
      });
      return box;
    }

    // ---- comments ----
    function readComments(tab) {
      try { return JSON.parse(tab.dataset.comments || '[]'); } catch (e) { return []; }
    }

    function addComment(c) {
      var item = document.createElement('div');
      item.className = 'cc-item';
      var av = document.createElement('div');
      av.className = 'cc-avatar';
      var wrap = document.createElement('div');
      var name = document.createElement('b');
      name.style.fontSize = '.85rem';
      name.textContent = c.name + (c.team ? ' \u00b7 Church team' : '');
      var body = document.createElement('p');
      body.style.cssText = 'font-size:.85rem;margin-top:2px';
      body.textContent = c.body;
      wrap.appendChild(name);
      wrap.appendChild(body);
      item.appendChild(av);
      item.appendChild(wrap);
      ccList.appendChild(item);
    }

    function renderComments(tab) {
      if (!ccList || !ccForm) return;
      activeTab = tab;
      var hasPost = !!tab.dataset.commentUrl;
      var on = hasPost && tab.dataset.commentsOn === '1';
      var items = on ? readComments(tab) : [];

      ccList.innerHTML = '';
      items.forEach(addComment);
      if (ccCount) ccCount.textContent = on ? items.length + (items.length === 1 ? ' comment' : ' comments') : '';
      if (ccError) ccError.textContent = '';

      if (ccNote) {
        ccNote.style.display = on ? 'none' : '';
        ccNote.textContent = hasPost
          ? 'Comments are turned off for this video.'
          : 'Comments are open on Excerpts and Featured episodes.';
      }
      ccForm.style.opacity = on ? '' : '.5';
      ccForm.querySelectorAll('input[type=text], button').forEach(function (el) { el.disabled = !on; });
    }

    if (ccForm) {
      ccForm.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!activeTab || !activeTab.dataset.commentUrl) return;
        var tab = activeTab;
        if (ccError) ccError.textContent = '';

        fetch(tab.dataset.commentUrl, {
          method: 'POST',
          body: new FormData(ccForm),
          credentials: 'same-origin',
          headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (r) {
          return r.json().catch(function () { return {}; }).then(function (j) { return { ok: r.ok, j: j }; });
        }).then(function (res) {
          if (!res.ok) {
            var firstErr = res.j.errors && Object.values(res.j.errors)[0];
            if (ccError) ccError.textContent = (firstErr && firstErr[0]) || res.j.message || 'Could not post your comment.';
            return;
          }
          if (!res.j || !res.j.comment) {
            if (ccError) ccError.textContent = 'Saved, but the server did not return JSON. Make sure the updated CommentController.php is deployed.';
            console.warn('[video.js] Unexpected comment response', res);
            return;
          }
          var items = readComments(tab);
          items.unshift(res.j.comment);
          tab.dataset.comments = JSON.stringify(items);
          if (tab === activeTab) renderComments(tab);
          ccForm.elements.body.value = '';
        }).catch(function () {
          if (ccError) ccError.textContent = 'Network error. Please try again.';
        });
      });
    }

    // ---- tab switching ----
    function show(tab) {
      tabs.forEach(function (t) {
        var on = t === tab;
        t.classList.toggle('is-active', on);
        t.setAttribute('aria-selected', on ? 'true' : 'false');
        // The Blade template sets inline colours at render time (first tab = active).
        // Keep them in sync so the active pill follows the click.
        if (t.style && t.style.background !== undefined) {
          t.style.background = on ? '#fff' : 'rgba(255,255,255,.08)';
          t.style.color = on ? '#111' : '#fff';
        }
      });

      renderComments(tab);

      var isLive = /live/i.test(tab.dataset.label || '');
      var rawSrc = (tab.dataset.src || '').trim();
      if (badge) badge.style.display = isLive && rawSrc ? '' : 'none';

      var thumb = tab.dataset.thumb, title = tab.dataset.title;
      player.innerHTML = '';
      if (caption) caption.textContent = rawSrc ? (title || '') : '';

      if (!rawSrc) {
        console.warn('[video.js] Tab "' + tab.dataset.label + '" has no data-src. Check what the controller passes in $capsules.');
        player.appendChild(emptyState(tab));
        return;
      }

      var kind = detectKind(rawSrc, tab.dataset.kind);

      if (kind === 'iframe') {
        var embed = toEmbed(rawSrc);
        player.appendChild(thumb ? makeFacade(embed, thumb, title) : makeIframe(embed, title, false));
      } else {
        var v = makeVideo(thumb);
        player.appendChild(v);
        if (kind === 'hls') attachHls(v, rawSrc); else v.src = rawSrc;
      }
    }

    var TAB_KEY = 'consoleTab';
    function saveTab(tab) {
      try { sessionStorage.setItem(TAB_KEY, tab.dataset.label || ''); } catch (e) {}
    }
    function savedTab() {
      var label = null;
      try { label = sessionStorage.getItem(TAB_KEY); } catch (e) {}
      if (!label) return null;
      for (var i = 0; i < tabs.length; i++) {
        if (tabs[i].dataset.label === label) return tabs[i];
      }
      return null;
    }

    tabs.forEach(function (t) {
      t.addEventListener('click', function () { saveTab(t); show(t); });
    });

    // Reopen the tab the visitor was last on. Otherwise open on the first tab that actually
    // has a video (so an idle Live tab doesn't greet visitors with an empty player).
    var start = savedTab();
    if (!start) {
      for (var i = 0; i < tabs.length; i++) {
        if ((tabs[i].dataset.src || '').trim()) { start = tabs[i]; break; }
      }
    }
    show(start || tabs[0]);
  }

  function init() {
    document.querySelectorAll('video[data-hls]').forEach(function (v) { attachHls(v, v.dataset.hls); });
    initConsole();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
