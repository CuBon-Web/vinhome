/**
 * Nội dung gốc trên site là tiếng Việt.
 * VI = không dịch. EN / 中文 = Google Translate.
 */
(function (global) {
    'use strict';

    var PAGE_LANG = 'vi';
    var DEFAULT_LANG = 'vi';
    var STORAGE_KEY = 'gt_site_lang';
    var COOKIE_NAME = 'googtrans';
    var LANGS = ['vi', 'en', 'zh-CN'];

    function readCookie() {
        var match = document.cookie.match(/(?:^|;\s*)googtrans=([^;]*)/);
        return match ? decodeURIComponent(match[1]) : '';
    }

    function purgeGoogTransCookies() {
        var expired = COOKIE_NAME + '=;path=/;expires=Thu, 01 Jan 1970 00:00:00 GMT';
        var host = global.location.hostname;
        var domains = [null];

        if (host && host.indexOf('.') !== -1 && host !== 'localhost') {
            var root = host.replace(/^www\./, '');
            domains.push('.' + root, root);
            if (host.indexOf('www.') === 0) {
                domains.push(host);
            }
        }

        domains.forEach(function (domain) {
            document.cookie = domain ? expired + ';domain=' + domain : expired;
        });
    }

    function setTargetCookie(lang) {
        var secure = global.location.protocol === 'https:' ? ';Secure' : '';
        purgeGoogTransCookies();
        document.cookie =
            COOKIE_NAME +
            '=/' +
            PAGE_LANG +
            '/' +
            lang +
            ';path=/;max-age=31536000;SameSite=Lax' +
            secure;
    }

    function stripTranslateArtifacts() {
        var html = document.documentElement;
        if (html) {
            html.classList.remove('translated-ltr', 'translated-rtl');
            if (html.getAttribute('lang') !== PAGE_LANG) {
                html.setAttribute('lang', PAGE_LANG);
            }
        }
        document.querySelectorAll('font[style*="vertical-align"]').forEach(function (node) {
            var parent = node.parentNode;
            if (!parent) {
                return;
            }
            while (node.firstChild) {
                parent.insertBefore(node.firstChild, node);
            }
            parent.removeChild(node);
        });
    }

    function normalizeLang(lang) {
        if (lang === 'zh' || lang === 'zh-cn') {
            return 'zh-CN';
        }
        return LANGS.indexOf(lang) !== -1 ? lang : '';
    }

    function getSavedLang() {
        try {
            var stored = normalizeLang(global.localStorage.getItem(STORAGE_KEY));
            if (stored) {
                return stored;
            }
        } catch (e) {}

        var cookie = readCookie();
        var match = cookie.match(/\/(en|zh-CN|zh-cn|vi)\/?$/i);
        if (match) {
            var fromCookie = normalizeLang(match[1]);
            if (fromCookie && fromCookie !== PAGE_LANG) {
                return fromCookie;
            }
        }

        return DEFAULT_LANG;
    }

    function shouldUseTranslate() {
        return getSavedLang() !== PAGE_LANG;
    }

    function updateButtons() {
        var active = getSavedLang();
        document.querySelectorAll('.gt-lang [data-gt-lang]').forEach(function (btn) {
            var lang = btn.getAttribute('data-gt-lang');
            var isActive = lang === active;
            btn.classList.toggle('is-active', isActive);
            btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    }

    function switchLanguage(lang) {
        lang = normalizeLang(lang);
        if (!lang || lang === getSavedLang()) {
            return;
        }

        document.querySelectorAll('.gt-lang').forEach(function (el) {
            el.classList.add('is-loading');
        });

        try {
            global.localStorage.setItem(STORAGE_KEY, lang);
        } catch (e) {}

        if (lang === PAGE_LANG) {
            purgeGoogTransCookies();
        } else {
            setTargetCookie(lang);
        }

        global.location.reload();
    }

    function loadGoogleTranslate() {
        var lang = getSavedLang();
        if (!shouldUseTranslate()) {
            stripTranslateArtifacts();
            return;
        }

        if (document.getElementById('google-translate-script')) {
            return;
        }

        setTargetCookie(lang);

        global.googleTranslateElementInit = function () {
            if (!global.google || !global.google.translate) {
                updateButtons();
                return;
            }

            new global.google.translate.TranslateElement(
                {
                    pageLanguage: PAGE_LANG,
                    includedLanguages: LANGS.join(','),
                    autoDisplay: false,
                },
                'google_translate_element'
            );

            updateButtons();

            global.setTimeout(function () {
                var combo = document.querySelector('select.goog-te-combo');
                if (combo && combo.value !== lang) {
                    combo.value = lang;
                    combo.dispatchEvent(new Event('change'));
                }
            }, 400);
        };

        var script = document.createElement('script');
        script.id = 'google-translate-script';
        script.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
        script.async = true;
        document.body.appendChild(script);
    }

    function bindSwitchers() {
        document.querySelectorAll('.gt-lang').forEach(function (root) {
            if (root.getAttribute('data-gt-inited') === '1') {
                return;
            }

            root.querySelectorAll('[data-gt-lang]').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    switchLanguage(btn.getAttribute('data-gt-lang'));
                });
            });

            root.setAttribute('data-gt-inited', '1');
        });

        updateButtons();
    }

    function init() {
        if (!shouldUseTranslate()) {
            purgeGoogTransCookies();
            stripTranslateArtifacts();
        }

        bindSwitchers();
        loadGoogleTranslate();
    }

    global.GTLang = {
        init: init,
        getSavedLang: getSavedLang,
        switchLanguage: switchLanguage,
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})(window);
